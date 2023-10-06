<?php

namespace App\Exports;

use App\HistoryPrice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HistoryPrices implements FromCollection,WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
    */
    public function headings():array
    {
        return [
            'Producto',
            'Url',
            'Precio'
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        if(isset(request()->from) || isset(request()->to)){
            $history = HistoryPrice::dateFilter()->get();
        }else{
            $history = HistoryPrice::all();
        }
        if(count($history) == 0){
            $array = [];
        }else{
            foreach ($history as $h) {
                $array[] = [
                'Producto' => $h->product->name,
                'Url' => $h->urlFormat,
                'Precio' => $h->maxPrice,
                ];
            }
        }
            return collect($array);
    }
}
