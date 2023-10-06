<?php

namespace App\Http\Controllers\Admin;

use App\Event;
use App\Exports\HistoryPricesForProduct;
use App\HistoryPrice;
use App\Http\Controllers\Controller;
use App\Notification;
use App\Product;
use App\Shop;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inspecionMes = HistoryPrice::thisMonth()->count();
        $inspecionSemana = HistoryPrice::thisWeek()->count();
        $inspecionDia = HistoryPrice::thisDay()->count();
        $events = Event::whereIn('status_id',[2,3])->orderBy('id','DESC')->get();
        $notification = Notification::noConfirmed()->get();

        return view('admin.home',compact('events','inspecionDia','inspecionMes','inspecionSemana','notification'));
    }

    /**
     * inspectionsPricesForProduct
     *  se el historial de precio por producto
     * @return void
     */
    public function inspectionsPricesForProduct(Request $request){
        $eventID = $request->id;
        $productID = $request->product_id;
        //hacer un filtro por evento, por tienda, y por producto
        //al seleccionar el producto , que cambie el grafico con todos los precios por fecha

        $history = HistoryPrice::whereEventId($eventID)->whereProductId($productID)
            ->select('real_price','labeled_price','created_at')
            ->selectRaw('DATE_FORMAT(created_at,"%d/%m/%Y") as fecha_formateada')
            ->orderBy('created_at','asc')
            ->get();

        $returnData = [
            'realPrice' => $history->pluck('real_price')->all(),
            'labeledPrice' => $history->pluck('labeled_price')->all(),
            'fechas' => $history->pluck('fecha_formateada')->all(),
        ];

        return response()->json($returnData);
    }

    /**
     * maxPrice
     *  se obtiene el historial de maximo precio por producto
     * @return void
     */
    public function maxPrice(Request $request){
        $eventID = $request->id;
        $productID = $request->product_id;

            $history = HistoryPrice::whereEventId($eventID)->whereProductId($productID)
            ->select('real_price','labeled_price','created_at')
            ->selectRaw('DATE_FORMAT(created_at,"%d/%m/%Y") as fecha_formateada')
            ->orderBy('created_at','asc')
            ->get();
        $realPrice = $history->pluck('real_price')->all();
        foreach ($history->pluck('labeled_price')->all() as $key => $value) {
            $price = $value !=null? $value : $realPrice[$key];

            $maxPrice[] = array($price);
        }

        $returnData = [
            'maxPrice' => $maxPrice,
            'fechas' => $history->pluck('fecha_formateada')->all(),
        ];

        return response()->json($returnData);
    }

    public function getShops(Request $request){
        $event = Event::find($request->id);
        $shopsForEvent = $event->shops;
        $shops =[];
        foreach ($shopsForEvent as $key => $value) {
            $shops[] = [
                'id' => $value->id,
                'name' => $value->name
            ];
        }

        return response()->json($shops);
    }

    public function getProducts(Request $request){
        $shop = Shop::find($request->id);
        $productsForEvent = $shop->products;
        $products =[];
        foreach ($productsForEvent as $key => $value) {
            $products[] = [
                'id' => $value->id,
                'name' => $value->name
            ];
        }

        return response()->json($products);
    }

    public function exportPriceForProduct(Request $request){
        $product = Product::find($request->product);
        $name = str_replace('_',' ',$product->name);
        return Excel::download(new HistoryPricesForProduct,'Historial_'.$name.'.csv');
    }

    /**
     * Update the status notification in home.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateNotification($id)
    {
        Notification::whereId($id)->update(['notified'=>true]);
        //return $this->index();
        return redirect()->route('admin.home');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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