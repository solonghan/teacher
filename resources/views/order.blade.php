@extends('components.master')
@section('title') {{ $title }} @endsection
@section('description') {{ $description }} @endsection
@section('css')

@endsection
@section('content')
@include('cart_step')
            <div class="container">
                <div class="cart_three_container">
                    <div class="cart_three_title">
                        @if (Auth::user()->transaction_type == 'normal')
                        我們已收到您的訂單，以下為您的訂購明細，敬請先行付款，待收到款項後，我司會立即發貨
                        @else
                        我們已收到您的訂單，以下為您的訂購明細，我司將盡速安排發貨，您的付款條件為
                        @if (Auth::user()->transaction_type == 'month')
                        月結 {{ Auth::user()->transaction_day }} 日
                        @elseif (Auth::user()->transaction_type == 'day')
                        日結 {{ Auth::user()->transaction_day }} 日
                        @endif
                        ，敬請留意
                        @endif
                    </div>
                    <div class="cart_order_detail_contaiiner">
                        <div class="order_detail_title">
                            <div class="no_date_wrap">
                                <div class="no me-4">訂單編號 : {{ $data->order_no }}</div>
                                <div class="date">訂購日期 : {{ date('Y/m/d', strtotime($data->created_at)) }}</div>
                            </div>
                            <div class="status_wrap">訂單狀態 : {{ $data->status_show() }}</div>
                        </div>
                        <div class="order_detail_content">
                            <div class="order_status_wrap">
                                <div class="status_wrap">
                                    付款狀態 : {{ $data->payment_status_show() }}
                                </div>
                                <div class="status_wrap">
                                    付款狀態 : {{ $data->shipping_status_show() }}
                                </div>
                            </div>
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
                                    @foreach ($data->cart->items as $item)
                                    <div class="cart_check_table_item row">
                                        <div class="col-5 col-md-2 table_detail">
                                            <img src="{{ env('APP_URL').Storage::url($item->cover) }}" style="height: 80px;">
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
                                
                                @if ($data->cart->price_remark != '')
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
                                            NT$ {{ number_format($data->cart->price) }}
                                        </div>
                                        <div class="col-6 col-md-9 text-end">
                                            {{ __('page.cart.tax') }}:
                                        </div>
                                        <div class="col-6 col-md-2 col-offset-md-1 text-start">
                                            NT$ {{ number_format(round($data->cart->price * 0.05)) }}
                                        </div>
                                        <div class="col-6 col-md-9 text-end">
                                            {{ __('page.cart.total') }}:
                                        </div>
                                        <div class="col-6 col-md-2 col-offset-md-1 text-start">
                                            NT$ {{ number_format($data->cart->price + round($data->cart->price * 0.05)) }}
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="receive_all_data_container">
                        <section>
                            <div class="blue_dash"></div>
                            <div class="receive_data_table">
                                <div class="data_title_wrap mb-2">
                                    <h4>收貨人資料</h4>
                                </div>
                                <div class="table_row">
                                    <div class="table_title">收貨人姓名</div>
                                    <div class="table_content">{{ $data->cart->recipient_username }}</div>
                                </div>
                                <div class="table_row">
                                    <div class="table_title">收貨地址</div>
                                    <div class="table_content">{{ $data->cart->recipient_address }}</div>
                                </div>
                                <div class="table_row">
                                    <div class="table_title">收貨人聯繫電話</div>
                                    <div class="table_content">{{ $data->cart->recipient_phone }}</div>
                                </div>
                                <div class="table_row">
                                    <div class="table_title">收貨人電話分機</div>
                                    <div class="table_content">{{ $data->cart->recipient_ext }}</div>
                                </div>
                                <div class="table_row">
                                    <div class="table_title">收貨人 E-Mail</div>
                                    <div class="table_content">{{ $data->cart->recipient_email }}</div>
                                </div>
                            </div>
                            <div class="blue_dash"></div>
                            <div class="receive_data_table">
                                <div class="data_title_wrap mb-2">
                                    <h4>發票資料</h4>
                                </div>
                                <div class="table_row">
                                    <div class="table_title">發票公司全名</div>
                                    <div class="table_content">{{ $data->cart->invoice_company }}</div>
                                </div>
                                <div class="table_row">
                                    <div class="table_title">發票統編</div>
                                    <div class="table_content">{{ $data->cart->invoice_tax_id }}</div>
                                </div>
                                <div class="table_row">
                                    <div class="table_title">發票收件人姓名</div>
                                    <div class="table_content">{{ $data->cart->invoice_username }}</div>
                                </div>
                                <div class="table_row">
                                    <div class="table_title">發票收件地址</div>
                                    <div class="table_content">{{ $data->cart->invoice_address }}</div>
                                </div>
                                <div class="table_row">
                                    <div class="table_title">發票收件聯繫電話</div>
                                    <div class="table_content">{{ $data->cart->invoice_phone }}</div>
                                </div>
                                <div class="table_row">
                                    <div class="table_title">發票收件電話分機</div>
                                    <div class="table_content">{{ $data->cart->invoice_ext }}</div>
                                </div>
                                <div class="table_row">
                                    <div class="table_title">收件人 E-Mail</div>
                                    <div class="table_content">{{ $data->cart->invoice_email }}</div>
                                </div>
                            </div>
                            <div class="blue_dash"></div>
                            <div class="remark_wrap">
                                <h4>備註</h4>
                                <p>{{ $data->cart->remark }}</p>
                            </div>
                            <div class="blue_dash"></div>
                            <div class="back_order_list_wrap">
                                <button type="button" onclick="location.href='{{ route('member') }}';" class="orange_btn get_bigger_btn">會員中心</button>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
@endsection
@section('script')
<script>
    $(document).ready(function(){
        $('.step_three').css('color', '#1173BA')
    })
</script>
@endsection