<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Suggestion
 * 
 * @method static Builder|Suggestion realPrice()
 * @method static Builder|Suggestion labeledPrice()
 * @method static Builder|Suggestion typeSearch()
 * @method static Builder|Suggestion typeSearchVtex()
 * @method static Builder|Suggestion wherePlatformId($platform)
 * @method static Builder|Suggestion whereRealPrice($int)
 */
class Suggestion extends Model
{
    protected $fillable = [
        'platform_id',
        'real_price',
        'type_search',
        'description'
    ];
    const TYPE_CSS = 'class';
    const TYPE_ID = 'id';
    const TYPE_HTML = 'etiqueta HTML';
    const REAL_PRICE = 1;
    const LABELED_PRICE= 0;
    
    public function platform(){
        return $this->hasMany(Platform::class);
    }

    /**
     * realPrice
     *
     * @return Builder
     */
    public function scopeRealPrice(){
        
        return $this->where('real_price',self::REAL_PRICE);
    }

    /**
     * labeledPrice
     *
     * @return Builder
     */
    public function scopeLabeledPrice(){
        
        return $this->where('real_price',self::LABELED_PRICE);
    }

    /**
     * typeSearch
     *
     * @return void|Builder
     */
    public function scopeTypeSearch(){
        
        if(session()->has('type_search')){

            $type = session()->get('type_search');
            session()->forget('type_search');
            
            return $this->where('type_search',$type);
        }

    }

    /**
     * typeSearchVtex
     *
     * @return void|Builder
     */
    public function scopeTypeSearchVtex(){
        
        if(session()->has('method')){

            $type = session()->get('method');
            session()->forget('method');
            if($type == 0){
                return $this->where('description','skuListPrice');
            }else{
                return $this->where('description','!=','skuListPrice');
            }
            
        }

    }

}