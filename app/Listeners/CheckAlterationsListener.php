<?php

namespace App\Listeners;

use App\Alteration;
use App\Events\CheckAlterationsEvent;
use App\HistoryPrice;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class CheckAlterationsListener
{
    public $type;
    public $newRealPrice;
    public $newLabeledPrice;
    public $oldRealPrice;
    public $oldLabeledPrice;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(CheckAlterationsEvent $event)
    {
        $lastPrice = HistoryPrice::where('event_id',$event->event_id)->
                                   where('shop_id',$event->shop_id)->
                                   where('product_id',$event->product_id)->
                                   orderby('created_at','DESC')->take(1)->get();
                        
        if(count($lastPrice) > 0){
            $this->oldRealPrice = (float)$lastPrice[0]->real_price;
            $this->oldLabeledPrice = (float )$lastPrice[0]->labeled_price;
            $this->newRealPrice = $event->prices[0];
            $this->newLabeledPrice = $event->prices[1];
            
            //comparamos precios
            $this->compareAndSaveRealPrice($event,$this->newRealPrice,$this->oldRealPrice);
            $this->compareAndSaveLabeledPrice($event,$this->newLabeledPrice,$this->oldLabeledPrice);
            
            Log::info(json_encode($event));
        }
    }

    public function compareAndSaveRealPrice($event,$newRealPrice,$oldRealPrice){

        if($oldRealPrice > $newRealPrice){
            //si ahora bajaron de precio //0 =subio el precio, 1 = el precio baja
            $this->type = 1; 
        }elseif($oldRealPrice < $newRealPrice){
            $this->type = 0;
        }else{
            //si sigue igual
            $this->type = 2;
        }

        if($this->type != 2){
            Alteration::create([
                'event_id' => $event->event_id,
                'product_id'=>$event->product_id,
                'shop_id' => $event->shop_id,
                'type' => $this->type,
                'real_price' => 1,
                'price_previous' => $oldRealPrice,
                'price_now' => $newRealPrice
            ]);
        }
    }

    public function compareAndSaveLabeledPrice($event,$newLabeledPrice,$oldLabeledPrice){

        //si hay precio nuevo tachado pasa
        if($newLabeledPrice != null && $newLabeledPrice != 0){

            if($oldLabeledPrice > $newLabeledPrice){
                //si ahora bajaron de precio //0 =subio el precio, 1 = el precio baja
                $this->type = 1; 
            }elseif($oldLabeledPrice < $newLabeledPrice){
                $this->type = 0;
            }else{
                //si sigue igual
                $this->type = 2;
            }
            if($this->type != 2){
                Alteration::create([
                    'event_id' => $event->event_id,
                    'product_id'=>$event->product_id,
                    'shop_id' => $event->shop_id,
                    'type' => $this->type,
                    'real_price' => 0,
                    'price_previous' => $oldLabeledPrice,
                    'price_now' => $newLabeledPrice
                ]);
            }
        }
    }
}