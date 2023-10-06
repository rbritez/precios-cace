<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    protected $fillable =[
        'name',
        'prefix'
    ];
    
    public function suggestion(){
        return $this->belongsTo(Suggestion::class);
    }
}