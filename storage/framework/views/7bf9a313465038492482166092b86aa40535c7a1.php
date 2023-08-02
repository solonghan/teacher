<tr data-id="<?php echo e($item->id); ?>">
    <td><?php echo e($item->id); ?></td>
    <td>
        <?php if($item->path != ''): ?>
        <img src="<?php echo e(env('APP_URL').Storage::url($item->path)); ?>" style="width: 200px;">
        
        <?php endif; ?>
    </td>
    <td>
        <?php if($item->link_txt != ''): ?>
        <a href='<?php echo e($item->link); ?>'><?php echo e($item->link_txt); ?></a>
        <?php endif; ?>
    </td>
    <td>
        <?php if(strtotime($item->offline_date) < time()): ?>
            <span class="text text-danger">已下架</span>
        <?php elseif(strtotime($item->online_date) < time()): ?>
            <span class="text text-success">上架中</span>
        <?php else: ?>
            <span class="text text-warning">排程中</span>
        <?php endif; ?>
        <br>
        起: <?php echo e($item->online_date); ?><br>
        迄: <?php echo e($item->offline_date); ?>

    </td>
    <td><?php echo e($item->created_at); ?></td>
    <td>
        <button class="btn btn-sm btn-primary edit-item-btn">編輯</button>

        <button class="btn btn-sm btn-danger del-item-btn">刪除</button>
    </td>
</tr><?php /**PATH C:\xampp\htdocs\NTU_02\resources\views/mgr/items/carousel_item.blade.php ENDPATH**/ ?>