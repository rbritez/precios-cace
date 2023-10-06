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
                    <h2 class="font-weight-bold">Productos</h2>
                    <hr>
                    @if (Session::has('message') && session::get('message') == 'ok')
                        <div class="alert alert-success" role="alert">
                            Se ha actualizado el registro correctamente !
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    <div class="alert alert-success" role="alert" id="alert-product" style="display: none">
                    </div>
                    <div class="col-3  float-right mb-3 pr-0">
                        <button class="btn btn-success btn-block" data-toggle="modal" data-target="#modalProduct" onclick="clearForm()"><i class="fas fa-plus"></i> Agregar</button>
                    </div>  
                    <div class="table-responsive">
                        <table id="products" class="table table-hover table-striped">
                            <thead>
                            <tr class="text-center">
                                <th>Id</th>
                                <th>Nombre</th>
                                <th>Url</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $d)
                                    <tr class="text-center">
                                        <td>{{ $d->id }} </td>
                                        <td>{{ $d->name }}</td>
                                        <td>{{ $d->url }}</td>
                                        <td>
                                            <span class="@if($d->status) bg-success @else bg-danger @endif p-2 rounded">
                                                {{$d->statusName}}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                                                <div class="btn-group m-1" role="group" aria-label="Third group">
                                                    <button type="button" class="btn btn-xs btn-info" title="Editar Información" onclick="showModal({{$d->id}},'{{$d->name}}')">
                                                        <i class="fas fa-fw fa-pencil-alt" aria-hidden="true"></i>
                                                    </button>
                                                </div>
                                                <div class="btn-group m-1" role="group" aria-label="Third group">
                                                    {{-- <form action="{{ route('products.destroy', $d['id']) }}" method="POST">
                                                        {{ csrf_field() }} {{ method_field('DELETE') }}
                                                        @if ($d->status)
                                                            <button  class="p-1 btn btn-danger btn-xs" title="Deshabilitar Categoria"><i class="fas fa-fw fa-trash" aria-hidden="true"></i> </button>
                                                        @else
                                                            <button  class="p-1 btn btn-success btn-xs" title="Habilitar Categoria"><i class="fas fa-fw fa-trash-restore" aria-hidden="true"></i> </button>
                                                        @endif
                                                    </form> --}}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" style="text-align: center">No se encontraron Tiendas </td></tr>
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
<script src="{{asset('js/products.js')}}"></script>
@stop
