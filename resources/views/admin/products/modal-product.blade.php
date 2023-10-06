<div class="modal fade" id="modalProduct" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Nuevo Producto</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form class="col-lg-12" id="formCategory">
                <div class="form-group row">
                    <input type="hidden" name="id" id="id">
                    <label for="name" class="col-sm-3 col-form-label">Nombre</label>
                    <div class="mt-2 col-sm-8">
                        <input type="text" class="form-control" id="name" name="name" placeholder="" value="" required>
                        <div class="text-danger" id="info-name" style="display: none">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="category_id" class="col-sm-3 col-form-label">URL</label>
                    <div class="mt-2 col-sm-8">
                        <input type="text" class="form-control" id="url" name="url" placeholder="" value="" required>
                        <div class="text-danger" id="info-category_id" style="display: none">
                           <small>El campo es obligatorio</small>
                        </div>
                    </div>
                </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn-close"><i class="fas fa-arrow-left"></i> Volver</button>
            <button type="button" class="btn btn-primary" id="btn-save"> <i class="fas fa-save"></i> Guardar</button>
        </div>
        </form>
        </div>
    </div>
</div>
