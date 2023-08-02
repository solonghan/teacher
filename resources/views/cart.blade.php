@extends('components.master')
@section('title') {{ $title }} @endsection
@section('description') {{ $description }} @endsection
@section('css')

@endsection
@section('content')
@include('cart_step')
            <div class="container">
                <div class="cart_one_container">
                    <div class="cart_one_table_title row">
                        <div class="col-1 table_name">
                            </div>
                            <div class="col-2 table_name">
                            <h5>{{ __('page.cart.product_cover') }}</h5>
                        </div>
                        <div class="col-3 table_name" id="title_product_name">
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
                    <div class="cart_one_table_items">

                        @foreach ($cart->items as $item)
                        <div class="cart_one_table_item row" id="product_{{ $item->product_id }}" data-remark="{{ $item->price_remark }}">
                            <div class="col-1 table_detail">
                                <i class="fa-solid fa-trash-can" data-id="{{ $item->product_id }}"></i>
                            </div>
                            <div class="col-4 col-md-2 table_detail">
                                <img src="{{ env('APP_URL').Storage::url($item->cover) }}" style="height:80px;">
                            </div>
                            <div class="col-7 col-md-3 table_detail" id="item_product_name">
                                <h6>{{ $item->name }}</h6>
                            </div>
                            <div class="col-5 mobile_show my-4">
                                <h6>單價：</h6>
                            </div>
                            <!-- <div class="col-4 mobile_show my-4">
                                <h6 class="unit_price" data-unitprice="{{ $item->price }}">
                                    <span style="display:none;" class="normal_price">$ {{ number_format($item->price) }}/{{ $item->weight }}</span>
                                    <span style="display:none;" class="text-danger over_quantity">{{ __('page.cart.over_weight'); }}</span>
                                    <span style="display:none;" class="text-danger custom_price">{{ __('page.cart.custom_price'); }}</span>
                                </h6>
                            </div> -->
                            <div class="col-7 col-md-2 table_detail my-4">
                                <h6 class="unit_price" data-unitprice="{{ $item->price }}">
                                    <span style="display:none;" class="normal_price">$ {{ number_format($item->price) }}/{{ $item->weight }}</span>
                                    <span style="display:none;" class="text-danger over_quantity">{{ __('page.cart.over_weight'); }}</span>
                                    <span style="display:none;" class="text-danger custom_price">{{ __('page.cart.custom_price'); }}</span>
                                </h6>
                            </div>
                            <div class="col-1 mobile_show">
                            </div>
                            <div class="col-4 col-md-2 table_detail">
                                <div class="amount_input_wrap">
                                    <input data-id="{{ $item->product_id }}" type="number" class="amount_input" data-weight="{{ $item->weight }}" data-unit="{{ $item->product->unit }}" step="{{ $item->product->unit }}" min="{{ $item->product->unit }}" value="{{ $item->quantity }}">
                                </div>
                            </div>
                            <div class="col-7 col-md-2 table_detail sub_total">
                                <h6 style="display:none;" class="sub_total_text normal_price">$ {{ number_format($item->price) }}</h6>
                                <span style="display:none;" class="text-danger over_quantity">{{ __('page.cart.over_weight'); }}</span>
                                <span style="display:none;" class="text-danger custom_price">{{ __('page.cart.custom_price'); }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="cart_total_wrap">
                        <h6 class="total_text">{{ __('page.cart.total') }}:</h6>
                        <h6 class="text-danger all_total_price"></h6>
                    </div>
                    <div class="check_out_wrap">
                        <button class="check_out_btn orange_btn get_bigger_btn">{{ __('page.cart.goto_checkout') }}</button>
                    </div>
                </div>
            </div>
@endsection
@section('script')

<script>
    let cart_remark = '{{ $cart->price_remark }}';
    
    $(document).ready(function(){
        $('.step_one').css('color', '#1173BA')
        // 刪除商品
        $('.table_detail .fa-trash-can').click(function () {
            $(this).parents('.cart_one_table_item').remove()
            productAmountControl()
            let product_id = $(this).data('id');

            $.ajax({
                type: "POST",
                url: '{{ route('cart.del') }}',
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: product_id,
                    spec: '',
                    uid: '{{ Auth::user()->id }}'
                },
                dataType: "json",
                success: function(data){
                    if (data.status){
                        $(".cart_badge").html(data.count);
                        cart_remark = data.cart.price_remark;
                    }
                },
                failure: function(errMsg) {}
            });
        })
        // 控制總計 
        productAmountControl()
        function productAmountControl() {
            $(".check_out_btn").show();
            let productArr = $('.cart_one_table_items').children('.cart_one_table_item').toArray()
            console.log(productArr)
            if (productArr.length === 0) {
                $('.all_total_price').text(`$${0}`)
                $(".check_out_btn").hide();
                return
            }    
            let total = 0
            productArr.forEach(item => {
                const unitPrice = Number($(item).find('.unit_price').data('unitprice'))
                const amount = Number($(item).find('.amount_input').val())
                $(item).find('.sub_total_text').text(`$${(unitPrice * amount).toLocaleString('en-US')}`)
                total += unitPrice * amount
                
                if ($(item).data('remark') == 'custom') {
                    $(item).find('.normal_price').hide();
                    $(item).find('.over_quantity').hide();
                    $(item).find('.custom_price').show();
                }else if ($(item).data('remark') == 'exceed') {
                    $(item).find('.normal_price').hide();
                    $(item).find('.over_quantity').show();
                    $(item).find('.custom_price').hide();
                }else if ($(item).data('remark') == '') {
                    $(item).find('.normal_price').show();
                    $(item).find('.over_quantity').hide();
                    $(item).find('.custom_price').hide();
                }
            })

            if (total.toString() === 'NaN' || cart_remark != '') {
                $('.all_total_price').text("{{ __('page.cart.bargain') }}")
            } else {
                $('.all_total_price').text(`$${total.toLocaleString('en-US')}`)
            }
        }
        $('.amount_input').on('blur, change', function () {
            update_product_price($(this));
        });

        $(".check_out_btn").on('click', function () {
            location.href = '{{ route('cart.step2') }}';
        });

        function update_product_price(_this){
            let unit = _this.data('unit');
            let weight = _this.data('weight');
            _this.val(Math.floor(_this.val() / unit) * unit);
            let product_id = _this.data('id');
            let quantity = _this.val()
            $.ajax({
                type: "POST",
                url: '{{ route('cart.update') }}',
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: product_id,
                    quantity: quantity,
                    spec: '',
                    uid: '{{ Auth::user()->id }}'
                },
                dataType: "json",
                success: function(data){
                    if (data.status){
                        let price = data.price.price;
                        cart_remark = data.cart.price_remark;
                        $.each(data.cart.items, function (i, item) { 
                            $("#product_"+item['product_id']).data('remark', item['price_remark']);
                        });
                        $("#product_"+product_id+" .unit_price").data('unitprice', price);
                        $("#product_"+product_id+" .unit_price .normal_price").html(`$ ${price.toLocaleString('en-US')}/${weight}`);
                        productAmountControl()            
                    }
                },
                failure: function(errMsg) {}
            });
            
        }
    })

</script>
@endsection