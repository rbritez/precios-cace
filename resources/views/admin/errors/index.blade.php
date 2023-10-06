@extends('adminlte::page')

@section('title', '| Productos')

{{-- @section('content_header')
    <h1>Credenciales</h1>
@stop --}}

@section('content')
        <div class="row">
        <div class="mt-3 col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h2 class="font-weight-bold">Errores</h2>
                    <hr> 
                    @include('admin.alterations.filter-date')
                    <div class="table-responsive">
                        <table id="errors" class="table table-hover table-striped">
                            <div class="row">
                                <div class="col-12">
                                    <a href="{{route('export.errors')}}" class="btn btn-success mb-3 float-right"><i class="fas fa-file-csv"></i> Exportar</a>
                                </div>
                            </div>
                            <thead>
                            <tr class="text-center">
                                <th>Id</th>
                                <th>Fecha</th>
                                <th>Tienda</th>
                                <th>Url</th>
                                <th>Error</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $d)
                                    <tr class="text-center">
                                        <td>{{ $d->id }} </td>
                                        <td>{{$d->created_at->format('d/m/Y H:i:s')}}</td>
                                        <td>{{ $d->shop->name }}</td>
                                        <td><a href="{{ $d->url }}" target="_blank">{{ $d->url }}</a></td>
                                         <td>{{ $d->error }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" style="text-align: center">No se encontraron Errores </td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin.products.modal-product')
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
<script>
let table;
function loadTable() {
    table = $("#errors").DataTable({
        lengthMenu: [10, 25, 50],
        pageLength: 10,
        responsive: true,
        order: [[0, "desc"]],
        language: {
            url:
                "https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json",
            searchPlaceholder: "Buscar"
        }
    });
}
loadTable();
</script>

@stop
