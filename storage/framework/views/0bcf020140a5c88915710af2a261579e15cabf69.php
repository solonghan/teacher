
<?php $__env->startSection('title'); ?> <?php echo e($title); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('mgr.components.breadcrumb', ['btns' => $btns??array()]); ?>
    <?php $__env->slot('li_1_url'); ?> <?php echo e($parent_url); ?> <?php $__env->endSlot(); ?>
    <?php $__env->slot('li_1'); ?> <?php echo e($parent); ?> <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> <?php echo e($title); ?> <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
    <div class="row">
        <?php if(isset($bar_btns)): ?>
            <div class="col-12 row mb-2">
                <?php $__currentLoopData = $bar_btns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $btn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-<?php echo e($btn[3]); ?>">
                    <button type="button" class="btn btn-<?php echo e($btn[2]); ?> btn-animation waves-effect waves-light" onclick="<?php echo e($btn[1]); ?>"><?php echo e($btn[0]); ?></button>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
        <?php $__currentLoopData = $charts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chart): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <div class="col-xl-<?php echo e($chart['layout']); ?>">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0"><?php echo e($chart['title']); ?></h4>
                    </div>
                    <div class="card-body">
                        <div id="<?php echo e($chart['id']); ?>" class="apex-charts" dir="ltr"></div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('assets/libs/apexcharts/apexcharts.min.js')); ?>"></script>
    <script>

        $(document).ready(function () {
            load_data();
        });

        function load_data(){
            let data = {
                _token: '<?php echo e(csrf_token()); ?>',
            };
            $.ajax({
                type: "POST",
                url: '<?php echo e(env('APP_URL')); ?>/mgr/<?php echo e($controller??''); ?>/data',
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

        <?php echo $custom_js??''; ?>

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('mgr.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\NTU_02\resources\views/mgr/chart.blade.php ENDPATH**/ ?>