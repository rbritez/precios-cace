<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    protected $fillable =[
        'revision_init',
        'revision_end',
        'event_id'
    ];

    protected $dates=[
        'revision_init',
        'revision_end',
    ];
    
    protected $guarded = [];


    public function event(){
        return $this->belongsTo(Event::class);
    }
}