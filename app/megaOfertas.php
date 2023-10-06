<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class megaOfertas extends Model
{
    protected $fillable =[
        'external_id',
        'sku',
        'marca',
        'link_anterior',
        'link_oferta',
        'titulo',
        'precio anterior',
        'precio_en_oferta',
        'categoria',
    ];
}
