<!doctype html >
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <base href="<?php echo e(env('APP_URL')); ?>" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title'); ?>| <?php echo e(env('APP_NAME')); ?></title>
    <meta content="<?php echo $__env->yieldContent('description'); ?>" name="description" />
    <meta content="<?php echo e(env('APP_NAME')); ?>" name="author" />
    
    <link rel="shortcut icon" href="<?php echo e(URL::asset('dist/assets/image/fav_ico.png')); ?>"/>
    <meta property="og:image" content="<?php echo e(URL::asset('dist/assets/image/about_page.png')); ?>"/>
    <meta property="og:description" content="<?php echo $__env->yieldContent('description'); ?>"/>
    <link rel="bookmark" href="<?php echo e(URL::asset('dist/assets/image/fav_ico.png')); ?>"/>
    <link rel="stylesheet" href="<?php echo e(URL::asset('dist/plugins/owl.theme.default.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(URL::asset('dist/plugins/owl.carousel.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(URL::asset('dist/assets/css/style.css')); ?>?v=<?php echo e(rand(100,999)); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.css" />
    <script src="<?php echo e(URL::asset('dist/plugins/owl.carousel.min.js')); ?>"></script>
    <style>
        .side_bar{
            z-index: 20000000009 !important;
        }
        .cart_badge{
            position: absolute;
            top: -8px;
            right: -12px;
            width: 16px;
            height: 16px;
            background-color: #C00;
            font-size: 8px;
            text-align: center;
            border-radius: 8px;
            line-height: 16px;
            color: #FFF;
        }

        .gotopbtn{
            position: fixed;
            width: 50px;
            height: 50px;
            background: #27ae60;
            bottom: 13px;
            left: 13px;
            text-decoration: none;
            text-align: center;
            line-height: 60px;
            color: white;
            font-size: 32px;
            border-radius: 5px;
            z-index: 999;
        }
        html {
            scroll-behavior: smooth;
        }

        @media  screen and (max-width: 767px) {
            /* .gotopbtn{
                right: 21px;
                bottom: 70px;
            } */

            .header_top_fix,
            .header_top_common {
                height: 70px !important;
            }
            .page_banner_container{
                margin-top: 70px !important;
            }
            .custom_title_wrap{
                width: 80%;
                text-align: center;
            }
            .product_btn{
                padding: 0;
            }

            .side_bar ul li{
                width: 94%;
                margin: 0 3%;
            }
            h1 {
                font-size: 2rem;
            }

            h2 {
                font-size: 1.5rem;
            }

            h3 {
                font-size: 1.5rem;
            }

            h4 {
                font-size: 1.5rem;
            }

            h5 {
                font-size: 1.25rem;
            }

            h6 {
                font-size: 1.25rem;
            }

            span {
                font-size: 1rem;
            }

            p {
                font-size: 1rem;
            }

        }
    </style>
    <?php echo $__env->yieldContent('css'); ?>
</head>
<body>
    <?php echo $__env->make('components.nav+menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <main>
        <?php if($breadcrumb): ?>
        <?php echo $__env->make('components.breadcrumb', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </main>
    <?php echo $__env->make('components.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('components.scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->yieldContent('script'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\NTU_02\resources\views/components/master.blade.php ENDPATH**/ ?>