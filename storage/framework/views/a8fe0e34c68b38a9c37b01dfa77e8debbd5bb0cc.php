
<?php $__env->startSection('title'); ?> <?php echo e($title); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php $__env->startComponent('mgr.components.breadcrumb', ['btns' => $btns??array()]); ?>
    <?php $__env->slot('li_1_url'); ?> <?php echo e($parent_url); ?> <?php $__env->endSlot(); ?>
    <?php $__env->slot('li_1'); ?> <?php echo e($parent); ?> <?php $__env->endSlot(); ?>
    <?php $__env->slot('title'); ?> <?php echo e($title); ?> <?php $__env->endSlot(); ?>
    <?php echo $__env->renderComponent(); ?>
    <div class="row">
        <div class="col-12">
        <?php if($type=='search'): ?>
             <p>查詢條件  姓名:XXX， 服務單位:公立學校 不含曾任，職稱:教授、研究員，專長:化學，學術專長:  ，最後異動時間:2023/4/1</p>
        <?php elseif($type=='output'): ?>   
            <p>查詢人: XXX，查詢條件  姓名:XXX， 服務單位:公立學校 不含曾任，職稱:教授、研究員，專長:化學，學術專長:  ，最後異動時間:2023/4/1，列印結果未保存到伺服器中</p>
            <p style="text-align: right;">列印時間：2023/4/12 11:00</p>
        <?php elseif($type=='search_have'): ?>
                <h4><p>系統已存在此專家，如需新增其他專長，請按下新增專長按鈕</p></h4>
        <?php endif; ?>
       
        
            <div class="row">
                <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div id="customerList">
                            <div class="row g-4 mb-3">
                                
                                <!-- <div class="col-sm">
                                    <div class="d-flex justify-content-sm-end">
                                        <div class="search-box ms-2">
                                            <input type="text" class="form-control search" placeholder="Search...">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="table-responsive table-card mb-1">
                                        <table class="table align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <?php $__currentLoopData = $th_title; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $th): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <th scope="col" style="width:<?php echo e($th['width']); ?>;"><?php echo e($th['title']); ?></th>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php $__env->startComponent($template_item, ['item'=>$item, 'th_title'=>$th_title]); ?>
                                                <?php echo $__env->renderComponent(); ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <div class="pagination-wrap hstack gap-2">
                                            <a class="page-item pagination-prev disabled" href="#">
                                                Previous
                                            </a>
                                            <ul class="pagination listjs-pagination mb-0"></ul>
                                            <a class="page-item pagination-next disabled" href="#">
                                                Next
                                            </a>
                                        </div>
                                    </div>
                                </div><!--end col-->

                            </div><!--end row-->
                        </div>
                    </div><!-- end card-body -->
                </div><!-- end card -->
            </div>      
            </div>

        </div>
        <?php if(isset($bar_btns)): ?>
            <div class="col-12 row mb-2">
                <?php $__currentLoopData = $bar_btns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $btn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-<?php echo e($btn[3]); ?>">
                    <button type="button" class="btn btn-<?php echo e($btn[2]); ?> btn-animation waves-effect waves-light" onclick="<?php echo e($btn[1]); ?>"><?php echo e($btn[0]); ?></button>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
    </div>

    <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <form>
                    <div class="modal-body" id="detail_content">

                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">關閉</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <!-- <script src="<?php echo e(URL::asset('js/app.min.js')); ?>"></script> -->

    <script src="<?php echo e(URL::asset('assets/libs/prismjs/prismjs.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('assets/libs/list.js/list.js.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('assets/libs/list.pagination.js/list.pagination.js.min.js')); ?>"></script>

    <!-- listjs init -->
    <!-- <script src="<?php echo e(URL::asset('assets/js/pages/listjs.init.js')); ?>"></script> -->

    <script>
        $(document).ready(function(e){
            $(document).on('click', '.show_detail', function(event) {
                $.ajax({
                    type: "GET",
                    url: '<?php echo e(env('APP_URL')); ?>/mgr/<?php echo e($controller??''); ?>/detail/' + $(this).data('id'),
                    data: {
                    },
                    dataType: "json",
                    success: function(data){
                        if (data.status){
                            $("#detail_content").html(data.html);
                            $("#showModal").modal('show');
                        }
                    },
                    failure: function(errMsg) {
                        alert(errMsg);
                    }
                });
            });

            $(document).on('click', '.edit-item-btn', function(event) {
                var id = $(this).closest('tr').data('id');
                location.href = '<?php echo e(env('APP_URL')); ?>/mgr/<?php echo e($controller??''); ?>/edit/' + id;
            });

            $(document).on('click', '.del-item-btn', function(event) {
                if (!confirm("確定刪除此筆資料?")) return;

                var id = $(this).closest('tr').data('id');
                $.ajax({
                    type: "POST",
                    url: '<?php echo e(env('APP_URL')); ?>/mgr/<?php echo e($controller??''); ?>/del',
                    data: {
                        'id': id
                    },
                    dataType: "json",
                    success: function(data){
                        if (data.status){
                            $("tr[data-id="+id+"]").fadeTo('fast', 0, function(e){
                                $(this).remove();
                            });
                        }
                    },
                    failure: function(errMsg) {
                        alert(errMsg);
                    }
                });
            });

            $(".switch_toggle").on('change', function () {
                let status = 'on';
                if (!$(this).is(':checked')) status = 'off';
                $.ajax({
                    type: "POST",
                    url: '<?php echo e(env('APP_URL')); ?>/<?php echo e($controller??''); ?>/switch_toggle',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>',
                        id: $(this).data('id'),
                        status: status
                    },
                    dataType: "json",
                    success: function(data){
                        if (data.status){
                            if (status == 'on') {
                                Toastify({
                                    gravity: "top",
                                    position: "center",
                                    text: "已開啟",
                                    className: "success",
                                }).showToast();
                            }else{
                                Toastify({
                                    gravity: "top",
                                    position: "center",
                                    text: "已關閉",
                                    className: "danger",
                                }).showToast();
                            }
                        }
                    },
                    failure: function(errMsg) {
                        alert(errMsg);
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('mgr.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\ntue_teacher\resources\views/mgr/template_list.blade.php ENDPATH**/ ?>