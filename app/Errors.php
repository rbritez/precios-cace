<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Errors extends Model
{
    protected $fillable = [
        'shop_id',
        'url',
        'error'
    ];    

    public function shop(){
        return $this->belongsTo(Shop::class);
    }
}
