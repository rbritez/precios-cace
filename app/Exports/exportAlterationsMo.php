<?php

namespace App\Exports;

use App\Notification;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class exportAlterationsMo implements FromCollection,WithHeadings
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array
    {
        return [
            'fecha',
            'producto',
            'url',
            'tipo_precio',
            'tipo_alteracion',
            'precio_anterior',
            'precio_actual'
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if(isset(request()->from) || isset(request()->to)){
            $history = Notification::join('alterations','alterations.id','=','notifications.alteration_id')->dateFilter()->get();
        }else{
            $history = Notification::all();
        }
        if(count($history) == 0){
            $array = [];
        }else{
            foreach ($history as $a) {
                $array[] = [
                    'fecha'             => $a->alteration->created_at->format('d/m/Y H:i:s'),
                    'producto'          => $a->alteration->product->name,
                    'url'               => $a->alteration->urlFormat,
                    'tipo_precio'       => $a->alteration->typePrice,
                    'tipo_alteracion'   => $a->alteration->typeName,
                    'precio_anterior'   => $a->alteration->price_previous,
                    'precio_actual'     => $a->alteration->price_now
                ];
            }
        }
        
        return collect($array);
    }
}
