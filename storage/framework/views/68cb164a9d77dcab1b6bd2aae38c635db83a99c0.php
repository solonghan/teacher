<tr data-id="<?php echo e($item->id); ?>">
    <td><?php echo e($item->id); ?></td>
    <td><?php echo e($item->name); ?><br><?php echo e($item->name_en); ?></td>
    <td>
        <?php if($item->logo != ''): ?>
        <img src="<?php echo e(env('APP_URL').Storage::url($item->logo)); ?>" style="width: 120px;">
        
        <?php endif; ?>
    </td>
    <td>
        <?php echo e((mb_strlen($item->summary)>100)?mb_substr($item->summary,0 ,100)."...":$item->summary); ?>

    </td>
    <td><?php echo e($item->created_at); ?></td>
    <td>
        <button class="btn btn-sm btn-primary edit-item-btn">編輯</button>
        
        <button class="btn btn-sm btn-danger del-item-btn">刪除</button>
    </td>
</tr><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/easchem/resources/views/mgr/items/brand_item.blade.php ENDPATH**/ ?>