<?php

namespace App\Jobs;

use App\Platforms\Alternative;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Events\CheckAlterationsEvent;
use App\HistoryPrice;
use App\Platform;
use App\Platforms\Magento;
use App\Platforms\MercadoShop;
use App\Platforms\Prestashop;
use App\Platforms\Tiendanube;
use App\Platforms\Shopify;
use App\Platforms\Vtex;
use App\Platforms\Woocommerce;
use App\Platforms\Wix;

class searchPrices implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $url;
    public $platform;
    public $event;
    public $shop;
    public $product;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($platform, $url, $eventId, $shopId, $productId)
    {
        $this->url = $url;
        $this->platform = $platform;
        $this->event = $eventId;
        $this->shop = $shopId;
        $this->product = $productId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $prices = $this->selectPlatform($this->platform,$this->shop, $this->url);

        if ($prices[0] != null && $prices[0] != 0) {
            $this->saveHistory($this->event, $this->shop, $this->product, $prices);
        }
    }

    public function saveHistory($event, $shop, $product, $prices)
    {
        //crear un evento y listener para verificar alteracion
        event(new CheckAlterationsEvent($event, $shop, $product, $prices));

        $history = HistoryPrice::create([
            'event_id'      => $event,
            'shop_id'       => $shop,
            'product_id'    => $product,
            'real_price'    => $prices[0],
            'labeled_price' => ($prices[1] == 0) ? null : $prices[1]
        ]);
    }

    public function selectPlatform($platform,$shop ,$url)
    {

        $p = Platform::wherePrefix(strtolower($platform))->first();
        $id = isset($p->id) ? $p->id : 10;
        switch ($id) {

            case 1: $response = new Vtex; break;
            case 2: $response = new Magento; break;
            case 3: $response = new Tiendanube; break;
            case 4: $response = new Woocommerce; break;
            case 5: $response = new Prestashop; break;
            case 6: $response = new Wix; break;
            case 7: $response = new MercadoShop; break;
            case 8: $response = new Shopify; break;
            default:
                //$response = new Alternative;
            break;
        }

        if(isset($response) && $response != null && $response != ''){
            $realPrice = $response->searchRealPrice($shop,$url);
            $labeledPrice = $response->searchLabeledPrice($shop,$url);
            $prices = [$realPrice, $labeledPrice];

            if ($prices[0] == $prices[1]) {
                $prices[1] = null;
            }
            //verficar si los precios son iguales, si son iguales solo dejar realPrice
            return $prices;
        }
    }
}