<?php

namespace App\Exports;

use App\HistoryPrice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HistoryPricesForProduct implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings():array
    {
        return [
            'Evento',
            'Tienda',
            'Producto',
            'Precio Maximo',
            'Fecha'
        ];
    }
    public function collection()
    {
        $array = [];
        $event = request()->event;
        $shop = request()->shop;
        $product = request()->product;

        $history = HistoryPrice::join('events', 'events.id', '=', 'history_prices.event_id')
                    ->join('shops', 'shops.id', '=', 'history_prices.shop_id')
                    ->join('products', 'products.id', '=', 'history_prices.product_id')
                    ->whereEventId($event)
                    ->whereShopId($shop)
                    ->whereProductId($product)
                    ->select('events.name as event','shops.name as shop','products.name as product','history_prices.created_at')
                    ->selectRaw("(CASE WHEN labeled_price IS NULL THEN real_price ELSE labeled_price END) as max_price")
                    ->get();

        foreach ($history as $h) {
            $array[] = [
                'Evento' => $h->event,
                'Tienda' => $h->shop,
                'Producto' => $h->product,
                'Precio_Maximo' => $h->max_price,
                'Fecha' =>  $h->created_at->format('d-m-Y H:i:s')
            ];
        }

        return collect($array);
    }

}
