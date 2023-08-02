<head>
    <!--[if mso]>
    <style type="text/css">
        span {padding:8px 15px;}
    </style>
    <![endif]-->
</head>
<div style="width: 205mm;">
    <table style="width: 100%;" border="0">
        <tr>
            <td style="font-size:12px;">
                伊士肯化學股份有限公司<br>
                台北市松山區南京東路三段261號4樓B室<br>
                RM.B,4FL.,NO.261,SEC.3,NANKING EAST ROAD,TAIPEI,TAIWAN<br>
                TEL: (02)2545-0099　　　　FAX: (02)2545-0088
                
            </td>
            <td style="text-align:right;">
                <img src="<?php echo e(env('APP_URL')."/assets/images/logo.png"); ?>" style="width: 180px;" width="180">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <br>
                <span style="color:#999; font-size:13px; font-weight: 300;">訂單日期: <?php echo e($order->created_at); ?></span><br>
                <span style="color:#1173BA; font-size: 24px; font-weight: 500;">訂單編號#<?php echo e($order->order_no); ?></span>
            </td>
        </tr>
    </table>
    <br>
    <?php if(isset($user)): ?>
    <table style="width: 100%; margin-bottom: 10px;" border="0">
        <tr>
            <td style="font-size: 14px;">
                <strong>公司：</strong> <?php echo e($user->company); ?><br>
                <strong>姓名：</strong> <?php echo e($user->username); ?><br>
                <strong>Email：</strong> <?php echo e($user->email); ?><br>
                <strong>聯絡電話：</strong> <?php echo e($user->phone??''); ?><br>
                <strong>分機：</strong> <?php echo e($user->ext??''); ?><br>
                <strong>傳真：</strong> <?php echo e($user->fax??''); ?><br>
            </td>
            <td style="font-size: 14px;">
                <strong>負責業務：</strong> <?php echo e($user->manage_user[0]->username??''); ?><br>
                <strong>公司電話：</strong> <?php echo e($user->manage_user[0]->tel??''); ?><br>
                <strong>分機：</strong> <?php echo e($user->manage_user[0]->ext??''); ?><br>
                <strong>手機：</strong> <?php echo e($user->manage_user[0]->mobile??''); ?><br>
                <strong>傳真：</strong> <?php echo e($user->manage_user[0]->fax??''); ?><br>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="font-size: 14px;">
                <br>
                <strong>收貨日期：</strong> <?php echo e($order->cart->recipient_date); ?><br>
                <strong>發票地址：</strong> <?php echo e($order->cart->invoice_address); ?><br>
                <strong>送貨地址：</strong> <?php echo e($order->cart->recipient_address); ?><br>
                <br>
                <strong>付款方式：</strong> <?php echo e($user->transaction_method()); ?><br>
                <strong>訂單狀態：</strong> <?php echo e($order->status_show()); ?><br>
                <strong>付款狀態：</strong> <?php echo e($order->payment_status_show()); ?><br>
                <strong>物流狀態：</strong> <?php echo e($order->shipping_status_show()); ?>

            </td>
        </tr>
    </table>
    <?php endif; ?>
    <table style="width: 100%;border: 1px solid #CCC;font-size: 15px;border-collapse: collapse;text-align: center;" border="1" cellpadding="2">
        <tr>
            <td colspan="4" style="font-size: 20px;font-weight: normal;text-align:center;background: #1173BA;color: white;padding: 5px;"><?php echo e($title); ?></td>
        </tr>
        <tr>
            <td>產品</td>
            <td>單價</td>
            <td>數量</td>
            <td>小計</td>
        </tr>
        <?php $__currentLoopData = $cart->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td>
                <strong><?php echo e($item->name); ?></strong>
            </td>
            <td>
                <span>$ <?php echo e(number_format($item->price)); ?>/<?php echo e($item->weight); ?></span>
            </td>
            <td>
                <span>$ <?php echo e(number_format($item->quantity)); ?>/<?php echo e($item->weight); ?></span>
            </td>
            <td>
                <strong>$ <?php echo e(number_format($item->price * $item->quantity)); ?></strong>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td colspan="2">

            </td>
            <td colspan="2" style="text-align:right;">
                小計：NT$ <?php echo e(number_format($cart->price)); ?><br>
                稅：NT$ <?php echo e(number_format(round($cart->price * 0.05))); ?><br>
                總額：NT$ <?php echo e(number_format($cart->price + round($cart->price * 0.05))); ?>

            </td>
        </tr>
    </table>
    <div style="font-size: 18px; text-align:center; margin: 25px 0;">
    <?php echo $msg??''; ?>

    </div>
    <?php if(!isset($is_print)): ?>
    <table style="width: 100%;">
        <tr>
            <td style="width: 150px;">
                <div style="width:100%; font-size: 18px; text-align:center; background:#1173BA; border-radius:4px; padding: 8px 15px; text-decoration:none; color:#FFF;  mso-line-height-rule:exactly; line-height:30px;">
                    <a height="30" href="<?php echo e(route('mgr.order')); ?>">
                        <span style="text-decoration:none;">前往訂單列表»</span>
                    </a>
                </div>
            </td>
        </tr>
    </table>
    <!-- <div style="font-size: 18px; text-align:center;">
        <a height="30" href="<?php echo e(route('mgr.order')); ?>" style="background:#1173BA; border-radius:4px; border: 1px solid #0163AA; padding: 8px 15px; text-decoration:none; color:#FFF;  mso-line-height-rule:exactly; line-height:30px;">
            <span style="text-decoration:none;">前往訂單列表»</span>
        </a>
    </div> -->
    <?php endif; ?>
</div>
<script>
<?php if(isset($is_print)): ?>
    print();
<?php endif; ?>
</script><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/easchem/resources/views/mail/order.blade.php ENDPATH**/ ?>