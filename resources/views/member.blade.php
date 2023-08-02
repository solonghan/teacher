@extends('components.master')
@section('title') {{ $title }} @endsection
@section('description') {{ $description }} @endsection
@section('css')
<style>
    .return_product,
    .return_product div,
    .return_product h6,
    .return_product a{
        text-decoration: line-through !important;
        color: #AAA !important;
    }
</style>
@endsection
@section('content')
<div class="container">
    <div class="member_container">
        <div class="member_center_nav d-flex align-items-start">
            <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <button class="nav-link active" id="v-pills-contact-data-tab" data-bs-toggle="pill" data-bs-target="#v-pills-contact-data" type="button" role="tab" aria-controls="v-pills-contact-data" aria-selected="true">聯絡人資料</button>
                <button class="nav-link" id="v-pills-bill-data-tab" data-bs-toggle="pill" data-bs-target="#v-pills-bill-data" type="button" role="tab" aria-controls="v-pills-bill-data" aria-selected="false">發票資料</button>
                <button class="nav-link" id="v-pills-receive-data-tab" data-bs-toggle="pill" data-bs-target="#v-pills-receive-data" type="button" role="tab" aria-controls="v-pills-receive-data" aria-selected="false">收貨人資料</button>
                <button class="nav-link" id="v-pills-order-list-tab" data-bs-toggle="pill" data-bs-target="#v-pills-order-list" type="button" role="tab" aria-controls="v-pills-order-list" aria-selected="false">訂單詳情</button>
                <button class="nav-link" onclick="location.href='{{ route('logout') }}';">{{ __('page.logout') }}</button>
            </div>
        </div>
        <div class="member_tab_content tab-content" id="v-pills-tabContent">
            <!-- 聯絡人資料 -->
            <div class="member_tab_item tab-pane fade show active" id="v-pills-contact-data" role="tabpanel" aria-labelledby="v-pills-contact-data-tab">
                <form action="{{ route('member.edit') }}" method="POST" id="user_form">
                    @csrf
                    <div class="contact_data_container">
                        <div class="register_title mb-3">
                            <h3>聯絡人資料</h3>
                        </div>
                        <div class="mail_wrap login_inputs_wrap">
                            <label for="contact_company_name">聯繫人公司全名<span class="require_icon">*</span></label>
                            <input class="login_inputs" type="text" name="company" value="{{ Auth::user()->company }}">
                        </div>
                        <div class="mail_wrap login_inputs_wrap">
                            <label for="contact_company_uniform">聯繫人公司統編<span class="require_icon">*</span></label>
                            <input class="login_inputs" type="text" name="tax_id" value="{{ Auth::user()->tax_id }}">
                        </div>
                        <div class="mail_wrap login_inputs_wrap">
                            <label for="contact_name">聯繫人姓名<span class="require_icon">*</span></label>
                            <input class="login_inputs" type="text" name="username" value="{{ Auth::user()->username }}">
                        </div>
                        <div class="mail_wrap login_inputs_wrap">
                            <label for="contact_phone">聯繫人電話<span class="require_icon">*</span></label>
                            <input class="login_inputs" type="tel" name="phone" value="{{ Auth::user()->phone }}">
                        </div>
                        <div class="mail_wrap login_inputs_wrap">
                            <label for="contact_phone_minor">聯繫人電話分機<span class="require_icon">*</span></label>
                            <input class="login_inputs" type="tel" name="ext" value="{{ Auth::user()->ext }}">
                        </div>
                        <div class="mail_wrap login_inputs_wrap">
                            <label for="contact_phone">聯繫人傳真</label>
                            <input class="login_inputs" type="tel" name="fax" value="{{ Auth::user()->fax }}">
                        </div>
                        <div class="mail_wrap login_inputs_wrap">
                            <label for="contact_mail">聯繫人E-mail<span class="require_icon">*</span></label>
                            <input class="login_inputs" type="email" name="email" value="{{ Auth::user()->email }}">
                        </div>
                        <div class="gray_dash"></div>
                        <div class="password_caption">
                            請輸入舊密碼及新密碼
                        </div>
                        <div class="password_wrap login_inputs_wrap">
                            <label for="contact_password">現在密碼</label>
                            <input class="login_inputs" type="password" name="old_password">
                        </div>

                        <div class="password_wrap login_inputs_wrap">
                            <label for="contact_password">新密碼</label>
                            <input class="login_inputs" type="password" name="password">
                        </div>
                        <div class="password_wrap login_inputs_wrap">
                            <label for="contact_password_check">確認新密碼</label>
                            <input class="login_inputs" type="password" name="password_confirm">
                        </div>
                        <div class="login_btn_wrap mt-5">
                            <button class="save_contact_data_btn orange_btn get_bigger_btn" data-bs-toggle="modal" data-bs-target="#save_modal">儲存</button>
                        </div>
                        <!-- 確認儲存modal -->
                        <div class="modal fade" id="save_modal" tabindex="-1" aria-labelledby="save_modal_label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal_title" id="save_modal_label">確定儲存?</h5>
                                    </div>
                                    <div class="modal-body">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="cancel_btn" data-bs-dismiss="modal">取消</button>
                                        <button type="submit" class="confirm_btn">確定</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- 發票資料 -->
            <div class="member_tab_item tab-pane fade" id="v-pills-bill-data" role="tabpanel" aria-labelledby="v-pills-bill-data-tab">
                <div class="bill_data_container">
                    <h3>發票資料</h3>
                    <div class="blue_dash"></div>
                    <div class="all_bill_data_container" id="invoice_list">
                        @foreach (Auth::user()->load('invoice')->invoice as $index => $invoice)
                        @component('member_invoice_item', ['invoice'=>$invoice, 'index'=>$index, 'cnt'=>count(Auth::user()->load('invoice')->invoice)])
                        @endcomponent
                        @endforeach
                    </div>
                    <div class="add_one_bill_info_wrap mt-3">
                        <div class="add_one_bill_info_btn" data-bs-toggle="modal" data-bs-target="#add_new_bill_data_modal">+ 新增另一組發票資訊</div>
                    </div>

                    <div class="modal fade" id="add_new_bill_data_modal" tabindex="-1" aria-labelledby="add_new_bill_data_modal_label" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('member.add_invoice') }}" method="POST">
                                        @csrf
                                        <div class="add_bill_modal_container">
                                            <div class="login_inputs_container">
                                                <div class="bill_info_all_wrap">
                                                    <div class="bill_info_one_wrap bill_info_wrap">
                                                        <div class="mail_wrap login_inputs_wrap">
                                                            <label for="bill_company_name">發票公司全名<span class="require_icon">*</span></label>
                                                            <input class="login_inputs" type="text" name="company">
                                                        </div>
                                                        <div class="mail_wrap login_inputs_wrap">
                                                            <label for="bill_company_uniform">發票統編<span class="require_icon">*</span></label>
                                                            <input class="login_inputs" type="text" name="tax_id">
                                                        </div>
                                                        <div class="mail_wrap login_inputs_wrap">
                                                            <label for="bill_name">發票收件人姓名<span class="require_icon">*</span></label>
                                                            <input class="login_inputs" type="text" name="username">
                                                        </div>
                                                        <div class="mail_wrap login_inputs_wrap">
                                                            <label for="bill_address">發票收件地址<span class="require_icon">*</span></label>
                                                            <input class="login_inputs" type="text" name="address">
                                                        </div>
                                                        <div class="mail_wrap login_inputs_wrap">
                                                            <label for="bill_receive_phone">發票收件聯繫電話<span class="require_icon">*</span></label>
                                                            <input class="login_inputs" type="tel" name="phone">
                                                        </div>
                                                        <div class="mail_wrap login_inputs_wrap">
                                                            <label for="bill_phone_minor">發票電話分機</label>
                                                            <input class="login_inputs" type="tel" name="ext">
                                                        </div>
                                                        <div class="mail_wrap login_inputs_wrap">
                                                            <label for="bill_receive_mail">發票E-mail<span class="require_icon">*</span></label>
                                                            <input class="login_inputs" type="email" name="email">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="login_btn_wrap">
                                                    <button type="submit" class="save_bill_data_btn orange_btn get_bigger_btn" data-info="" data-bs-dismiss="modal">儲存</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 刪除資料modal -->
                    <div class="modal fade" id="remove_bill_modal" tabindex="-1" aria-labelledby="remove_bill_modal_label" aria-hidden="true">\
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal_title" id="remove_bill_modal_label">確定刪除?</h5>
                                </div>
                                <div class="modal-body">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="cancel_btn" data-bs-dismiss="modal">取消</button>
                                    <button type="button" class="confirm_btn confirm_remove_bill_btn" data-id="" data-bs-dismiss="modal">確定</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 收貨人資料 -->
            <div class="member_tab_item tab-pane fade" id="v-pills-receive-data" role="tabpanel" aria-labelledby="v-pills-receive-data-tab">
                <div class="bill_data_container">
                    <h3>收貨人資料</h3>
                    <div class="blue_dash"></div>
                    <div class="receive_data_wrap" id="receive_data1">
                        @foreach (Auth::user()->load('recipient')->recipient as $index => $recipient)
                        @component('member_recipient_item', ['recipient'=>$recipient, 'index'=>$index, 'cnt'=>count(Auth::user()->load('recipient')->recipient)])
                        @endcomponent
                        @endforeach
                    </div>
                    <div class="add_one_bill_info_wrap mt-3">
                        <div class="add_one_receive_info_btn" data-bs-toggle="modal" data-bs-target="#add_new_receive_data_modal">+ 新增另一組收貨人資訊</div>
                    </div>
                    <!-- 編輯收貨人資料modal -->
                    <div class="modal fade" id="add_new_receive_data_modal" tabindex="-1" aria-labelledby="add_new_receive_data_modal_label" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('member.add_recipient') }}" method="POST">
                                        @csrf
                                        <div class="add_bill_modal_container">
                                            <div class="login_inputs_container">
                                                <div class="bill_info_all_wrap">
                                                    <div class="bill_info_one_wrap bill_info_wrap">
                                                        <div class="mail_wrap login_inputs_wrap">
                                                            <label for="receive_name">收貨人姓名<span class="require_icon">*</span></label>
                                                            <input class="login_inputs" type="text" name="username">
                                                        </div>
                                                        <div class="mail_wrap login_inputs_wrap">
                                                            <label for="receive_address">收貨人地址<span class="require_icon">*</span></label>
                                                            <input class="login_inputs" type="text" name="address">
                                                        </div>
                                                        <div class="mail_wrap login_inputs_wrap">
                                                            <label for="receive_phone">收貨人聯繫電話<span class="require_icon">*</span></label>
                                                            <input class="login_inputs" type="tel" name="phone">
                                                        </div>
                                                        <div class="mail_wrap login_inputs_wrap">
                                                            <label for="receive_phone_minor">收貨人電話分機</label>
                                                            <input class="login_inputs" type="tel" name="ext">
                                                        </div>
                                                        <div class="mail_wrap login_inputs_wrap">
                                                            <label for="receive_mail">收貨人 E-mail<span class="require_icon">*</span></label>
                                                            <input class="login_inputs" type="email" name="email">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="login_btn_wrap">
                                                    <button type="submit" class="save_receive_data_btn orange_btn get_bigger_btn" data-info="" data-bs-dismiss="modal">儲存</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 刪除資料modal -->
                    <div class="modal fade" id="remove_receive_modal" tabindex="-1" aria-labelledby="remove_receive_modal_label" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal_title" id="remove_receive_modal_label">確定刪除?</h5>
                                </div>
                                <div class="modal-body">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="cancel_btn" data-bs-dismiss="modal">取消</button>
                                    <button type="button" class="confirm_btn confirm_remove_receive_btn" data-id="" data-bs-dismiss="modal">確定</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 訂單查詢 -->
            <div class="member_tab_item tab-pane fade" id="v-pills-order-list" role="tabpanel" aria-labelledby="v-pills-order-list-tab">
                <!-- 訂單列表 -->
                <div class="order_list_container">
                    <div class="order_list_title">
                        <h3 class="mb-4">訂單查詢</h3>
                        <p class="mb-4">如需要修改訂單、取消訂單、商品退換貨時，請進入訂單後，點擊取消/退換貨按紐，點擊後將會有專人與您聯繫</p>
                    </div>
                    <div class="order_items_container">
                        @foreach ($orders as $order)
                        <div class="order_item_wrap">
                            <div class="order_detail_title">
                                <div class="no_date_wrap">
                                    <div class="no me-4">訂單編號 : {{ $order->order_no }}</div>
                                    <div class="date">訂購日期 : {{ date('Y/m/d', strtotime($order->created_at)) }}</div>
                                </div>
                                <div class="status_wrap">訂單狀態 :  <span id="order_status_{{ $order->order_no }}">{{ $order->status_show() }}</span></div>
                            </div>
                            <div class="item_detail_wrap">
                                @if (count($order->cart->items) > 0)
                                <img class="item_img item" src="{{ env('APP_URL').Storage::url($order->cart->items[0]->cover) }}" style="width:auto; height:100px;">
                                <h6 class="item_name item">
                                    <a style="color:initial;" href="{{ route('products.detail', ['id'=>$order->cart->items[0]->product_id]) }}">
                                        {{ $order->cart->items[0]->name }}
                                    </a>
                                </h6>
                                <h6 class="item_amount item">共 {{ count($order->cart->items) }} 件商品</h6>
                                @endif
                                <h6 class="item_total item">
                                    @if ($order->price_remark != '')
                                    {{ __('page.cart.bargain') }}
                                    @else
                                        總計 : 
                                        NT$ {{ number_format($order->price + round($order->price * 0.05)) }}
                                    @endif
                                </h6>
                                <div class="btn_wrap me-2">
                                    @if ($order->status == 'confirmed')
                                    <button class="orange_btn get_bigger_btn mb-2 list_agree_{{ $order->order_no }}" data-bs-toggle="modal" data-bs-target="#agree_order{{$order->id}}_modal">同意訂單</button>
                                    <!-- 同意訂單modal -->
                                    <div class="modal fade" id="agree_order{{ $order->id }}_modal" tabindex="-1" aria-labelledby="agree_order{{$order->id}}_modal_label" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal_title" id="agree_order{{$order->id}}_modal_label">確定同意訂單?</h5>
                                                </div>
                                                <div class="modal-body">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="cancel_btn" data-bs-dismiss="modal">取消</button>
                                                    <button type="button" class="confirm_btn confirm_order_btn agree_order_btn" data-no="{{ $order->order_no }}" data-bs-dismiss="modal">確定</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <button class="orange_btn get_bigger_btn goto_bill" data-bill="{{ $order->order_no }}">查看訂單</button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <!-- 訂單細節 -->
                <div class="order_detail_container">
                    
                </div>
            </div>
        </div>
    </div>
</div>
        
@endsection
@section('script')

<script>
    $(document).ready(function () {
        if(window.location.hash) {
            $(document).find('.nav-link').each(function (index, element) {
                $(this).removeClass('active');
            });
            $(document).find('div[role=tabpanel]').each(function (index, element) {
                $(this).removeClass('show active');
            });

            if (window.location.hash == '#invoice') {
                $("#v-pills-bill-data-tab").addClass('active');
                $("#v-pills-bill-data").addClass('show active');
            }else if (window.location.hash == '#recipient') {
                $("#v-pills-receive-data-tab").addClass('active');
                $("#v-pills-receive-data").addClass('show active');
            }else if (window.location.hash == '#order') {
                $("#v-pills-order-list-tab").addClass('active');
                $("#v-pills-order-list").addClass('show active');
            }
        }
        history.pushState(null, document.title, location.href);
        window.addEventListener('popstate', function (event){
            if (view_bill_detail){
                gotoBillList();
                view_bill_detail = false;
            }else{
                history.pushState(null, document.title, location.href);
            }
        });
    });
    
    let view_bill_detail = false;
    $(document).on('click', '.goto_bill', function () {
        load_bill($(this).data('bill'));
        view_bill_detail = true;
    });
    function load_bill(order_no){
        $.ajax({
            type: "POST",
            url: '{{ route('member.bill') }}',
            data: {
                _token: "{{ csrf_token() }}",
                order_no: order_no
            },
            dataType: "json",
            success: function(data){
                if (data.status){
                    $('.order_list_container').hide()
                    $('.order_detail_container').show().html(data.html)
                }else{
                    alert(data.msg)
                }
            },
            failure: function(errMsg) {}
        });
    }
    $('.order_detail_container').hide()
    $(document).on('click', '.back_order_list_btn, #v-pills-order-list-tab', function () {
        gotoBillList();
    });

    function gotoBillList(){
        $('.order_list_container').show()
        $('.order_detail_container').hide()
    }
    //刪除發票資料
    $('.remove_bill_btn').click(function() {
        $('.confirm_remove_bill_btn').data('id', $(this).data('id'))
    })
    $('.confirm_remove_bill_btn').click(function() {
        const id = $(this).data('id')
        // $(`#bill_data${id}`).remove()
        location.href = '{{ route('member.del_invoice') }}/' + id;
    })
    
    //刪除收件人資料
    $('.remove_receive_btn').click(function() {
        $('.confirm_remove_receive_btn').data('id', $(this).data('id'))
    })
    $('.confirm_remove_receive_btn').click(function() {
        const id = $(this).data('id')
        location.href = '{{ route('member.del_recipient') }}/' + id;
    })

    $(document).on('click', ".open_buy_again_modal", function () {
        let order_no = $(this).data('no');
        $.ajax({
            type: "POST",
            url: '{{ route('member.bill.again') }}',
            data: {
                _token: "{{ csrf_token() }}",
                order_no: order_no
            },
            dataType: "json",
            success: function(data){
                if (data.status){
                    location.href = '{{ route('cart') }}';
                }else{
                    alert(data.msg)
                }
            },
            failure: function(errMsg) {}
        });        
    });

    $(document).on('click', ".confirm_order_btn", function () {
        let order_no = $(this).data('no');
        $.ajax({
            type: "POST",
            url: '{{ route('member.bill.confirm') }}',
            data: {
                _token: "{{ csrf_token() }}",
                order_no: order_no
            },
            dataType: "json",
            success: function(data){
                if (data.status){
                    load_bill(order_no);
                    $(".list_agree_"+order_no).hide();
                    $("#order_status_"+order_no).html("訂單成立");
                }else{
                    alert(data.msg)
                }
            },
            failure: function(errMsg) {}
        });
    });

    $(document).on('click', '.exchange_btn', function () {
        let order_no = $(this).data('no');
        $.ajax({
            type: "POST",
            url: '{{ route('member.bill.exchange') }}',
            data: {
                _token: "{{ csrf_token() }}",
                order_no: order_no
            },
            dataType: "json",
            success: function(data){
                if (data.status){
                    load_bill(order_no);
                    alert('將有專人與您聯繫換貨事宜，謝謝');
                }else{
                    alert(data.msg)
                }
            },
            failure: function(errMsg) {}
        });
    });

    $(document).on('click', '.return_btn', function () {
        $(".return_action").show();
        $(".subtotal").hide();
    });
    $(document).on('click', '.return_cancel_btn', function () {
        $(".return_action").hide();
        $(".subtotal").show();
    });

    $(document).on('click', '.return_confirm_btn', function () {
        let data = [];
        $(document).find(".return_product").each(function (index, elem) { 
            if ($(this).is(":checked")) data.push($(this).val());            
        });
        if (data.length <= 0) {
            alert("請選擇欲退貨商品");
            return;
        }
        if (!confirm("確認送出退貨申請嗎?")) return;
        
        let order_no = $(this).data('no');
        $.ajax({
            type: "POST",
            url: '{{ route('member.bill.return') }}',
            data: {
                _token: "{{ csrf_token() }}",
                order_no: order_no,
                data: data
            },
            dataType: "json",
            success: function(data){
                if (data.status){
                    load_bill(order_no);
                    alert('將有專人與您聯繫退貨事宜，謝謝');
                }else{
                    alert(data.msg)
                }
            },
            failure: function(errMsg) {}
        });
    });
    </script>
@endsection
