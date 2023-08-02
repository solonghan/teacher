<?php $__env->startSection('title'); ?> <?php echo e($title); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<link href="<?php echo e(URL::asset('assets/libs/quill/quill.min.css')); ?>" rel="stylesheet" type="text/css" />
<link href="<?php echo e(URL::asset('css/cropper.min.css')); ?>" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="dist/cropper.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.css" />

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php $__env->startComponent('mgr.components.breadcrumb'); ?>
<?php $__env->slot('li_1_url'); ?> <?php echo e($parent_url); ?> <?php $__env->endSlot(); ?>
<?php $__env->slot('li_1'); ?> <?php echo e($parent); ?> <?php $__env->endSlot(); ?>
<?php $__env->slot('title'); ?> <?php echo e($title); ?> <?php $__env->endSlot(); ?>
<?php echo $__env->renderComponent(); ?>
<div class="card row">
    <div class="card-body">
        <form action="<?php echo e($form_action); ?>" method="POST" enctype="multipart/form-data" id="form">
            <?php echo csrf_field(); ?>
            <?php if($action == 'default'): ?>
            <button type="button" class="btn btn-sm btn-info" onclick="file.click();">上傳Excel</button>
            
            <input type="file" name="file" id="file" style="position: absoulte; top:-100px; left:-100px; width:1px; height:1px;">
            <input type="hidden" name="action" value="default">
            <?php else: ?>
            <div class="table-responsive table-card mb-1">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <?php $__currentLoopData = $th_title; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($item[1] == ''): ?> <?php continue; ?> <?php endif; ?>
                            <th scope="col" style="<?php if($item[2]!=''): ?> min-width:<?php echo e($item[2]); ?>px; <?php endif; ?>"><?php echo $item[0]; ?></th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <?php $__currentLoopData = $th_title; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($f[1] == ''): ?> <?php continue; ?> <?php endif; ?>
                            <td>
                                <?php if(is_array($item[$f[1]])): ?>
                                    <?php $__currentLoopData = $item[$f[1]]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo $sub; ?><br>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                <?php echo $item[$f[1]]; ?>

                                <?php endif; ?>
                            </td>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <button type="submit" class="btn btn-sm btn-primary">確認匯入</button>
            <input type="hidden" name="action" value="check">
            <?php endif; ?>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
$(document).ready(function(e) {
    $("#file").on('change', function () {
        $("#form").submit();
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('mgr.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ntue_teacher_0509\resources\views/mgr/template_import.blade.php ENDPATH**/ ?>