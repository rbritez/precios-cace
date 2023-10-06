jQuery(function(){

    var event_id = $('#id').val();
    var tableShops = $("#shops-event").DataTable({
        lengthMenu  : [5, 10, 15],
        pageLength  : 5,
        responsive: true,
        "order": [[ 1, "desc" ]],
        "language": {
            url: 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json',
            searchPlaceholder: "Buscar",
        },
        ajax: 'datatable/shops-event/'+event_id,
        columns:[
            {
                data:'name'
            },
            {
                data:'platform'
            },
            // {
            //     data:null,
            //     render: function(data,type,row){
            //        var buttons =    '<button type="button" class="btn btn-xs btn-info" onclick="showProductsShop('+data.id+','+event_id+')" title="Listar Productos"><i class="fas fa-list-ol"></i></button> '+
            //                         '<button type="button" class="btn btn-xs btn-success" onclick="addProducts('+data.id+', `'+data.name+'`)" title="Agregar Productos"><i class="fas fa-plus"></i></button>';
            //        return buttons;
            //     }
            // },
            {
                data:'id',
                render: function(id){
                    var buttons = '<button type="button" class="btn btn-xs btn-danger" onclick="removeShopList('+id+')" title="Quitar Tienda" data-filter-remove><i class="fas fa-minus"></i></button>';
                    
                    return buttons
                }
            }
        ]
    });
    //-------------------- se agrega registro ------------------------------
    $('[data-filter]').on('click',function(element){
        var token = $("meta[name=csrf-token]").attr("content");
        var shop_id = $("#shop_id_modal_select").val()

        if(shop_id != undefined && shop_id != null){
            $.post('addShop',{_token:token,id:event_id,shop_id:shop_id},function(resp){
                
                getShopsId();
                tableShops.ajax.reload();
            })
            
        }else{
            alert('actualizar la pagina');
        }

    })
    //------------------ se quita registro ---------------------------------
    $('[data-filter-remove]').on('click',function(element){
        var token = $("meta[name=csrf-token]").attr("content");
        var shop_id = $("#shop_id_modal_select").val()
 
        if(shop_id != undefined && shop_id != null){
            $.post('shop-remove',{_token:token,id:event_id,shop_id:shop_id},function(resp){
                $("#btn-plus-"+shop_id).show();
                $("#btn-minus-"+shop_id).hide();
                getShopsId();
                tableShops.ajax.reload();
            })
            
        }else{
            alert('actualizar la pagina');
        }

    })


    loadTableShops();

})

function getShopsId(){
    var id = $("#id").val();

    $.get('get-shops-id',{id:id},function(response){
        if(response.length > 0){
            response.forEach(element => {
                    $("#btn-minus-"+element).show();
                    $("#btn-plus-"+element).hide();
            });
        }
    })
    
}

function loadTableShops(){
    var tableShop = $("#shops-select").DataTable({
        lengthMenu  : [5, 10, 15],
        pageLength  : 5,
        responsive: true,
        "order": [[ 0, "desc" ]],
        "language": {
            url: 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json',
            searchPlaceholder: "Buscar",
        }
    });

    tableShop.on('draw',function(){
        getShopsId();
    })
}


function addShop(id){
    $("#shop_id_modal_select").val(id);
}

function removeShop(id){
    $("#shop_id_modal_select").val(id);
}

function removeShopList(id){
    removeShop(id);
    $("#btn-minus-"+id).trigger('click');
}

