<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 multi_img_item">
    <a data-fancybox="gallery_<?php echo e($field); ?>" href="<?php echo e(env('APP_URL').Storage::url($pic)); ?>">
        <img src="<?php echo e(env('APP_URL').Storage::url($pic)); ?>" class="img-thumbnail" style="width:100%;">
    </a>
    <button type="button" class="btn btn-sm btn-danger del-btn" onclick="del_multi_img(this, '<?php echo e($pic); ?>', '<?php echo e($field); ?>');">
        <i class="ri-delete-bin-2-line"></i>
    </button>
</div><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/easchem/resources/views/mgr/items/template_multi_img_item.blade.php ENDPATH**/ ?>