<div class="modal fade" id="modalProductSelect" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Lista de Productos</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <hr>
            <table class="table table-striped" id="products-select">
                <thead>
                    <th>Agregar</th>
                    <th>Nombre</th>
                    <th>URL Producto</th>
                </thead>
                <tbody>
                    @foreach ($products as $product )
                    <tr>
                        <td>
                            <button type="button" class="btn btn-success add-btn" id="btn-plus-{{$product->id}}" onclick="addProduct({{$product->id}},'{{$product->name}}','{{$product->url}}')"><i class="fas fa-plus"></i></button>
                            <button type="button" class="btn btn-danger subs-btn" id="btn-minus-{{$product->id}}" onclick="removeProduct({{$product->id}})" style="display: none"><i class="fas fa-minus"></i></button>
                        </td>
                        <td>{{$product->name}}</td>
                        <td>{{$product->url}}</td>
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
