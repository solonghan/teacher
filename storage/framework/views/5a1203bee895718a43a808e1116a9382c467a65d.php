<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="<?php echo e(route('mgr.home')); ?>" class="logo logo-dark">
            <span class="logo-sm">
                <img src="<?php echo e(URL::asset('assets/images/logo_sm.png')); ?>" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="<?php echo e(URL::asset('assets/images/logo.svg')); ?>" alt="" height="47">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="<?php echo e(route('mgr.home')); ?>" class="logo logo-light">
            <span class="logo-sm">
                <img src="<?php echo e(URL::asset('assets/images/logo_sm.png')); ?>" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="<?php echo e(URL::asset('assets/images/logo.svg')); ?>" alt="" height="47">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link menu-link <?php if($active == 'dashboard'): ?> active <?php endif; ?>" href="/mgr">
                        <i class="ri-home-7-fill"></i> <span >Dashboard</span>
                    </a>
                    
                    <?php $__currentLoopData = $nav; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nav_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                    <a class="nav-link menu-link <?php if($active == $nav_item['function']): ?> active <?php endif; ?>"<?php if(count($nav_item['sub']) > 0): ?> href="#nav_<?php echo e($nav_item['id']); ?>" data-bs-toggle="collapse"
                        role="button" aria-expanded="false" aria-controls="nav_<?php echo e($nav_item['id']); ?>"<?php else: ?> href="<?php echo e(($nav_item['url']!='')?route($nav_item['url']):'#'); ?>" <?php endif; ?>>
                        <i class="<?php echo e($nav_item['icon']); ?>"></i> <span ><?php echo e($nav_item['name']); ?></span>
                        <?php if(isset($nav_item['badge'])): ?>
                            <?php if($nav_item['badge'] == 'v'): ?>
                            <span class="badge badge-pill bg-success"><?php echo e($nav_item['badge']); ?></span>
                            <?php elseif($nav_item['badge'] == 'ing'): ?>
                            <span class="badge badge-pill bg-warning"><?php echo e($nav_item['badge']); ?></span>
                            <?php elseif($nav_item['badge'] != 0): ?>
                            <span class="badge badge-pill bg-primary"><?php echo e($nav_item['badge']); ?></span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </a>
                    <?php if(count($nav_item['sub']) > 0): ?>
                    <div class="collapse menu-dropdown <?php if($active == $nav_item['function']): ?> show <?php endif; ?>" id="nav_<?php echo e($nav_item['id']); ?>">
                        <ul class="nav nav-sm flex-column">
                            <?php $__currentLoopData = $nav_item['sub']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="nav-item">
                                <a class="nav-link <?php if($sub_active == $sub['function']): ?> active <?php endif; ?>" href="<?php echo e(($sub['url']!='')?route($sub['url']):'#'); ?>"><?php echo e($sub['name']); ?>

                                <?php if(isset($sub['badge'])): ?>
                                    <?php if($sub['badge'] == 'v'): ?>
                                    <span class="badge badge-pill bg-success"><?php echo e($sub['badge']); ?></span>
                                    <?php elseif($sub['badge'] == 'ing'): ?>
                                    <span class="badge badge-pill bg-warning"><?php echo e($sub['badge']); ?></span>
                                    <?php elseif($sub['badge'] != 0): ?>
                                    <span class="badge badge-pill bg-primary"><?php echo e($sub['badge']); ?></span>
                                    <?php endif; ?>
                                <?php endif; ?>
                                </a>
                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
<?php /**PATH C:\xampp\htdocs\NTU_02\resources\views/mgr/layouts/sidebar.blade.php ENDPATH**/ ?>