<div class="col-lg-12 multi_file_item" id="file_<?php echo e($file_id); ?>">
    <button type="button" class="btn btn-sm btn-danger" onclick="delete_file('<?php echo e($field); ?>', '<?php echo e($file_id); ?>', '<?php echo e($path); ?>');">
        <i class="ri-delete-bin-2-line"></i>
    </button>
    <a href="<?php echo e(env('APP_URL').'/file/download/'.$file_id); ?>">
        <?php echo e($filename); ?>

    </a>
</div><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/easchem/resources/views/mgr/items/template_multi_file_item.blade.php ENDPATH**/ ?>