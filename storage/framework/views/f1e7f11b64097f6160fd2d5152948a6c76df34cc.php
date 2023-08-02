<header>
    <div class="<?php if($nav_index): ?> header_top_index <?php else: ?> header_top_common <?php endif; ?>" id="header">
        <div class="header_logo">
            <a href="<?php echo e(route('home')); ?>">
                <img id="header_logo_img" src="" alt="">
            </a>
        </div>
        <div class="nav_bar">
            <div class="language">
                <?php if(!$nav_index): ?>
                <div class="language_select">
                    <?php if($locale == 'tw'): ?>
                    <img src="dist/assets/image/taiwan.png" alt="">
                    <span style="margin-right: 1rem;margin-left: 1rem;">
                        中文(台灣)
                    </span>
                    <?php elseif($locale == 'en'): ?>
                    <img src="dist/assets/image/us.png" alt="">
                    <span style="margin-right: 1rem;margin-left: 1rem;">
                        English
                    </span>
                    <?php endif; ?>
                    <i class="fa-solid fa-caret-down"></i>
                </div>
                <div class="language_select_wrap" style="top:44%;right:8px;">
                    <div class="language_option_list">
                        <div class="language_option" onclick="location.href='<?php echo e(env('APP_URL').'/locale/tw'); ?>';">
                            <img src="dist/assets/image/taiwan.png" alt="">
                            <span>中文(台灣)</span>
                        </div>
                        <div class="language_option" onclick="location.href='<?php echo e(env('APP_URL').'/locale/en'); ?>';">
                            <img src="dist/assets/image/us.png" alt="">
                            <span>English</span>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <?php //首頁會使用到的part ?>
                <div class="language_select">
                    <?php if($locale == 'tw'): ?>
                    <img src="dist/assets/image/taiwan.png" alt="">
                    <span style="margin-right: 1rem;margin-left: 1rem;">
                        中文(台灣)
                    </span>
                    <?php elseif($locale == 'en'): ?>
                    <img src="dist/assets/image/us.png" alt="">
                    <span style="margin-right: 1rem;margin-left: 1rem;">
                        English
                    </span>
                    <?php endif; ?>
                    <i class="fa-solid fa-caret-down"></i>
                    <div class="language_select_wrap">
                        <div class="language_option_list">
                            <div class="language_option" onclick="location.href='<?php echo e(env('APP_URL').'/locale/tw'); ?>';">
                                <img src="dist/assets/image/taiwan.png" alt="">
                                <span>中文(台灣)</span>
                            </div>
                            <div class="language_option" onclick="location.href='<?php echo e(env('APP_URL').'/locale/en'); ?>';">
                                <img src="dist/assets/image/us.png" alt="">
                                <span>English</span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <nav>
                <ul>
                    <li><a href="<?php echo e(route('about')); ?>"><?php echo e(__('page.about')); ?></a></li>
                    <li><a href="<?php echo e(route('brand')); ?>"><?php echo e(__('page.agency_brand')); ?></a></li>
                    <li><a href="<?php echo e(route('news')); ?>"><?php echo e(__('page.news')); ?></a></li>
                    <li><a href="<?php echo e(route('products')); ?>"><?php echo e(__('page.products')); ?></a></li>
                    <li><a href="<?php echo e(route('contact')); ?>"><?php echo e(__('page.contact')); ?></a></li>
                    <?php if(Auth::check()): ?>
                    <li class=""><a href="javascript:;" class="quick_buy_btn"><?php echo e(__('page.quickly_buy')); ?></a></li>
                    <li class="nav_member">
                        <a href="<?php echo e(route('member')); ?>#order"><?php echo e(__('page.member.center')); ?></a>
                        <div class="member_option_list">
                            <div class="member_option" onclick="location.href='<?php echo e(route('logout')); ?>';">
                                <?php echo e(__('page.logout')); ?>

                            </div>
                        </div>
                    </li>

                    <li class="shopping_car nav_icon">
                        <a href="<?php echo e(route('cart')); ?>" style="position:relative;">
                            <i class="fa-solid fa-cart-shopping"></i>
                            <span class="cart_badge"><?php echo e($cart->count); ?></span>
                        </a>
                    </li>
                    <?php else: ?>
                    <li class=""><a href="<?php echo e(route('login')); ?>"><?php echo e(__('page.login_btn')); ?>/<?php echo e(__('page.register_btn')); ?></a></li>
                    <?php endif; ?>
                    <li class="search_btn nav_icon">
                        <i class="fa-solid fa-magnifying-glass" id="open_search_mask"></i>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="nav_btn">
            <!-- <div class="search_input">
                <i class="fa-solid fa-magnifying-glass" id="search_mobile"></i>
                <input type="text">
            </div> -->
            <?php if(Auth::check()): ?> 
            <i class="fa-solid fa-cart-shopping shopping_car" style="position:relative;" onclick="location.href='<?php echo e(route('cart')); ?>';">
                    <span class="cart_badge"><?php echo e($cart->count); ?></span>
            </i>
            <?php endif; ?>
            <i class="fa-solid fa-bars menu_btn"></i>
        </div>
        
        <div class="search_mask">
            <div class="close">
                <i class="fa-solid fa-xmark" id="close_search_mask_btn"></i>
            </div>
            <section>
                <div class="search_div">
                    <input type="text" name="search_input" class="search_input" id="go_search_page" placeholder="搜尋產品">
                    <i class="fa-solid fa-magnifying-glass" id="search_mask_btn"></i>
                </div>
                <div class="keyword_tag_section">
                    <!-- <button class="keyword_tag_btn">#<span class="tag_text">巴斯夫</span></button>
                    <button class="keyword_tag_btn">#<span class="tag_text">伊士曼</span></button>
                    <button class="keyword_tag_btn">#<span class="tag_text">柏拉碳黑</span></button>
                    <button class="keyword_tag_btn">#<span class="tag_text">巴斯夫</span></button>
                    <button class="keyword_tag_btn">#<span class="tag_text">伊士曼</span></button>
                    <button class="keyword_tag_btn">#<span class="tag_text">柏拉碳黑</span></button>
                    <button class="keyword_tag_btn">#<span class="tag_text">巴斯夫</span></button>
                    <button class="keyword_tag_btn">#<span class="tag_text">伊士曼</span></button>
                    <button class="keyword_tag_btn">#<span class="tag_text">柏拉碳黑</span></button> -->
                </div>
            </section>
        </div>
    </div>
</header>

<div class="side_bar">
    <div class="close">
        <i class="fa-solid fa-xmark" id="close_side_bar_btn"></i>
    </div>
    <div class="search_div">
        <input type="text" name="search_input" class="search_input" id="go_search_page" placeholder="搜尋產品">
        <i class="fa-solid fa-magnifying-glass" id="search_mobile"></i>
        <!-- <button type="button" class="btn btn-primary">搜尋</button> -->
    </div>
    <ul>
        <li><a href="<?php echo e(route('about')); ?>"><?php echo e(__('page.about')); ?></a></li><li><a href="<?php echo e(route('brand')); ?>"><?php echo e(__('page.agency_brand')); ?></a></li>
        <li><a href="<?php echo e(route('news')); ?>"><?php echo e(__('page.news')); ?></a></li>
        <li><a href="<?php echo e(route('products')); ?>"><?php echo e(__('page.products')); ?></a></li>
        <li><a href="<?php echo e(route('contact')); ?>"><?php echo e(__('page.contact')); ?></a></li>
        <?php if(Auth::check()): ?> 
        <li><a href="javascript:;" class="quick_buy_btn"><?php echo e(__('page.quickly_buy')); ?></a></li>
        <li><a href="<?php echo e(route('member')); ?>#order"><?php echo e(__('page.member.center')); ?></a></li>

        <li><a href="<?php echo e(route('logout')); ?>"><?php echo e(__('page.logout')); ?></a></li>
        <?php else: ?>
        <li><a href="<?php echo e(route('login')); ?>"><?php echo e(__('page.login_btn')); ?>/<?php echo e(__('page.register_btn')); ?></a></li>
        <?php endif; ?>
    </ul>
</div>
<?php /**PATH C:\xampp\htdocs\ntue_teacher\resources\views/components/nav+menu.blade.php ENDPATH**/ ?>