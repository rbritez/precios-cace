<?php

namespace App\Platforms;

use App\Errors;
use App\Suggestion;
use Exception;
use Goutte\Client as GoutteClient;
use Illuminate\Support\Facades\Log;

/**
 * @property mixed $shopify
 * @property float idRealPrice;
 * @property float $idLabeledPrice;
 */
final class Shopify extends GoutteClient{
    
    // ejemplo: https://www.craftsociety.com.ar/
    //hay que buscar id o por class

    public $shopify;
    public $idRealPrice;
    public $idLabeledPrice;

    const SHOPIFY = 8;

    /**
     * Search real price 
     *
     * @param string $url
     * @return float
     */
    public function searchRealPrice($shop,$url){
        $this->shopify = Suggestion::realPrice()->wherePlatformId(self::SHOPIFY)->get();
    
        if($this->shopify){
                
                $this->idRealPrice = $this->shopify;
                try {
                    $scrawler = $this->request('GET',$url);
                    foreach ($this->idRealPrice as $methodSearch) {
                        //verificamos si encuentra por ID
                        $priceHTML = $scrawler->filter("[".$methodSearch->type_search."=".$methodSearch->description."]")->count();
                        if($priceHTML > 0){
                            
                            $priceHTML = $scrawler->filter("[".$methodSearch->type_search."=".$methodSearch->description."]")->first();
                            
                            session()->put('type_search',$methodSearch->type_search);

                            $price = removeSpecialCharacters($priceHTML->html());
                            
                            
                            return $price;
                        }
                    }
                    Log::channel('scraping')->info('Shopify: No se encuentran o no se puede acceder a la informacion del sitio '.$url);
                    return null;

                } catch (Exception $e) {
                    Errors::create([ 'shop_id'=>$shop, 'url' => $url,'error' => $e->getMessage() ]);
                    Log::channel('scraping')->error('Se produjo un error en Shopify: '.json_encode(['error'=> $e->getMessage()]));
                    return null;
                }
        }else{
            Log::channel('scraping')->info('No se encuentran metodos de busqueda para Shopify');
            return null;
        }
    }

    public function searchLabeledPrice($shop,$url){
        
        $this->idLabeledPrice =  $this->shopify = Suggestion::typeSearch()->whereRealPrice(0)->wherePlatformId(self::SHOPIFY)->first();
        
        if($this->idLabeledPrice){
                //$cliente = new GoutteClient();
                try {
                    $scrawler = $this->request('GET',$url);
                  
                        //verificamos si encuentra 
                        $exist = $scrawler->filter("[".$this->idLabeledPrice->type_search."=".$this->idLabeledPrice->description."]")->count();

                        if($exist > 0){
                            $priceHTML = $scrawler->filter("[".$this->idLabeledPrice->type_search."=".$this->idLabeledPrice->description."]")->first();
                            $price = removeSpecialCharacters($priceHTML->html());
                            
                            return $price;
                        }else{
                            Log::channel('scraping')->info('Shopify: No se encuentran o no se puede acceder a la informacion del sitio '.$url);
                            return null;
                        }

                } catch (Exception $e) {
                    Errors::create([ 'shop_id'=>$shop, 'url' => $url,'error' => $e->getMessage() ]);
                    Log::channel('scraping')->error('Se produjo un error: '.json_encode(['error'=> $e->getMessage()]));
                    return null;
                }
        }else{
            Log::channel('scraping')->info('No se encuentran metodos de busqueda para Tiendanube');
            return null;
        }
    }

}