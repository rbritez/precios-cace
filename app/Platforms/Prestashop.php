<?php

namespace App\Platforms;

use App\Errors;
use App\Suggestion;
use Exception;
use Goutte\Client as GoutteClient;
use Illuminate\Support\Facades\Log;

final class Prestashop extends GoutteClient{

    public $prestaShop;
    public $realPriceContainerType;
    public $realPriceContainerDescription;

    public $labeledPriceContainerType;
    public $labeledPriceContainerDescription;
    const PRESTASHOP = 5;
    
    public function searchRealPrice($shop,$url){
        $this->prestaShop = Suggestion::realPrice()->wherePlatformId(self::PRESTASHOP)->get();
        
        if($this->prestaShop){
            $this->realPriceContainerType = $this->prestaShop->get(0)->type_search;
            $this->realPriceContainerDescription = $this->prestaShop->get(0)->description;
            try {

                $scrawler = $this->request('GET',$url);
                $exist = $scrawler->filter("[".$this->realPriceContainerType."=".$this->realPriceContainerDescription."]")->count();
                if($exist){

                     $containerRealPrice = $scrawler->filter("[".$this->realPriceContainerType."=".$this->realPriceContainerDescription."]")->each(function ($realPriceNode){
                        
                        //buscamos dentro del contenedor del precio real de venta
                        $realPrice = $realPriceNode->filter("[".$this->prestaShop->get(1)->type_search."=".$this->prestaShop->get(1)->description."]")->first();
                        return $realPrice->text();
                    });
                    
                    $price = removeSpecialCharacters($containerRealPrice[0]);
                   
                    return $price;
                }else{
                    
                    Log::channel('scraping')->info('Prestashop: No se encuentran o no se puede acceder a la informacion del sitio '.$url);
                    return null;
                }

            } catch (Exception $e) {
                Errors::create([ 'shop_id'=>$shop, 'url' => $url,'error' => $e->getMessage() ]);
                Log::channel('scraping')->error('Se produjo un error en Prestashop: '.
                json_encode(['error'=> $e->getMessage(),'url'=> $url]));
                
                return null;
            }
        }
 
    }

    public function searchLabeledPrice($shop,$url){
        $this->prestaShop = Suggestion::labeledPrice()->wherePlatformId(self::PRESTASHOP)->get();
        
        if($this->prestaShop){
            $this->labeledPriceContainerType = $this->prestaShop->get(0)->type_search;
            $this->labeledPriceContainerDescription = $this->prestaShop->get(0)->description;
            try {

                $scrawler = $this->request('GET',$url);
                $exist = $scrawler->filter("[".$this->labeledPriceContainerType."=".$this->labeledPriceContainerDescription."]")->count();
                if($exist){

                     $containerLabeledPrice = $scrawler->filter("[".$this->labeledPriceContainerType."=".$this->labeledPriceContainerDescription."]")->each(function ($labeledPriceNode){
                        
                        //buscamos dentro del contenedor del precio real de venta
                        $realPrice = $labeledPriceNode->filter("[".$this->prestaShop->get(1)->type_search."=".$this->prestaShop->get(1)->description."]")->first();
                        return $realPrice->text();
                    });
                    
                    $price = removeSpecialCharacters($containerLabeledPrice[0]);
                   
                    return $price;   
                }else{
                      
                    Log::channel('scraping')->info('Prestashop: No se encuentran o no se puede acceder a la informacion del sitio '.$url);
                    return null;
                }

            } catch (Exception $e) {
                Errors::create([ 'shop_id'=>$shop, 'url' => $url,'error' => $e->getMessage() ]);
                Log::channel('scraping')->error('Se produjo un error en Prestashop: '.
                json_encode(['error'=> $e->getMessage(),'url'=> $url]));
                
                return null;
            }
        }
 
    }
}