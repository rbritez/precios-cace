<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Alteration extends Model
{
    protected $fillable =[
        'event_id',
        'product_id',
        'shop_id',
        'type', // si subio o bajo el precio [0,1]  //0 =subio el precio, 1 = el precio baja
        'real_price', //si es precio real o precio tachado [1,0]
        'price_previous',
        'price_now',
    ];

    const TYPE_PRICE_UP = 0;
    const TYPE_PRICE_DOWN = 1;
    const REAL_PRICE = 1;
    const LABELED_PRICE = 0;
     

    public function shop(){
        return $this->belongsTo(Shop::class);
    }

    public function event(){
        return $this->belongsTo(Event::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function notifications(){
        return $this->hasMany(Notification::class);
    }

    public function getTypePriceAttribute(){
        return ($this->real_price == 1)? 'Precio Oferta' : 'Precio Tachado';
    }
    public function getTypeNameAttribute(){
        return ($this->type == 1) ? 'Bajo Precio' : 'Subio Precio';
    }

    public function getUrlFormatAttribute(){
        $urlProduct = $this->product->url;
        $urlHttps = str_starts_with($urlProduct,'https');
        $urlHttpss = str_starts_with($urlProduct,'Https');

        if($urlHttps == true || $urlHttpss ==true){
            $fullUrl = $urlProduct;
        }else{
            
            $fullUrl = $this->shop->url.$urlProduct;
        }
        
        return $fullUrl;
    }

    /**
     * Filtro por fecha
     * @return Builder
     */
    public function scopeDateFilter(Builder $query){
        if(isset(request()->from ) && request()->from != ''){
           $from =  Carbon::parse(request()->from)->startOfDay();
        }
        if(isset(request()->to ) && request()->to != ''){
            $to = Carbon::parse(request()->to)->endOfDay();
        }
        
        if(isset($from) && isset($to)){
            return $query->whereBetween('created_at',[$from,$to]);
        }

        if(isset($from)){
            return $query->where('created_at','>=',$from);
        }

        if(isset($to)){
            return $query->where('created_at','<=',$to);
        }

    }

}