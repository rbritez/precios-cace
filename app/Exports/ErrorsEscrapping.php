<?php

namespace App\Exports;

use App\Errors;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ErrorsEscrapping implements FromCollection,WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
    */
    public function headings():array
    {
        return [
            'Tienda',
            'Url',
            'Error'
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        
        $errors = Errors::all();
        
        foreach ($errors as $h) {
            $array[] = [
                'Tienda' => $h->shop->name,
                'url' => $h->url,
                'error' => $h->error,
            ];
        }

        return collect($array);
    }
}
