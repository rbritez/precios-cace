<?php

namespace App\Platforms;

use App\Errors;
use App\Suggestion;
use Exception;
use Goutte\Client as GoutteClient;
use Illuminate\Support\Facades\Log;

final class Woocommerce extends GoutteClient{
    
    public $woo ;
    public $containerType;
    public $containerDescription;
    public $etiqueta;
    const WOOCOMMERCE = 4;
    public $urlRealPrice;
    public $urlLabeledPrice;

    // ejemplo : https://arcorencasa.com/producto/combo-regaleria/  //tiene bloqueado para scrapping
    //todo se encuentra dentro del contenedor price
    //https://saboresandinos.com/ 
    //https://abacojugueteria.com/

    public function searchRealPrice($shop,$url){
         
        $this->woo = Suggestion::realPrice()->wherePlatformId(self::WOOCOMMERCE)->get();
        $this->urlRealPrice = $url;
        if($this->woo){
            $this->containerType = $this->woo->get(0)->type_search;
            $this->containerDescription = $this->woo->get(0)->description;
            
            try {
                $scrawler = $this->request('GET',$url);
                $exist = $scrawler->filter("p.".$this->containerDescription)->count();
                
                if($exist){
                    $this->etiqueta = $this->woo->get(1)->description;
                    $containerRealPrice = $scrawler->filter("p.".$this->containerDescription)->each(function ($realPriceNode){
                        //verificamos si existe precio tachado para luego buscar la etiqueta del precio real
                        
                        $realPriceExistIns = $realPriceNode->filter($this->etiqueta)->count();
                        $realPriceExistBdi = $realPriceNode->filter('bdi')->count();
                        $realPriceExistSinglePrice = $realPriceNode->filter('[class=single-payment-price]')->count();
                
                        if($realPriceExistIns){
                            $realPrice = $realPriceNode->filter($this->etiqueta);
                            return $realPrice->text();
                        }elseif($realPriceExistBdi){
                            //si no hay precio tachado ,el precio real esta dentro de una etiqueta bdi
                            $realPrice = $realPriceNode->filter('bdi');
                            return $realPrice->text();
                        }elseif($realPriceExistSinglePrice){
                            $singlePrice = $realPriceNode->filter('[class=single-payment-price]');
                            $realPrice = $singlePrice->filter('span.woocommerce-Price-amount')->text();
                            return $realPrice;
                        }else{
                            Log::channel('scraping')->info('Woocommerce: no se encontro precio real en '.$this->urlRealPrice);
                        }
                        
                    });

                    $price = removeSpecialCharacters($containerRealPrice[0]);

                    return $price;
                }else{
                    
                    Log::channel('scraping')->info('Woocommerce: No se encuentran o no se puede acceder a la informacion del precio real del sitio '.$url);
                    return null;
                }

            
            } catch (Exception $e) {
                Errors::create([ 'shop_id'=>$shop, 'url' => $url,'error' => $e->getMessage() ]);
                Log::channel('scraping')->error('Se produjo un error en Woocommerce: url :'.$url.', error: '. $e->getMessage());
                return null;
            }
            
        }
    }

    public function searchLabeledPrice($shop,$url){
        $this->woo = Suggestion::labeledPrice()->wherePlatformId(self::WOOCOMMERCE)->get();

        if($this->woo){
            $this->containerType = $this->woo->get(0)->type_search;
            $this->containerDescription = $this->woo->get(0)->description;

            try {
                $scrawler = $this->request('GET',$url);
                $exist = $scrawler->filter("p.".$this->containerDescription)->count();

                if($exist){
                      $this->etiqueta = $this->woo->get(1)->description;
                    $containerlabeledPrice = $scrawler->filter("p.".$this->containerDescription)->each(function ($labeledPriceNode){
                        
                        $existLabeledPrice = $labeledPriceNode->filter($this->etiqueta)->count();
                        $existLabeledPriceList = $labeledPriceNode->filter('[class=list-price]')->count();
    
                        if($existLabeledPrice){
                            $labeledPrice = $labeledPriceNode->filter($this->etiqueta);
                            return $labeledPrice->text();
                        }elseif($existLabeledPriceList){
                            $listPrice = $labeledPriceNode->filter('[class=list-price]');
                            $labeledPrice = $listPrice->filter('span.woocommerce-Price-amount')->text();
                            return $labeledPrice;
                        }else{
                            Log::channel('scraping')->info('Woocommerce: no se encontro precio tachado en '.$this->urlRealPrice);
                        }
                        //no hay precio tachado
                        return null;
                        
                    });

                    $price =  ($containerlabeledPrice[0] != null) ? removeSpecialCharacters($containerlabeledPrice[0]) : null;

                    return $price;
            
                }else{
                    
                    Log::channel('scraping')->info('Woocommerce: No se encuentran o no se puede acceder a la informacion del precio tachado del sitio '.$url);
                    return null;
                }

            } catch (Exception $e) {
                Errors::create([ 'shop_id'=>$shop, 'url' => $url,'error' => $e->getMessage() ]);
                Log::channel('scraping')->error('Se produjo un error en Woocommerce: '.
                json_encode(['error'=> $e->getMessage(),'url' =>$url]));
                return null;
            }
        }
    }
}