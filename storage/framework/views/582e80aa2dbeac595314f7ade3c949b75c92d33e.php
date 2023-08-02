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
           

            <?php if(isset($item['item_member_id'])  && $item['item_member_id'] != $item['member_id']): ?>
                
            <?php else: ?>
                <?php if(isset($item['member_department'])): ?>
                    <?php $__currentLoopData = $item['member_department']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m_department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($m_department != $item['my_department']): ?>
                         
                         <?php else: ?>
                            <button class="btn btn-sm btn-primary edit-item-btn">編輯</button>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                     <button class="btn btn-sm btn-primary edit-item-btn">編輯</button>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
        
        <?php if(!isset($item['priv_del']) || $item['priv_del']): ?>
        <button class="btn btn-sm btn-danger del-item-btn">刪除</button>
        <?php endif; ?>

        <?php if(!isset($item['priv_edit_academics']) || $item['priv_edit_academics']): ?>
        <button class="btn btn-sm btn-primary edit_academics-item-btn">編輯</button>
        <?php endif; ?>

        <?php if(!isset($item['priv_del_academics']) || $item['priv_del_academics']): ?>
        <button class="btn btn-sm btn-danger del_academics-item-btn">刪除</button>
        <?php endif; ?>

    </td>
</tr><?php /**PATH C:\xampp\htdocs\ntue_teacher_0509\resources\views/mgr/items/academics_item.blade.php ENDPATH**/ ?>