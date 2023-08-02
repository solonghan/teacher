<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">
                <?php echo e($title); ?>

                <?php $__currentLoopData = $btns??array(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $btn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e($btn[2]); ?>" type="button" class="btn btn-sm btn-outline-<?php echo e($btn[3]); ?> btn-icon waves-effect waves-light" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-html="true" title="<?php echo e($btn[1]); ?>"><?php echo $btn[0]; ?></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('mgr.home')); ?>">Dashboard</a></li>
                    <?php if(isset($li_1) && $li_1 != ''): ?>
                    <li class="breadcrumb-item"><a href="<?php echo e($li_1_url); ?>"><?php echo e($li_1); ?></a></li>
                    <?php endif; ?>
                    <?php if(isset($title)): ?>
                        <li class="breadcrumb-item active"><?php echo e($title); ?></li>
                    <?php endif; ?>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
<?php /**PATH C:\xampp\htdocs\ntue_teacher_0509\resources\views/mgr/components/breadcrumb.blade.php ENDPATH**/ ?>