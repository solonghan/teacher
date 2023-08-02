<tr data-id="<?php echo e($item->id); ?>">
    <td><?php echo e($item->id); ?></td>
    <td><?php echo e($item->title); ?></td>
    <td><?php echo e($item->created_at); ?></td>
    <td>
        <button class="btn btn-sm btn-primary edit-item-btn">編輯</button>
        
        <button class="btn btn-sm btn-danger del-item-btn">刪除</button>
    </td>
</tr><?php /**PATH C:\xampp\htdocs\NTU_02\resources\views/mgr/items/page_item.blade.php ENDPATH**/ ?>