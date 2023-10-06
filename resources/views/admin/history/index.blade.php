@extends('adminlte::page')

@section('title', '| Historial')

{{-- @section('content_header')
    <h1>Credenciales</h1>
@stop --}}

@section('content')
        <div class="row">
        <div class="mt-3 col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h2 class="font-weight-bold">Historial de Precios</h2>
                    <hr>
                    <form class="form-inline mb-3 justify-content-center" method="GET">
                        <div class="form-group col-3 text-center">
                            <label for="">Desde:</label>
                            <input type="date" name="from" id="from" value="{{request()->from ?? old('from')}}" class="form-control col-12">
                        </div>
                        <div class="form-group col-3 text-center">
                            <label for="">Hasta:</label>
                            <input type="date" name="to" id="to" value="{{request()->to ?? old('to')}}" class="form-control col-12">
                        </div>
                        <div class="form-group col-md-2 text-center">   
                            <button type="submit" class="btn btn-primary  mt-4"><i class="fas fa-filter"></i> Filtrar</button>
                        </div>
                    </form>
                    <div class="alert alert-success" role="alert" id="alert-pass" >
                        El <strong>Precio</strong> que figura en el listado es el precio máximo que se pudo obtener del producto, ya sea precio de lista o no.
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                    </div>
                    <div class="table-responsive">
                        <table id="history" class="table table-hover table-striped">
                            <div class="row">
                                <div class="col-12">
                                    <form action="{{route('export.history')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="from" id="from-export" value="{{request()->from ?? old('from')}}">
                                        <input type="hidden" name="to" id="to-export" value="{{request()->to ?? old('to')}}">
                                        <button type="submit" class="btn btn-success mb-3 "><i class="fas fa-file-csv"></i> Exportar</button>
                                    </form>
                                    
                                </div>
                            </div>
                            <thead>
                            <tr class="text-center">
                                <th>#</th></th>
                                <th>Fecha</th>
                                <th>Producto</th>
                                <th>Url</th>
                                <th>Precio</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $d)
                                    <tr class="text-center">
                                        <td>{{$d->id}}</td>
                                        <td>{{$d->created_at->format('d/m/Y H:i:s')}}</td>
                                        <td>{{$d->product->name}}</td>
                                        <td><a href="{{$d->urlFormat}}" target="_blank"  >{{$d->urlFormat}}</a></td>
                                        <td>{{$d->maxPrice}}</td>
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
        	table = $("#history").DataTable({
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
