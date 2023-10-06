<?php

namespace App\Http\Controllers\Admin;

use App\Errors;
use App\Exports\ErrorsEscrapping;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ErrorsController extends Controller
{
    public function index(){
        $data = Errors::all();
        return view('admin.errors.index',compact('data'));
    }

    public function exportErrors(){

        return Excel::download(new ErrorsEscrapping,'errores.csv');
    }
}
