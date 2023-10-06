<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\ShopsImport;
use App\Platform;
use App\Product;
use App\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use MadeITBelgium\Wappalyzer\WappalyzerFacade as Wappalyzer;

class ShopsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Shop::all();
        return view('admin.shops.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::whereIsActive()->get();
        $platforms = Platform::all();
        return view('admin.shops.create',compact('products','platforms'));
    }

    public function SearchCMS(){
        $url = request()->url;
        $data=[];
        $status = 200;
        $urlparts= parse_url($url);
        $scheme = $urlparts['scheme'];

        if ($scheme === 'https') {
            try {
                $result = Wappalyzer::analyze($url);
                if($result['detected']){
                    foreach ($result['detected'] as $key => $value) {
                        $data[]= $key;
                    }
                }else{
                    $status = 204;
                    $data = 'No se pudieron detectar tecnologías';
                }
            } catch (\Throwable $error) {
                $status = 404;
                $data ="no se pudo encontrar el sitio $url"; 
                Log::info($data.': '. $error->getMessage());
            }
        } else {
            $status = 404;
            $data ="$url no es una URL válida"; 
        }
        
        
        return response()->json(['status'=> $status ,'data'=>$data]);
    }

    public function getProductsForId(){
        $id = request()->id;
        $shop = Shop::find($id);
        $products=[];
        foreach ($shop->products as $p) {
            $products[] = $p->id;
        }
        
        return response()->json($products);
    }

    public function importView(){
        
        return view('admin.shops.import');
    }

    public function importStore(Request $request){
        $rules = Array(
            'csv' => 'required|file|mimes:csv,txt'
        );

        if($request->tipo == 2){
            session()->put('tipo',$request->tipo);
        }

        $request->validate($rules);
        $file = $request->file('csv');

        Excel::import(new ShopsImport($request->tipo),$file);

        return back()->with('message','importaciond e tiendas completada!');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $shop = new Shop();
        $shop->name = $request->name;
        $shop->url = $request->url;
        $shop->platform = $request->platform;
        $shop->save();
        if(isset($request->product_id) && count($request->product_id) > 0){
            foreach ($request->product_id as $key => $p) {
                Product::find($p)->update(['url'=>$request->url_product[$key]]);
                $shop->addProducts($p,$request->url_product[$key]);
            }
        }


        $respuesta = 'ok';
        return back()->with('message', $respuesta);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $shop = Shop::find($id);

        return view('admin.shops.show',compact('shop'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shop = Shop::find($id);
        $products = Product::whereIsActive()->get();

        return view('admin.shops.edit',compact('shop','products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $shop = Shop::find($id);
        $shop->name = $request->name;
        $shop->url = $request->url;
        $shop->platform = $request->platform;
        $shop->save();
        $shop->removeAllProducts();
        
        if(isset($request->product_id) && count($request->product_id) > 0){
            foreach ($request->product_id as $key => $p) {
                Product::find($p)->update(['url'=>$request->url_product[$key]]);
                $shop->addProducts($p,$request->url_product[$key]);
            }
        }

        $respuesta = 'ok';
        return back()->with('message', $respuesta);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}