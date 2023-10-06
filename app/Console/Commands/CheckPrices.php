<?php

namespace App\Console\Commands;

use App\Event;
use App\Jobs\searchPrices;
use App\Platform;
use App\Platforms\Shopify;
use App\Platforms\Vtex;
use App\Platforms\Wix;
use App\Platforms\Magento;
use Illuminate\Console\Command;
use App\Platforms\Woocommerce;


class CheckPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revisa los precios de los productos de los eventos activos';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $events = Event::all();
        
        $bar = $this->output->createProgressBar(count($events));

        foreach ($events as $key => $event) {

            $inicio = $event->measurement->get(0)->revision_init;
            $fin = $event->measurement->get(0)->revision_end;
            $now = now()->format('Y-m-d H:i:s');
            if($inicio < $now && $fin > $now){
                foreach ($event->shops as $key => $shop) {
                    $platform = $shop->platform;
                    $url = $shop->url;
                    //if($shop->id == 10){
                        foreach ($shop->products as $key => $product) {
                            $urlProduct = $product->url;
                            $urlHttps = str_starts_with($urlProduct,'https');
                            $urlHttpss = str_starts_with($urlProduct,'Https');

                            if($urlHttps == true || $urlHttpss ==true){
                                $fullUrl = $urlProduct;
                            }else{
                                $fullUrl = $shop->url.$urlProduct;
                            }
                            if($platform != null && $platform != ''){
                               searchPrices::dispatch($platform,$fullUrl,$event->id,$shop->id,$product->id)->delay(now()->addSeconds(5));
                                //$this->selectPlatform($platform,$fullUrl);
                            }
                        }
                       $bar->advance();
                    //}
                }
            }
            
        }
        $bar->finish();
    } 

    

    // public function selectPlatform($platform,$url){
    
    //   $p = Platform::wherePrefix($platform)->first();
      
    //   $id = isset($p->id)? $p->id:10;

    //     switch ($id) {
    //         case 1: $response = new Vtex; break;
    //         case 2: $response = new Magento; break;
    //         case 3: $response = new Tiendanube; break;
    //         case 4: $response = new Woocommerce; break;
    //         case 5: $response = new Prestashop; break;
    //         case 6: $response = new Wix; break;
    //         case 7: $response = new MercadoShop; break;
    //         case 8: $response = new Shopify; break;
    //         default: $response = new Alternative; break;
    //     }

    //     $realPrice = $response->searchRealPrice($url);
    //     $labeledPrice =$response->searchLabeledPrice($url);
    //     $prices = [$realPrice,$labeledPrice];

    //     if($prices[0] == $prices[1]){
    //         $prices[1] = null;
    //     }
    //     dd($prices);
    //             //verficar si los precios son iguales, si son iguales solo dejar realPrice
    //     return $prices;

    // } 

}