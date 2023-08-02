<tr data-id="<?php echo e($item['id']); ?>">
    <?php $__currentLoopData = $item['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $obj): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <td
        <?php if(isset($th_title[$index]) && $th_title[$index]['width'] != ''): ?>
        style = "max-width: <?php echo e($th_title[$index]['width']); ?>"
        <?php endif; ?>
    ><?php echo $obj; ?></td>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    
</tr><?php /**PATH C:\xampp\htdocs\ntue_teacher_0509\resources\views/mgr/items/change_record_item.blade.php ENDPATH**/ ?>