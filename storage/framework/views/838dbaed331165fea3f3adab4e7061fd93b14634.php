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
    <table style="width: 100%; font-size: 14px; margin-bottom: 10px;" border="0">
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
                <strong>付款方式：</strong> <?php echo e($user->transaction_method(false)); ?><br>
                <strong>訂單狀態：</strong> <?php echo e($order->status_show()); ?><br>
                <strong>付款狀態：</strong> <?php echo e($order->payment_status_show()); ?><br>
                <strong>物流狀態：</strong> <?php echo e($order->shipping_status_show()); ?>

            </td>
        </tr>
    </table>
    
    <div style="font-size: 14px; text-align:center; margin: 15px 0;">
    <?php echo $msg??''; ?>

    </div>
    <?php endif; ?>
    <table style="width: 100%;border: 1px solid #CCC;font-size: 15px;border-collapse: collapse;text-align: center;" border="1" cellpadding="2">
        <tr>
            <td colspan="5" style="font-size: 20px;font-weight: normal;text-align:center;background: #1173BA;color: white;padding: 5px;"><?php echo e($title); ?></td>
        </tr>
        <tr>
            <td></td>
            <td>產品</td>
            <td>單價</td>
            <td>數量</td>
            <td>小計</td>
        </tr>
        <?php $__currentLoopData = $cart->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td>
                <img src="<?php echo e(env('APP_URL').Storage::url($item->cover)); ?>" style="height:80px;" height="80">
            </td>
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
            <td colspan="3">

            </td>
            <td colspan="2" style="text-align:right;">
                小計：NT$ <?php echo e(number_format($cart->price)); ?><br>
                稅：NT$ <?php echo e(number_format(round($cart->price * 0.05))); ?><br>
                總額：NT$ <?php echo e(number_format($cart->price + round($cart->price * 0.05))); ?>

            </td>
        </tr>
    </table>
    <div style="font-size: 14px; margin: 15px 0;">
        <strong>備註：</strong><br>
        1、報價有限日期：<?php echo e(date('Y/m/d', strtotime('+ 7 days', strtotime($order->created_at)))); ?><br>
        2、若價格有任何異動，我司有權於交貨前做修正
    </div>
    <div style="font-size: 18px; text-align:center; margin: 25px 0;">
    <?php echo $payment_des??''; ?>

    </div>
    <?php if(!isset($is_print)): ?>
    <div style="font-size: 15px; text-align:center;">
        <a height="30" href="<?php echo e(route('order.quotation', ['order_no'=>$order->order_no])); ?>" style="background:#1173BA; border: 1px solid #0163AA; padding: 8px 15px; border-radius:4px; color:#FFF; margin-right: 10px; mso-line-height-rule:exactly; line-height:30px;">報價單»</a>

        <a height="30" href="<?php echo e(route('member')); ?>" style="background:#1173BA; padding: 8px 15px; border-radius:4px; border: 1px solid #0163AA; color:#FFF; margin-left:10px; mso-line-height-rule:exactly; line-height:30px;">訂單查詢 MyOrder»</a>
    </div>
    <?php endif; ?>
</div><?php /**PATH C:\xampp\htdocs\NTU_02\resources\views/mail/user_order.blade.php ENDPATH**/ ?>