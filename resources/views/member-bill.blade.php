<div class="cart_three_container">
    <div class="order_search_top_wrap">
        <h3 class="mb-4">訂單查詢</h3>
        <div class="order_search_btns">
            @if ($data->status == 'confirmed')
            <button class="open_agree_order_modal orange_btn get_bigger_btn me-4 mb-4" data-bs-toggle="modal" data-bs-target="#agree_order_modal">同意訂單</button>
            @endif
            
            @if ($data->status == 'success' || $data->status == 'complete')
            @if ($data->is_return == 0)
                <button class="return_btn orange_btn get_bigger_btn me-4 mb-4">我要退貨</button>
            @endif
            <!-- <div class="modal fade" id="agree_order_modal" tabindex="-1" aria-labelledby="agree_order_modal_label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal_title" id="agree_order_modal_label">確定同意訂單?</h5>
                        </div>
                        <div class="modal-body">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="cancel_btn" data-bs-dismiss="modal">取消</button>
                            <button type="button" class="confirm_btn confirm_order_btn agree_order_btn" data-no="{{ $data->order_no }}" data-bs-dismiss="modal">確定</button>
                        </div>
                    </div>
                </div>
            </div> -->
                @if ($data->is_exchange == 0)
                <button class="orange_btn get_bigger_btn me-4 mb-4" data-bs-toggle="modal" data-bs-target="#exchange_modal">我要換貨</button>
                <div class="modal fade" id="exchange_modal" tabindex="-1" aria-labelledby="exchange_modal_label" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal_title" id="exchange_modal_label">確定進入換貨程序? 將有專人與您聯繫</h5>
                            </div>
                            <div class="modal-body">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="cancel_btn" data-bs-dismiss="modal">取消</button>
                                <button type="button" class="exchange_btn confirm_btn" data-no="{{ $data->order_no }}" data-bs-dismiss="modal">確定</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endif

            <button class="open_buy_again_modal orange_btn get_bigger_btn mb-4" data-no="{{ $data->order_no }}">再次購買</button>
            @if ($data->status == 'confirmed')
            <!-- 同意訂單modal -->
            <div class="modal fade" id="agree_order_modal" tabindex="-1" aria-labelledby="agree_order_modal_label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal_title" id="agree_order_modal_label">確定同意訂單?</h5>
                        </div>
                        <div class="modal-body">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="cancel_btn" data-bs-dismiss="modal">取消</button>
                            <button type="button" class="confirm_btn confirm_order_btn agree_order_btn" data-no="{{ $data->order_no }}" data-bs-dismiss="modal">確定</button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            <!-- 再次購買modal -->
            <div class="modal fade" id="buy_again_modal" tabindex="-1" aria-labelledby="buy_again_modal_label" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal_title" id="buy_again_modal_label">同樣商品不同時間下單，價格可能會有變動。<br>
                            已將此筆訂單之商品新增至購物車，請至購物車確認項目。</h5>
                        </div>
                        <div class="modal-body">
                        </div>
                        <div class="modal-footer">
                            <!-- <button type="button" class="cancel_btn" data-bs-dismiss="modal">取消</button> -->
                            <button type="button" class="confirm_btn buy_again_btn" data-bs-dismiss="modal">確定</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
    <div class="cart_order_detail_contaiiner">
        <div class="text-center">
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
        <div class="order_detail_title">
            <div class="no_date_wrap">
                <div class="no me-4">訂單編號 : {{ $data->order_no }}</div>
                <div class="date">訂購日期 : {{ date('Y/m/d', strtotime($data->created_at)) }}</div>
            </div>
            <div class="status_wrap">訂單狀態 :  {{ $data->status_show() }}</div>
        </div>
        <div class="order_detail_content">
            <div class="order_status_wrap">
                <div class="status_wrap">
                    付款狀態 : {{ $data->payment_status_show(false) }}
                </div>
                <div class="status_wrap">
                    物流狀態 : {{ $data->shipping_status_show() }}
                </div>
            </div>
            <div class="cart_check_table">
                <div class="cart_check_table_title row">
                    <div class="col-2 table_name return_action" style="display:none;">
                        <h5>{{ __('page.cart.return_product') }}?</h5>
                    </div>
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
                    <div class="col-2 table_name subtotal">
                        <h5>{{ __('page.cart.sub_total') }}</h5>
                    </div>
                </div>
                <div class="cart_check_table_items">
                    @foreach ($data->cart->items as $item)
                    <div class="cart_check_table_item row @if($item->is_return == 2) return_product @endif">
                        <div class="col-7 col-md-2 table_detail return_action" style="display:none;">
                            <input type="checkbox" name="return[]" class="return_product" value="{{ $item->id }}">
                        </div>
                        <div class="col-5 col-md-2 table_detail">
                            <img src="{{ env('APP_URL').Storage::url($item->cover) }}" style="height: 80px;">
                        </div>
                        <div class="col-7 col-md-4 table_detail" id="check_item_product_name">
                            <h6>
                                <a style="color:initial;" href="{{ route('products.detail', ['id'=>$item->product_id]) }}">
                                    {{ $item->name }}
                                </a>
                            </h6>
                        </div>
                        <div class="col-5 mobile_show"><h6>{{ __('page.cart.product_price') }}</h6></div>
                        <div class="col-7 col-md-2 table_detail my-4">
                            @if ($item->status == 'not_enough')
                            -
                            @elseif ($item->price_remark == '')
                                @if ($item->original_price != $item->price)
                                <h6>$ {{ number_format($item->price) }}/{{ $item->weight }}
                                    <br><small style="text-decoration: line-through !important; color:#999;">$ {{ number_format($item->original_price) }}/{{ $item->weight }}</small>
                                </h6>
                                @else
                                <h6>$ {{ number_format($item->price) }}/{{ $item->weight }}</h6>
                                @endif
                            @elseif ($item->price_remark == 'custom')
                            <h6 class="text-danger">{{ __('page.cart.custom_price') }}</h6>
                            @elseif ($item->price_remark == 'exceed')
                            <h6 class="text-danger">{{ __('page.cart.over_weight') }}</h6>
                            @endif
                        </div>
                        <div class="col-5 col-md-2 table_detail">
                            @if ($item->status == 'not_enough')
                            <h6 class="text-danger">庫存不足</h6>
                            @else
                            <h6>{{ $item->quantity }}{{ $item->weight }}</h6>
                            @endif
                        </div>
                        <div class="col-7 col-md-2 table_detail subtotal">
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
                    <div class="cart_check_table_item row subtotal" style="font-size: 18px; font-weight: 500;">
                        <div class="col-6 col-md-9 text-end">
                            {{  __('page.cart.sub_total') }}:
                        </div>
                        <div class="col-6 col-md-3 col-offset-md-1 text-start">
                            NT$ {{ number_format($data->cart->price) }}
                        </div>
                        <div class="col-6 col-md-9 text-end">
                            {{ __('page.cart.tax') }}:
                        </div>
                        <div class="col-6 col-md-3 col-offset-md-1 text-start">
                            NT$ {{ number_format(round($data->cart->price * 0.05)) }}
                        </div>
                        <div class="col-6 col-md-9 text-end">
                            {{ __('page.cart.total') }}:
                        </div>
                        <div class="col-6 col-md-3 col-offset-md-1 text-start">
                            NT$ {{ number_format($data->cart->price + round($data->cart->price * 0.05)) }}
                        </div>
                    </div>
                    <div class="cart_check_table_item row return_action" style="display:none;">
                        <div class="col-6 col-md-12 text-end">
                            <button data-no="{{ $data->order_no }}" class="return_confirm_btn orange_btn get_bigger_btn me-4 mb-4">確認退貨</button>
                            <button class="return_cancel_btn orange_btn get_bigger_btn me-4 mb-4" style="color: #333;background:linear-gradient(132.81deg, #D2D2D2 -12.23%, #C3C3C3 190.2%)">取消</button>
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
                <button type="button" class="orange_btn get_bigger_btn me-5" onclick="window.open('{{ route('order.quotation', ['order_no'=>$data->order_no]) }}');">報價單下載</button>
                <button type="button" class="back_order_list_btn orange_btn get_bigger_btn">回訂單列表</button>
            </div>
        </section>
    </div>
</div>