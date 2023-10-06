<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Product
 * @method static Attribute|Product statusName()
 * @method static Scope|Product whereIsActive()
 */
class Product extends Model
{
    protected $fillable =[
        'name',
        'url',
        'status',
    ];

    const ACTIVE = 1;
    const INACTIVE = 0;

    private $statusDescription =[
        self::INACTIVE  => 'Inactivo',
        self::ACTIVE    => 'Activo',
    ];

    public function history(){
        return $this->hasMany(HistoryPrice::class);
    }
    
    public function alterations(){
        return $this->hasMany(Alteration::class);
    }

    public function getStatusNameAttribute()
    {
        return $this->statusDescription[$this->status];
    }

    /**
     * return All Categories with status 1
     * 
     * @return mixed
     */
    public function scopeWhereIsActive(){
        
        return $this->where('status',self::ACTIVE);
    }

}