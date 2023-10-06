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