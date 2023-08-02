<tr data-id="<?php echo e($item['id']); ?>">
    <?php $__currentLoopData = $item['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $obj): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <td
        <?php if(isset($th_title[$index]) && $th_title[$index]['width'] != ''): ?>
        style = "max-width: <?php echo e($th_title[$index]['width']); ?>"
        <?php endif; ?>
    ><?php echo $obj; ?></td>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <td>
        <?php if(isset($item['other_btns'])): ?>
            <?php $__currentLoopData = $item['other_btns']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $btn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button class="btn btn-sm <?php echo e($btn['class']); ?>" onclick="<?php echo e($btn['action']); ?>"><?php echo e($btn['text']); ?></button>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>

        <?php if(!isset($item['priv_edit']) || $item['priv_edit']): ?>
        <button class="btn btn-sm btn-primary edit-item-btn">編輯</button>
        <?php endif; ?>
        
        <?php if(!isset($item['priv_del']) || $item['priv_del']): ?>
        <button class="btn btn-sm btn-danger del-item-btn">刪除</button>
        <?php endif; ?>

        <?php if(!isset($item['priv_edit_department']) || $item['priv_edit_department']): ?>
        <button class="btn btn-sm btn-primary edit_department-item-btn">編輯</button>
        <?php endif; ?>

         <?php if(!isset($item['priv_view_department']) || $item['priv_view_department']): ?>
        <button class="btn btn-sm btn-primary view_department-item-btn"style="color: #fff;background-color: orange; border-color: orange;">檢視</button>
        <?php endif; ?>

    </td>
</tr><?php /**PATH C:\xampp\htdocs\ntue_teacher_0509\resources\views/mgr/items/department_item.blade.php ENDPATH**/ ?>