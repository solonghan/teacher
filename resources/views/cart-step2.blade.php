@extends('components.master')
@section('title') {{ $title }} @endsection
@section('description') {{ $description }} @endsection
@section('css')

@endsection
@section('content')
@include('cart_step')
            <div class="container">
                <form action="{{ route('cart.confirm') }}" method="POST">
                    @csrf
                    <div class="cart_two_container">
                        <div class="cart_check_table">
                            <div class="cart_check_table_title row">
                                <div class="col-2 table_name">
                                    <h5>{{ __('page.cart.product_cover') }}</h5>
                                </div>
                                <div class="col-4 table_name" id="check_title_product_name">
                                    <h5>{{ __('page.cart.product_name') }}</h5>
                                </div>
                                <div class="col-2 table_name">
                                    <h5>{{ __('page.cart.product_price') }}</h5>
                                </div>
                                <div class="col-2 table_name">
                                    <h5>{{ __('page.cart.quantity') }}</h5>
                                </div>
                                <div class="col-2 table_name">
                                    <h5>{{ __('page.cart.sub_total') }}</h5>
                                </div>
                            </div>
                            <div class="cart_check_table_items">
                            @foreach ($cart->items as $item)
                                <div class="cart_check_table_item row">
                                    <div class="col-5 col-md-2 table_detail">
                                        <img src="{{ env('APP_URL').Storage::url($item->cover) }}" style="height:80px;">
                                    </div>
                                    <div class="col-7 col-md-4 table_detail" id="check_item_product_name">
                                        <h6>{{ $item->name }}</h6>
                                    </div>
                                    <div class="col-5 mobile_show"><h6>{{ __('page.cart.product_price') }}</h6></div>
                                    <div class="col-7 col-md-2 table_detail my-4">
                                        @if ($item->price_remark == '')
                                        <h6>$ {{ number_format($item->price) }}/{{ $item->weight }}</h6>
                                        @elseif ($item->price_remark == 'custom')
                                        <h6 class="text-danger">{{ __('page.cart.custom_price') }}</h6>
                                        @elseif ($item->price_remark == 'exceed')
                                        <h6 class="text-danger">{{ __('page.cart.over_weight') }}</h6>
                                        @endif
                                    </div>
                                    <div class="col-5 col-md-2 table_detail">
                                        <h6>{{ $item->quantity }}{{ $item->weight }}</h6>
                                    </div>
                                    <div class="col-7 col-md-2 table_detail">
                                        @if ($item->price_remark == '')
                                        <h6>$ {{ number_format($item->quantity * $item->price) }}</h6>
                                        @elseif ($item->price_remark == 'custom')
                                        <h6 class="text-danger">{{ __('page.cart.custom_price') }}</h6>
                                        @elseif ($item->price_remark == 'exceed')
                                        <h6 class="text-danger">{{ __('page.cart.over_weight') }}</h6>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            </div>
                            @if ($cart->price_remark != '')
                            <div class="cart_total_wrap">
                                <h6 class="text-danger">{{ __('page.cart.bargain') }}</h6>
                            </div>
                            @else
                            <div class="cart_check_table_items">
                                <div class="cart_check_table_item row" style="font-size: 18px; font-weight: 500;">
                                    <div class="col-6 col-md-9 text-end">
                                        {{  __('page.cart.sub_total') }}:
                                    </div>
                                    <div class="col-6 col-md-2 col-offset-md-1 text-start">
                                        NT$ {{ number_format($cart->price) }}
                                    </div>
                                    <div class="col-6 col-md-9 text-end">
                                        {{ __('page.cart.tax') }}:
                                    </div>
                                    <div class="col-6 col-md-2 col-offset-md-1 text-start">
                                        NT$ {{ number_format(round($cart->price * 0.05)) }}
                                    </div>
                                    <div class="col-6 col-md-9 text-end">
                                        {{ __('page.cart.total') }}:
                                    </div>
                                    <div class="col-6 col-md-2 col-offset-md-1 text-start">
                                        NT$ {{ number_format($cart->price + round($cart->price * 0.05)) }}
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="blue_dash"></div>
                        <div class="cart_receive_container">
                            <div class="login_inputs_container">
                                <div class="bill_info_bg_title_wrap">
                                    <div class="register_title mb-4">
                                        <h3>收貨人資訊</h3>
                                    </div>
                                </div>
                                <div class="product_btn_wrap">
                                    <button type="button" class="product_btn" data-bs-toggle="modal" data-bs-target="#receive_data_select_modal">從收貨人資料挑選</button>
                                    <!-- 快速挑選收貨人資料 -->
                                    <div class="modal fade" id="receive_data_select_modal" tabindex="-1" aria-labelledby="receive_data_select_modal_label" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="receive_modal_container">
                                                        <h5>請選擇收貨人資料</h5>
                                                        <div class="blue_dash"></div>
                                                        <div class="receive_all_data_container">
                                                            @foreach (Auth::user()->load('recipient')->recipient as $index => $recipient)
                                                            <div class="receive_data_wrap">
                                                                <div class="select_input_wrap mb-2">
                                                                    <label for="receive_{{ ($index+1) }}">
                                                                        <input type="radio" @if($index==0) checked @endif name="receive_data" id="receive_{{ ($index+1) }}">
                                                                        收貨人資料{{ ($index + 1) }}
                                                                    </label>
                                                                </div>
                                                                <div class="receive_data_table">
                                                                    <div class="table_row">
                                                                        <div class="table_title">收貨人姓名</div>
                                                                        <div class="table_content receive_name">{{ $recipient->username }}</div>
                                                                    </div>
                                                                    <div class="table_row">
                                                                        <div class="table_title">收貨地址</div>
                                                                        <div class="table_content receive_address">{{ $recipient->address }}</div>
                                                                    </div>
                                                                    <div class="table_row">
                                                                        <div class="table_title">收貨人聯繫電話</div>
                                                                        <div class="table_content receive_phone">{{ $recipient->phone }}</div>
                                                                    </div>
                                                                    <div class="table_row">
                                                                        <div class="table_title">收貨人電話分機
                                                                        </div>
                                                                        <div class="table_content receive_phone_minor">{{ $recipient->ext }}</div>
                                                                    </div>
                                                                    <div class="table_row">
                                                                        <div class="table_title"> 收貨人 E-Mail</div>
                                                                        <div class="table_content receive_mail">{{ $recipient->email }}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                        <div class="data_select_wrap">
                                                            <button type="button" class="receive_data_select_btn orange_btn get_bigger_btn" data-bs-dismiss="modal" aria-label="Close">確定</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 快速挑選收貨人資料 -->
                                </div>
                                <div class="bill_info_all_wrap">
                                    <div class="bill_info_one_wrap bill_info_wrap">
                                        <div class="mail_wrap login_inputs_wrap">
                                            <label for="receive_name">收貨人姓名<span class="require_icon">*</span></label>
                                            <input class="login_inputs" type="text" id="receive_name" name="recipient_username">
                                        </div>
                                        <div class="mail_wrap login_inputs_wrap">
                                            <label for="receive_address">收貨地址<span class="require_icon">*</span></label>
                                            <input class="login_inputs" type="text" id="receive_address" name="recipient_address">
                                        </div>
                                        <div class="mail_wrap login_inputs_wrap">
                                            <label for="receive_phone">收貨人聯繫電話<span class="require_icon">*</span></label>
                                            <input class="login_inputs" type="tel" id="receive_phone" name="recipient_phone">
                                        </div>
                                        <div class="mail_wrap login_inputs_wrap">
                                            <label for="receive_phone_minor">收貨人電話分機</label>
                                            <input class="login_inputs" type="tel" id="receive_phone_minor" name="recipient_ext">
                                        </div>
                                        <div class="mail_wrap login_inputs_wrap">
                                            <label for="receive_mail">收貨人 E-Mail<span class="require_icon">*</span></label>
                                            <input class="login_inputs" type="email" id="receive_mail" name="recipient_email">
                                        </div>
                                        <div class="mail_wrap login_inputs_wrap">
                                            <label for="receive_date">收貨日期</label>
                                            <input class="login_inputs" type="date" id="receive_date" name="recipient_date">
                                        </div>
                                        <div class="same_contact_info_wrap mt-4">
                                            <input class="me-2" type="checkbox" name="sync_recipient" id="sync_recipient">
                                            <label for="sync_recipient">將此次填寫加入收貨人資訊</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="blue_dash"></div>
                        <div class="cart_bill_container">
                            <div class="login_inputs_container">
                                <div class="bill_info_bg_title_wrap">
                                    <div class="register_title mb-4">
                                        <h3>發票資訊</h3>
                                    </div>
                                </div>
                                <div class="product_btn_wrap">
                                    <button type="button" class="product_btn" data-bs-toggle="modal" data-bs-target="#bill_data_select_modal">從發票資訊挑選</button>
                                    <!-- 快速挑選收貨人資料 -->
                                    <div class="modal fade" id="bill_data_select_modal" tabindex="-1" aria-labelledby="bill_data_select_modal_label" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="receive_modal_container">
                                                        <h5>請選擇發票資料</h5>
                                                        <div class="blue_dash"></div>
                                                        <div class="receive_all_data_container">
                                                            @foreach (Auth::user()->load('invoice')->invoice as $index => $invoice)
                                                            <div class="receive_data_wrap">
                                                                <div class="select_input_wrap mb-2">
                                                                    <label for="bill_{{ ($index+1) }}">
                                                                        <input type="radio" name="bill_data" id="bill_{{ ($index+1) }}" @if($index==0) checked @endif>
                                                                        收貨人資料{{ ($index + 1) }}
                                                                    </label>
                                                                </div>
                                                                <div class="receive_data_table">
                                                                    <div class="table_row">
                                                                        <div class="table_title">發票公司全名</div>
                                                                        <div class="table_content bill_company_name">{{ $invoice->company }}</div>
                                                                    </div>
                                                                    <div class="table_row">
                                                                        <div class="table_title">發票統編</div>
                                                                        <div class="table_content bill_company_uniform">{{ $invoice->tax_id }}</div>
                                                                    </div>
                                                                    <div class="table_row">
                                                                        <div class="table_title">發票收件人姓名</div>
                                                                        <div class="table_content bill_name">{{ $invoice->username }}</div>
                                                                    </div>
                                                                    <div class="table_row">
                                                                        <div class="table_title">發票收件地址</div>
                                                                        <div class="table_content bill_address">{{ $invoice->address }}</div>
                                                                    </div>
                                                                    <div class="table_row">
                                                                        <div class="table_title">發票收件聯繫電話</div>
                                                                        <div class="table_content bill_receive_phone">{{ $invoice->phone }}</div>
                                                                    </div>
                                                                    <div class="table_row">
                                                                        <div class="table_title">發票收件電話分機</div>
                                                                        <div class="table_content bill_phone_minor">{{ $invoice->ext }}</div>
                                                                    </div>
                                                                    <div class="table_row">
                                                                        <div class="table_title">收件人 E-Mail</div>
                                                                        <div class="table_content bill_receive_mail">{{ $invoice->email }}</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                        <div class="data_select_wrap">
                                                            <button type="button" class="bill_data_select_btn orange_btn get_bigger_btn" data-bs-dismiss="modal" aria-label="Close">確定</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- 快速挑選收貨人資料 -->
                                </div>
                                <div class="bill_info_all_wrap">
                                    <div class="bill_info_one_wrap bill_info_wrap">
                                        <div class="mail_wrap login_inputs_wrap">
                                            <label for="bill_company_name">發票公司全名<span class="require_icon">*</span></label>
                                            <input class="login_inputs" type="text" id="bill_company_name" name="invoice_company">
                                        </div>
                                        <div class="mail_wrap login_inputs_wrap">
                                            <label for="bill_company_uniform">發票統編<span class="require_icon">*</span></label>
                                            <input class="login_inputs" type="text" id="bill_company_uniform" name="invoice_tax_id">
                                        </div>
                                        <div class="mail_wrap login_inputs_wrap">
                                            <label for="bill_name">發票收件人姓名<span class="require_icon">*</span></label>
                                            <input class="login_inputs" type="text" id="bill_name" name="invoice_username">
                                        </div>
                                        <div class="mail_wrap login_inputs_wrap">
                                            <label for="bill_address">發票收件地址<span class="require_icon">*</span></label>
                                            <input class="login_inputs" type="text" id="bill_address" name="invoice_address">
                                        </div>
                                        <div class="mail_wrap login_inputs_wrap">
                                            <label for="bill_receive_phone">發票收件聯繫電話<span class="require_icon">*</span></label>
                                            <input class="login_inputs" type="tel" id="bill_receive_phone" name="invoice_phone">
                                        </div>
                                        <div class="mail_wrap login_inputs_wrap">
                                            <label for="bill_phone_minor">發票電話分機</label>
                                            <input class="login_inputs" type="tel" id="bill_phone_minor" name="invoice_ext">
                                        </div>
                                        <div class="mail_wrap login_inputs_wrap">
                                            <label for="bill_receive_mail">發票E-mail<span class="require_icon">*</span></label>
                                            <input class="login_inputs" type="email" id="bill_receive_mail" name="invoice_email">
                                        </div>
                                        <div class="same_contact_info_wrap mt-4">
                                            <input class="me-2" type="checkbox" name="sync_invoice" id="sync_invoice">
                                            <label for="sync_invoice">將此次填寫加入發票資訊</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="blue_dash"></div>
                        <div class="remark_submit_container">
                            <h4 class="mb-4">備註</h4>
                            <textarea class="login_inputs mb-5" name="remark" id="" cols="30" rows="6"></textarea>
                            <div class="cart_two_btn_wrap">
                                <div class="back_cart_one_btn white_btn get_bigger_btn me-4">上一步</div>
                                <button type="submit" class="orange_btn get_bigger_btn">送出需求</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
@endsection
@section('script')

<script>
    $(document).ready(function(){
        $('.step_two').css('color', '#1173BA')
        // 快速帶入資料
        $('.receive_data_select_btn').click(function () {
            console.log($(this));
            fastBringReceiveData($(this))
        })
        $('.bill_data_select_btn').click(function () {
            console.log($(this));
            fastBringBillData($(this))
        })
    })
    function fastBringReceiveData(ele) {
        const dataArr = $(ele).parent().prev().find('input').toArray()
        const selectItem = dataArr.find(item => {
            return $(item).prop('checked') === true
        })
        
        const dataTable = $(selectItem).parent().parent().next()
        $('#receive_name').val(dataTable.find('.receive_name').text())
        $('#receive_address').val(dataTable.find('.receive_address').text())
        $('#receive_phone').val(dataTable.find('.receive_phone').text())
        $('#receive_phone_minor').val(dataTable.find('.receive_phone_minor').text()) 
        $('#receive_mail').val(dataTable.find('.receive_mail').text()) 
    }
    function fastBringBillData(ele) {
        const dataArr = $(ele).parent().prev().find('input').toArray()
        const selectItem = dataArr.find(item => {
            return $(item).prop('checked') === true
        })
        const dataTable = $(selectItem).parent().parent().next()
        $('#bill_company_name').val(dataTable.find('.bill_company_name').text())
        $('#bill_company_uniform').val(dataTable.find('.bill_company_uniform').text())
        $('#bill_name').val(dataTable.find('.bill_name').text())
        $('#bill_address').val(dataTable.find('.bill_address').text()) 
        $('#bill_receive_phone').val(dataTable.find('.bill_receive_phone').text()) 
        $('#bill_phone_minor').val(dataTable.find('.bill_phone_minor').text()) 
        $('#bill_receive_mail').val(dataTable.find('.bill_receive_mail').text()) 
    }

</script>
@endsection