@extends('adminlte::page')

@section('title', '| Tiendas')

{{-- @section('content_header')
    <h1>Credenciales</h1>
@stop --}}

@section('content')
        <div class="row">
        <div class="mt-3 col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h2 class="font-weight-bold">Detalles de <strong class="text-capitalize">{{$shop->name}}</strong></h2>
                    <hr>
                    @if (Session::has('message') && session::get('message') == 'ok')
                        <div class="alert alert-success" role="alert">
                            Se ha eliminado el registro correctamente !
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                        <div class="alert alert-success" role="alert" id="alert-pass" style="display: none">
                            Se actualizo la contraseña correctamente
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                    <fieldset class="card p-3">
                        <legend>Información de la tienda</legend>                        
                        <div class="col-xs-2 col-sm-4 col-md-6 col-lg-8 col-xl-12">
                            <div class="row">
                                <div class="col-4">
                                    <strong>Creado:</strong> {{$shop->created_at->format('d/m/Y')}}
                                </div>
                                <div class="col-4">
                                   <strong>URL:</strong> <a href="{{$shop->url}}">{{$shop->url}}</a>
                                </div>
                                <div class="col-4">
                                   <strong>Plataforma:</strong> {{$shop->platform}}
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="card p-3">
                        <legend>Información de Eventos</legend>                        
                        <div class="col-xs-2 col-sm-4 col-md-6 col-lg-8 col-xl-12">
                            <div class="row">
                                <div class="col-4">
                                    <strong>Total de Participaciones:</strong> 12
                                </div>
                                <div class="col-4">
                                    <strong>Participaciones en espera:</strong> 6
                                </div>
                                <div class="col-4">
                                    <strong>Participaciones en proceso:</strong> 1 (evento)
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="card p-3">
                        <legend>Información de Productos</legend> 
                        <div class="col-xs-2 col-sm-4 col-md-6 col-lg-8 col-xl-12">
                            <div class="row">
                                <div class="col-3">
                                    <strong>Total de Productos:</strong> {{count($shop->products)}}
                                </div>
                                <div class="col-3">
                                    <strong>Productos Participando:</strong> 6
                                </div>
                                <div class="col-6">
                                    <strong>Producto con más participaciones:</strong> nombre_producto
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <a href="{{route('shops.index')}}" type="button" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Volver</a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
   
@stop

@section('js')

<script type="text/javascript">

</script>
@stop
