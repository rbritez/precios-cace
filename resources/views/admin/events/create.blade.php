@extends('adminlte::page')

@section('title', '| Crear Evento')

{{-- @section('content_header')
    <h1>Credenciales</h1>
@stop --}}

@section('content')
        <div class="row">
        <div class="mt-3 col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h2 class="font-weight-bold">Nuevo Evento </h2>
                    <hr>
                    @if (Session::has('message') && session::get('message') == 'ok')
                        <div class="alert alert-success" role="alert">
                            Se ha creado el registro correctamente !
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{route('events.store')}}" method="post" class="col-lg-12">
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label">Nombre Evento</label>
                            <div class="col-sm-9">
                            <input type="text" class="form-control" id="" name="name" placeholder="" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="event_init" class="col-sm-3 col-form-label">Fecha Inicio</label>
                            <div class="col-sm-9">
                            <input type="datetime-local" class="form-control" id="event_init" name="event_init" placeholder="" value="" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="event_end" class="col-sm-3 col-form-label">Fecha Finalización</label>
                            <div class="col-sm-9">
                            <input type="datetime-local" class="form-control" id="event_end" name="event_end" placeholder="" value="" required>
                            </div>
                        </div>
                        <hr>
                        {{-- FECHAS PARA LA MEDICION --}}
                        <div class="mb-2">
                            <button type="button" class="btn btn-block btn-success" onclick="addDates()"> <i class="fa fas fa-plus"></i> Agregar Fecha</button>
                        </div>
                        <div class="form-group row" id="dates_measurement">
                            <label for="revision_init" class="ml-2 mt-1">Fecha Inicio de Revisión: </label>
                            <div class="col-xs-12 col-sm-12 col-md-3">
                                <input type="datetime-local" class="form-control" id="revision_init" name="revision_init[]" placeholder="" value="" required>
                            </div>

                            <label for="revision_end" class="ml-2 mt-1">Fecha Finalización de Revisión: </label>
                            <div class="col-xs-12 col-sm-12 col-md-3">
                                <input type="datetime-local" class="form-control" id="revision_end" name="revision_end[]" placeholder="" value="" required>
                            </div>
                        </div>
                        {{-- FIN FECHAS PARA MEDICION --}}
                        <a type="button" class="btn btn-info" onclick="previous()"> <i class="fas fa-arrow-left"></i> Volver</a>
                        <button type="submit" class="btn btn-primary float-right"> <i class="fas fa-save"></i> Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
<script>
    function previous(){
        window.history.back();
    }
</script>
<script>

    function addDates(){
        var array_revision_init = $("input[name='revision_init[]']");
  
        addFormRevision(array_revision_init.length);
    }

    function addFormRevision(number){

        var elements = '<div class="form-group row" id="dates_measurement_'+number+'">'+
                            '<label for="revision_init" class="ml-2 mt-1">Fecha Inicio de Revisión: </label>'+
                            '<div class="col-xs-12 col-sm-12 col-md-3">'+
                                '<input type="datetime-local" class="form-control" id="revision_init_'+number+'" name="revision_init[]" placeholder="" value="" required>'+
                            '</div>'+

                            '<label for="revision_end" class="ml-2 mt-1">Fecha Finalización de Revisión: </label>'+
                            '<div class="col-xs-12 col-sm-12 col-md-3">'+
                                '<input type="datetime-local" class="form-control" id="revision_end_'+number+'" name="revision_end[]" placeholder="" value="" required>'+
                            '</div>'+
                            '<button class="btn btn-danger ml-2" onclick="removeDates('+number+')"><i class="fa fas fa-minus"></i> Quitar</button>'+
                        '</div>';

        if(number == 1){
            $("#dates_measurement").after(elements);
        }else{
            var id = number - 1;
            $("#dates_measurement_"+id).after(elements);
        }
    }

    function removeDates(number){
        $("#dates_measurement_"+number).remove();
    }

</script>
@stop
