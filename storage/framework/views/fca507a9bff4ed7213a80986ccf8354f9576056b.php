<tr data-id="<?php echo e($item->id); ?>">
    <td><?php echo e($item->id); ?></td>
    <td>
        <?php if($priv_view): ?>
        <a href="<?php echo e(route('mgr.order.detail', ['id'=>$item->order_no])); ?>">
            <?php echo $item->order_no; ?>

        </a>
        <?php else: ?>
        <?php echo $item->order_no; ?>

        <?php endif; ?>

        <?php if(($role == 'super' || $role == 'mgr' || $role == 'director' || ($role == 'saler' && $item->user->iam))): ?>
            <?php if($saleslip_no == ''): ?>
            <br><small class="text text-danger" style="font-size:11px;">尚未輸入銷貨單</small>
            <?php else: ?>
            <br><small class="text text-seconday" style="font-size:11px;"><?php echo str_replace(', ','<br>',$saleslip_no); ?></small>
            <?php endif; ?>
        <?php endif; ?>

        <?php if($role == 'assistant'): ?>
            <?php if($saleslip_no == ''): ?>
            <br><small class="text text-danger" style="font-size:11px;">尚未輸入銷貨單</small>
            <?php else: ?>
            <br><small class="text text-seconday" style="font-size:11px;"><?php echo e($saleslip_no); ?></small>
            <?php endif; ?>
        <?php endif; ?>
    </td>
    <td>
        <?php echo $item->user->username.'<br>'.$item->user->company; ?>

        <?php if(!$credit): ?>
        <br>
        <span class="badge rounded-pill bg-danger mb-3">信用額度不足</span>
        <?php endif; ?>
        <?php if($role == 'saler' && $item->user->iam): ?>
        <br>
        <span class="badge rounded-pill bg-danger">業務負責</span>
        <?php endif; ?>
    </td>
    <td style="width: 400px; white-space:initial;">
        <?php $__currentLoopData = $item->cart->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($role == 'assistant' && $p->product->assistant != Auth::guard('mgr')->user()->id): ?> <?php continue; ?> <?php endif; ?>
        <div class="row mb-3 border-1 border-start-dotted">
            <!-- <div class="col-sm-4">
                <img class="img-thumbnail" style="width:100%;" src='<?php echo e(env('APP_URL').Storage::url($p->cover)); ?>'>
            </div> -->
            <div class="col-sm-8">
                <strong>
                    <?php echo e($p->name); ?> <?php echo e($p->quantity); ?> <?php echo e($p->weight); ?>

                </strong><br>
                <?php if($role == 'mgr' || $role == 'super' || ($role == 'saler' && $p->iam) ): ?>
                    <span class="text text-primary">$<?php echo e(number_format($p->price)); ?></span>/<?php echo e($p->weight); ?>

                        <?php if($p->status == 'bargain'): ?>
                            <span class="text text-danger">-> $<?php echo e(number_format($p->bargain_price)); ?>/<?php echo e($p->weight); ?></span>
                        <?php endif; ?>
                    <br>
                    <?php if($p->price_remark == 'custom'): ?>
                    <span class="badge rounded-pill bg-warning">需專人洽詢</span><br>
                    <?php elseif($p->price_remark == 'exceed'): ?>
                    <span class="badge rounded-pill bg-danger">數量超出庫存/最大量</span><br>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if($p->iam && $item->status == 'pending' && ($p->status == 'pending') ): ?>
                    <button style="margin-top:10px;" type="button" class="btn btn-danger btn-animation waves-effect waves-light" data-text="點我審核" data-bs-toggle="modal" data-bs-target="#review_product<?php echo e($item->id); ?>_<?php echo e($p->id); ?>"><span>審核價格</span></button>
                    <div class="modal fade" id="review_product<?php echo e($item->id); ?>_<?php echo e($p->id); ?>" tabindex="-1" aria-labelledby="review_product<?php echo e($item->id); ?>_<?php echo e($p->id); ?>Label" aria-modal="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="review_product<?php echo e($item->id); ?>_<?php echo e($p->id); ?>Label">審核價格 <?php echo e($p->name); ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="<?php echo e(route('mgr.order.action')); ?>" method="POST" id="form<?php echo e($item->id); ?>_<?php echo e($p->product_id); ?>">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="id" value="<?php echo e($item->id); ?>">
                                        <input type="hidden" name="product_id" value="<?php echo e($p->product_id); ?>">
                                        <div class="row g-3">
                                            <div class="col-xxl-6">
                                                <div>
                                                    <label for="action" class="form-label">審核結果</label>
                                                    <select name="action" class="form-control">
                                                        <option value="product_pass">通過</option>
                                                        <option value="product_invalid">拒絕並退回</option>
                                                        <option value="product_not_enough">庫存不足</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xxl-6">
                                                <div>
                                                    <label for="price" class="form-label">建議單價</label>
                                                    <input type="number" class="form-control" name="price" value="<?php echo e($p->price); ?>" data-original="<?php echo e($p->price); ?>" disabled>
                                                </div>
                                            </div>
                                            <div class="col-xxl-12">
                                                <div>
                                                    <label for="remark" class="form-label">備註</label>
                                                    <textarea  class="form-control" name="remark"></textarea>
                                                </div>
                                            </div>
                                            <div class="col-xxl-12">
                                                <button type="submit" class="btn btn-primary waves-effect waves-light float-end">確認儲存</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                
                <?php elseif($p->status == 'bargain'): ?>
                <span class="badge rounded-pill bg-danger">審核不通過</span>
                <?php elseif($p->status == 'pending'): ?>
                <span class="badge badge-soft-danger">尚未審核</span>
                <?php elseif($p->status == 'confirmed'): ?>
                <span class="badge rounded-pill bg-success">審核通過</span>
                <?php elseif($p->status == 'bargain'): ?>
                <span class="badge rounded-pill bg-danger">審核不通過</span>
                <?php elseif($p->status == 'not_enough'): ?>
                <span class="badge rounded-pill bg-warning">庫存不足</span>
                <?php elseif($p->status == 'bargain'): ?>
                <span class="badge rounded-pill bg-danger">退回議價</span>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </td>
    <td>
        $<?php echo number_format($item->price); ?>

    </td>
    <td>
        <?php if($item->status == 'reject'): ?>
            <?php if(count($item->logs) > 0 && ($item->logs[0]->member->role == 'super' || $item->logs[0]->member->role == 'mgr')): ?>
            <span class="badge badge-soft-danger">主管審核退回</span>
            <?php else: ?>
            <span class="badge badge-soft-danger">審核退回</span>
            <?php endif; ?>
        <?php else: ?>
            <?php
                $status_badge = 'warning';
                if (in_array($item->status, ['success', 'complete'])){
                    $status_badge = 'success';
                }else if (in_array($item->status, ['cancel'])) {
                    $status_badge = 'secondary';
                }
            ?>
            <span class="badge rounded-pill badge-soft-<?php echo e($status_badge); ?>"><?php echo $item->status_show(); ?></span>
            <?php if($item->status == 'pending'): ?>
            <br>
            <span class="badge badge-soft-warning">產品業務審核中</span>
            <?php elseif($item->status == 'director_review'): ?>
                <?php if($role == 'director' || $role == 'super'): ?>
                <br>
                <button 
                    type="button" 
                    class="btn btn-primary btn-animation waves-effect waves-light" 
                    data-text="執行審核" 
                    data-bs-toggle="modal" 
                    data-bs-target="#director_review_bill<?php echo e($item->id); ?>">
                    <span>審核訂單</span>
                </button>
                <div class="modal fade" id="director_review_bill<?php echo e($item->id); ?>" tabindex="-1" aria-labelledby="director_review_bill<?php echo e($item->id); ?>Label" aria-modal="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="director_review_bill<?php echo e($item->id); ?>Label">大主管/總監審核訂單</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="<?php echo e(route('mgr.order.action')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="id" value="<?php echo e($item->id); ?>">
                                    <input type="hidden" name="product_id" value="0">
                                    <div class="row g-3">
                                        <div class="col-xxl-12">
                                            <div>
                                                <label for="action" class="form-label">審核結果</label>
                                                <select name="action" class="form-control">
                                                    <option value="order_director_pass">通過</option>
                                                    <option value="order_director_invalid">拒絕並退回</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-12">
                                            <div>
                                                <label for="remark" class="form-label">備註</label>
                                                <textarea  class="form-control" name="remark"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-xxl-12">
                                            <button type="submit" class="btn btn-primary waves-effect waves-light float-end">確認儲存</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <br>
                    <span class="badge badge-soft-primary">大主管/總監審核中</span>
                <?php endif; ?>
            <?php elseif($item->status == 'inreview'): ?>
                <?php if($item->iam_manager): ?>
                <br>
                <button 
                    type="button" 
                    class="btn btn-info btn-animation waves-effect waves-light" 
                    data-text="執行審核" 
                    data-bs-toggle="modal" 
                    data-bs-target="#review_bill<?php echo e($item->id); ?>">
                    <span>審核訂單</span>
                </button>
                <div class="modal fade" id="review_bill<?php echo e($item->id); ?>" tabindex="-1" aria-labelledby="review_bill<?php echo e($item->id); ?>Label" aria-modal="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="review_bill<?php echo e($item->id); ?>Label">審核訂單</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="<?php echo e(route('mgr.order.action')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="id" value="<?php echo e($item->id); ?>">
                                    <input type="hidden" name="product_id" value="0">
                                    <div class="row g-3">
                                        <div class="col-xxl-12">
                                            <div>
                                                <label for="action" class="form-label">審核結果</label>
                                                <select name="action" class="form-control">
                                                    <option value="order_pass">通過</option>
                                                    <option value="order_invalid">拒絕並退回</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xxl-12">
                                            <div>
                                                <label for="remark" class="form-label">備註</label>
                                                <textarea  class="form-control" name="remark"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-xxl-12">
                                            <button type="submit" class="btn btn-primary waves-effect waves-light float-end">確認儲存</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                    <br>
                    <span class="badge badge-soft-info">主管審核中</span>
                <?php endif; ?>
            <?php elseif($item->status == 'confirmed'): ?>
            <br>
            <span class="badge badge-soft-warning">客戶確認中</span>
            <?php endif; ?>
        <?php endif; ?>

        <?php if($item->is_exchange == 1): ?>
        <br>
            <span class="badge badge-soft-danger">客戶申請換貨</span>
        <?php endif; ?>

        <?php if($item->is_return == 1): ?>
        <br>
            <span class="badge badge-soft-danger">客戶申請退貨</span>
        <?php endif; ?>
    </td>
    <td>
        <?php echo $item->payment_status_show(); ?>

    </td>
    <td>
        <?php echo $item->shipping_status_show(); ?>

    </td>
    <td><?php echo str_replace(' ', '<br>', $item->created_at); ?></td>
    <td class="no-search">
        <!-- <button class="mb-2 btn btn-sm btn-primary">編輯訂單</button> -->
        <?php if($item->status == 'new' || $item->status == 'reject'): ?>
            <?php if( $role == 'mgr' || $role == 'super' || ($role == 'saler' && $item->user->iam) ): ?>
            <br>
            <button class="mb-2 btn btn-sm btn-warning" onclick="action('<?php echo e($item->id); ?>', 'pending')">提交審核</button>
            <?php endif; ?>
        <?php elseif($item->status == 'pending'): ?>

        <?php endif; ?>
        <?php if( $role == 'mgr' || $role == 'super' || ($role == 'saler' && $item->user->iam) ): ?>
            <?php if($item->status != 'cancel' && $item->status != 'success' && $item->status != 'complete'): ?>
            <br>
            <button class="mb-2 btn btn-sm btn-light" onclick="action('<?php echo e($item->id); ?>', 'cancel')">取消訂單</button>
            <?php endif; ?>
        <?php endif; ?>
        <?php if($role == 'super'): ?>
            <br>
            <button class="mb-2 btn btn-sm btn-danger" onclick="action('<?php echo e($item->id); ?>', 'del')">刪除訂單</button>
        <?php endif; ?>

        <?php if($role == 'assistant' && $saleslip_no == ''): ?>
            <?php if($item->status == 'success' || $item->status == 'complete'): ?>
            <br>
            <button class="mb-2 btn btn-sm btn-primary btn-saleslip_no">輸入銷貨單號</button>
            <?php endif; ?>
        <?php endif; ?>
    </td>
</tr><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/easchem/resources/views/mgr/order_item.blade.php ENDPATH**/ ?>