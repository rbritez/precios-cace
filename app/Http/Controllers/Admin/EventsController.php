<?php

namespace App\Http\Controllers\Admin;

use App\Event;
use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Measurement;
use App\Product;
use App\Shop;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\DB;

class EventsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Event::all();
        return view('admin.events.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.events.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EventRequest $request)
    {
        $event = new Event();
        $event->name = $request->name;
        $event->event_init = $request->event_init;
        $event->event_end = $request->event_end;
        $event->save();
        $event->updateStatus($request->event_init,$request->event_end);

        foreach ($request->revision_init as $key => $value) {
            $measurement = new Measurement();
            $measurement->revision_init = $value;
            $measurement->revision_end = $request->revision_end[$key];
            $measurement->event_id = $event->id;
            $measurement->save();
        }

        $respuesta = 'ok';

        return back()->with('message', $respuesta);
    }
    /**
     * datatable shops of event
     *
     * @param int $id
     * @return mixed json 
     */
    public function getShopsEvent($id){
        $event = Event::find($id);
        
        return Datatables::of($event->shops)->make(true);
    }

    public function getProductsShopsEvent(Request $request){
        $productsShopEvent = DB::table('event_shop_product')->select('product_id')->where([['shop_id',$request->shop_id],['event_id',$request->event_id]])->get();

        $products = collect();
        foreach ($productsShopEvent as $key => $value) {
            $product = Product::find($value->product_id);
            
            $products[] = collect($product);
        }
        
        return Datatables::of($products)->make(true);
    }

    public function getShopsForId(){
        $id = request()->id;
        $event = Event::find($id);
        $shops=[];
        foreach ($event->shops as $s) {
            $shops[] = $s->id;
        }
        
        return response()->json($shops);
    }

    public function addShop(Request $request){

        $event = Event::find($request->id);
        $event->shops()->attach($request->shop_id);
        
        $response = true;

        return response()->json(['response' => $response]);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = Event::find($id);
        $shops = Shop::where('platform','!=',null)->where('platform','!=','')->get();
        return view('admin.events.show',compact('event','shops'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $data = Event::find($id);
        return view('admin.events.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EventRequest $request, $id)
    {

        $event = Event::find($id);
        $event->name = $request->name;
        $event->event_init = $request->event_init;
        $event->event_end = $request->event_end;
        $event->save();
        $event->updateStatus($request->event_init,$request->event_end);

        Measurement::where('event_id',$id)->delete();
        
        foreach ($request->revision_init as $key => $value) {
            if($value != null && $request->revision_end[$key] != null){
                $measurement = new Measurement();
                $measurement->revision_init = $value;
                $measurement->revision_end = $request->revision_end[$key];
                $measurement->event_id = $event->id;
                $measurement->save();
            }
        }

        $respuesta = 'update';
        return redirect()->route('events.index')->with('message', $respuesta);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //preguntar si existe tiendas asociadas al evento
        Measurement::where('event_id',$id)->delete();
        
        Event::destroy($id);
        $respuesta = 'ok';
        return redirect()->route('events.index')->with('message', $respuesta);

    }

    public function shopRemove(Request $request){
        $event = Event::find($request->id);
        $event->shops()->detach($request->shop_id);
        $response = true;
        return response()->json(['response' => $response]);
    }

}