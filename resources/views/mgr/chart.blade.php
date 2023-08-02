@extends('mgr.layouts.master')
@section('title') {{$title}} @endsection
@section('css')

@endsection
@section('content')
    @component('mgr.components.breadcrumb', ['btns' => $btns??array()])
    @slot('li_1_url') {{$parent_url}} @endslot
    @slot('li_1') {{$parent}} @endslot
    @slot('title') {{$title}} @endslot
    @endcomponent
    <div class="row">
        @if (isset($bar_btns))
            <div class="col-12 row mb-2">
                @foreach ($bar_btns as $btn)
                <div class="col-{{ $btn[3] }}">
                    <button type="button" class="btn btn-{{ $btn[2] }} btn-animation waves-effect waves-light" onclick="{{ $btn[1] }}">{{ $btn[0] }}</button>
                </div>
                @endforeach
            </div>
        @endif
        @foreach ($charts as $chart)

            <div class="col-xl-{{ $chart['layout'] }}">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">{{ $chart['title'] }}</h4>
                    </div>
                    <div class="card-body">
                        <div id="{{ $chart['id'] }}" class="apex-charts" dir="ltr"></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script>

        $(document).ready(function () {
            load_data();
        });

        function load_data(){
            let data = {
                _token: '{{ csrf_token() }}',
            };
            $.ajax({
                type: "POST",
                url: '{{env('APP_URL')}}/mgr/{{$controller??''}}/data',
                data: data,
                dataType: "json",
                success: function(data){
                    console.log(data)
                    if (data.status){
                        $.each(data.data, function (index, elem) { 
                            if (elem.type == 'bar') {
                                generate_bar(elem);
                            }else if (elem.type == 'mix') {
                                generate_mix_chartbar(elem);
                            }
                        });                        
                    }
                },
                failure: function(errMsg) {
                    alert(errMsg);
                }
            });
        }

        function generate_bar(item){
            console.log('generate bar: '+item.id)
            var chart = new ApexCharts(
                document.querySelector("#"+item.id),
                {
                    chart: {
                        height: item.height,
                        type: 'bar',
                        toolbar: {
                            show: true,
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: (item.horizontal),
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    series: [{
                        name: item.label,
                        data: item.data
                    }],
                    colors: [
                        "rgba("+getComputedStyle(document.documentElement).getPropertyValue('--vz-primary-rgb')+")"
                    ],
                    grid: {
                        borderColor: '#f1f1f1',
                    },
                    xaxis: {
                        categories: item.labels
                    },
                    legend: {
                        show: true
                    }
                }
            ).render();
        }

        function generate_mix_chartbar(item){
            let yaxis = [];
            let colors = [];
            $.each(item.x_labels, function (i, label) { 
                colors.push(label.color);
                yaxis.push( {
                            seriesName: label.name,
                            opposite: true,
                            axisTicks: {
                                show: true,
                            },
                            axisBorder: {
                                show: true,
                                color: label.color
                            },
                            labels: {
                                style: {
                                    colors: label.color,
                                }
                            },
                            title: {
                                text: label.name,
                                style: {
                                    color: label.color,
                                    fontWeight: 400
                                }
                            },
                        }
                );
            });
            var chart = new ApexCharts(document.querySelector("#"+item.id), 
                {
                    series: item.data,
                    // [{
                    //     name: '訂單數量',
                    //     type: 'column',
                    //     data: [23, 11, 22, 27, 13, 22, 37, 21, 44, 22, 30]
                    // }, {
                    //     name: '總金額',
                    //     type: 'line',
                    //     data: [300000, 200005, 3600000, 3000000, 4500000, 300005, 600004, 5200000, 5900000, 3000006, 3000009]
                    // }],
                    chart: {
                        height: item.height,
                        type: 'line',
                        stacked: false,
                        toolbar: {
                            show: true,
                        }
                    },
                    stroke: {
                        width: [0, 2, 5],
                        curve: 'smooth'
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: '50%'
                        }
                    },
                    // fill: {
                    //     opacity: [0.85, 0.25, 1],
                    //     gradient: {
                    //         inverseColors: false,
                    //         shade: 'light',
                    //         type: "vertical",
                    //         opacityFrom: 0.85,
                    //         opacityTo: 0.55,
                    //         stops: [0, 100, 100, 100]
                    //     }
                    // },
                    labels: item.labels,
                    markers: {
                        size: 0
                    },
                    xaxis: {
                        type: 'text'
                    },
                    yaxis: yaxis,
                    tooltip: {
                        shared: true,
                        intersect: false,
                        y: {
                            formatter: function (y) {
                                if (typeof y !== "undefined") {
                                    return y.toFixed(0);
                                }
                                return y;
                            }
                        }
                    },
                    colors: colors
                }
            ).render();
        }

        {!! $custom_js??'' !!}
    </script>
@endsection
