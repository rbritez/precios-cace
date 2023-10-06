<?php

use App\Platform;
use App\Suggestion;
use Illuminate\Database\Seeder;

class SuggestionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data =[
            //VTEX Legacy
            [
                'platform_id'=> 1,
                'type_search' => Suggestion::TYPE_CSS,
                'description' => 'skuBestPrice'
            ],
            [
                'platform_id'=> 1,
                'real_price' => 0, //precio tachado
                'type_search' => Suggestion::TYPE_CSS,
                'description' => 'skuListPrice'
            ],

            //VTEX IO
            [
                'platform_id'=> 1,
                'type_search' => Suggestion::TYPE_CSS,
                'description' => 'vtex-store-components-3-x-sellingPrice'//contenedor principal 
            ],
            [
                'platform_id'=> 1,
                'real_price' => 0, //precio tachado
                'type_search' => Suggestion::TYPE_CSS,
                'description' => 'vtex-store-components-3-x-listPrice' //contenedor principal
            ],
            [
                'platform_id'=> 1,
                'real_price' => 0, //precio tachado
                'type_search' => Suggestion::TYPE_CSS,
                'description' => 'vtex-store-components-3-x-currencyInteger' //dentro del contenedor principal buscar todas las css y concatenar
            ],
            //magento
            [
                'platform_id'=> 2,
                'type_search' => 'data-price-type',
                'description' => 'finalPrice'
            ],
            [
                'platform_id'=> 2,
                'real_price' => 0, //precio tachado
                'type_search' => 'data-price-type',
                'description' => 'oldPrice' 
            ],

            //TiendaNube
            [
                'platform_id' => 3,
                'type_search' => Suggestion::TYPE_ID,
                'description' => 'price_display'
            ],
            [
                'platform_id' => 3,
                'real_price' => 0,//precio tachado
                'type_search' => Suggestion::TYPE_ID,
                'description' => 'compare_price_display'
            ],
            //Woocommerce
            [
                'platform_id' => 4,
                'type_search' => Suggestion::TYPE_CSS,
                'description' => 'price' //un contenedor p class=price, dentro estan los 2 precios, se deberia contar las clases woocommerce-Price-amount o etiqueta bdi, 
            ],
            [
                'platform_id' => 4,
                'real_price' => 0,//precio tachado
                'type_search' => Suggestion::TYPE_CSS,
                'description' => 'price' //un contenedor p class=price, dentro estan los 2 precios, se deberia contar las clases woocommerce-Price-amount o etiqueta bdi, 
            ],                          //el primero es precio tachado y segundo precio real
            [
                'platform_id' => 4,
                'type_search' => Suggestion::TYPE_HTML,
                'description' => 'ins'
            ],
            [//otra forma es buscar etiqueta <del> ,dentro se encuentra el precio tachado en la clase woocommerce-Price-amount,
                'platform_id' => 4,
                'real_price' => 0,//precio tachado
                'type_search' => Suggestion::TYPE_HTML,
                'description' => 'del'
            ],
            //prestashop
            [
                'platform_id' => 5,
                'type_search' => Suggestion::TYPE_CSS,
                'description' => 'current-price' //contenedor, dentro de current-price buscar product-price
            ],
            [
                'platform_id' => 5,
                'type_search' => Suggestion::TYPE_CSS,
                'description' => 'product-price'
            ],
            [
                'platform_id' => 5,
                'real_price' => 0,//precio tachado
                'type_search' => Suggestion::TYPE_CSS,
                'description' => 'product-discount' //contenedor, dentro de product-discount buscar regular-price
            ],
            [
                'platform_id' => 5,
                'real_price' => 0,//precio tachado
                'type_search' => Suggestion::TYPE_CSS,
                'description' => 'regular-price'
            ],
            //WIX
            [
                'platform_id' => 6,
                'type_search' => 'data-hook',
                'description' => 'formatted-primary-price'
            ],
            [
                'platform_id' => 6,
                'real_price' => 0, //precio tachado
                'type_search' => 'data-hook',
                'description' => 'formatted-secondary-price'
            ],
            
            //mercadoshop - mercadolibre
            [
                'platform_id' => 7,
                'type_search' => Suggestion::TYPE_CSS,
                'description' => 'ui-pdp-price__second-line'//contenedor para precio real dentro del contenedor buscar price-tag-fraction
            ],
            [
                'platform_id' => 7,
                'type_search' => Suggestion::TYPE_CSS,
                'description' => 'price-tag-fraction'
            ],
            [
                'platform_id' => 7,
                'real_price' => 0,//precio tachado
                'type_search' => Suggestion::TYPE_CSS,
                'description' => 'ui-pdp-price__original-value'//contenedor para precio real dentro del contenedor buscar price-tag-fraction
            ],
            [
                'platform_id' => 7,
                'real_price' => 0,//precio tachado
                'type_search' => Suggestion::TYPE_CSS,
                'description' => 'price-tag-fraction'
            ],

            //shopify
            [
                'platform_id' => 8,
                'type_search' => Suggestion::TYPE_ID,
                'description' => 'product-price'
            ],
            [
                'platform_id' => 8,
                'real_price' => 0,//precio tachado
                'type_search' => Suggestion::TYPE_ID,
                'description' => 'old-product-price'
            ],
            [//segundo metodo
                'platform_id' => 8,
                'type_search' => Suggestion::TYPE_CSS,
                'description' => 'variant-price'
            ],
            [//segundo metodo
                'platform_id' => 8,
                'real_price' => 0,//precio tachado
                'type_search' => Suggestion::TYPE_CSS,
                'description' => 'compare-price'
            ],
            //FALTA SAP Commerce CLoud
            
            // [
            //     'platform_id'=> 1,
            //     'type_search' => Suggestion::TYPE_HTML,
            //     'description' => 'em'
            // ],
            // [
            //     'platform_id'=> 1,
            //     'real_price' => 0, //precio tachado
            //     'type_search' => Suggestion::TYPE_HTML,
            //     'description' => 'em'
            // ],
        ];


        foreach ($data as $key => $value) {
            Suggestion::create($value);
        }
        
    }
}
//Mercado Libre
/**
 * -------------------  en home  -----------------------------
 *  class conteendor ui-search-price                -> para precio tachado
 * class contenedor ui-search-price__second-line    -> precio real
 * 
 * class  price-tag-fraction                        -> para precio real y tachado
 * 
 * ----------  producto en especifico  -----------------------
 *
 * class contenedor ui-pdp-price__second-line   -> precio real
 * class contenedor ui-pdp-price                -> precio tachado
 * class contenedor <u class="ui-pdp-price__part"></u> ->precio tachado 
 *  
 * class price-tag-fraction                     -> precio ral y precio tachado
 * 
 */