<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
/**
 * App\HistoryPrice
 * @method static Builder|HistoryPrice thisMonth()
 * @method static Builder|HistoryPrice thisDay()
 * @method static Builder|HistoryPrice thisWeek()
 * @method static Builder|HistoryPrice dateFilter()
 */
class HistoryPrice extends Model
{
    protected $table = 'history_prices';

    protected $fillable =[
        'event_id',
        'shop_id',
        'product_id',
        'real_price',
        'labeled_price',
        'created_at'
    ];
    
    protected $dates =[
        'created_at',
        'updated_at'
    ];

    /**
     * filtro por el ultimo mes
     * @return Builder
     */
    public function scopeThisMonth()
    {
        // limit startMonth / endMonth
        $from = today()->startOfMonth();
        $to = today()->endOfMonth();

        return $this->whereBetween('created_at',[$from, $to]);
    }
    
    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function shop(){
        return $this->belongsTo(Shop::class);
    }

    /**
     * Filtro por el comienzo del dia
     * @return Builder
     */
    public function scopeThisDay()
    {
        return $this->where('created_at', '>=', today()->startOfDay());

    }

    /**
     * Filtro por el comienzo del dia
     * @return Builder
     */
    public function scopeThisWeek()
    {
        return $this->where('created_at', '>=', today()->startOfDay()->subDays(7));

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

    public function getMaxPriceAttribute(){

        $maxPrice = ($this->labeled_price && $this->labeled_price != '' ) ? $this->labeled_price : $this->real_price;

        return $maxPrice;
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
}