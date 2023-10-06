@extends('adminlte::page')

@section('title', '| Crear Tienda')

{{-- @section('content_header')
    <h1>Credenciales</h1>
@stop --}}

@section('content')
        <div class="row">
        <div class="mt-3 col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h2 class="font-weight-bold">Nueva Tienda</h2>
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

                    <form action="{{route('shops.store')}}" method="post" class="col-lg-12">
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label">Nombre Tienda</label>
                            <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" name="name" placeholder="tienda" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="event_init" class="col-sm-3 col-form-label">URL Principal</label>
                            <div class="col-sm-9">
                            <input type="text" class="form-control" id="url" name="url" placeholder="https://tienda.com/" value="" required>
                                <div class="text-danger" id="info-url-validation" style="display: none">
                                    La URL ingresada no es válida
                                </div>
                            </div>
                        </div>
                        <div class="form-group row" id="platform-show">
                            <label for="event_end" class="col-sm-3 col-form-label">Plataforma</label>
                            <div class="col-sm-9">
                            <input type="text" class="form-control" id="platform" list="platform-list" name="platform" placeholder="WordPress,Salesforce,Zendesk,Laravel" value="" required>
                            <datalist id="platform-list">
                                @foreach ($platforms as $platform)
                                    <option value="{{$platform->name}}">
                                @endforeach
                            </datalist>
                                <div id="show_tecnology" class="mt-1" style="display:none">
                                    <h6>Tecnologias encontradas en <strong id="url_title"></strong>:</h6>
                                    <ul id="ul_tecnology" style="font-size: 13px">
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row">
                            <label for="products" class="col-sm-3 col-form-label">Seleccionar productos:</label>
                            <div class="col-sm-9">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" onclick="showFormProducts(true)" value="option1">
                                    <label class="form-check-label" for="inlineRadio1">Seleccionar ahora</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" onclick="showFormProducts(false)" value="option2" checked>
                                    <label class="form-check-label" for="inlineRadio2">Seleccionar más tarde</label>
                                </div>
                            </div>
                        </div>
                        <div id="products-selected-add" style="display: none;">
                            <hr>
                            <button type="button" class="btn btn-block btn-success m-3" data-toggle="modal" data-target="#modalProductSelect"> <i class="fas fa-plus"></i> Agregar productos</button>
                            <table class="table table-striped">
                                <thead>
                                    <th>Quitar</th>
                                    <th>Nombre</th>
                                    <th>URL del producto</th>
                                </thead>
                                <tbody id="products-adds">

                                </tbody>
                            </table>
                        </div>

                        <a type="button" class="btn btn-info" onclick="previous()"> <i class="fas fa-arrow-left"></i> Volver</a>
                        <button type="submit" class="btn btn-primary float-right"> <i class="fas fa-save"></i> Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('admin.shops.modal-product-select')
@stop

@section('css')
    <style>
        .form-check-input, .form-check-label{
            cursor: pointer;
        }
    </style>
@stop

@section('js')
<script src="{{asset('js/shops.js')}}"></script>
<script>
    function previous(){
        window.history.back();
    }
</script>
<script>
    $("#url").change(searchCSM);

    function searchCSM(){
        clearTecnology();

        var url = $('#url').val();
        var validarprotocolo = isValidHttpUrl(url);
        var validateUrl = validURL(url);
        if(!validarprotocolo){
            $("#url").addClass('is-invalid');
            $("#info-url-validation").text('La URL ingresada debé contener el protocolo HTTPS').show();
            $("#info-url-validation").show();
        }else if(!validateUrl){
            $("#url").addClass('is-invalid');
            $("#info-url-validation").text('LA URL ingresada es inválida').show();
            $("#info-url-validation").show();
        }else{
            $("#info-url-validation").hide();
            $("#url").removeClass('is-invalid');
            $.get('../search-cms',{'url':url},function(response){
                
                $("#show_tecnology").show();
                $('#platform-show').addClass('bg-gradient-lightblue pt-1 pb-1');
                $("#url_title").text(url);
              
                if(response.status == 200){
                    response.data.forEach(element => {
                        $("#ul_tecnology" ).append('<li class="li-list">'+element+'</li>')
                    });
                }else{
                    $("#ul_tecnology" ).append('<li class="li-list">'+response.data+'</li>')
                }
               
            });
        }
        

    }

    function validURL(str) {
        var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
            '(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
            '(\\#[-a-z\\d_]*)?$','i'); // fragment locator
        return !!pattern.test(str);
    }

    function isValidHttpUrl(string) {
        let url;
        
        try {
            url = new URL(string);
        } catch (_) {
            return false;  
        }

        return url.protocol === "http:" || url.protocol === "https:";
    }
    
    function clearTecnology(){
        $('#platform-show').removeClass('bg-gradient-lightblue pt-1 pb-1');
        $("#show_tecnology").hide();
        $("#url_title").text('');
        $('.li-list').remove();
    }


</script>
@stop
