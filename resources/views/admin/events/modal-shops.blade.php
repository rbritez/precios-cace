<div class="modal fade" id="modalShopSelect" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Lista de Tiendas</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="shop_id_modal_select" value="">
            <hr>
            <table class="table table-striped" id="shops-select">
                <thead>
                    <th>Agregar</th>
                    <th>Nombre</th>
                    <th>Plataforma</th>
                </thead>
                <tbody>
                    @foreach ($shops as $shop )
                    <tr>
                        <td>
                            <button type="button" class="btn btn-success add-btn" id="btn-plus-{{$shop->id}}"  onclick="addShop({{$shop->id}})" data-filter ><i class="fas fa-plus"></i></button>
                            <button type="button" class="btn btn-danger subs-btn" id="btn-minus-{{$shop->id}}" onclick="removeShop({{$shop->id}})" style="display: none" data-filter-remove><i class="fas fa-minus"></i></button>
                        </td>
                        <td>{{$shop->name}}</td>
                        <td>{{$shop->platform}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-block btn-secondary " data-dismiss="modal" id="btn-close"><i class="fas fa-arrow-left"></i> Volver</button>
        </div>
        </div>
    </div>
</div>
