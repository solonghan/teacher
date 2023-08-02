
<?php $__env->startSection('title'); ?> <?php echo e($title); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('description'); ?> <?php echo e($description); ?> <?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<!-- banner輪播 -->
<div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <?php $__currentLoopData = $carousel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="<?php echo e($index); ?>"<?php if($index==0): ?> class="active" aria-current="true" <?php endif; ?> aria-label="<?php echo e($c->link_txt); ?>"></button>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <div class="carousel-inner">
        <?php if(count($carousel) > 0): ?>
            <?php $__currentLoopData = $carousel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="carousel-item <?php if($index==0): ?> active <?php endif; ?>" data-bs-interval="5000">
                <img src="<?php echo e(env('APP_URL').Storage::url($c->path)); ?>" style="min-height:350px;object-fit:cover;" class="d-block w-100" alt="">
                <?php if($c->link_txt != '' && $c->link != ''): ?>
                <div class="mask">
                    <div class="custom_title_wrap">
                        <div class="bg_title mb-4">
                            <h1 class="mb-0"><?php echo $c->link_txt; ?></h1>
                            <?php if($c->sub_text != ''): ?>
                            <h2 class="mb-3"><?php echo $c->sub_text; ?></h2>
                            <?php endif; ?>
                        </div>
                        <a href="<?php echo e($c->link); ?>" class="read_more_btn">read more</a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <div class="carousel-item active" data-bs-interval="5000">
                <img src="<?php echo e($default_carousel); ?>" style="min-height:350px;object-fit:cover;" class="d-block w-100" alt="">
            </div>
        <?php endif; ?>
    </div>
    <a class="carousel-control-prev" href="javascript:void(0)" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#jvascript:void(0)" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
<!-- 品牌輪播 -->
<div class="brand_carousel">
    <div class="owl-carousel owl-theme" loop="false">
        <?php $__currentLoopData = $brand; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="item">
            <a href="<?php echo e(route('brand.detail', ['id' => $b->id])); ?>">
                <div class="brand" style="background-image: url('<?php echo e(env('APP_URL').Storage::url($b->logo)); ?>'); background-size:contain;"></div>
            </a>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<div class="news_container mb-5">
    <div class="main_title"><h2><?php echo e(__('page.news')); ?></h2></div>
    <div class="news_carousel">
        <div class="owl-carousel owl-theme">
            <?php $__currentLoopData = $news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="news_item" onclick="location.href='<?php echo e(route('news.detail', ['id'=>$n->id])); ?>';">
                <img class="mb-2" src="<?php echo e(env('APP_URL').Storage::url($n->cover)); ?>" alt="">
                <div class="new_time mb-3">
                    <span><?php echo e(date('Y/m/d', strtotime($n->date))); ?></span> | By <?php echo e($n->member->username); ?> | <?php echo e($news_category[$n->category]['text']); ?>

                </div>
                <h5 class="new_title mb-3">
                    <?php echo e($n->title); ?>

                </h5>
                <div class="new_content limit_three mb-2">
                    <?php echo e($n->summary); ?>

                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<!-- 代理品牌 -->
<div class="proxy_brand_container">
    <div class="container">
        <div class="main_title"><h2><?php echo e(__('page.agency_brand')); ?></h2></div>
        <div class="brand_classify">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <?php $__currentLoopData = $brand; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $bitem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php if($index==0): ?> active <?php endif; ?>" id="pills-<?php echo e($bitem->id); ?>" data-bs-toggle="pill" data-bs-target="#pills-<?php echo e($bitem->id); ?>-content" type="button" role="tab" aria-controls="pills-<?php echo e($bitem->id); ?>" aria-selected="<?php if($index==0): ?> true <?php else: ?> false <?php endif; ?>"><?php echo e($bitem->name); ?></button>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <div class="tab-content" id="pills-tabContent">
            <?php $__currentLoopData = $brand; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $bitem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="tab-pane fade <?php if($index==0): ?> show active <?php endif; ?>" id="pills-<?php echo e($bitem->id); ?>-content" role="tabpanel" aria-labelledby="pills-<?php echo e($bitem->id); ?>" tabindex="<?php echo e($index); ?>">
                <div class="brand_title mb-5">
                    <h4><?php echo e($bitem->name); ?></h4>
                </div>
                <div class="brand_list">
                    <div class="row">
                        <?php $__currentLoopData = $bitem->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pindex => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($pindex > 0 && $pindex % 3 == 0): ?>
                        </div>
                        <hr style="color: #FFF; border: 1px solid #FFF; margin: 3rem 0;">
                        <div class="row">
                        <?php endif; ?>
                        <div class="brand_card col-sm-12 col-md-6 col-lg-4 mb-4" onclick="location.href='<?php echo e(route('products.detail', ['id'=>$product->id])); ?>';">
                            <img src="<?php echo e(env('APP_URL').Storage::url($product->cover)); ?>" style="width: 130px; max-height: 130px;">
                            <div class="card_content">
                                <h6 class="card-title"><?php echo e($product->name); ?></h6>
                                <p class="card-text limit_two"><?php echo e($product->summary); ?></p>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<!-- 產品分類 -->
<div class="product_classify_container">
    <div class="container">
        <div class="main_title"><h2><?php echo e(__('page.product_category')); ?></h2></div>
        <div class="title_info"><?php echo e(__('page.product_category_sub')); ?></p></div>
        <div class="product_classify mb-3">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <?php $__currentLoopData = $product_category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?php if($index==0): ?> active <?php endif; ?>" id="pills-category-<?php echo e($c->id); ?>-tab" data-bs-toggle="pill" data-bs-target="#pills-category-<?php echo e($c->id); ?>" type="button" role="tab" aria-controls="pills-category-<?php echo e($c->id); ?>" aria-selected="<?php if($index==0): ?> true <?php else: ?> false <?php endif; ?>"><?php echo e($c->title); ?></button>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <div class="tab-content" id="pills-tabContent">
            <?php $__currentLoopData = $product_category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="tab-pane fade <?php if($index == 0): ?> show active <?php endif; ?>" id="pills-category-<?php echo e($c->id); ?>" role="tabpanel" aria-labelledby="pills-category-<?php echo e($c->id); ?>-tab" tabindex="<?php echo e($index); ?>">
                <div class="product_list">
                    <div class="row">
                        <?php $__currentLoopData = $c->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pindex => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="product_card col-sm-12 col-md-6 col-lg-4" onclick="location.href='<?php echo e(route('products.detail', ['id'=>$product->id])); ?>';">
                            <div style="background-image:url(<?php echo e(env('APP_URL').Storage::url($product->cover)); ?>); background-size:cover; height: 180px; width: 100%;"></div>
                            <!-- <img src="<?php echo e(env('APP_URL').Storage::url($product->cover)); ?>" style="height: 180px; width:auto; max-width: 100%;"> -->
                            <div class="card_content mt-3">
                                <h6 class="card-title" style="font-weight:400;"><?php echo e($product->name); ?></h6>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<!-- 公司介紹 -->
<div class="company_info_container">
    <div class="container">
        <section class="my-5">
            <div class="company_info ml-5">
                <div class="info_title mb-3"><h2><?php echo e($intro->title); ?></h2></div>
                <div class="info_content">
                    <div class="zh mb-3"><?php echo $intro->content; ?></div>
                </div>
            </div>
            <?php if($intro->img != ''): ?>
            <img src="<?php echo e(env('APP_URL').Storage::url($intro->img)); ?>" alt="">
            <?php endif; ?>
        </section>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script src="<?php echo e(URL::asset('dist/js/index.js')); ?>"></script>
<script>
    
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('components.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\NTU_02\resources\views/index.blade.php ENDPATH**/ ?>