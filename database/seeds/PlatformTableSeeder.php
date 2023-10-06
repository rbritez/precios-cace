<?php

use App\Platform;
use Illuminate\Database\Seeder;

class PlatformTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'VTEX',          'prefix' => 'vtex'],
            ['name' => 'Magento',       'prefix' => 'magento'],
            ['name' => 'Tiendanube',    'prefix' => 'tiendanube'],
            ['name' => 'Woocommerce',   'prefix' => 'woocommerce'],
            ['name' => 'Prestashop',    'prefix' => 'prestashop'],
            ['name' => 'WIX',           'prefix' => 'wix'],
            ['name' => 'Mercadoshops',  'prefix' => 'mercadoshop'],
            ['name' => 'Shopify',       'prefix' => 'shopify']
        ];
        
        foreach ($data as $value) {
           Platform::create($value);
        }
    }
}