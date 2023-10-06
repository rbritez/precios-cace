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
                    <h2 class="font-weight-bold">Tiendas</h2>
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

                        <div class="alert alert-success">
                            Si el campo <strong>Plataforma</strong> esta vacío, es porque nuestro sistema no pudo capturar el CMS del sitio web. <br>
                            Puede Buscarlo de forma manual en <a href="https://whatcms.org/" target="_blank">https://whatcms.org/</a>. <br>
                            Recuerde que para realizar la busqueda de los precios es necesario el campo <strong>Plataforma</strong>. <br>
                            Plataformas Soportadas por el sistema:
                            <ul>
                                <li>Vtex</li>
                                <li>Magento</li>
                                <li>Shopify</li>
                                <li>Tiendanube</li>
                                <li>Woocommerce</li>
                                <li>Prestashop</li>
                                <li>Wix</li>
                                <li>MercadoShop</li>
                            </ul>

                            {{-- <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button> --}}
                        </div>
                    <div class="table-responsive">
                        <table id="shops" class="table table-hover table-striped">
                            <thead>
                            <tr class="text-center">
                                <th>Id</th>
                                <th>Nombre</th>
                                <th>URL</th>
                                <th>Plataforma</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $d)
                                    <tr class="text-center">
                                        <td>{{ $d->id }} </td>
                                        <td>{{ $d->name }}</td>
                                        <td>{{ $d->url }}</td>
                                        <td>{{ $d->platform }}</td>
                                        <td>
                                            <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                                                {{-- <div class="btn-group m-1" role="group" aria-label="Third group">
                                                    <a href="{{ route('shops.show', $d['id']) }}" class="btn btn-xs btn-primary" title="Ver Información">
                                                        <i class="fas fa-fw fa-info" aria-hidden="true"></i>
                                                    </a>
                                                </div> --}}
                                                <div class="btn-group m-1" role="group" aria-label="Third group">
                                                    <a href="{{ route('shops.edit', $d['id']) }}" class="btn btn-xs btn-info" title="Editar Información">
                                                        <i class="fas fa-fw fa-pencil-alt" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                                {{-- <div class="btn-group m-1" role="group" aria-label="Third group">
                                                    <form action="{{ route('shops.destroy', $d['id']) }}" method="POST">
                                                        {{ csrf_field() }} {{ method_field('DELETE') }}
                                                        <button  class="p-1 btn btn-danger btn-xs" ><i class="fas fa-fw fa-trash" aria-hidden="true"></i> </button>
                                                    </form>
                                                </div> --}}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" style="text-align: center">No se encontraron Tiendas </td></tr>
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

<script type="text/javascript">
    let table;
    $(document).ready(function(){
        table = $("#shops").DataTable({
            lengthMenu  : [10, 25, 50],
            pageLength  : 10,
            responsive: true,
            "order": [[ 0, "desc" ]],
            "language": {
                url: 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json',
                searchPlaceholder: "Buscar",
            }
        });
    })
    </script>
@stop
