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
                    <h2 class="font-weight-bold">Alteraciones</h2>
                    <hr>
                    {{-- formulario filtro fecha --}}
                    @include('admin.alterations.filter-date')
                     
                    <div class="table-responsive">
                        <div class="row">
                            <div class="col-12">
                                <form action="{{route('export.alterations')}}" method="post">
                                    @csrf
                                    <input type="hidden" name="from" id="from-export" value="{{request()->from ?? old('from')}}">
                                    <input type="hidden" name="to" id="to-export" value="{{request()->to ?? old('to')}}">
                                    <button type="submit" class="btn btn-success mb-3 "><i class="fas fa-file-csv"></i> Exportar</button>
                                </form>
                                
                            </div>
                        </div>
                        <table id="events" class="table table-hover table-striped">
                            <thead>
                            <tr class="text-center">
                                <th>#</th></th>
                                <th>Fecha</th>
                                <th>Producto</th>
                                <th>Url</th>
                                <th>Tipo Precio</th>
                                <th>Tipo Alteracion</th>
                                <th>Precio Anterior</th>
                                <th>Precio Actual</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $d)
                                    <tr class="text-center">
                                        <td>{{$d->id}}</td>
                                        <td>{{$d->created_at->format('d/m/Y H:i:s')}}</td>
                                        <td>{{$d->product->name}}</td>
                                        <td><a href="{{$d->urlFormat}}" target="_blank">{{$d->urlFormat}}</a></td>
                                        <td>{{$d->typePrice}}</td>
                                        <td>{{$d->typeName}}</td>
                                        <td>{{$d->price_previous}}</td>
                                        <td>{{$d->price_now}}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="9" style="text-align: center">No se encontraron Alteraciones</td></tr>
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
