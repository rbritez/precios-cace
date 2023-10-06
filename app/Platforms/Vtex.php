<?php

namespace App\Platforms;

use App\Errors;
use App\Suggestion;
use Error;
use Exception;
use Goutte\Client as GoutteClient;
use Illuminate\Support\Facades\Log;

final class Vtex extends GoutteClient
{
    //ejemplo VTEX LEGACY: https://www.havaianas.com.ar/41442640555-ojotas-havaianas-top-logomania
    //https://www.puppis.com.ar/
    //el precio esta dentro de la clase  bestPrice o skuBestPrice, no hay precio tachado

    /**  ejemplo VTEX LEGACY : https://www.tramontina.com.ar/
     * el precio real esta dentro de la clase skuBestPrice,
     * el precio tachado esta dentro de la clase skuListPrice
     */
    //
    public $vtex;
    const VTEX = 1;

    public function searchRealPrice($shop,$url)
    {
        $this->vtex = Suggestion::realPrice()->wherePlatformId(self::VTEX)->get();
        if (count($this->vtex) > 0) {

            try {
                $scrawler = $this->request('GET', $url);
                
                foreach ($this->vtex as $key => $methodSearch) {
                    $exist = $scrawler->html();
                    if ($exist && $methodSearch->description == 'skuBestPrice') {
                        //VTEX LEGACY
                        session()->put('method', $key);
                        $price = $this->vtexLegacyRealPrice($scrawler, $methodSearch, $url);
                        return $price;
                        
                    } else if ($exist && $methodSearch->description == 'vtex-store-components-3-x-sellingPrice') {
                        // VTEX IO
                       //no se consiguio avances
                        //session()->put('method', $key);

                        //$price = $this->vtexIoRealPrice($scrawler, $this->vtex, $url);
                        return null;
                    }

                }
                Log::channel('scraping')->info('VTEX: No se encuentran o no se puede acceder a la informacion del sitio ' . $url);
                Errors::create([ 'shop_id'=>$shop, 'url' => $url,'error' => 'VTEX: No se pudo encontrar el precio de oferta' ]);
                return null;
            } catch (Exception $e) {
                Errors::create([ 'shop_id'=>$shop, 'url' => $url,'error' => $e->getMessage() ]);
                Log::channel('scraping')->error('Se produjo un error en VTEX: ' . json_encode(['error' => $e->getMessage(), 'url' => $url]));
                return null;
            }
        }
    }

    public function searchLabeledPrice($shop,$url)
    {

        $this->vtex = Suggestion::typeSearchVtex()->whereRealPrice(0)->wherePlatformId(self::VTEX)->get();
       
        if (count($this->vtex) > 0) {

            try {
                $scrawler = $this->request('GET', $url);
                if (count($this->vtex) == 1) {
                    //VTEX LEGACY
                    $price = $this->vtexLegacyLabeledPrice($scrawler, $this->vtex, $url);
                    return $price;
                } else {
                    //VTEX IO
                    //no se consiguio avances 
                    //$price = $this->vtexIoLabeledPrice($scrawler, $this->vtex, $url);
                    return null;
                }
            } catch (Exception $e) {
                Errors::create([ 'shop_id'=>$shop, 'url' => $url,'error' => $e->getMessage() ]);
                Log::channel('scraping')->error('Se produjo un error en VTEX: ' . json_encode(['error' => $e->getMessage(), 'url' => $url]));
                return null;
            }
        }
    }

    /**
     * vtexLegacyRealPrice
     *
     * @param mixed $scrawler
     * @param mixed $methodSearch
     * @param string $url
     * @return float|null
     */
    public function vtexLegacyRealPrice($scrawler, $methodSearch, $url)
    {
        //traigo el precio tachado
        $existePrecio = $scrawler->filter("[" . $methodSearch->type_search . "= 'skuListPrice']")->count();
        $price = null;
        if ($existePrecio) {

            //en   algunas tiendas VTEX el precio tachado esta en 0, y el precio real tiene el valor del precio tachado (es decir, mal configurado)
            $precioTachado = $scrawler->filter("[" . $methodSearch->type_search . "= 'skuListPrice' ]")->first();
            $precioLabeled = removeSpecialCharacters($precioTachado->text());

            $realPrice = $scrawler->filter("[" . $methodSearch->type_search . "=".$methodSearch->description."]")->first();
            $price = removeSpecialCharacters($realPrice->text());

            if ($precioLabeled == 0) {
                //busco si hay porcentaje para bajar al precio real
                $percentage = $this->searchPercentage($scrawler);

                if ($percentage) {
                    $subsPrice =  number_format($price * $percentage / 100, 2);

                    $price = $price - $subsPrice;
                } else {
                    Log::channel('scraping')->info('VTEX: No se pudo obtener el porcentaje para el precio real de ' . $url);
                }
            }
        }

        return $price;
    }

    public function vtexLegacyLabeledPrice($scrawler, $methodSearch, $url)
    {
        
        $exist =  $scrawler->filter("[" . $methodSearch->get(0)->type_search . "= 'skuBestPrice' ]")->count();

        if ($exist) {

            //en algunas tiendas VTEX el precio tachado esta en 0, y el precio real tiene el valor del precio tachado (es decir, mal configurado)
            $precioTachado = $scrawler->filter("[" . $methodSearch->get(0)->type_search . "=" . $methodSearch->get(0)->description . "]")->first();
            $precioLabeled = removeSpecialCharacters($precioTachado->text());
            $price = $precioLabeled;
            
            if ($precioLabeled == 0) {
                //busco si hay porcentaje para bajar al precio real
                $labeledPrice = $scrawler->filter("[" . $methodSearch->get(0)->type_search . "= 'skuBestPrice' ]")->first();
                $price = removeSpecialCharacters($labeledPrice->text());

            }

            return $price;
        } else {
            Log::channel('scraping')->info('VTEX: No se encuentran o no se puede acceder a la informacion del sitio ' . $url);
            return null;
        }
    }

    /**
     * vtex IO Real Price
     *
     * @param mixed $scrawler
     * @param mixed $methodSearch
     * @param string $url
     * @return float|null
     */
    public function vtexIoRealPrice($scrawler, $methodSearch, $url)
    {
        $realPrice =  $scrawler->filter("[" . $methodSearch->type_search . "=" . $methodSearch->description . "]")->first();
        $price = removeSpecialCharacters($realPrice->text());
    }

    /**
     * vtex IO Labeeld Price
     *
     * @param mixed $scrawler
     * @param mixed $methodSearch
     * @param string $url
     * @return float|null
     */
    public function vtexIoLabeledPrice($scrawler, $methodSearch, $url)
    {
        $realPrice =  $scrawler->filter("[" . $methodSearch->type_search . "=" . $methodSearch->description . "]")->first();
        $price = removeSpecialCharacters($realPrice->text());
    }

    /**
     * searchPercentage for VTEX Legacy
     *
     * @param mixed $scrawler
     * @return int|null
     */
    public function searchPercentage($scrawler)
    {
        $exisFlagPercentaje = $scrawler->filter('p.flag')->count();

        if ($exisFlagPercentaje) {

            $textPorcentaje = $scrawler->filter('p.flag')->each(function ($promoNode) {

                if (strpos($promoNode->text(), 'Promo')) {
                    return $promoNode->text();
                } elseif (strpos($promoNode->text(), '%')) {
                    return $promoNode->text();
                }
            });

            foreach ($textPorcentaje as $key => $value) {
                if ($value != null) {
                    $arrayPorcentaje = explode('-', $value);

                    if (isset($arrayPorcentaje[1])) {
                        $porcentaje = (int) str_replace('Promo', '', $arrayPorcentaje[1]);
                    } else {
                        $porcentaje = (int) str_replace('%', '', $arrayPorcentaje[0]);
                    }

                    return $porcentaje;
                }
            }
        }

        $exisClassPercentaje = $scrawler->filter('span.porcent')->count();

        if ($exisClassPercentaje) {
            $textPorcentaje = $scrawler->filter('span.porcent')->first();
            if ($textPorcentaje->text() != '') {
                $porcentaje = (int) str_replace('%', '', $textPorcentaje->text());
                return $porcentaje;
            }
        }
        return null;
    }
}