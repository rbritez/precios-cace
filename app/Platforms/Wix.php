<?php
namespace App\Platforms;

use App\Errors;
use App\Suggestion;
use Exception;
use Goutte\Client as GoutteClient;
use Illuminate\Support\Facades\Log;

final class Wix extends GoutteClient{
    // ejemplo : https://cityjenifertienda.wixsite.com/minorista/product-page/copia-de-remera-cadenita
    public $wix;
    const WIX = 6;

    public function searchRealPrice($shop,$url){
        $this->wix = Suggestion::realPrice()->wherePlatformId(self::WIX)->first();

        if($this->wix){
            
            try {
                $scrawler = $this->request('GET',$url);
                $exist = $scrawler->filter("[".$this->wix->type_search."=".$this->wix->description."]")->count();
                
                if($exist){
                    $realPrice = $scrawler->filter("[".$this->wix->type_search."=".$this->wix->description."]")->first();
                    $price = removeSpecialCharacters($realPrice->text());
                    return $price;
                }else{
                    Log::channel('scraping')->info('WIX: No se encuentran o no se puede acceder a la informacion del sitio '.$url);
                    return null;
                }
            } catch (Exception $e) {
                Errors::create([ 'shop_id'=>$shop, 'url' => $url,'error' => $e->getMessage() ]);
                Log::channel('scraping')->error('Se produjo un error en WIX: '.json_encode(['error'=> $e->getMessage(),'url'=>$url]));
                return null;
            }

        }
        
    }

    public function searchLabeledPrice($shop,$url){
        $this->wix = Suggestion::labeledPrice()->wherePlatformId(self::WIX)->first();

        if($this->wix){

            try {
                $scrawler = $this->request('GET',$url);
                $exist = $scrawler->filter("[".$this->wix->type_search."=".$this->wix->description."]")->count();

                if($exist){
                    $realPrice = $scrawler->filter("[".$this->wix->type_search."=".$this->wix->description."]")->first();
                    $price = removeSpecialCharacters($realPrice->text());
                    return $price;
                }else{
                    Log::channel('scraping')->info('WIX: No se encuentran o no se puede acceder a la informacion del sitio '.$url);
                    return null;
                }
                
            } catch (Exception $e) {
                Errors::create([ 'shop_id'=>$shop, 'url' => $url,'error' => $e->getMessage() ]);
                Log::channel('scraping')->error('Se produjo un error en WIX: '.json_encode(['error'=> $e->getMessage(),'url'=>$url]));
                return null;
            }
        }
    }
}