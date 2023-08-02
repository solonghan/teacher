
<footer>
    <div class="footer_container">
        <div class="container">
            <div class="footer_top">
                <div class="row">
                    <div class="footer_logo col">
                        <?php if($footer_intro->img != ''): ?>
                        <a href="<?php echo e(route('home')); ?>" class="mb-3">
                            <img src="<?php echo e(env('APP_URL').Storage::url($footer_intro->img)); ?>" alt="">
                        </a>
                        <?php endif; ?>
                        <div class="logo_info">
                            <?php echo e($footer_intro->content); ?>

                        </div>
                    </div>
                    <?php $__currentLoopData = $company; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-xxl col-sm-12 company_data">
                        <div class="title mb-3"><h5><?php echo e($item->company); ?></h5></div>
                        <?php if($item->mobile != ''): ?>
                        <div class="phone info_div">
                            <div class="img_container">
                                <img src="dist/assets/icon/phone.png" alt="">
                            </div>
                            <div class="number limit_two">
                                <a style="color:#FFF;" href="tel:<?php echo e($item->mobile); ?>"><?php echo e($item->mobile); ?></a>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if($item->tel != ''): ?>
                        <div class="telephone info_div">
                            <div class="img_container">
                                <img src="dist/assets/icon/telephone.png" alt="">
                            </div>
                            <div class="number limit_two">
                                <a style="color:#FFF;" href="tel:<?php echo e($item->tel); ?>"><?php echo e($item->tel); ?></a>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if($item->email != ''): ?>
                        <div class="mail info_div">
                            <div class="img_container">
                                <img src="dist/assets/icon/mail.png" alt="">
                            </div>
                            <div class="number limit_two">
                                <a style="color:#FFF;" href="mailto:<?php echo e($item->email); ?>"><?php echo e($item->email); ?></a>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if($item->address != ''): ?>
                        <div class="position info_div">
                            <div class="img_container">
                                <img src="dist/assets/icon/position.png" alt="">
                            </div>
                            <div class="number limit_two">
                                <a style="color:#FFF;" href="https://www.google.com/maps/dir/?api=1&origin=<?php echo e($item->address); ?>"><?php echo e($item->address); ?></a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <div class="footer_bottom">
                <div class="row justify-content-between">
                    <div class="col-lg-4 col-sm-12">
                        <?php echo e(__('page.copyright')); ?>

                    </div>
                    <div class="col-lg-4 col-sm-12">
                        <a href="<?php echo e(route('shopping_flow')); ?>"><?php echo e(__('page.shopping_flow')); ?></a> | <a href="<?php echo e(route('payment_method')); ?>"><?php echo e(__('page.payment_method')); ?></a> | <a href="<?php echo e(route('privacy')); ?>"><?php echo e(__('page.privacy')); ?></a> 
                    </div>
                </div>
            </div>
        </div>
        <!-- 快速採買modal -->
        <div class="modal fade" id="quick_add_cart_modal" tabindex="-1" aria-labelledby="quick_add_cart_modal_label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <p class="modal_title"></p>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="confirm_btn" data-bs-dismiss="modal" onclick="location.href='<?php echo e(route('cart')); ?>';">OK</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="quick_buy_fail_modal" tabindex="-1" aria-labelledby="quick_buy_fail_modal_label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <p class="modal_title" id="quick_buy_fail_modal_label">您尚未於網站購買任何商品。<br>(快速採買功能為 將您上次購買之所有品項快速加入購物車)</p>
                    </div>
                    <div class="modal-body">
                        
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="cancel_btn" data-bs-dismiss="modal">取消</button> -->
                        <button type="button" class="confirm_btn" data-bs-dismiss="modal">確定</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<a href="javascript:void(0);" class="gotopbtn">
  <ion-icon name="arrow-up-outline"></ion-icon>
</a>
<!-- Ionic icons CDN -->
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script><?php /**PATH C:\xampp\htdocs\NTU_02\resources\views/components/footer.blade.php ENDPATH**/ ?>