
<?php $__env->startSection('title'); ?> Dashboard <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<!-- <link href="assets/libs/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css" /> -->
<!-- <link href="assets/libs/swiper/swiper.min.css" rel="stylesheet" type="text/css" /> -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('mgr.components.breadcrumb'); ?>
<?php $__env->slot('li_1_url'); ?>  <?php $__env->endSlot(); ?>
<?php $__env->slot('li_1'); ?>  <?php $__env->endSlot(); ?>
<?php $__env->slot('title'); ?> <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>
<?php if($role == 'super' || $role == 'mgr'): ?>
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header p-0 border-0 bg-soft-light">
                <div class="row g-0 text-center">
                    <div class="col-6 col-sm-4">
                        <div class="p-3 border border-dashed border-start-0" style="cursor:pointer;" onclick="location.href='<?php echo e(route('mgr.users')); ?>';">
                            <h5 class="mb-1"><span class="counter-value" data-target="<?php echo e($user_cnt); ?>"><?php echo e($user_cnt); ?></span>
                            </h5>
                            <p class="text-muted mb-0">會員數</p>
                        </div>
                    </div><!--end col-->
                    <div class="col-6 col-sm-4">
                        <div class="p-3 border border-dashed border-start-0" style="cursor:pointer;" onclick="location.href='<?php echo e(route('mgr.users.new')); ?>';">
                            <h5 class="mb-1"><span class="counter-value" data-target="<?php echo e($user_inreview_cnt); ?>"><?php echo e($user_inreview_cnt); ?></span>
                            </h5>
                            <p class="text-muted mb-0">待審核會員</p>
                        </div>
                    </div><!--end col-->
                    <div class="col-6 col-sm-4">
                        <div class="p-3 border border-dashed border-start-0 border-end-0" style="cursor:pointer;" onclick="location.href='<?php echo e(route('mgr.contact')); ?>';">
                            <h5 class="mb-1"><span class="counter-value" data-target="<?php echo e($unread_msg); ?>"><?php echo e($unread_msg); ?></span>
                            </h5>
                            <p class="text-muted mb-0">未讀留言</p>
                        </div>
                    </div><!--end col-->
                </div>
            </div>
        </div><!-- end card -->
    </div><!-- end col -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header p-0 border-0 bg-soft-light">
                <div class="row g-0 text-center">
                    <div class="col-6 col-sm-4">
                        <div class="p-3 border border-dashed border-start-0" style="cursor:pointer;" onclick="location.href='<?php echo e(route('mgr.order')); ?>';">
                            <h5 class="mb-1"><span class="counter-value" data-target="<?php echo e($order_cnt); ?>"><?php echo e($order_cnt); ?></span>
                            </h5>
                            <p class="text-muted mb-0">訂單數</p>
                        </div>
                    </div><!--end col-->
                    <div class="col-6 col-sm-4">
                        <div class="p-3 border border-dashed border-start-0" style="cursor:pointer;" onclick="location.href='<?php echo e(route('mgr.order')); ?>';">
                            <h5 class="mb-1"><span class="counter-value" data-target="<?php echo e($total_income); ?>">$<?php echo e(number_format($total_income)); ?></span>
                            </h5>
                            <p class="text-muted mb-0">總營收</p>
                        </div>
                    </div><!--end col-->
                    <div class="col-6 col-sm-4">
                        <div class="p-3 border border-dashed border-start-0 border-end-0" style="cursor:pointer;" onclick="location.href='<?php echo e(route('mgr.order')); ?>';">
                            <h5 class="mb-1"><span class="counter-value" data-target="<?php echo e($total_receivable); ?>">$<?php echo e(number_format($total_receivable)); ?></span>
                            </h5>
                            <p class="text-muted mb-0">待收款</p>
                        </div>
                    </div><!--end col-->
                </div>
            </div>
        </div><!-- end card -->
    </div><!-- end col -->
</div><!-- end row -->
<?php endif; ?>
<div class="row">
    <div class="col-xl-12">
        <div class="card crm-widget">
            <div class="card-body p-0" style="cursor:pointer;">
                <div class="row g-0">
                    <div class="col" onclick="location.href='<?php echo e(route('mgr.order')); ?>';">
                        <div class="py-4 px-3">
                            <h5 class="text-muted text-uppercase fs-13">待審核訂單</h5>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-auction-line display-6 text-muted"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h2 class="mb-0"><span class="counter-value" data-target="<?php echo e($summary['inreview']); ?>"><?php echo e($summary['inreview']); ?></span></h2>
                                </div>
                            </div>
                        </div>
                    </div><!-- end col -->

                    <div class="col" onclick="location.href='<?php echo e(route('mgr.order')); ?>';">
                        <div class="py-4 px-3">
                            <h5 class="text-muted text-uppercase fs-13">待付款訂單</h5>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-exchange-dollar-line display-6 text-muted"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h2 class="mb-0"><span class="counter-value" data-target="<?php echo e($summary['inreview']); ?>"><?php echo e($summary['watting_pay']); ?></span></h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col" onclick="location.href='<?php echo e(route('mgr.order')); ?>';">
                        <div class="py-4 px-3">
                            <h5 class="text-muted text-uppercase fs-13">待出貨訂單</h5>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-luggage-cart-line display-6 text-muted"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h2 class="mb-0"><span class="counter-value" data-target="<?php echo e($summary['inreview']); ?>"><?php echo e($summary['watting_ship']); ?></span></h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col" onclick="location.href='<?php echo e(route('mgr.order')); ?>';">
                        <div class="mt-3 mt-md-0 py-4 px-3">
                            <h5 class="text-muted text-uppercase fs-13">出貨中</h5>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-truck-line display-6 text-muted"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h2 class="mb-0"><span class="counter-value" data-target="<?php echo e($summary['shipping']); ?>"><?php echo e($summary['shipping']); ?></span></h2>
                                </div>
                            </div>
                        </div>
                    </div><!-- end col -->
                    <div class="col" onclick="location.href='<?php echo e(route('mgr.order')); ?>';">
                        <div class="mt-3 mt-md-0 py-4 px-3">
                            <h5 class="text-muted text-uppercase fs-13">已完成</h5>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-check-line display-6 text-muted"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h2 class="mb-0"><span class="counter-value" data-target="<?php echo e($summary['complete']); ?>"><?php echo e($summary['complete']); ?></span></h2>
                                </div>
                            </div>
                        </div>
                    </div><!-- end col -->
                    <div class="col" onclick="location.href='<?php echo e(route('mgr.order')); ?>';">
                        <div class="mt-3 mt-lg-0 py-4 px-3">
                            <h5 class="text-muted text-uppercase fs-13">已取消</h5>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-checkbox-indeterminate-line display-6 text-muted"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h2 class="mb-0"><span class="counter-value" data-target="<?php echo e($summary['cancel']); ?>"><?php echo e($summary['cancel']); ?></span></h2>
                                </div>
                            </div>
                        </div>
                    </div><!-- end col -->
                    <div class="col" onclick="location.href='<?php echo e(route('mgr.report.stock')); ?>';">
                        <div class="mt-3 mt-lg-0 py-4 px-3">
                            <h5 class="text-muted text-uppercase fs-13">低庫存</h5>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-error-warning-line display-6 text-muted"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h2 class="mb-0"><span class="counter-value" data-target="<?php echo e($summary['low_stock']); ?>"><?php echo e($summary['low_stock']); ?></span></h2>
                                </div>
                            </div>
                        </div>
                    </div><!-- end col -->
                    <div class="col" onclick="location.href='<?php echo e(route('mgr.report.stock')); ?>';">
                        <div class="mt-3 mt-lg-0 py-4 px-3">
                            <h5 class="text-muted text-uppercase fs-13">缺貨</h5>
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="ri-flashlight-line display-6 text-muted"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h2 class="mb-0"><span class="counter-value" data-target="<?php echo e($summary['not_enough']); ?>"><?php echo e($summary['not_enough']); ?></span></h2>
                                </div>
                            </div>
                        </div>
                    </div><!-- end col -->
                </div><!-- end row -->
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div><!-- end row -->
<?php if($role == 'super' || $role == 'mgr'): ?>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header border-0 align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">營收狀態</h4>
                <!-- <div>
                    <button type="button" class="btn btn-soft-secondary btn-sm">
                        ALL
                    </button>
                    <button type="button" class="btn btn-soft-secondary btn-sm">
                        1M
                    </button>
                    <button type="button" class="btn btn-soft-secondary btn-sm">
                        6M
                    </button>
                    <button type="button" class="btn btn-soft-primary btn-sm">
                        1Y
                    </button>
                </div> -->
            </div><!-- end card header -->
            <div class="card-body p-0 pb-2">
                <div>
                    <div id="revenu-chart" data-colors='["--vz-primary", "--vz-primary-rgb, 0.1", "--vz-primary-rgb, 0.50"]' class="apex-charts" dir="ltr"></div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
</div><!-- end row -->
<?php endif; ?>
<div class="row" style="display:none;">
    <div class="col-xl-6 col-md-6">
        <div class="card card-height-100">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">銷售排行榜</h4>
                <!-- <div class="flex-shrink-0">
                    <div class="dropdown card-header-dropdown">
                        <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="text-muted fs-16"><i class="mdi mdi-dots-vertical align-middle"></i></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">Today</a>
                            <a class="dropdown-item" href="#">Last Week</a>
                            <a class="dropdown-item" href="#">Last Month</a>
                            <a class="dropdown-item" href="#">Current Year</a>
                        </div>
                    </div>
                </div> -->
            </div><!-- end card header -->
            <div class="card-body">
                <div class="table-responsive table-card">
                    <table class="table align-middle table-borderless table-centered table-nowrap mb-0">
                        <thead class="text-muted table-light">
                            <tr>
                                <th scope="col">No.</th>
                                <th scope="col">商品</th>
                                <th scope="col">數量</th>
                                <th scope="col">銷售額</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for($i = 1; $i <= 10; $i++): ?>
                            <tr>
                                <td><?php echo e($i); ?></td>
                                <td>Product<?php echo e($i); ?></td>
                                <td><?php echo e(rand(1,100)); ?></td>
                                <td>$<?php echo e(number_format(rand(1000, 1000000))); ?></td>
                            </tr>
                            <?php endfor; ?>
                        </tbody><!-- end tbody -->
                    </table><!-- end table -->
                </div><!-- end -->
            </div><!-- end cardbody -->
        </div><!-- end card -->
    </div><!-- end col -->
    <div class="col-xl-6 col-md-6">
        <div class="card card-height-100">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">庫存狀況</h4>
                <!-- <div class="flex-shrink-0">
                    <div class="dropdown card-header-dropdown">
                        <a class="text-reset dropdown-btn" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="text-muted fs-16"><i class="mdi mdi-dots-vertical align-middle"></i></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">Today</a>
                            <a class="dropdown-item" href="#">Last Week</a>
                            <a class="dropdown-item" href="#">Last Month</a>
                            <a class="dropdown-item" href="#">Current Year</a>
                        </div>
                    </div>
                </div> -->
            </div><!-- end card header -->
            <div class="card-body">
                <div class="table-responsive table-card">
                    <table class="table align-middle table-borderless table-centered table-nowrap mb-0">
                        <thead class="text-muted table-light">
                            <tr>
                                <th scope="col">商品</th>
                                <th scope="col">負責業務</th>
                                <th scope="col">庫存數量</th>
                                <th scope="col">庫存狀況</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>ProductA</td>
                                <td>demo</td>
                                <td>10</td>
                                <td>低</td>
                            </tr>
                            <tr>
                                <td>ProductB</td>
                                <td>demo</td>
                                <td>100</td>
                                <td>無</td>
                            </tr>
                        </tbody><!-- end tbody -->
                    </table><!-- end table -->
                </div><!-- end -->
            </div><!-- end cardbody -->
        </div><!-- end card -->
    </div><!-- end col -->
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<!-- apexcharts -->
<script src="<?php echo e(URL::asset('/assets/libs/apexcharts/apexcharts.min.js')); ?>"></script>
<!-- <script src="<?php echo e(URL::asset('/assets/libs/jsvectormap/jsvectormap.min.js')); ?>"></script> -->
<!-- <script src="<?php echo e(URL::asset('assets/libs/swiper/swiper.min.js')); ?>"></script> -->
<!-- dashboard init -->
<!-- <script src="<?php echo e(URL::asset('/assets/js/pages/dashboard-ecommerce.init.js')); ?>"></script> -->
<!-- <script src="<?php echo e(URL::asset('/assets/js/pages/dashboard-crm.init.js')); ?>"></script> -->
<!-- <script src="<?php echo e(URL::asset('/assets/js/app.min.js')); ?>"></script> -->
<script>
var linechartcustomerColors = getChartColorsArray("revenu-chart");

var options = {
  series: [{
    name: '總營收',
    type: 'bar',
    data: <?php echo e(json_encode($chart_income)); ?>

  }, {
    name: '待收款',
    type: 'bar',
    data: <?php echo e(json_encode($chart_receivable)); ?>

  }, {
    name: '已取消',
    type: 'bar',
    data: <?php echo e(json_encode($chart_cancel)); ?>

  }],
  chart: {
    height: 374,
    type: 'line',
    toolbar: {
      show: false
    }
  },
  stroke: {
    curve: 'smooth',
    dashArray: [0, 3, 0],
    width: [0, 1, 0]
  },
  fill: {
    opacity: [1, 0.1, 1]
  },
  markers: {
    size: [0, 4, 0],
    strokeWidth: 2,
    hover: {
      size: 4
    }
  },
  xaxis: {
    categories: <?php echo $chart_x; ?>,
    axisTicks: {
      show: false
    },
    axisBorder: {
      show: false
    }
  },
  grid: {
    show: true,
    xaxis: {
      lines: {
        show: true
      }
    },
    yaxis: {
      lines: {
        show: false
      }
    },
    padding: {
      top: 0,
      right: -2,
      bottom: 15,
      left: 10
    }
  },
  legend: {
    show: true,
    horizontalAlign: 'center',
    offsetX: 0,
    offsetY: -5,
    markers: {
      width: 9,
      height: 9,
      radius: 6
    },
    itemMargin: {
      horizontal: 10,
      vertical: 0
    }
  },
  plotOptions: {
    bar: {
      columnWidth: '30%',
      barHeight: '70%'
    }
  },
  colors: linechartcustomerColors,
  tooltip: {
    shared: true,
    y: [{
      formatter: function formatter(y) {
        if (typeof y !== "undefined") {
          return "$" + y.toFixed(0);
        }

        return y;
      }
    }, {
      formatter: function formatter(y) {
        if (typeof y !== "undefined") {
            return "$" + y.toFixed(0);
        }

        return y;
      }
    }, {
      formatter: function formatter(y) {
        if (typeof y !== "undefined") {
          return y.toFixed(0);
        }

        return y;
      }
    }]
  }
};
var chart = new ApexCharts(document.querySelector("#revenu-chart"), options);
chart.render(); //Radial chart data

function getChartColorsArray(chartId) {
  if (document.getElementById(chartId) !== null) {
    var colors = document.getElementById(chartId).getAttribute("data-colors");
    colors = JSON.parse(colors);
    return colors.map(function (value) {
      var newValue = value.replace(" ", "");

      if (newValue.indexOf(",") === -1) {
        var color = getComputedStyle(document.documentElement).getPropertyValue(newValue);
        if (color) return color;else return newValue;
        ;
      } else {
        var val = value.split(',');

        if (val.length == 2) {
          var rgbaColor = getComputedStyle(document.documentElement).getPropertyValue(val[0]);
          rgbaColor = "rgba(" + rgbaColor + "," + val[1] + ")";
          return rgbaColor;
        } else {
          return newValue;
        }
      }
    });
  }
} // Projects Overview

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('mgr.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\NTU_02\resources\views/mgr/index.blade.php ENDPATH**/ ?>