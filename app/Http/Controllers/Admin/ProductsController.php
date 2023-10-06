<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Product::all();
        return view('admin.products.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $product = Product::updateOrCreate(['id'=>$request->id],
        [
            'name'=>$request->name,
            'url' => $request->url,    
        ]);

        return response()->json($product);
    }

    /**
     * Get name exits in products table.
     *
     * @return \Illuminate\Http\Response
     */
    public function getName()
    {
        $name = request()->name;
        $result = Product::where('name','LIKE','%'.$name.'%')->first();
        if($result){
            $data = true;
        }else{
            $data = false;
        }
        return response()->json($data);
    }

    public function importView(){
        
        return view('admin.products.import');
    }

    public function importStore(Request $request){
        

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if($product->status == 1){
            $product->update(['status'=> 0]);
        }else{
            $product->update(['status'=> 1]);
        }

        $respuesta = 'ok';
        return back()->with('message', $respuesta);
    }
}