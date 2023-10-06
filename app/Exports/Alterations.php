<?php

namespace App\Exports;

use App\Alteration;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Alterations implements FromCollection,WithHeadings
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
                    $history = Alteration::dateFilter()->get();
                }else{
                    $history = Alteration::all();
                }
                if(count($history) == 0){
                    $array = [];
                }else{
                    foreach ($history as $a) {
                        $array[] = [
                            'fecha'             => $a->created_at->format('d/m/Y H:i:s'),
                            'producto'          => $a->product->name,
                            'url'               => $a->urlFormat,
                            'tipo_precio'       => $a->typePrice,
                            'tipo_alteracion'   => $a->typeName,
                            'precio_anterior'   => $a->price_previous,
                            'precio_actual'     => $a->price_now
                        ];
                    }
                }
            return collect($array);
    }
}
