@extends('adminlte::page')

@section('title', '| Dashboard')

@section('content')

{{-- @section('content_header')
    <h1>Credenciales</h1>
@stop --}}
@section('css')
<link rel="stylesheet" href="{{asset('css/preloader.css')}}">
@stop
@section('content')
        {{-- <div class="row">
        <div class="mt-3 col-lg-12 grid-margin stretch-card">
            <div class="card"> 
                <div class="card-body">
                    <h2 class="font-weight-bold">Dashboard</h2>
                    <hr>--}}
                    <div class="row mt-4"> <!-- row init -->
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card mb-0">
                            <div class="card text-white bg-primary mb-3">
                                <div class="card-body">
                                    <div class="clearfix">
                                        <p class="mb-0 text-center">Precios Inspeccionados Mensual</p>
                                        <div class="fluid-container">
                                            <h3 class="font-weight-medium text-center mb-0">{{$inspecionMes}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card mb-0">
                            <div class="card text-white bg-primary mb-3">
                                <div class="card-body">
                                    <div class="clearfix">
                                        <p class="mb-0 text-center">Precios Inspeccionados Semanal</p>
                                        <div class="fluid-container">
                                            <h3 class="font-weight-medium text-center mb-0">{{$inspecionSemana}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 grid-margin stretch-card mb-0">
                            <div class="card text-white bg-primary mb-3">
                                <div class="card-body">
                                    <div class="clearfix">
                                        <p class="mb-0 text-center">Precios Inspeccionados del Día</p>
                                        <div class="fluid-container">
                                            <h3 class="font-weight-medium text-center mb-0">{{$inspecionDia}}</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if (count($notification) > 0)
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 grid-margin stretch-card mb-0">
                                <div class="card text-white bg-danger mb-3">
                                    <div class="card-body">
                                        <div class="clearfix">
                                            <p class="h4 text-center mb-3"><i class="fas fa-exclamation-triangle"></i> Se encontraron Alteraciones para productos de MO <i class="fas fa-exclamation-triangle"></i></p>
                                            <div class="fluid-container">
                                                <table class="table table-striped" style="text-align: center">
                                                    <thead>
                                                        <tr>
                                                            <th>Evento</th>
                                                            <th>Tienda</th>
                                                            <th>Producto</th>
                                                            <th>Confirmar</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($notification as $notify )
                                                            <tr>
                                                                <td>{{$notify->alteration->event->name}}</td>
                                                                <td>{{$notify->alteration->shop->name}}</td>
                                                                <td>{{$notify->alteration->product->name}}</td>
                                                                <td><a href="{{route('update.notification',$notify->id)}}" type="button" class="btn btn-xs btn-success"><i class="fas fa-check"></i></a></td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 grid-margin stretch-card mb-0">
                            <div class="card">
                                <div class="card-title">
                                    <h3 class="font-weight-bold ml-3 mt-3">Historial de Precio por Producto</h3>
                                </div>
                                <hr>
                                <div class="card-body">
                                    <form class="form-inline mb-3 justify-content-center" method="POST" action="{{route('export.price.product')}}">
                                        @csrf
                                        <div class="form-group col-3 text-center">
                                            <label for="">Evento:</label>
                                            <select name="event" id="event" class="form-control col-12">
                                                @foreach ($events as $event )
                                                    <option value="{{$event->id}}">{{$event->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-3 text-center">
                                            <label for="">Tienda:</label>
                                            <select name="shop" id="shop" class="form-control col-12">
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3 text-center">
                                            <label for="">Producto:</label>
                                            <select name="product" id="product" class="form-control col-12">
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2 text-center">   
                                            <button type="submit" class="btn btn-success  mt-4"><i class="fas fa-file-csv"></i> Exporar</button>
                                        </div>

                                    </form>


                                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                                        <ol class="carousel-indicators">
                                            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active" style="background-color: rgba(96, 86, 86, 0.816)"></li>
                                            <li data-target="#carouselExampleIndicators" data-slide-to="1" style="background-color: rgba(96, 86, 86, 0.816)"></li>
                                        </ol>
                                        <div class="carousel-inner">
                                            <div class="carousel-item active justify-content-center">
                                                <div class="load-center"><div class="loader"></div></div>
                                                <canvas id="myAreaChart" height="60" style="display: none"></canvas>
                                                {{-- <div class="row">
                                                    <div class="col-1">&nbsp;</div>
                                                    <div class="col-10 "><canvas id="myAreaChart" height="60" style="display: none"></canvas></div>
                                                </div> --}}
                                            </div>
                                            <div class="carousel-item">
                                                <div class="load-center"><div class="loader"></div></div>
                                                <canvas id="LineMaxPrice" height="60"  style="display: none"></canvas>
                                            </div>
                                        </div>
                                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                            <span class="carousel-control-prev-icon rounded-circle m-1" aria-hidden="true" style="background-color: rgba(96, 86, 86, 0.816)"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next" >
                                            <span class="carousel-control-next-icon rounded-circle" aria-hidden="true" style="background-color: rgba(96, 86, 86, 0.816)"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 grid-margin stretch-card mb-0">
                            <div class="card">
                                <div class="card-title">
                                    <h3 class="font-weight-bold ml-3">Alteraciones</h3>
                                </div>
                                <div class="card-body">
                                    <div class="col-12  mb-0">
                                        <canvas id="myAreaChart2"  height="50"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div> <!-- row finish -->
            {{--    </div>
             </div>
        </div>
    </div> --}}
@stop

@section('js')

<script src="{{asset('js/home.js')}}"></script>
    {{-- <script>


        function createLineChart2(){
            var line = $("#myAreaChart2");
            var lineChart = new Chart(line,{
                type:'line',
                data:{
                    labels:['2021-01-02','2021-01-03','2021-02-03','2021-02-04'],
                    datasets:[
                        {
                            label: 'Alteraciones',
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
                            data:[40,105,50,70],
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
                    }
                }
            })
        }
        createLineChart2();
    </script> --}}
@stop