
<footer>
    <div class="footer_container">
        <div class="container">
            <div class="footer_top">
                <div class="row">
                    <div class="footer_logo col">
                        @if ($footer_intro->img != '')
                        <a href="{{ route('home') }}" class="mb-3">
                            <img src="{{ env('APP_URL').Storage::url($footer_intro->img) }}" alt="">
                        </a>
                        @endif
                        <div class="logo_info">
                            {{ $footer_intro->content }}
                        </div>
                    </div>
                    @foreach ($company as $item)
                    <div class="col-xxl col-sm-12 company_data">
                        <div class="title mb-3"><h5>{{ $item->company }}</h5></div>
                        @if ($item->mobile != '')
                        <div class="phone info_div">
                            <div class="img_container">
                                <img src="dist/assets/icon/phone.png" alt="">
                            </div>
                            <div class="number limit_two">
                                <a style="color:#FFF;" href="tel:{{ $item->mobile }}">{{ $item->mobile }}</a>
                            </div>
                        </div>
                        @endif
                        @if ($item->tel != '')
                        <div class="telephone info_div">
                            <div class="img_container">
                                <img src="dist/assets/icon/telephone.png" alt="">
                            </div>
                            <div class="number limit_two">
                                <a style="color:#FFF;" href="tel:{{ $item->tel }}">{{ $item->tel }}</a>
                            </div>
                        </div>
                        @endif
                        @if ($item->email != '')
                        <div class="mail info_div">
                            <div class="img_container">
                                <img src="dist/assets/icon/mail.png" alt="">
                            </div>
                            <div class="number limit_two">
                                <a style="color:#FFF;" href="mailto:{{ $item->email }}">{{ $item->email }}</a>
                            </div>
                        </div>
                        @endif
                        @if ($item->address != '')
                        <div class="position info_div">
                            <div class="img_container">
                                <img src="dist/assets/icon/position.png" alt="">
                            </div>
                            <div class="number limit_two">
                                <a style="color:#FFF;" href="https://www.google.com/maps/dir/?api=1&origin={{ $item->address }}">{{ $item->address }}</a>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="footer_bottom">
                <div class="row justify-content-between">
                    <div class="col-lg-4 col-sm-12">
                        {{ __('page.copyright') }}
                    </div>
                    <div class="col-lg-4 col-sm-12">
                        <a href="{{ route('shopping_flow') }}">{{ __('page.shopping_flow') }}</a> | <a href="{{ route('payment_method') }}">{{ __('page.payment_method') }}</a> | <a href="{{ route('privacy') }}">{{ __('page.privacy') }}</a> 
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
                        <button type="button" class="confirm_btn" data-bs-dismiss="modal" onclick="location.href='{{ route('cart') }}';">OK</button>
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
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>