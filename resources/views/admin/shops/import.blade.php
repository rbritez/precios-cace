@extends('adminlte::page')

@section('title', '| Importar Tiendas')

{{-- @section('content_header')
    <h1>Credenciales</h1>
@stop --}}

@section('content')
  <div class="row">
        <div class="mt-3 col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h3 class="m-b-20">Crear Tiendas por lote</h3>
                    <div class="alert alert-success col-6">
                        Reglas archivo a importar:
                        <ul>
                            <li>Estar delimitado por coma (,).</li>
                            <li>Campos requeridos: tienda<sup>1</sup>, url<sup>2</sup>.</li>
                            <li>Campos opcionales: plataforma<sup>3</sup><span id="show_fields_productos" style="display: none">
                                , producto_1<sup>4</sup>, producto_2<sup>4</sup>, producto_3<sup>4</sup>
                                , producto_4<sup>4</sup>, producto_5<sup>4</sup>, producto_6<sup>4</sup>
                                , producto_7<sup>4</sup>, producto_8<sup>4</sup></span>.
                            </li>
                        </ul>
                        <small><sup>1</sup> <strong>tienda: </strong>el nombre de la tienda.</small><br>
                        <small><sup>2</sup> <strong>url: </strong>debe contener HTTPS, y con "/" al final </small><br>
                        <small><sup>3</sup> <strong>plataforma: </strong>es la tecnología (CMS) con la que esta creada la tienda.</small><br>
                        <small id="show_sub_product" style="display: none"><sup>4</sup> <strong>producto_x: </strong>full url del producto.</small><br>
                        <div class="mt-2">
                            Descargar Ejemplo:  <a type="button" href="{{asset('examples/csv/tiendas.csv')}}" target="blank" download="ejemplo_tiendas.csv" id="tienda_csv" class="btn btn-success"><i class="fas fa-file-csv"></i> Descargar</a>
                                                <a type="button" href="{{asset('examples/csv/tiendas_productos.csv')}}" target="blank" download="ejemplo_tiendas_productos.csv" id="tienda_producto_csv" class="btn btn-success" style="display: none"><i class="fas fa-file-csv"></i> Descargar</a>
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
                                Se cargaron las tiendas , puedes ver el listado en :
                                <a href="{{route('shops.index')}}">Listado de tiendas</a>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form method="POST" enctype="multipart/form-data"
                          action="{{ route('shops.import.store') }}">
                        @csrf
                        <div class="col-sm-7">
                            <div class="form-group">
                                <label for="tipo">Tipo de Importación : </label>
                                <select name="tipo" id="tipo" class="form-control col-6">
                                    <option value="1">Solo Tiendas</option>
                                    <option value="2">Tiendas con Productos</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="csv">Archivo de lote (.csv) *</label>
                                <input type="file" name="csv" class="form-control-file col-6"
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
    <script>
        $("#tipo").change(function(){
            var tipo = $("#tipo").val();
            showCsvProduct(tipo);
            showFieldProducts(tipo);
        });

        function showCsvProduct(tipo){
            if(tipo == 2){
                $("#tienda_producto_csv").show();
                $("#tienda_csv").hide();
            }else{
                $("#tienda_producto_csv").hide();
                $("#tienda_csv").show();
            }
        }
        function showFieldProducts(tipo){
            if(tipo == 2){
                $("#show_fields_productos").show();
                $("#show_sub_product").show();
            }else{
                $("#show_fields_productos").hide();
                $("#show_sub_product").hide();
            }
            
        }
    </script>
@stop
