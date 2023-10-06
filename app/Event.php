<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name',
        'event_init',
        'event_end',
        'status_id',
    ];

    protected $dates = [
        'event_init',
        'event_end',
    ];

    /**
     * relation function
     *
     * @return BelongsTo
     */
    public function status(){
        
        return $this->belongsTo(EventStatus::class);
    }

    public function shops(){
        return $this->belongsToMany(Shop::class,'event_shop')->withTimestamps();
    }

    public function productsShop(){
        
        return $this->belongsToMany(Shop::class,'event_shop_product')->withPivot('shop_id','product_id');
    }

    public function alterations(){
        return $this->hasMany(Alteration::class);
    }

    /**
     * relation function
     *
     * @return BelongsTo
     */
    public function measurement(){
        
        return $this->hasMany(Measurement::class);
    }

    public function updateStatus($event_init,$event_end){
        $event_init = new DateTime($event_init);
        $event_end = new DateTime($event_end);
        $now = new DateTime(now());
        
        if($event_init > $now){
            $this->update(['status_id'=> 1]);
        }
        if($event_init < $now){
            $this->update(['status_id'=> 2]);
        }
        if($event_end < $now){
            $this->update(['status_id'=> 3]);
        }
    }

    public function scopeIsActive(){
        return $this->where('status_id',2); //evento en curso
    }
    
    public function getColorStatusAttribute(){

        $circle = '';
        
        switch ($this->status_id) {
            case 1:
                $circle = 'bg-gradient-yellow';
                break;
            case 2:
                $circle = 'bg-gradient-green';
            break;
            case 3:
                $circle = 'bg-gradient-red';
            break;
            
            default:
                $circle = 'bg-gradient-secondary';
            break;
        }
        return $circle;
    }
}