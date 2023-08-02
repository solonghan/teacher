
<?php $__env->startSection('title'); ?> <?php echo e($title); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
<style>
    .table_row{
        display: flex;
    }
    .table_title{
        width: 35%;
        text-align: right;
        font-weight: 500;
        padding: 0 10px;
        margin: 4px 0;
    }
    .table_content{
        width: 65%;
        margin: 4px 0;
    }
    .return_product,
    .return_product strong{
        text-decoration: line-through;
        color: #AAA !important;
    }
</style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('mgr.components.breadcrumb', ['btns' => $btns??array()]); ?>
    <?php $__env->slot('li_1_url'); ?> <?php echo e($parent_url); ?> <?php $__env->endSlot(); ?>
    <?php $__env->slot('li_1'); ?> <?php echo e($parent); ?> <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> <?php echo e($title); ?> <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
    <div class="row">
        <div class="col-xl-7">
            <div class="card">
                <div class="card-header">
                   <div class="d-flex align-items-center">
                        <h5 class="card-title flex-grow-1 mb-0">
                            訂單 #<?php echo e($data->order_no); ?>

                            <br>
                            <?php if($role == 'assistant'): ?>
                            <form action="<?php echo e(route('mgr.order.action')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                                <div class="input-group" style="width:60%; font-size: 11px; height:25px;">
                                    <span style="padding: 1px 5px;" class="input-group-text">銷貨單號</span>
                                    <input style="padding: 2px 5px;" class="form-control" type="text" name="saleslip_no" value="<?php echo e($data->saleslip_no); ?>">
                                    <button style="padding: 1px 5px;" class="input-group-text btn btn-success" id="basic-addon2">點擊儲存</button>
                                </div>
                                <input type="hidden" name="action" value="saleslip">
                                <input type="hidden" name="form" value="form">
                                <input type="hidden" name="id" value="<?php echo e($data->id); ?>">
                                
                            </form>
                            <?php else: ?>
                            <small class="text text-secondary" style="font-size: 12px;">
                                <?php if($data->saleslip_no != ''): ?>
                                銷貨單號: <?php echo e($data->saleslip_no); ?>

                                <?php else: ?>
                                (尚未輸入銷貨單號)
                                <?php endif; ?>
                            </small>
                            <?php endif; ?>
                        </h5>
                        <div class="flex-shrink-0">
                            <!-- <a href="apps-invoices-details" class="btn btn-primary btn-sm"><i class="ri-download-2-fill align-middle me-1"></i> Invoice</a> -->
                            <?php if($data->status != 'cancel'): ?>
                            <a href="javascript:action('cancel');" class="btn btn-soft-secondary btn-sm me-2"><i class="mdi mdi-archive-remove-outline align-middle"></i> 取消訂單</a>
                            <?php endif; ?>
                            訂單狀態：<?php echo e($data->status_show()); ?>

                        </div>
                   </div>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('mgr.order.action')); ?>" method="POST" id="form">
                        <?php echo csrf_field(); ?>
                        <div class="table-responsive table-card">
                            <table class="table table-nowrap align-middle table-borderless mb-0">
                                <thead class="table-light text-muted">
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">產品</th>
                                        <th scope="col">單價</th>
                                        <th scope="col">數量</th>
                                        <th scope="col">狀態</th>
                                        <th scope="col" class="text-end">小計</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $sum = 0;
                                        $tax = 0;
                                    ?>
                                    <?php $__currentLoopData = $data->cart->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        if ($p->is_return < 2)
                                            $sum += $p->price * $p->quantity;
                                    ?>
                                    <?php if($role == 'assistant' && $p->product->assistant != Auth::guard('mgr')->user()->id): ?> <?php continue; ?> <?php endif; ?>
                                    <tr id="product_<?php echo e($p->product_id); ?>" class="<?php if($p->is_return == 2): ?> return_product <?php endif; ?>">
                                        <td>
                                            <div class="flex-shrink-0 avatar-md rounded p-1">
                                                <img src="<?php echo e(env('APP_URL').Storage::url($p->cover)); ?>" alt="" class="img-fluid d-block">
                                            </div>
                                        </td>
                                        <td>
                                            <h5 class="fs-15">
                                                <?php if($p->is_return == 1): ?>
                                                <span class="badge rounded-pill bg-danger">申請退貨</span><br>
                                                <?php endif; ?>
                                                <strong><?php echo e($p->name); ?></strong>
                                                <?php if($p->saleslip_no != ''): ?>
                                                <br><small class='text text-muted' style="font-size:11px;"><?php echo e($p->saleslip_no); ?></small>
                                                <?php endif; ?>
                                                <?php if($editable && $p->status != 'confirmed'): ?>
                                                <button onclick="priceRange('<?php echo e($p->product_id); ?>', '<?php echo e($p->name); ?>');" type="button" class="btn btn-sm btn-outline-primary btn-icon waves-effect waves-light">
                                                    <i class="bx bx-money-withdraw"></i>
                                                </button>
                                                <?php endif; ?>
                                                <?php if($p->price_remark == 'custom'): ?>
                                                <br><span class="badge rounded-pill bg-warning">需專人洽詢</span>
                                                <?php elseif($p->price_remark == 'exceed'): ?>
                                                    <?php if($p->status == 'bargain' && $p->bargain_price > 0): ?>
                                                    <br><span class="badge rounded-pill bg-danger">議價: $<?php echo e(number_format($p->bargain_price)); ?></span>
                                                    <?php else: ?>
                                                    <br><span class="badge rounded-pill bg-danger">數量超出庫存/最大量: <?php echo e($p->product_range['max']); ?></span>
                                                    <?php endif; ?>
                                                <?php elseif($p->status == 'bargain'): ?>
                                                <br><span class="badge rounded-pill bg-danger">退回議價: $<?php echo e(number_format($p->bargain_price)); ?></span>
                                                <?php endif; ?>
                                            </h5>
                                        </td>
                                        <td>$<span class="price" data-price="<?php echo e($p->price); ?>" data-id="<?php echo e($p->id); ?>"><?php echo e(number_format($p->price)); ?></span>/<?php echo e($p->weight); ?></td>
                                        <?php if($editable && $p->status != 'confirmed'): ?>

                                        <!-- <td>
                                            <div class="input-group">
                                                <input class="form-control price" type="number" name="item_<?php echo e($p->id); ?>_price" value="<?php echo e($p->price); ?>" data-id="<?php echo e($p->id); ?>">
                                                <span class="input-group-text" id="basic-addon2">/<?php echo e($p->weight); ?></span>
                                            </div>
                                        </td> -->
                                        <td>
                                            <div class="input-group">
                                                <input class="form-control quantity" data-editabled='1' type="number" name="item_<?php echo e($p->id); ?>_quantity" value="<?php echo e($p->quantity); ?>" data-id="<?php echo e($p->id); ?>" data-product="<?php echo e($p->product_id); ?>">
                                                <span class="input-group-text" id="basic-addon2"><?php echo e($p->weight); ?></span>
                                            </div>
                                        </td>

                                        <?php else: ?>
                                        
                                        <td><span class="quantity" data-id="<?php echo e($p->id); ?>" data-editabled='0' data-val="<?php echo e($p->quantity); ?>"><?php echo e(number_format($p->quantity)); ?></span><?php echo e($p->weight); ?></td>
                                        <?php endif; ?>
                                        <td>
                                            <?php if($p->status == 'confirmed'): ?>
                                            <span class="badge rounded-pill bg-success">審核通過</span>
                                            <?php elseif(isset($p->logs) && count($p->logs) > 0 && $p->logs[0]->status != 'pass'): ?>
                                            <span class="badge rounded-pill bg-danger">退回</span>
                                            <?php else: ?>
                                            尚未審核
                                            <?php endif; ?>
                                        </td>
                                        <td class="fw-medium text-end subtotal subtotal_<?php echo e($p->id); ?>" data-id="<?php echo e($p->id); ?>">
                                            $<span><?php echo e(number_format($p->price * $p->quantity)); ?></span>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="border-top border-top-dashed">
                                        <td colspan="4">
                                            <span class="text text-warning">
                                                ※數量修改即更新購物單(總)金額；若需變更金額，請點擊產品右方按鈕編輯
                                            </span><br><br>
                                            <span class="text text-primary">
                                                ※存在一或多個需有專人詢談的產品價格/數量<br>確認後修改訂單並提交審核，待主管最終審核通過，即通知客戶確認
                                            </span>
                                        </td>
                                        <td colspan="2" class="fw-medium p-0">
                                            <table class="table table-borderless mb-0">
                                                <tbody>
                                                    <tr>
                                                        <td>小計:</td>
                                                        <td class="text-end total_price">$<span><?php echo e(number_format($sum)); ?></span></td>
                                                    </tr>
                                                    <tr>
                                                        <td>稅外:</td>
                                                        <?php
                                                            $tax = $sum * 0.05;
                                                        ?>
                                                        <td class="text-end tax">$<span><?php echo e(number_format($tax)); ?></span></td>
                                                    </tr>
                                                    <tr class="border-top border-top-dashed">
                                                        <th scope="row">總計:</th>
                                                        <th class="text-end total_price_tax">$<span><?php echo e(number_format($sum + $tax)); ?></span></th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    
                                    <tr>
                                        <td colspan="4">
                                        <button onclick="window.open('<?php echo e(route('order.quotation', [
                                            'order_no'=>$data->order_no
                                        ])); ?>');" type="button" class="btn btn-secondary bg-gradient waves-effect waves-light">報價單</button>
                                        </td>
                                        <td colspan="2" class="text-end">
                                            <?php if($editable): ?>
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="form" value="form">
                                            <input type="hidden" name="id" value="<?php echo e($data->id); ?>">
                                            <input type="hidden" name="commit" value="0">
                                            
                                            <button type="button" class="commit btn btn-primary bg-gradient waves-effect waves-light">確認提交</button>
                                            <?php endif; ?>
                                            <!-- <button type="submit" class="btn btn-primary bg-gradient waves-effect waves-light">確認修改</button> -->

                                            <?php if($data->is_exchange == 1): ?>
                                            <input type="hidden" name="action" value="check_exchange">
                                            <input type="hidden" name="form" value="form">
                                            <input type="hidden" name="id" value="<?php echo e($data->id); ?>">
                                            <input type="hidden" name="commit" value="0">
                                            <input type="hidden" name="remark" value="" id="exchange_remark">
                                            
                                            <button type="button" class="exchange_action btn btn-danger bg-gradient waves-effect waves-light">同意換貨</button>
                                            <?php endif; ?>


                                            <?php if($data->is_return == 1): ?>
                                            <input type="hidden" name="action" value="check_return">
                                            <input type="hidden" name="form" value="form">
                                            <input type="hidden" name="id" value="<?php echo e($data->id); ?>">
                                            <input type="hidden" name="commit" value="0">
                                            <input type="hidden" name="remark" value="" id="return_remark">
                                            
                                            <button type="button" class="return_action btn btn-danger bg-gradient waves-effect waves-light">同意退貨</button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div><!--end card-->
            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">備註</h5>
                        </div>
                        <div class="card-body">
                            <?php echo str_replace("\n", "<br>", $data->remark); ?>

                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">銷貨單號</h5>
                        </div>
                        <div class="card-body">
                            <?php echo str_replace(", ", "<br>", $data->all_saleslip_no); ?>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                        <div class="d-flex">
                                <h5 class="card-title flex-grow-1 mb-0">客戶資訊</h5>
                                <div class="flex-shrink-0">
                                    <?php if(!$credit['status']): ?>
                                    <span class="badge bg-danger">超過信用額度</span>
                                    <?php endif; ?>
                                    <!-- <a href="javascript:void(0);" class="link-secondary">View Profile</a> -->
                                    負責業務：<?php echo e($data->user->manage_user[0]->username); ?>

                                </div>
                            </div>
                        </div>
                        <div class="card-body row">
                            <div class="col-xl-6">
                                <ul class="list-unstyled mb-0 vstack gap-3">
                                    <li><i class="ri-building-4-line me-2 align-middle text-muted fs-16"></i><?php echo e($data->user->company); ?> (<?php echo e($data->user->tax_id); ?>)</li>
                                    <li><i class="ri-user-3-line me-2 align-middle text-muted fs-16"></i><?php echo e($data->user->username); ?></li>
                                    <li><i class=" ri-exchange-dollar-fill me-2 align-middle text-muted fs-16"></i>
                                    <?php if($data->user->transaction_type == 'normal'): ?>
                                        款到發貨
                                    <?php else: ?>
                                        <?php if($data->user->transaction_type == 'month'): ?>
                                            月結 <?php echo e($data->user->transaction_day); ?> 日
                                        <?php elseif($data->user->transaction_type == 'day'): ?>
                                            日結 <?php echo e($data->user->transaction_day); ?> 日
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-xl-6">
                                <ul class="list-unstyled mb-0 vstack gap-3">
                                    <li><i class="ri-mail-line me-2 align-middle text-muted fs-16"></i><?php echo e($data->user->email); ?></li>
                                    <li><i class="ri-phone-line me-2 align-middle text-muted fs-16"></i><?php echo e($data->user->phone); ?> <?php echo e(($data->user->ext!='')?'#'.$data->user->ext:''); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div><!--end card-->
                </div>
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">發票資料</h5>
                        </div>
                        <div class="card-body">
                            <div class="table_row">
                                <div class="table_title">公司</div>
                                <div class="table_content"><?php echo e($data->cart->invoice_company); ?></div>
                            </div>
                            <div class="table_row">
                                <div class="table_title">統編</div>
                                <div class="table_content"><?php echo e($data->cart->invoice_tax_id); ?></div>
                            </div>
                            <div class="table_row">
                                <div class="table_title">姓名</div>
                                <div class="table_content"><?php echo e($data->cart->invoice_username); ?></div>
                            </div>
                            <div class="table_row">
                                <div class="table_title">地址</div>
                                <div class="table_content"><?php echo e($data->cart->invoice_address); ?></div>
                            </div>
                            <div class="table_row">
                                <div class="table_title">電話</div>
                                <div class="table_content"><?php echo e($data->cart->invoice_phone); ?></div>
                            </div>
                            <div class="table_row">
                                <div class="table_title">電話分機</div>
                                <div class="table_content"><?php echo e($data->cart->invoice_ext); ?></div>
                            </div>
                            <div class="table_row">
                                <div class="table_title">E-Mail</div>
                                <div class="table_content"><?php echo e($data->cart->invoice_email); ?></div>
                            </div>
                        </div>
                    </div><!--end card-->
                </div>
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">收貨人資料</h5>
                        </div>
                        <div class="card-body">
                            
                            <div class="receive_data_table">
                                <div class="table_row">
                                    <div class="table_title">姓名</div>
                                    <div class="table_content"><?php echo e($data->cart->recipient_username); ?></div>
                                </div>
                                <div class="table_row">
                                    <div class="table_title">地址</div>
                                    <div class="table_content"><?php echo e($data->cart->recipient_address); ?></div>
                                </div>
                                <div class="table_row">
                                    <div class="table_title">電話</div>
                                    <div class="table_content"><?php echo e($data->cart->recipient_phone); ?></div>
                                </div>
                                <div class="table_row">
                                    <div class="table_title">電話分機</div>
                                    <div class="table_content"><?php echo e($data->cart->recipient_ext); ?></div>
                                </div>
                                <div class="table_row">
                                    <div class="table_title">E-Mail</div>
                                    <div class="table_content"><?php echo e($data->cart->recipient_email); ?></div>
                                </div>
                                <div class="table_row">
                                    <div class="table_title">收貨日期</div>
                                    <div class="table_content"><?php echo e($data->cart->recipient_date); ?></div>
                                </div>
                            </div>
                        </div>
                    </div><!--end card-->
                </div>
            </div>
        </div><!--end col-->
        <div class="col-xl-5">
            <div class="row">
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">付款狀態</h5>
                        </div>
                        <div class="card-body">
                            <?php
                            $payment_enabled = false;
                            $shipping_enabled = false;
                            $payment_roles = ['accounting', 'super', 'saler'];
                            $shipping_roles = ['depot', 'super', 'saler'];
                            if (in_array($role, $payment_roles)) $payment_enabled = true;
                            if (in_array($role, $shipping_roles)) $shipping_enabled = true;

                            if ($role == 'saler') {
                                if ($data->shipping_status == 'shipping' || $data->shipping_status == 'complete'){
                                    $shipping_enabled = true;
                                }else{
                                    $shipping_enabled = false;
                                }
                            }
                            ?>
                            <select class="form-control form-select-lg" aria-label=".form-select-lg" id="payment_status" <?php echo e(($payment_enabled)?'':'disabled'); ?>>
                                <?php if($data->status == 'cancel'): ?>
                                <option value="cancel">取消訂單</option>
                                <?php elseif(!in_array($data->status, ['success', 'complete'])): ?>
                                <option value="pending">訂單確認中</option>
                                <?php else: ?>
                                <option <?php echo e(($data->payment_status == 'waiting')?'selected':''); ?> value="waiting">待確認</option>
                                <option <?php echo e(($data->payment_status == 'unpaid')?'selected':''); ?> value="unpaid">待收款 (月結/日結)</option>
                                <option <?php echo e(($data->payment_status == 'cheque_received')?'selected':''); ?> value="cheque_received">支票已兌現(月結/日結)</option>
                                <!-- <option <?php echo e(($data->payment_status == 'cheque_cashed')?'selected':''); ?> value="cheque_cashed">支票已兌現</option> -->
                                <option <?php echo e(($data->payment_status == 'atm_received')?'selected':''); ?> value="atm_received">匯款已入帳(月結/日結)</option>
                                <option <?php echo e(($data->payment_status == 'cash_received')?'selected':''); ?> value="cash_received">已收到款項 (款到發貨)</option>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">出貨狀態</h5>
                        </div>
                        <div class="card-body">
                            <select class="form-control form-select-lg" aria-label=".form-select-lg" id="shipping_status" <?php echo e(($shipping_enabled)?'':'disabled'); ?>>
                                <?php if($data->status == 'cancel'): ?>
                                <option value="cancel">取消訂單</option>
                                <?php elseif(!in_array($data->status, ['success', 'complete'])): ?>
                                <option value="pending">訂單確認中</option>
                                <?php else: ?>     
                                    <?php if($data->payment_status == 'waiting'): ?>           
                                    <option value="cash_waiting">付款確認中</option>
                                    <?php else: ?>
                                    <option <?php echo e(($data->shipping_status == 'shipping_waiting')?'selected':''); ?> value="shipping_waiting">待出貨</option>
                                    <!-- <option <?php echo e(($data->shipping_status == 'shipping_confirmed')?'selected':''); ?> value="shipping_confirmed">確認出貨</option>
                                    <option <?php echo e(($data->shipping_status == 'shipping_prepare')?'selected':''); ?> value="shipping_prepare">出貨準備</option> -->
                                    <option <?php echo e(($data->shipping_status == 'shipping')?'selected':''); ?> value="shipping">已出貨</option>
                                    <option <?php echo e(($data->shipping_status == 'complete')?'selected':''); ?> value="complete">配送完成</option>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <div class="d-sm-flex align-items-center">
                        <h5 class="card-title flex-grow-1 mb-0">訂單操作LOG</h5>
                        <div class="flex-shrink-0 mt-2 mt-sm-0">
                            <!-- <a href="javasccript:void(0;)" class="btn btn-soft-secondary btn-sm mt-2 mt-sm-0"><i class="mdi mdi-archive-remove-outline align-middle me-1"></i> 取消訂單</a> -->
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="profile-timeline">
                        <div class="accordion accordion-flush">
                            <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $icon = "ri-file-list-line";
                                if ($log->type == 'payment'){
                                    $icon = "ri-money-dollar-circle-line";
                                }else if ($log->type == 'shipping'){
                                    $icon = "ri-truck-line";
                                }else if($log->type == 'order'){
                                    $icon = "ri-shopping-bag-3-line";
                                }else if($log->type == 'product'){
                                    $icon = "ri-gift-line";
                                }
                                $bg = "bg-light text-primary";
                                if ($log->status == 'not_enough'){
                                    $bg = "bg-warning";
                                }else if ($log->status == 'pass'){
                                    $bg = "bg-success";
                                }else if ($log->status == 'invalid'){
                                    $bg = "bg-danger";
                                }

                                $title = $log->title;
                                $content = $log->remark??'';
                                $content = str_replace("\n", "<br>", $content);
                                $member = '';
                                if ($log->member_id > 0) {
                                    $member = '操作者: '.$log->member->username;
                                }else if ($log->member_id == -1) {
                                    $member = '會員操作';
                                }
                            ?>
                            <div class="accordion-item border-0">
                                <div class="accordion-header" id="log<?php echo e($index); ?>">
                                    <a class="accordion-button p-2 shadow-none" data-bs-toggle="collapse" href="#collapseLog<?php echo e($index); ?>" aria-expanded="false" aria-controls="collapseThree">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 avatar-xs">
                                                <div class="avatar-title rounded-circle <?php echo e($bg); ?>">
                                                    <i class="<?php echo e($icon); ?>"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="fs-15 mb-1 fw-semibold"><?php echo e($title); ?> - <span class="fw-normal"><?php echo e(date('m/d H:i', strtotime($log->created_at))); ?></span></h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div id="collapseLog<?php echo e($index); ?>" class="accordion-collapse collapse show" aria-labelledby="log<?php echo e($index); ?>" data-bs-parent="#accordionExample">
                                    <div class="accordion-body ms-2 ps-5 pt-0">
                                        <h6 class="mb-1"><?php echo $content; ?></h6>
                                        <p class="text-muted mb-0"><?php echo e($member); ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <div class="accordion-item border-0">
                                <div class="accordion-header" id="start">
                                    <a class="accordion-button p-2 shadow-none">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 avatar-xs">
                                                <div class="avatar-title bg-light text-primary rounded-circle">
                                                    <i class="ri-task-line"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="fs-14 mb-0 fw-semibold">訂單建立- <span class="fw-normal"><?php echo e(date('m/d H:i', strtotime($data->created_at))); ?></span></h6>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div><!--end accordion-->
                    </div>
                </div>
            </div><!--end card-->

        </div><!--end col-->
    </div><!--end row-->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="priceRangeArea" aria-labelledby="priceRangeAreaLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="priceRangeAreaLabel">價格級距 - <span></span></h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0 overflow-hidden">
            <div data-simplebar style="height: calc(100vh - 112px);">
                <form action="" method="POST" enctype="multipart/form-data" id="rangeFrom">
                    <?php echo csrf_field(); ?>
                    <div class="row content">
                        
                    </div>
                </form>
            </div>
        </div>
        <div class="offcanvas-foorter border p-3 text-center">
            <input type="hidden" id="price_id" value="">
            <a href="javascript:save_price();">確認儲存 <i class="ri-save-line align-middle ms-1"></i></a>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
    $(document).ready(function () {
        $('#payment_status').on('change', function () {
            action('payment', $(this).val());
        });
        $('#shipping_status').on('change', function () {
            action('shipping', $(this).val());
        });

        $(".quantity").on('keyup', function () {
            if ($(this).val() == '' || $(this).val() == 0) $(this).val(1);
            // calculate_price();
            change_quantity($(this).val(), $(this).data('product'));
        });
        $(".price").on('keyup', function () {
            if ($(this).val() == '' || $(this).val() == 0) $(this).val(1);
            calculate_price();
        });
        $(".commit").on('click', function () {
            if (!confirm('確認訂單已修改完成，並且進審核流程?')) return;
            $("input[name=commit]").val("1");
            $("#form").submit();
        });

        $(".exchange_action").on('click', function () {
            if (!confirm('確認已完成換貨程序?')) return;

            let remark = prompt("請輸入換貨原因");
            $("#exchange_remark").val(remark);
            
            $("#form").submit();
        });

        $(".return_action").on('click', function () {
            if (!confirm('確認已完成退貨程序? 確認後，訂單金額將重新計算')) return;
            
            let remark = prompt("請輸入退貨原因");
            $("#return_remark").val(remark);
            
            $("#form").submit();
        });
    });

    function calculate_price(){
        let sum = 0;
        $(document).find('.price').each(function (index, element) {
            let id = $(this).data('id');
            // let price = parseInt($(this).val());
            let price = parseInt($(this).data('price'));
            
            let quantity = 0;
            if ($('.quantity[data-id='+id+']').data('editabled') == '1') {
                console.log('e1')
                quantity = parseInt($('.quantity[data-id='+id+']').val());    
            }else{
                console.log('e0')
                quantity = parseInt($('.quantity[data-id='+id+']').data('val'));
            }
            
            let subtotal = price * quantity;
            
            $(".subtotal_"+id+" span").html(subtotal.toLocaleString('en-US'))
            sum += subtotal;
        });

        $(".total_price span").html(sum.toLocaleString('en-US'));
        tax = Math.round(sum * 0.05);
        $(".tax span").html(tax.toLocaleString('en-US'))
        $(".total_price_tax span").html( (sum + tax).toLocaleString('en-US') );
    }

    function action(action, param = false){
        $.ajax({
            type: "POST",
            url: '<?php echo e(route('mgr.order.action')); ?>',
            data: {
                _token: '<?php echo e(csrf_token()); ?>',
                id: '<?php echo e($data->id); ?>',
                action: action,
                param: param
            },
            dataType: "json",
            success: function(data){
                if (data.status){
                    if (data.action == 'reload') {
                        window.location.reload();
                    }else if (data.action == 'redirect') {
                        location.href = data.url;
                    }
                }
            },
            failure: function(errMsg) {
                alert(errMsg);
            }
        });
    }

    function priceRange(product_id, product_name){
        
        $.ajax({
            type: "POST",
            url: '<?php echo e(route('mgr.users.product_price')); ?>',
            data: {
                _token: '<?php echo e(csrf_token()); ?>',
                user_id: '<?php echo e($data->user->id); ?>',
                product_id: product_id
            },
            dataType: "json",
            success: function(data){
                if (data.status){
                    $("#priceRangeArea .content").html(data.html)
                    $("#priceRangeAreaLabel span").html(product_name);
                    $("#price_id").val(data.id);
                    $("#priceRangeArea").offcanvas('show');
                }
            },
            failure: function(errMsg) {
                alert(errMsg);
            }
        });
    }

    function save_price(){
        let product_id = $("#price_id").val();
        $.ajax({
            type: "POST",
            url: '<?php echo e(route('mgr.users.product_price')); ?>',
            data: $("#rangeFrom").serialize()+"_token=<?php echo e(csrf_token()); ?>&order_id=<?php echo e($data->id); ?>&user_id=<?php echo e($data->user->id); ?>&product_id="+product_id+"&action=save",
            dataType: "json",
            success: function(data){
                if (data.status){
                    $("#priceRangeArea").offcanvas('hide');
                    $("#priceRangeArea .content").html('')
                    Toastify({
                        // destination: "",
                        gravity: "top", // `top` or `bottom`
                        position: "center", // `left`, `center` or `right`
                        text: data.msg,
                        className: "success",
                    }).showToast();

                    change_quantity($("#product_"+product_id+" .quantity").val(), product_id);
                }else{
                    alert(data.msg);
                }
            },
            failure: function(errMsg) {
                alert(errMsg);
            }
        });
    }

    function change_quantity(quantity, product_id){
        $.ajax({
            type: "POST",
            url: '<?php echo e(route('mgr.order.quantity_change')); ?>',
            data: {
                _token: '<?php echo e(csrf_token()); ?>',
                order_id: '<?php echo e($data->id); ?>',
                user_id: '<?php echo e($data->user->id); ?>',
                product_id: product_id,
                quantity: quantity
            },
            dataType: "json",
            success: function(data){
                if (data.status){
                    $.each(data.cart.items, function (index, elem) { 
                        $("#product_"+elem.product_id+" .price").data('price', elem.price).html(elem.price.toLocaleString('en-US'));
                    });
                    calculate_price();
                }else{
                    alert(data.msg);
                }
            },
            failure: function(errMsg) {
                alert(errMsg);
            }
        });
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('mgr.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\NTU_02\resources\views/mgr/order_detail.blade.php ENDPATH**/ ?>