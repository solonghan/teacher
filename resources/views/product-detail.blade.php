@extends('components.master')
@section('title') {{ $title }} @endsection
@section('description') {{ $description }} @endsection
@section('css')
<style>
    .active{
        color: #1173BA;
    }
</style>
@endsection
@section('content')
<div class="container">
    <div class="products_page_container">
        <div class="filter_bar">
            <div class="product_classify">
                <h5 class="mb-4">{{ __('page.product.industry_classify') }}</h5>
                <div class="classify_wrap">
                    @foreach ($category_list as $category)
                    <div class="main_classfiy">
                        <div class="input_wrap">
                            <label for="" class="@if($category->selected) active @endif">{{ $category->title }}</label>
                        </div>
                        <i class="fa-solid fa-angle-down open_minor_classify @if($category->selected) rotate @endif"></i>
                    </div>
                    <div class="minor_classify" @if($category->selected) style="display:block;" @endif>
                        @foreach ($category->classify as $index => $classify)
                        <div class="input_wrap">
                            <label class="product_detail_router @if($classify->selected) active @endif" onclick="location.href = '{{ route('products') }}?c={{ $classify->id }}'" for="">{{ $classify->title }}</label>
                        </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="product_classify">
                <h5 class="mb-4">{{ __('page.product.brand_classify') }}</h5>
                @foreach ($brand_list as $brand)
                <div class="classify_wrap">
                    <div class="main_classfiy">
                        <div class="input_wrap">
                            <label class="product_detail_router @if($brand->selected) active @endif" onclick="location.href = '{{ route('products') }}?b={{ $brand->id }}'" for="">{{ $brand->name }}</label> 
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="tag_container product_classify">
                <h5 class="mb-3">{{ __('page.product.functional_classify') }}</h5>
                <div class="tag_link_container mb-3">
                    @foreach ($function_list as $func)
                    <button class="tag_link product_tag_link @if($func->selected) active @endif" onclick="location.href = '{{ route('products') }}?f={{ $func->id }}'">{{ $func->title }}</button>
                    @endforeach
                </div>
            </div>
        </div>
                    
        <div class="product_detail_container">
            <div class="main_top">
                <div class="img_container">
                    <div class="img_main">
                        <img src="{{ env('APP_URL').Storage::url($data->cover) }}" alt="{{ $data->name }}">
                    </div>
                    <div class="img_minor">
                        <div class="row">
                            <div class="owl-carousel owl-theme">
                                <div class="item">
                                    <img src="{{ env('APP_URL').Storage::url($data->cover) }}" alt="{{ $data->name }}">
                                </div>
                                @foreach ($data->pics as $pic)
                                <div class="item">
                                    <img src="{{ env('APP_URL').Storage::url($pic->path) }}" alt="{{ $data->name }}">
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content_top_container">
                    <h4>{{ $data->name }}</h4>
                    <p>{{ $data->summary }}</p>
                    @if (Auth::check())
                    <div class="weight_rank_wrap">
                        <table style="border:0;" border="0">
                            @foreach ($product_range as $price)
                            @if ($price['range_start'] == 0 || $price['range_start'] == '') @continue @endif
                            <tr>
                                <td>{{ $price['range_start'] }}~{{ $price['range_end'] }}{{ $data->weight }}</td>
                                @php
                                    $show_original = false;
                                @endphp
                                @if (isset($price['price_new']) && $price['price_new'] < $price['price'])
                                @php
                                    $show_original = true;
                                @endphp
                                <td class="origin_price">${{ number_format($price['price']) }} /{{ $data->weight }}</td>
                                @endif
                                <td class="special_price" style="@if (!$show_original) color: #000; @endif">
                                    @if (isset($price['price_new']))
                                    ${{ number_format($price['price_new']) }} /{{ $data->weight }}
                                    @else
                                    ${{ number_format($price['price']) }} /{{ $data->weight }}
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                    
                    <p class="rank_rule">{{ __('page.product.outbound_hint') }}</p>
                    <div class="amount_input_wrap">
                        <input type="number" step="{{ $data->unit }}" value="{{ $data->unit }}" data-weight="{{ $data->weight }}" data-package="{{ $data->package }}" data-multiple="{{ $data->unit }}" id="product_weight_input">
                        <span>{{ $data->weight }}</span>
                    </div>
                    @else
                    <span style="color: #C66;">
                        {{ __('page.product.login_in_to_view_price') }}
                    </span>
                    @endif
                    <!-- 以下倍數購買modal -->
                    <div class="modal fade" id="multiple_hint_modal" tabindex="-1" aria-labelledby="multiple_hint_modal_label" aria-hidden="true">\
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal_title" id="multiple_hint_modal_label"></h5>
                                </div>
                                <div class="modal-body">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="confirm_btn" data-bs-dismiss="modal">{{ __('page.product.confirm') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 以上倍數購買modal -->
                    <div class="stock_pack_wrap">
                        <div class="stock">{{ __('page.quota') }} : <span class="blue_text">{{ number_format($data->quota) }}{{ $data->weight }}</span></div>
                        <div class="pack">{{ __('page.package') }} : <span class="blue_text">{{ $data->unit }}{{ $data->weight }}/{{ $data->package }}</span></div>
                    </div>
                    @if(Auth::check())
                    <div class="total_price">
                        <h4></h4>
                        <h6>{{ __('page.product.amount_outbound') }}</h6>
                    </div>
                    <div class="content_btns">
                        <button class="add_shopping_car_btn content_btn get_bigger_btn" onclick="add_cart()">{{ __('page.product.add_cart') }}</button>
                        <button class="buy_btn content_btn get_bigger_btn" onclick="add_cart(true)">{{ __('page.product.go_buy') }}</button>
                    </div>
                    @endif
                </div>
            </div>
            <div class="main_bottom">
                <div class="detail_tab">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description-tab-pane" type="button" role="tab" aria-controls="description-tab-pane" aria-selected="true">{{ __('page.product.description') }}</a>
                        </li>
                        <li class="nav-item plz_login">
                            <a class="nav-link" id="standard-tab" data-bs-toggle="tab" data-bs-target="#standard-tab-pane" type="button" role="tab" aria-controls="standard-tab-pane" aria-selected="false">{{ __('page.product.spec') }}</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="description-tab-pane" role="tabpanel" aria-labelledby="description-tab" tabindex="0">
                            <div class="description_wrap">
                                <div>
                                    {!! $data->des !!}
                                </div>
                                <ul>
                                    <li>{{ __('page.product.product_no') }} : {{ $data->no }}</li>
                                    <li>{{ __('page.product.brand_classify') }} : 
                                        <span>
                                            @foreach ($data->brand as $index => $brand)
                                            @if ($index > 0) 、 @endif
                                            <a href="{{ route('products') }}?b={{ $brand->id }}">{{ $brand->name }}</a>
                                            @endforeach
                                        </span>
                                    </li>
                                    <li>{{ __('page.product.industry_classify') }} : 
                                        <span>
                                        @foreach ($data->classify as $index => $classify)
                                        @if ($index > 0) 、 @endif
                                        <a href="{{ route('products') }}?c={{ $classify->id }}">{{ $classify->title }}</a>
                                        @endforeach
                                        </span>
                                    </li>
                                    <li>{{ __('page.product.functional_classify') }} : 
                                        <span>
                                            @foreach ($data->functional as $index => $func)
                                            @if ($index > 0) 、 @endif
                                            <a href="{{ route('products') }}?f={{ $func->id }}">{{ $func->title }}</a>
                                            @endforeach
                                        </span>
                                    </li>
                                    <li>{{ __('page.product.tags') }} : 
                                        <span>
                                            @foreach ($data->tags as $index => $tag)
                                            @if ($index > 0) 、 @endif
                                            <a href="#">{{ $tag->title }}</a>
                                            @endforeach
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="standard-tab-pane" role="tabpanel" aria-labelledby="standard-tab" tabindex="0">
                            <div class="standard_wrap">
                                <ul>
                                    @foreach ($data->files as $file)
                                    <li><h6>{{ $file->realname }}</h6><span><a href="{{ env('APP_URL').'/file/download/'.$file->code }}">下載</a> </span></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <div class="modal fade" id="please_login_modal" tabindex="-1" aria-labelledby="please_login_label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal_title" id="please_login_label">{{ __('page.product.plz_login') }}</h5>
                                    </div>
                                    <div class="modal-body">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="confirm_btn" data-bs-dismiss="modal" onclick="location.href = '{{ route('login') }}'">{{ __('page.product.confirm') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
            
@endsection
@section('script')
<script>
    @if (!Auth::check())
    $('#standard-tab').addClass('disabled')
    $('.plz_login').click(function () {
        $('#please_login_modal').modal('show');
    })
    @endif
    $('.img_minor .owl-carousel').owlCarousel({
        // loop: true,
        margin: 15,
        items: 3,
        nav: true,
        dots: false
    })
    $('.owl-carousel .item img').click(function () {
        console.log($(this).attr('src'))
        $('.img_main img').attr('src', $(this).attr('src'))
    })
    //價格級距範圍
    const priceRange = {{ json_encode($range['range']) }}
    const priceValue = {{ json_encode($range['price']) }}
    const max = '{{ $range['max'] }}'
    const quota = '{{ $data->quota }}'
    let calc_price = 0
    let defaultPrice = {{ $default_price }}
    //是否強制專人詢價
    let custom_force = @if ($data->order_type == 'custom') true @else false @endif

    checkMultiple($('#product_weight_input'))
    $('#product_weight_input').on('blur, change', function () {
        // console.log($(this).val());
        checkMultiple($(this))
    })
    //專人詢價
    function custom_price(val){
        $('.total_price h6').show()
        $('.total_price h4').hide()
        $('.buy_btn').hide()
        $('.add_shopping_car_btn').text('{{ __('page.product.custom_price') }}')
        @if ($data->custom_hint != '')
        $('.total_price h6').html('{{ $data->custom_hint }}');
        @else
            if (Number(val) > Number(quota)) {
                $('.total_price h6').html('{{ __('page.product.amount_not_enougth') }}');
            }else{
                $('.total_price h6').html('{{ __('page.product.amount_outbound') }}');
            }
        @endif
    }
    //價格確認
    function checkMultiple(item) {
        let val = Number($(item).val())
        let multiple = Number($(item).data('multiple'))
        let package = $(item).data('package')
        let weight = $(item).data('weight')
        
        if (custom_force || Number($(item).val()) > Number(max) || Number($(item).val()) > Number(quota)) {
            custom_price($(item).val());
            return
        }
        $('.add_shopping_car_btn').text('{{ __('page.product.add_cart') }}');
        $('.buy_btn').show()
        $('.total_price h4').show()
        $('.total_price h6').hide()

        if (val < multiple || val % multiple !== 0) {
            val = Math.floor( val / multiple ) * multiple;
            $(item).val( val )
            
            let UnitPrice = defaultPrice
            for (let i = 0; i < priceRange.length; i++) {
                if (val >= Number(priceRange[i])) UnitPrice = priceValue[i]
            }
            calc_price = UnitPrice * val;
            
            $('.total_price h4').text(`NT$ ${calc_price.toLocaleString('en-US')}`)
            @if (Lang::locale() == 'tw')
            $('#multiple_hint_modal_label').text(`此產品需以${multiple}${weight}/${package}為單位出售，請以${multiple}的倍數購買`)
            @else
            $('#multiple_hint_modal_label').text(`This product needs to be purchased in multiples of ${multiple}(${weight}/${package})`)
            @endif
            $('#multiple_hint_modal').modal('show')
            return
        }
        let UnitPrice = defaultPrice
        for (let i = 0; i < priceRange.length; i++) {
            if (val >= Number(priceRange[i])) UnitPrice = priceValue[i]
        }
        calc_price = UnitPrice * val;
        $('.total_price h4').text(`NT$ ${calc_price.toLocaleString('en-US')}`)
        
    }
    
    function add_cart(goto = false) {
        @if (!Auth::check())
        alert('{{ __('page.product.plz_login') }}');
        @else
        $.ajax({
            type: "POST",
            url: '{{ route('cart.add') }}',
            data: {
                _token: "{{ csrf_token() }}",
                product_id: {{ $data->id }},
                quantity: $("#product_weight_input").val(),
                spec: '',
                uid: '{{ Auth::user()->id }}'
            },
            dataType: "json",
            success: function(data){
                if (data.status){
                    $(".cart_badge").html(data.count);
                    if (goto) 
                        location.href = '{{ route('cart') }}';      
                    else 
                        alert('{{ __('page.cart.add_cart_success') }}');
                }
            },
            failure: function(errMsg) {}
        });
        @endif
    }
</script>
@endsection