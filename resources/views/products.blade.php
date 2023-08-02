@extends('components.master')
@section('title') {{ $title }} @endsection
@section('description') {{ $description }} @endsection
@section('css')

@endsection
@section('content')
<div class="container">
    <div class="products_page_container">
        <div class="filter_bar">
            <!-- <div class="product_search_wrap">
                <div class="product_search_input_wrap">
                    <input class="product_search_input search_input side" type="text">
                    <div class="clear_filter_btn">
                        {{ __('page.clear_all_filter') }}
                    </div>
                </div>
                <button class="product_btn search_product_btn" data-position="side">{{ __('page.search') }}</button>
            </div> -->
            <div class="product_filter_classify">
                <h5 class="mb-4">{{ __('page.industry_classify') }}</h5>
                @foreach ($category_list as $category)
                <div class="classify_wrap">
                    <div class="main_classfiy industry_classify">
                        <div class="input_wrap">
                            <label for="category_{{ $category->id }}">
                                <input class="category" type="checkbox" id="category_{{ $category->id }}" data-id="{{ $category->id }}">
                                {{ $category->title }}
                            </label>
                        </div>
                        <i class="fa-solid fa-angle-down open_minor_classify"></i>
                    </div>
                    <div class="minor_classify category_{{ $category->id }}">
                        @foreach ($category->classify as $index => $classify)
                        <div class="input_wrap">
                            <label for="classify_{{ $classify->id }}">
                                <input class="classify" type="checkbox" id="classify_{{ $classify->id }}" data-id="{{$classify->id}}" data-category="{{ $category->id }}">
                                {{ $classify->title }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
                <div class="clear_filter_btn" data-type="classify">
                    {{ __('page.clear_filter') }}
                </div>
            </div>
            <div class="product_filter_classify all_brand">
                <h5 class="mb-4">{{ __('page.brand') }}</h5>
                @foreach ($brand_list as $brand)
                <div class="classify_wrap">
                    <div class="main_classfiy">
                        <div class="input_wrap">
                            <label for="brand_{{ $brand->id }}">
                                <input class="brand filter" data-type="brand" type="checkbox" name="brand" id="brand_{{ $brand->id }}" data-id="{{ $brand->id }}">
                                {{ $brand->name }}
                            </label>
                        </div>
                    </div>
                </div>
                @endforeach
                <div class="clear_filter_btn" data-type="brand">
                {{ __('page.clear_filter') }}
                </div>
            </div>
            <div class="tag_container product_filter_classify all_functionals">
                <h5 class="mb-3">{{ __('page.functional') }}</h5>
                <div class="tag_link_container mb-3">
                    @foreach ($function_list as $func)
                    <button class="tag_link product_tag_link functional" id="func_{{ $func->id }}" data-id="{{ $func->id }}">{{ $func->title }}</button>
                    @endforeach
                </div>
                <div class="clear_filter_btn" data-type="func">
                {{ __('page.clear_filter') }}
                </div>
            </div>
        </div>
        <div class="product_list_container">
            <div class="filter_search_bar">
                <div class="bar_top">
                    <div class="row w-100 m-0" style="height: auto;">
                        <div class="col-lg-3 col-md-6 col-sm-6 col-6 mb-3">
                            <div class="bar_top_inputs_container">
                                <div class="bar_top_inputs" id="industry_select">
                                    <div class="main_filter">
                                        <div class="industry_select_title">{{ __('page.industry_classify') }}</div>
                                        <span class="filter_amount" style="display:none;"><div>0</div></span>
                                        <i class="fa-solid fa-angle-down open_minor_filter"></i>
                                    </div> 
                                    <div class="minor_filter">
                                        <div class="classifys">
                                            @foreach ($category_list as $category)
                                            <div class="top_filter">
                                                <div class="main_classfiy category">
                                                    <div class="input_wrap">
                                                        <label for="top_category_{{ $category->id }}">
                                                            <input class="category" type="checkbox" id="top_category_{{ $category->id }}" data-id="{{ $category->id }}">
                                                            {{ $category->title }}
                                                        </label>
                                                    </div>
                                                    <i class="fa-solid fa-angle-down open_minor_classify"></i>
                                                </div>
                                                <div class="minor_classify classify category_{{ $category->id }}">
                                                    @foreach ($category->classify as $classify)
                                                    <div class="input_wrap">
                                                        <label for="top_classify_{{ $classify->id }}">
                                                            <input class="classify" type="checkbox" id="top_classify_{{ $classify->id }}" data-id="{{ $classify->id }}" data-category="{{ $category->id }}">
                                                            {{ $classify->title }}
                                                        </label>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        
                                        <div class="filter_btns">
                                            <button class="product_btn clear_filter_btn" data-type="classify">{{ __('page.clear_filter') }}</button>
                                            <!-- <button class="product_btn" id="product_filter_save_btn">儲存</button> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6 col-6 mb-3">
                            <div class="bar_top_inputs_container">
                                <div class="bar_top_inputs" id="brand_select">
                                    <div class="main_filter">
                                        <div class="industry_select_title">{{ __('page.brand') }}</div>
                                        <span class="filter_amount" style="display:none;"><div>0</div></span>
                                        <i class="fa-solid fa-angle-down open_minor_filter"></i>
                                    </div>
                                    <div class="minor_filter">
                                        <div class="classifys">
                                            @foreach ($brand_list as $brand)
                                            <div class="top_filter">
                                                <div class="main_classfiy">
                                                    <div class="input_wrap">
                                                        <label for="top_brand_{{ $brand->id }}">
                                                            <input class="brand" type="checkbox" id="top_brand_{{ $brand->id }}" data-id="{{ $brand->id }}">
                                                            {{ $brand->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="filter_btns">
                                            <button class="product_btn clear_filter_btn" data-type="brand">{{ __('page.clear_filter') }}</button>
                                            <!-- <button class="product_btn" id="product_filter_save_btn">儲存</button> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-10 col-sm-10 col-9 mb-3">
                            <div class="bar_top_inputs_container">
                                <input class="bar_top_input search_input top" type="text">
                                <div class="clear_filter_btn">
                                {{ __('page.clear_all_filter') }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-3 mb-3">
                            <div class="bar_top_inputs_container">
                                <button class="product_btn search_product_btn" data-position='top'>{{ __('page.search') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="bar_mid">
                    <h5>已篩選條件</h5>
                    <div class="filter_tag_wrap">
                        <div class="filter_title">產業分類: </div>
                        <div class="filter_tags">
                            <div class="filter_tag tag_cancel" id="test1">無毒環保可塑劑<i class="fa-solid fa-xmark"></i></div>
                        </div>
                    </div>
                    <div class="filter_tag_wrap">
                        <div class="filter_title">品牌: </div>
                        <div class="filter_tags">
                            <div class="filter_tag brand_cancel" id="brand1">Eastman<i class="fa-solid fa-xmark"></i></div>
                        </div>
                    </div>
                </div> -->
            </div>
            <div class="bar_bottom">
                <i class="fa-solid fa-border-all active" id="row_version"></i>
                <i class="fa-solid fa-list-ul" id="col_version"></i>
            </div>
            <div class="product_list_row">
                <div class="product_none">
                    <h4>沒有搜尋到相關產品</h4>
                </div>
                <div class="row" id="grid_content"></div>
            </div>
            <div class="product_list_col" id="list_content"></div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script>
let classifyArr = []
let brandArr = []
let funcArr = []
let searchStr = ''
$(document).ready(function(){
    filterControlWhenLoad()

    $('#row_version').click(function() {
        $(this).addClass('active')
        $('#col_version').removeClass('active')
        $('.product_list_row').show()
        $('.product_list_col').hide()
    })
    $('#col_version').click(function() {
        $(this).addClass('active')
        $('#row_version').removeClass('active')
        $('.product_list_row').hide()
        $('.product_list_col').show()
    })

    $('.search_product_btn').on('click', function () {
        let position = $(this).data('position');
        searchProductKeyword($(".search_input."+position).val());
    });
    $('.search_input').on('keyup', function (e) {
        let keyCode = e.witch?e.witch:e.keyCode;
        if (keyCode == '13') searchProductKeyword($(this).val());
    });
    $('.category').on('change', function () {
        let category_id = $(this).data('id');
        if ($(this).is(":checked")){
            $('#category_'+category_id).prop('checked', true);
            $('#top_category_'+category_id).prop('checked', true);
            $('.category_'+category_id).find('.classify').each(function (index, element) {
                $(this).prop('checked', true);
            });
            $('.top_category_'+category_id).find('.classify').each(function (index, element) {
                $(this).prop('checked', true);
            });
        }else{
            $('#category_'+category_id).prop('checked', false);
            $('#top_category_'+category_id).prop('checked', false);
            $('.category_'+category_id).find('.classify').each(function (index, element) {
                $(this).prop('checked', false);
            });
            $('.top_category_'+category_id).find('.classify').each(function (index, element) {
                $(this).prop('checked', false);
            });
        }
        refreshQuery();
    });
    $('.classify').on('change', function () {
        let category_id = $(this).data('category');
        let classify_id = $(this).data('id');
        if ($(this).is(":checked")){
            $('#classify_'+classify_id).prop('checked', true);
            $('#top_classify_'+classify_id).prop('checked', true);

            let all_checked = true
            $(this).closest('.category_'+category_id).find('.classify').each(function (index, element) {
                if (!$(this).is(":checked")) all_checked = false
            });
            $('#category_'+category_id).prop('checked', all_checked);
            $('#top_category_'+category_id).prop('checked', all_checked);
        }else{
            $('#classify_'+classify_id).prop('checked', false);
            $('#top_classify_'+classify_id).prop('checked', false);
            $('#category_'+category_id).prop('checked', false);
            $('#top_category_'+category_id).prop('checked', false);
        }
        refreshQuery();
    });
    $('.brand').on('change', function () {
        let brand_id = $(this).data('id');
        if ($(this).is(":checked")){
            $('#brand_'+brand_id).prop('checked', true);
            $('#top_brand_'+brand_id).prop('checked', true);
        }else{
            $('#brand_'+brand_id).prop('checked', false);
            $('#top_brand_'+brand_id).prop('checked', false);
        }
        refreshQuery();
    });
    $('.functional').on('click', function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active');
        }else{
            $(this).addClass('active');
        }
        refreshQuery();
    });

    //clear
    $('.clear_filter_btn').on('click', function () {
        let type = $(this).data('type');
        if (type == '' || type == undefined) type = 'all';
        if (type == 'classify' || type == 'all') {
            $(".product_filter_classify .main_classfiy").find('.category').each(function (index, elem) { 
                let category_id = $(this).data('id');
                $("#category_"+category_id).prop('checked', false);
                $("#top_category_"+category_id).prop('checked', false);

                $(".category_"+category_id).find(".classify").each(function (index, elem) { 
                    $(this).prop('checked', false);
                });
                $(".top_category_"+category_id).find(".classify").each(function (index, elem) { 
                    $(this).prop('checked', false);
                });
            });
        }
        if (type == 'brand' || type == 'all') {
            $('.all_brand').find('.brand').each(function (index, elem) { 
                let brand_id = $(this).data('id');
                $("#brand_"+brand_id).prop('checked', false);
                $("#top_brand_"+brand_id).prop('checked', false);
            });;
        }

        if (type == 'func' || type == 'all') {
            $('.all_functionals').find('.functional').each(function (index, elem) { 
                $(this).removeClass('active');
            });;
        }
        
        if (type == 'all') {
            searchProductKeyword('');
        }
        refreshQuery();
    });

    //top filter bar
    $('.main_filter').click(function() {
        $(this).next().slideToggle('fast')
        $(this).next().css('display','flex')
        $(this).find('.open_minor_filter').toggleClass('rotate')
    })
});


//有query 進入搜尋頁面控制篩選checkbox
function filterControlWhenLoad() {
    let classify = geturlQuery('c')
    let brand = geturlQuery('b')
    let func = geturlQuery('f')
    let search = geturlQuery('s')
    let filterURL = new URL(location.href)
    
    if (classify != '' && classify != null) {
        $.each(classify.split(';'), function (i, elem) { 
            if (elem != '') {
                let _this = null;
                let _this_top = null;
                if (elem.substr(0, 1) == 'c') {
                    if($("#category_"+elem.replace('c', '')).length > 0) {
                        $("#category_"+elem.replace('c', '')).prop('checked', true);
                        $("#top_category_"+elem.replace('c', '')).prop('checked', true);
                        _this = $("#category_"+elem.replace('c', ''));
                        _this_top = $("#top_category_"+elem.replace('c', ''));
                    }
                }else{
                    if($("#classify_"+elem).length > 0) {
                        $("#classify_"+elem).prop('checked', true);
                        $("#top_classify_"+elem).prop('checked', true);
                        _this = $("#classify_"+elem);
                        _this_top = $("#top_classify_"+elem);
                    }
                }
                if (_this != null){
                    _this.closest('.classify_wrap').find('.open_minor_classify').addClass('rotate');
                    _this.closest('.classify_wrap').find('.minor_classify').show();
                    _this_top.closest('.top_filter').find('.open_minor_classify').addClass('rotate');
                    _this_top.closest('.top_filter').find('.minor_classify').show();
                }
            }
        });
    }

    if (brand != '' && brand != null) {
        $.each(brand.split(';'), function (i, elem) { 
            if (elem != '' && $("#brand_"+elem).length > 0) {
                $("#brand_"+elem).prop('checked', true);
                $("#top_brand_"+elem).prop('checked', true);
            }
        });
    }
    
    if (func != '' && func != null) {
        $.each(func.split(';'), function (i, elem) { 
            if (elem != '' && $("#func_"+elem).length > 0) {
                $("#func_"+elem).addClass('active');
            }
        });
    }
    if (search != '' && search != null) searchProductKeyword(search);

    refreshQuery();
}

function searchProductKeyword(str){
    $('.search_input').val(str);
    searchStr = str;
    refreshQuery();
}

//改變query不要重新刷新頁面
function refreshQuery() {
    window.history.pushState({}, document.title, window.location.pathname);
    let url = window.location.pathname;

    //classify
    classifyArr = [];
    let c = '';
    $(".product_filter_classify .main_classfiy").find('.category').each(function (index, elem) { 
        if ($(this).is(":checked")) {
            c += 'c'+$(this).data('id')+";";

            $(".category_"+$(this).data('id')).find('.classify').each(function (index, elem) { 
                if (classifyArr.indexOf($(this).data('id')) < 0) classifyArr.push($(this).data('id'));    
            });
        }
    });
    $(".product_filter_classify .minor_classify").find('.classify').each(function (index, elem) { 
        if ($(this).is(":checked")) {
            c += $(this).data('id')+";";
            if (classifyArr.indexOf($(this).data('id')) < 0) classifyArr.push($(this).data('id'));
        }
    });
    if (classifyArr.length > 0) {
        $("#industry_select .filter_amount").show().html(classifyArr.length);
    }else{
        $("#industry_select .filter_amount").hide().html();
    }

    //brand
    brandArr = [];
    let b = '';
    $(".all_brand").find('.brand').each(function (index, elem) { 
        if ($(this).is(":checked")) {
            b += $(this).data('id')+";";
            brandArr.push($(this).data('id'));
        }
    });
    if (brandArr.length > 0) {
        $("#brand_select .filter_amount").show().html(brandArr.length);
    }else{
        $("#brand_select .filter_amount").hide().html();
    }

    //functional
    funcArr = [];
    let f = '';
    $(".all_functionals").find('.functional').each(function (index, elem) { 
        if ($(this).hasClass("active")) {
            f += $(this).data('id')+";";
            funcArr.push($(this).data('id'));
        }
    });

    //search string
    let s = '';
    if (searchStr != '') s = searchStr;

    url += "?c="+c+"&b="+b+"&f="+f+"&s="+s;
    history.pushState({newUrl: url}, '', url)
    load_product_data();
}

function load_product_data(){
    var formData = new FormData();
    formData.append('classify', classifyArr);
    formData.append('brand', brandArr);
    formData.append('function', funcArr);
    formData.append('search', searchStr);
    fetch(document.querySelector('base').href+document.querySelector('html').lang+'/products/data', {
        method: "POST",
        body: formData
    })
    .then((response) => {
        return response.json();
    })
    .then((data) => {
        if (data.status) {
            $("#list_content").html(data.list);
            $("#grid_content").html(data.grid);
        }
    })
    .catch((error) => {
        
    })
}
</script>
@endsection