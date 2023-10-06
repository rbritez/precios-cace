<?php

namespace App\Imports;

use App\megaOfertas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class megaOfertasImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new megaOfertas([
            'external_id' => $row['id'],
            'sku' => $row['sku'],
            'marca'=> $row['marca'],
            'link_anterior' => $row['link_anterior'],
            'link_oferta' => $row['link_oferta'],
            'titulo' => $row['titulo'],
            'precio anterior' => removeSpecialCharacters($row['precio_anterior']),
            'precio_en_oferta' => removeSpecialCharacters($row['precio_oferta']),
            'categoria' => $row['categoria'],
        ]);
    }
}
