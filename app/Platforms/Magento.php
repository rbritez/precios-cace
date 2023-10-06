<?php
namespace App\Platforms;

use App\Errors;
use App\Suggestion;
use Exception;
use Goutte\Client as GoutteClient;
use Illuminate\Support\Facades\Log;

final class Magento extends GoutteClient{
    //ejemplo https://www.47street.com.ar/

    public $magento;
    public $containerType;
    public $containerDescription;
    const MAGENTO =2;

    public function searchRealPrice($shop,$url){
        
        $this->magento = Suggestion::realPrice()->wherePlatformId(self::MAGENTO)->first();
        if($this->magento){
            $this->containerType = $this->magento->type_search;
            $this->containerDescription = $this->magento->description;
           
            try {
                $scrawler = $this->request('GET',$url);
                $exist = $scrawler->filter("[".$this->containerType."=".$this->containerDescription."]")->count();
                if($exist){
                    $realPrice =$scrawler->filter("[".$this->containerType."=".$this->containerDescription."]")->first();
                   
                    $price = removeSpecialCharacters($realPrice->text());
                    
                    return $price;
                }else{
                    //Errors::create([ 'shop_id'=>$shop, 'url' => $url,'error' => 'Magento: No se encuentran o no se puede acceder a la informacion del sitio ' ]);
                    Log::channel('scraping')->info('Magento: No se encuentran o no se puede acceder a la informacion del sitio '.$url);
                    return null;
                }
            } catch (Exception $e) {
                Errors::create([ 'shop_id'=>$shop, 'url' => $url,'error' => $e->getMessage() ]);
                Log::channel('scraping')->error('Se produjo un error en Magento: '.json_encode(['error'=> $e->getMessage(),'url'=>$url]));
                return null;
            }
        }
        
    }

    public function searchLabeledPrice($shop,$url){

        $this->magento = Suggestion::labeledPrice()->wherePlatformId(self::MAGENTO)->first();

        if($this->magento){
            $this->containerType = $this->magento->type_search;
            $this->containerDescription = $this->magento->description;

            try {
                
                $scrawler = $this->request('GET',$url);
                $exist = $scrawler->filter("[".$this->containerType."=".$this->containerDescription."]")->count();
                
                if($exist){
                    $labeledPrice =$scrawler->filter("[".$this->containerType."=".$this->containerDescription."]")->first();
                    $price = removeSpecialCharacters($labeledPrice->text());

                    return $price;
                }else{
                    Log::channel('scraping')->info('Magento: No se encuentran o no se puede acceder a la informacion del sitio '.$url);
                    return null;
                }
            } catch (Exception $e) {
                Errors::create([ 'shop_id'=>$shop, 'url' => $url,'error' => $e->getMessage() ]);
                Log::channel('scraping')->error('Se produjo un error en Magento: '.json_encode(['error'=> $e->getMessage(),'url'=>$url]));
                return null;
            }
        }
    
    }
}