@extends('adminlte::page')

@section('title', '| Importar Tiendas')

{{-- @section('content_header')
    <h1>Credenciales</h1>
@stop --}}
@section('css')
    <link rel="stylesheet" href="{{asset('css/preloader.css')}}">
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
@section('content')
  <div class="row">
        <div class="mt-3 col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h3 class="m-b-20">Importar Mega Ofertas</h3>
                    <div class="alert alert-success col-6">
                        Reglas archivo a importar:
                        <ul>
                            <li>Estar delimitado por coma (,).</li>
                            <li>Campos requeridos: sku, link_oferta, titulo, precio_anterior, precio_oferta.</li>
                            <li>Campos opcionales: id, marca, link_anterior, categoria.</li>
                        </ul>
                        <div class="mt-2">
                            Descargar Ejemplo: <a type="button" href="{{asset('examples/csv/mega_ofertas.csv')}}" target="blank" download="ejemplo_tiendas.csv" class="btn btn-success"><i class="fas fa-file-csv"></i> Descargar</a>
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

                    @if(session()->has('message'))
                        <div class="alert alert-info m-b-20" role="alert">
                            Se cargó el listado con éxito.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form method="POST" enctype="multipart/form-data" action="{{ route('mo.import.store') }}">
                        @csrf
                        <div class="col-sm-7">
                            <div class="form-group">
                                <label for="csv">Archivo de lote (.csv, .xlsx) *</label>
                                <input type="file" name="csv" class="form-control-file col-6"
                                       id="csv"
                                       required>
                            </div>
                        </div>
                        <div class="row m-t-20">
                            <div class="col text-center">
                                <div class="load-center" style="display: none" ><div class="loader"></div> <small>Cargando...</small></div>
                                <button type="submit" class="btn btn-primary" id="btn-submit"> <i class="fas fa-upload"></i> &nbsp;Importar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@stop

@section('js')
    <script>
        $("#btn-submit").click(function(){
            $("#btn-submit").toggle(400)
            $(".load-center").toggle(600);
        });
    </script>
@stop
