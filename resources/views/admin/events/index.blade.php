@extends('adminlte::page')

@section('title', '| Eventos')

{{-- @section('content_header')
    <h1>Credenciales</h1>
@stop --}}

@section('content')
        <div class="row">
        <div class="mt-3 col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h2 class="font-weight-bold">Eventos</h2>
                    <hr>
                    @if (Session::has('message') && session::get('message') == 'ok')
                        <div class="alert alert-success" role="alert">
                            Se ha eliminado el registro correctamente !
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if (Session::has('message') && session::get('message') == 'update')
                        <div class="alert alert-success" role="alert">
                            Se ha actualizado el registro correctamente !
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
                    <div class="table-responsive">
                        <table id="events" class="table table-hover table-striped">
                            <thead>
                            <tr class="text-center">
                                <th>Id</th>
                                <th>Nombre</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $d)
                                    <tr class="text-center">
                                        <td>{{ $d->id }} </td>
                                        <td>{{ $d->name }}</td>
                                        <td>{{ $d->event_init }}</td>
                                        <td>{{ $d->event_end }}</td>
                                        <td> <strong class="p-2 {{$d->colorStatus}} rounded">{{ $d->status->name_event }}</strong></td>
                                        <td>
                                            <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                                                <div class="btn-group m-1" role="group" aria-label="Third group">
                                                    <a href="{{ route('events.show', $d['id']) }}" class="btn btn-xs btn-info" title="Más Información">
                                                        <i class="fas fa-fw fa-info" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                                <div class="btn-group m-1" role="group" aria-label="Third group">
                                                    <a href="{{ route('events.edit', $d['id']) }}" class="btn btn-xs btn-warning" title="Editar Evento">
                                                        <i class="fas fa-fw fa-pencil-alt" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                                {{-- <div class="btn-group m-1" role="group" aria-label="Third group">
                                                    <form action="{{ route('events.destroy', $d['id']) }}" method="POST">
                                                        {{ csrf_field() }} {{ method_field('DELETE') }}
                                                        <button  class="p-1 btn btn-danger btn-xs" title="Eliminar Evento"><i class="fas fa-fw fa-trash" aria-hidden="true"></i> </button>
                                                    </form>
                                                </div> --}}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" style="text-align: center">No se encontraron Eventos</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
{{-- <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css"> --}}
    <script type="text/javascript">
    let table;
    $(document).ready(function () {
        	table = $("#events").DataTable({
        		lengthMenu  : [10, 25, 50],
                pageLength  : 10,
                responsive: true,
                "order": [[ 0, "desc" ]],
                "language": {
                    url: 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json',
                    searchPlaceholder: "Buscar",
                }
            });
    });
    </script>
@stop
