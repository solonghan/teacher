
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
<div class="row">
    
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<!-- apexcharts -->
<!-- <script src="<?php echo e(URL::asset('/assets/libs/apexcharts/apexcharts.min.js')); ?>"></script> -->
<!-- <script src="<?php echo e(URL::asset('/assets/libs/jsvectormap/jsvectormap.min.js')); ?>"></script> -->
<!-- <script src="<?php echo e(URL::asset('assets/libs/swiper/swiper.min.js')); ?>"></script> -->
<!-- dashboard init -->
<!-- <script src="<?php echo e(URL::asset('/assets/js/pages/dashboard-ecommerce.init.js')); ?>"></script> -->
<!-- <script src="<?php echo e(URL::asset('/assets/js/app.min.js')); ?>"></script> -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('mgr.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ntue_teacher\resources\views/mgr/index.blade.php ENDPATH**/ ?>