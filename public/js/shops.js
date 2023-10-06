function init(){
    loadTableProducts();
    getProductsId();
}

function loadTableProducts(){
    table = $("#products-select").DataTable({
        lengthMenu  : [5,10],
        pageLength  : 5,
        responsive: true,
        "order": [[ 1, "asc" ]],
        "language": {
            url: 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json',
            searchPlaceholder: "Buscar",
        }
    });
}


function getProductsId(){
    var pathname = window.location.pathname;
    var edit = pathname.includes('edit');
    var id = $("#id").val();
    if(edit){
        $.get('../get-products-id',{id:id},function(response){
            if(response.length > 0){
                response.forEach(element => {
                        $("#btn-minus-"+element).show();
                        $("#btn-plus-"+element).hide();
                });
            }
        })
    }
}

function dividirCadena(cadenaADividir,separador) {
   var arrayDeCadenas = cadenaADividir.split(separador);
   console.log("<br>El array tiene " + arrayDeCadenas.length + " elementos: ");

   for (var i=0; i < arrayDeCadenas.length; i++) {
      console.log(arrayDeCadenas[i] + " / ");
   }
}

function showFormProducts(flag){
    if(flag){
        $('#products-selected-add').show();
        
    }else{
        $('#products-selected-add').hide();
        $('#products-adds').text('');
        $('.add-btn').show();
        $('.subs-btn').hide();
    }
    
}

function addProduct(id,name,url_product){
    const url = $("#url").val();
    var rowProduct =    '<tr id="tr_'+id+'">'+
                            '<td><button type="button" class="btn btn-danger" onclick="removeProduct('+id+')"><i class="fas fa-minus"></i></button></td>'+
                            '<td><input type="hidden" name="product_id[]" value="'+id+'">'+name+'</td>'+
                            '<td>'+
                              '<div class="input-group mb-2 mr-sm-2">'+
                                    '<div class="input-group-prepend">'+
                                    '<div class="input-group-text">'+url+'</div>'+
                                    '</div>'+
                                    '<input type="text" class="form-control"  name="url_product[]" placeholder="productos/" value="'+url_product+'" required>'+
                                '</div>'+
                            '</td>'+
                        '</tr>';
    $("#btn-minus-"+id).show();
    $("#btn-plus-"+id).hide();
    $('#products-adds').append(rowProduct);
}

function removeProduct(id){

    $('#tr_'+id).remove();

    $("#btn-minus-"+id).hide();
    $("#btn-plus-"+id).show();
}

init();