<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/
Route::get('/home', 'Admin\HomeController@index')->name('admin.home');

Route::get('search-cms','Admin\ShopsController@SearchCMS');
Route::get('products/get-name','Admin\ProductsController@getName');
Route::get('shops/get-products-id','Admin\ShopsController@getProductsForId');
Route::get('events/datatable/shops-event/{id}','Admin\EventsController@getShopsEvent');
Route::post('events/shop-remove','Admin\EventsController@shopRemove');
Route::get('events/get-shops-id','Admin\EventsController@getShopsForId');
Route::post('events/addShop','Admin\EventsController@addShop');
Route::post('events/datatable/products-shops-event/','Admin\EventsController@getProductsShopsEvent');
Route::post('home/getShops','Admin\HomeController@getShops');
Route::post('home/getProducts','Admin\HomeController@getProducts');
Route::post('home/load-history-chart','Admin\HomeController@inspectionsPricesForProduct');
Route::post('home/load-maxprice-chart','Admin\HomeController@maxPrice');
Route::get('home/check-notification/{id}','Admin\HomeController@updateNotification')->name('update.notification');
Route::get('alterations/MO', 'Admin\AlterationsController@indexMO')->name('alterations.mo');

//exports
Route::post('home/export-price-for-product','Admin\HomeController@exportPriceForProduct')->name('export.price.product');
Route::get('errors/export-errors','Admin\ErrorsController@exportErrors')->name('export.errors');
Route::post('histories/export-histories','Admin\HistoryController@exportHistory')->name('export.history');
Route::post('alterations/export-alterations','Admin\AlterationsController@exportAlterations')->name('export.alterations');
Route::post('alterations/export-alterations-mo','Admin\AlterationsController@exportAlterationsMo')->name('export.alterations-mo');

//imports
Route::get('shops/import','Admin\ShopsController@importView');
Route::post('shops/import-store','Admin\ShopsController@importStore')->name('shops.import.store');

Route::get('products/import','Admin\ProductsController@importView');
Route::post('products/import-store','Admin\ProductsController@importStore')->name('products.import.store');

Route::get('mo/import-mo','Admin\MegaOfertasController@importViewMegaOferta');
Route::post('mo/import-mo-store','Admin\MegaOfertasController@importStoreMegaOferta')->name('mo.import.store');

Route::resource('products', 'Admin\ProductsController');
Route::resource('events', 'Admin\EventsController');
Route::resource('shops', 'Admin\ShopsController');
Route::resource('alterations', 'Admin\AlterationsController');
Route::resource('histories', 'Admin\HistoryController');
Route::get('errors','Admin\ErrorsController@index')->name('errors.index');
Route::get('/this-user', function () {
    return auth()->user();
});