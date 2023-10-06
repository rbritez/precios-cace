jQuery(function(){
    
    var event_id = $('#event').val();
    getShops(event_id);
    //tomar los cambios del select evento
    $("#event").on('change',function(){
        limpiarSelectShop();
        limpiarSelectProduct();
        var event_id = $('#event').val();
        console.log(event_id);
        getShops(event_id);

    });

    $("#shop").on('change',function(){
        limpiarSelectProduct()
        var shop_id = $('#shop').val();
        getProduct(shop_id);
    });

    $("#product").on('change',function(){
        loadHistoryChart()
    });

    $(".load-center").fadeOut("slow");

})


function getShops(event_id){
    var token = $("meta[name=csrf-token]").attr("content");
    var shopElement = $("#shop");
    $.post('home/getShops',{_token:token,id:event_id},function(response){
        response.sort((a,b) => a.name < b.name ? -1 : +(a.name > b.name))
        response.forEach(element => {
            shopElement.append("<option value="+element.id+">"+element.name+"</option>");
            if(response[0].id == element.id){
                $("#shop option[value='"+element.id+"']").attr("selected", true);
                getProduct(element.id);
            };
        });

    })
}

function getProduct(shop_id){
    var token = $("meta[name=csrf-token]").attr("content");
    var productElement = $("#product");
    $.post('home/getProducts',{_token:token,id:shop_id},function(response){
        response.sort((a,b) => a.name < b.name ? -1 : +(a.name > b.name))
        response.forEach(element => {
            productElement.append("<option value="+element.id+">"+element.name+"</option>");
            if(response[0].id == element.id){
                $("#product option[value='"+element.id+"']").attr("selected", true);
                loadHistoryChart();
    
            };
        });

    })
}

function loadHistoryChart(){
    loadMaxPriceChart()

    var event_id = $('#event').val();
    var product_id = $('#product').val();
    var token = $("meta[name=csrf-token]").attr("content");
    var line = $("#myAreaChart");
    line.fadeIn();

    
    $.post('home/load-history-chart',{_token:token,id:event_id,product_id:product_id},function(response){
        //console.log(response);
        if(line.hasClass('chartjs-render-monitor')){
            window.grafica.clear();
            window.grafica.destroy();
        }
        window.grafica = new Chart(line,{
            type:'line',
            data:{
                labels:response.fechas,
                datasets:[
                    {
                        label: 'Precio Real',
                        lineTension:0.3,
                        backgroundColor: "rgba(2,177,216,0.2)",
                        borderColor: "rgba(2,117,216,1)",
                        pointRadius:5,
                        pointBackgroundColor:"rgba(2,117,216,1)",
                        pointBorderColor: "rgba(255,255,255,0.8)",
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(2,117,216,1)",
                        pointHitRadius:20,
                        pointBorderWidth:2,
                        data:response.realPrice,
                    },
                    {
                        label: 'Precio Tachado',
                        lineTension:0.3,
                        backgroundColor: "rgba(201,17,17,0.2)",
                        borderColor: "rgba(201,17,17,1)",
                        pointRadius:5,
                        pointBackgroundColor:"rgba(201,17,17,1)",
                        pointBorderColor: "rgba(255,255,255,0.8)",
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(201,17,17,1)",
                        pointHitRadius:20,
                        pointBorderWidth:2,
                        spanGaps:true,
                        data: response.labeledPrice,
                    }
                ]
            },
            options:{
                scales:{
                    xAxes:[{
                        time:{
                            unit:'date'
                        },
                        gridLines:{
                            display:false
                        },
                        ticks:{
                            maxTicksLimit:7
                        }
                    }]
                },
                responsive:true,
                maintainAspectRatio:true,
                animation:{
                    delay: 2500,
                },
            }
        })
        
    }).fail(function(response){
       // alert('se producto un error al cargar grafico: ' + response.status);
    })
    
}


function loadMaxPriceChart(){
    var event_id = $('#event').val();
    var product_id = $('#product').val();
    var token = $("meta[name=csrf-token]").attr("content");
    var line = $("#LineMaxPrice");
    line.fadeIn();
    
    $.post('home/load-maxprice-chart',{_token:token,id:event_id,product_id:product_id},function(response){
        //console.log(response);
        if(line.hasClass('chartjs-render-monitor')){
            window.graficaMax.clear();
            window.graficaMax.destroy();
        }
        window.graficaMax = new Chart(line,{
            type:'line',
            data:{
                labels:response.fechas,
                datasets:[
                    {
                        label: 'Precio Máximo',
                        lineTension:0.3,
                        backgroundColor: "rgba(2,177,216,0.2)",
                        borderColor: "rgba(2,117,216,1)",
                        pointRadius:5,
                        pointBackgroundColor:"rgba(2,117,216,1)",
                        pointBorderColor: "rgba(255,255,255,0.8)",
                        pointHoverRadius: 5,
                        pointHoverBackgroundColor: "rgba(2,117,216,1)",
                        pointHitRadius:20,
                        pointBorderWidth:2,
                        data:response.maxPrice,
                    }
                ]
            },
            options:{
                scales:{
                    xAxes:[{
                        time:{
                            unit:'date'
                        },
                        gridLines:{
                            display:false
                        },
                        ticks:{
                            maxTicksLimit:7
                        }
                    }]
                },
                animation:{
                    delay: 2500,
                },
            }
        })
        
    }).fail(function(response){
        //alert('se producto un error al cargar grafico: ' + response.status);
    })
    
}

function limpiarSelectProduct(){
    $("#product").find('option').remove();

}
function limpiarSelectShop(){
    $("#shop").find('option').remove();

}
