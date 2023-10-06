<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Notification
 * 
 * @method static Builder|Notification noConfirmed($query)
 * @method static Builder|Notification today($query)
 * 
 */
class Notification extends Model
{
    protected $fillable = [
        'alteration_id',
        'notified'
    ];

    public function alteration(){
        return $this->belongsTo(Alteration::class);
    }

    public function scopeNoConfirmed(Builder $query){
        return $query->where('notified',0);
    }

    public function scopeToday(Builder $query){
        return $query->whereBetween('created_at',[today()->startOfDay(),today()->endOfDay()]);
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
            return $query->whereBetween('alterations.created_at',[$from,$to]);
        }

        if(isset($from)){
            return $query->where('alterations.created_at','>=',$from);
        }

        if(isset($to)){
            return $query->where('alterations.created_at','<=',$to);
        }

    }
}
