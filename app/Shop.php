<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Shop
 * @method static Builder|Shop  addProducts($product,$url)
 * @method static Builder|Shop removeAllProducts()
 */

class Shop extends Model
{
    protected $fillable = [
        'name',
        'url',
        'platform',
    ];

    public function products(){
        return $this->belongsToMany(Product::class)->withTimestamps()->withPivot('url');
    }

    public function events(){

        return $this->belongsToMany(Event::class);
    }

    public function productsEvent($event_id){
        return $this->belongsToMany(Event::class,'event_shop_product')->withPivot('event_id','product_id')->wherePivot('event_id','=',(int)$event_id);
    }

    public function alterations(){
        return $this->hasMany(Alteration::class);
    }

    public function history(){
        return $this->hasMany(HistoryPrice::class);
    }

    public function errors(){
        return $this->hasMany(Errors::class);
    }

    /**
     * addProducts
     *
     * @param string $product
     * @param string $url
     * @return void
     */
    public function addProducts($product,$url){
        $this->products()->attach($product,['url'=>$url]);
    }
    
    /**
     * removeAllProducts
     *
     * @return void
     */
    public function removeAllProducts(){
        $this->products()->detach();
    }

}