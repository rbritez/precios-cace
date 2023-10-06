<?php

namespace App\Http\Controllers\Admin;

use App\Exports\HistoryPrices;
use App\HistoryPrice;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(isset(request()->from) || isset(request()->to)){
            $data = HistoryPrice::dateFilter()->get();
        }else{
            $data = HistoryPrice::all();
        }
        
        return view('admin.history.index',compact('data'));
    }

    public function exportHistory(Request $request){

        if(isset(request()->from) || isset(request()->to)){
            if($request->from && $request->to){
                $name = Carbon::parse($request->from)->format('d_m_Y') .'__'. Carbon::parse($request->to)->format('d_m_Y') ;
            }elseif($request->from){
                $name = Carbon::parse($request->from)->format('d_m_Y');
            }elseif ($request->to) {
                $name = Carbon::parse($request->from)->format('d_m_Y');
            }else{
                $name = '__';
            }
        }else{
            $name = today()->format('d_m_Y');
        }
                                                                            //export a excel
        return Excel::download(new HistoryPrices,'historial_precios_'.$name.'.xlsx');
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
