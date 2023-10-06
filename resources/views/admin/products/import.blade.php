@extends('adminlte::page')

@section('title', '| Importar Productos')

{{-- @section('content_header')
    <h1>Credenciales</h1>
@stop --}}

@section('content')
  <div class="row">
        <div class="mt-3 col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h3 class="m-b-20">Crear Productos por lote</h3>
                    <div class="alert alert-success col-6">
                        Reglas archivo a importar:
                        <ul>
                            <li>Estar delimitado por coma (,) o punto y coma (;).</li>
                            <li>Campos requeridos: producto<sup>1</sup>, url<sup>2</sup>.</li>
                        </ul>
                        <small><sup>1</sup> <strong>producto: </strong>el nombre del producto.</small><br>
                        <small><sup>2</sup> <strong>url: </strong>debe contener HTTPS, es la url completa para acceder al producto.</small><br>
                        <div class="mt-2">
                            Descargar Ejemplo: <button type="button" class="btn btn-success"><i class="fas fa-file-csv"></i> Descargar</button>
                        </div>
                        
                    </div>
                    @if($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session()->has('processing'))
                        <div class="alert alert-success m-b-20" role="alert">
                            <p>Se estan procesando los datos ingresados. Las suscripciones irán apareciendo en el <a
                                    href="">Listado de suscripciones</a></p>
                        </div>
                    @endif

                    <form method="POST" enctype="multipart/form-data"
                          action="{{ route('shops.import.store') }}">
                        @csrf
                        <div class="col-sm-7">


                            <div class="form-group">
                                <label for="csv">Archivo de lote (.csv) *</label>
                                <input type="file" name="csv" class="form-control-file"
                                       id="csv"
                                       required>
                            </div>
                        </div>
                        <div class="row m-t-20">
                            <div class="col text-center">
                                <button type="submit" class="btn btn-primary"> <i class="fas fa-upload"></i> &nbsp;Importar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')
    <style>
        .form-check-input, .form-check-label{
            cursor: pointer;
        }
        .alert-success{
            color: black;
            background-color: rgba(119, 210, 105, 0.71)
        }
    </style>
@stop

@section('js')

@stop
