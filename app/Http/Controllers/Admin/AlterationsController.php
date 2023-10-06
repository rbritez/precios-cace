<?php

namespace App\Http\Controllers\Admin;

use App\Alteration;
use App\Exports\Alterations;
use App\Exports\exportAlterationsMo;
use App\Http\Controllers\Controller;
use App\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AlterationsController extends Controller
{
    public function index(){
        if(isset(request()->from) || isset(request()->to)){
            $data = Alteration::dateFilter()->get();
        }else{

            $data = Alteration::orderBy('id','desc')->get();
        }

        return view('admin.alterations.index',compact('data'));
    }

    public function exportAlterations(Request $request){

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
        return Excel::download(new Alterations,'alteraciones_'.$name.'.xlsx');
    }

    public function indexMO(){
        if(isset(request()->from) || isset(request()->to)){
            $data = Notification::join('alterations','alterations.id','=','notifications.alteration_id')->dateFilter()->get();
        }else{
            $data = Notification::orderBy('id','desc')->get();
        }
        return view('admin.alterations.mo',compact('data'));
    }

    public function exportAlterationsMo(Request $request){
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
        return Excel::download(new exportAlterationsMo,'alteracionesMegaOfertas_'.$name.'.xlsx');
    }
}
