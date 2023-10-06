<?php

namespace App\Platforms;

use App\Errors;
use App\Suggestion;
use Exception;
use Goutte\Client as GoutteClient;
use Illuminate\Support\Facades\Log;

final class Tiendanube extends GoutteClient{

    public $tiendanube;
    public $idRealPrice;
    public $idLabeledPrice;
    const TIENDANUBE = 3;

    

    public function searchRealPrice($shop,$url){
    
        $this->tiendanube = Suggestion::realPrice()->wherePlatformId(self::TIENDANUBE)->first();

        if($this->tiendanube){
        
            $this->idRealPrice = $this->tiendanube;
            
                try {
                    $scrawler = $this->request('GET',$url);
                    $priceHTML = $scrawler->filter("[".$this->idRealPrice->type_search."=".$this->idRealPrice->description."]")->first(); 
                    $price = removeSpecialCharacters($priceHTML->html());
                    
                    return $price;
                    
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

    public function searchLabeledPrice($shop,$url){

        $this->tiendanube = Suggestion::labeledPrice()->wherePlatformId(self::TIENDANUBE)->first();

        if($this->tiendanube){
            $this->idlabeledPrice =  $this->tiendanube;
            try {
                $scrawler = $this->request('GET',$url);
                $priceHTML = $scrawler->filter("[".$this->idlabeledPrice->type_search."=".$this->idlabeledPrice->description."]")->first(); 
                
                $price = removeSpecialCharacters($priceHTML->html());
                
                return $price; // se comprobo que si no existe devuelve 0

            } catch (\Throwable $e) {
                Errors::create([ 'shop_id'=>$shop, 'url' => $url,'error' => $e->getMessage() ]);
                Log::channel('scraping')->error('Se produjo un error en Tiendanube: '.json_encode(['error'=> $e->getMessage(),'url'=>$url]));
                return null;
            }

        }else{
            Log::channel('scraping')->info('No se encuentran metodos de busqueda para Tiendanube');
            return null;
        }
    }
}