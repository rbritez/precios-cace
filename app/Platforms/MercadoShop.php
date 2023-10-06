<?php

namespace App\Platforms;

use App\Errors;
use App\Suggestion;
use Exception;
use Goutte\Client as GoutteClient;
use Illuminate\Support\Facades\Log;

/**
 * App\Platforms\MercadoShop
 * 
 * @property mixed $mercadoShop
 * @property string $idRealPrice
 * @property string $descriptionRealPrice
 * @property string $descriptionLabeledPrice
 */
final class MercadoShop extends GoutteClient{

    //ejemplo : https://www.wolfings.com.ar/

    public $mercadoShop;
    public $idRealPrice;
    public $descriptionRealPrice;
    public $descriptionLabeledPrice;

    const MERCADOSHOP = 7;


    public function searchRealPrice($shop,$url){

        $this->mercadoShop = Suggestion::realPrice()->wherePlatformId(self::MERCADOSHOP)->get();
        
        if($this->mercadoShop){
            //contenedor 
            $this->idRealPrice = $this->mercadoShop->get(0)->type_search; 
            $this->descriptionRealPrice = $this->mercadoShop->get(0)->description;
            
            try {
                
                $scrawler = $this->request('GET',$url);
                $exist = $scrawler->filter("[".$this->idRealPrice."=".$this->descriptionRealPrice."]")->count();
                if($exist){
                    $containerRealPrice = $scrawler->filter("[".$this->idRealPrice."=".$this->descriptionRealPrice."]")->each(function ($realPriceNode){
                        
                        //buscamos dentro del contenedor del precio real de venta
                        $realPrice = $realPriceNode->filter("[".$this->mercadoShop->get(1)->type_search."=".$this->mercadoShop->get(1)->description."]")->first();
                        return $realPrice->text();
                        
                    });
            
                    $price = removeSpecialCharacters($containerRealPrice[0]);
                   
                    return $price;                
                }else{
                    Log::channel('scraping')->info('Mercadoshop: No se encuentran o no se puede acceder a la informacion del sitio '.$url);
                    return null;
                }

            } catch (Exception $e) {

                Errors::create([ 'shop_id'=>$shop, 'url' => $url,'error' => $e->getMessage() ]);
                Log::channel('scraping')->error('Se produjo un error: '.json_encode(['error'=> $e->getMessage(),'url'=>$url]));
                return null;
            }

        }
    }

    public function searchLabeledPrice($shop,$url){
        $this->mercadoShop = Suggestion::labeledPrice()->wherePlatformId(self::MERCADOSHOP)->get();

        if($this->mercadoShop){
            
            //conteendor precio tachado
            
            $this->descriptionLabeledPrice = $this->mercadoShop->get(0)->description;
            try {
                
                $scrawler = $this->request('GET',$url);
                $exist = $scrawler->filter("s.".$this->descriptionLabeledPrice."")->count();
                if($exist){
                    $containerLabeledPrice = $scrawler->filter("s.".$this->descriptionLabeledPrice."")->each(function($labeledPriceNode){
                        
                        $labeledPrice =$labeledPriceNode->filter("[".$this->mercadoShop->get(1)->type_search."=".$this->mercadoShop->get(1)->description."]")->first();
                        
                        return $labeledPrice->text();
                        
                    });

                    $price = removeSpecialCharacters($containerLabeledPrice[0]);
                   
                    return $price;   
                    
                }else{
                    Log::channel('scraping')->info('Mercadoshop: No se encuentran o no se puede acceder a la informacion del sitio '.$url);
                    return null;
                }
            } catch (Exception $e) {
                Errors::create([ 'shop_id'=>$shop, 'url' => $url,'error' => $e->getMessage() ]);
                Log::channel('scraping')->error('Se produjo un error: '.json_encode(['error'=> $e->getMessage(),'url'=>$url]));
                return null;
            }


        }
    }

}