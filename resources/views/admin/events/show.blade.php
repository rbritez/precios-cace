@extends('adminlte::page')

@section('title', '| Eventos')

{{-- @section('content_header')
    <h1>Credenciales</h1>
@stop --}}

{{-- 
    Se debera mostrar tiendas participantes , agregar los productos que participan de la tienda 
    y el metodo de busqueda del precio normal y precio tachado 
    
    --}}
@section('content')
        <div class="row">
        <div class="mt-3 col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h2 class="font-weight-bold">Detalles de <strong class="text-capitalize">{{$event->name}}</strong></h2>
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
                        <h4 class="mb-3">Información del evento</h4>
                        <input type="hidden" id="id" value="{{$event->id}}">                        
                        <div class="col-xs-2 col-sm-4 col-md-6 col-lg-8 col-xl-12">
                            <div class="row">
                                <div class="col-4">
                                    <strong>Creado:</strong> {{$event->created_at->format('d/m/Y')}}
                                </div>
                                <div class="col-4">
                                   <strong>Estado:</strong> {{$event->status->name_event}} <i class="far fa-circle {{$event->colorStatus}} rounded-circle"></i>
                                </div>
                                <div class="col-4">
                                    {{-- Segun el estado mostrar fecha inicial o fecha final --}}
                                    @if ($event->status_id == 1)
                                        <strong>Fecha de Inicio:</strong> {{$event->event_init->format('d/m/Y')}}
                                        @else
                                        <strong>Fecha de Fin:</strong> {{$event->event_end->format('d/m/Y')}}
                                    @endif
                                </div>
                                <div class="col-12 mt-2">
                                    <strong>Fecha de comprobación: </strong> Los precios de los productos participantes no puede variar del <strong>{{$event->measurement->get(0)->revision_init->format('d/m/Y')}}</strong> al <strong>{{$event->measurement->get(0)->revision_end->format('d/m/Y')}}</strong>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="card p-3">
                        <div class="row">
                            <h4 class="mb-3 col-sm-12 col-md-6 col-lg-4">Tiendas Participantes</h4>  
                            <div class="col-sm-12 col-md-6 col-lg-8 mb-3 pr-0 ">
                                <div class="col-sm-12 col-md-8 col-lg-3 float-right">
                                    @if ($event->status_id == 1)
                                        <button class="btn btn-success btn-block" data-toggle="modal" data-target="#modalShopSelect"><i class="fas fa-plus"></i> Agregar</button>
                                    @endif
                                </div> 
                            </div> 
   
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped text-center" id="shops-event">
                                <thead>
                                    <th>Tienda</th>
                                    <th>Plataforma</th>
                                    <th>Acciones</th>
                                </thead>
                            </table>
                        </div>
                    </fieldset>

                    <a href="{{route('events.index')}}" type="button" class="btn btn-primary"><i class="fas fa-arrow-left"></i> Volver</a>
                </div>
            </div>
        </div>
    </div>
    @include('admin.events.modal-shops')
@stop

@section('css')
   
@stop

@section('js')
<script src="{{asset('js/event.js')}}"></script>
<script type="text/javascript">

</script>
@stop
