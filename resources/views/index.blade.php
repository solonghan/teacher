@extends('components.master')
@section('title') {{ $title }} @endsection
@section('description') {{ $description }} @endsection
@section('css')

@endsection
@section('content')
<!-- banner輪播 -->
<div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-indicators">
        @foreach ($carousel as $index => $c)
        <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="{{ $index }}"@if ($index==0) class="active" aria-current="true" @endif aria-label="{{ $c->link_txt }}"></button>
        @endforeach
    </div>
    <div class="carousel-inner">
        @if (count($carousel) > 0)
            @foreach ($carousel as $index => $c)
            <div class="carousel-item @if ($index==0) active @endif" data-bs-interval="5000">
                <img src="{{ env('APP_URL').Storage::url($c->path) }}" style="min-height:350px;object-fit:cover;" class="d-block w-100" alt="">
                @if ($c->link_txt != '' && $c->link != '')
                <div class="mask">
                    <div class="custom_title_wrap">
                        <div class="bg_title mb-4">
                            <h1 class="mb-0">{!! $c->link_txt !!}</h1>
                            @if ($c->sub_text != '')
                            <h2 class="mb-3">{!! $c->sub_text !!}</h2>
                            @endif
                        </div>
                        <a href="{{ $c->link }}" class="read_more_btn">read more</a>
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        @else
            <div class="carousel-item active" data-bs-interval="5000">
                <img src="{{ $default_carousel }}" style="min-height:350px;object-fit:cover;" class="d-block w-100" alt="">
            </div>
        @endif
    </div>
    <a class="carousel-control-prev" href="javascript:void(0)" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#jvascript:void(0)" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
<!-- 品牌輪播 -->
<div class="brand_carousel">
    <div class="owl-carousel owl-theme" loop="false">
        @foreach ($brand as $b)
        <div class="item">
            <a href="{{ route('brand.detail', ['id' => $b->id]) }}">
                <div class="brand" style="background-image: url('{{ env('APP_URL').Storage::url($b->logo) }}'); background-size:contain;"></div>
            </a>
        </div>
        @endforeach
    </div>
</div>

<div class="news_container mb-5">
    <div class="main_title"><h2>{{__('page.news')}}</h2></div>
    <div class="news_carousel">
        <div class="owl-carousel owl-theme">
            @foreach ($news as $n)
            <div class="news_item" onclick="location.href='{{ route('news.detail', ['id'=>$n->id]) }}';">
                <img class="mb-2" src="{{ env('APP_URL').Storage::url($n->cover) }}" alt="">
                <div class="new_time mb-3">
                    <span>{{ date('Y/m/d', strtotime($n->date)) }}</span> | By {{ $n->member->username }} | {{ $news_category[$n->category]['text'] }}
                </div>
                <h5 class="new_title mb-3">
                    {{$n->title}}
                </h5>
                <div class="new_content limit_three mb-2">
                    {{$n->summary}}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- 代理品牌 -->
<div class="proxy_brand_container">
    <div class="container">
        <div class="main_title"><h2>{{ __('page.agency_brand') }}</h2></div>
        <div class="brand_classify">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                @foreach ($brand as $index => $bitem)
                <li class="nav-item" role="presentation">
                    <button class="nav-link @if ($index==0) active @endif" id="pills-{{ $bitem->id }}" data-bs-toggle="pill" data-bs-target="#pills-{{ $bitem->id }}-content" type="button" role="tab" aria-controls="pills-{{ $bitem->id }}" aria-selected="@if($index==0) true @else false @endif">{{ $bitem->name }}</button>
                </li>
                @endforeach
            </ul>
        </div>
        <div class="tab-content" id="pills-tabContent">
            @foreach ($brand as $index => $bitem)
            <div class="tab-pane fade @if ($index==0) show active @endif" id="pills-{{ $bitem->id }}-content" role="tabpanel" aria-labelledby="pills-{{ $bitem->id }}" tabindex="{{ $index }}">
                <div class="brand_title mb-5">
                    <h4>{{ $bitem->name }}</h4>
                </div>
                <div class="brand_list">
                    <div class="row">
                        @foreach ($bitem->products as $pindex => $product)
                        @if($pindex > 0 && $pindex % 3 == 0)
                        </div>
                        <hr style="color: #FFF; border: 1px solid #FFF; margin: 3rem 0;">
                        <div class="row">
                        @endif
                        <div class="brand_card col-sm-12 col-md-6 col-lg-4 mb-4" onclick="location.href='{{ route('products.detail', ['id'=>$product->id]) }}';">
                            <img src="{{ env('APP_URL').Storage::url($product->cover) }}" style="width: 130px; max-height: 130px;">
                            <div class="card_content">
                                <h6 class="card-title">{{ $product->name }}</h6>
                                <p class="card-text limit_two">{{ $product->summary }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- 產品分類 -->
<div class="product_classify_container">
    <div class="container">
        <div class="main_title"><h2>{{ __('page.product_category') }}</h2></div>
        <div class="title_info">{{ __('page.product_category_sub') }}</p></div>
        <div class="product_classify mb-3">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                @foreach ($product_category as $index => $c)
                <li class="nav-item" role="presentation">
                    <button class="nav-link @if ($index==0) active @endif" id="pills-category-{{ $c->id }}-tab" data-bs-toggle="pill" data-bs-target="#pills-category-{{ $c->id }}" type="button" role="tab" aria-controls="pills-category-{{ $c->id }}" aria-selected="@if ($index==0) true @else false @endif">{{ $c->title }}</button>
                </li>
                @endforeach
            </ul>
        </div>
        <div class="tab-content" id="pills-tabContent">
            @foreach ($product_category as $index => $c)
            <div class="tab-pane fade @if ($index == 0) show active @endif" id="pills-category-{{ $c->id }}" role="tabpanel" aria-labelledby="pills-category-{{ $c->id }}-tab" tabindex="{{ $index }}">
                <div class="product_list">
                    <div class="row">
                        @foreach ($c->products as $pindex => $product)
                        <div class="product_card col-sm-12 col-md-6 col-lg-4" onclick="location.href='{{ route('products.detail', ['id'=>$product->id]) }}';">
                            <div style="background-image:url({{ env('APP_URL').Storage::url($product->cover) }}); background-size:cover; height: 180px; width: 100%;"></div>
                            <!-- <img src="{{ env('APP_URL').Storage::url($product->cover) }}" style="height: 180px; width:auto; max-width: 100%;"> -->
                            <div class="card_content mt-3">
                                <h6 class="card-title" style="font-weight:400;">{{ $product->name }}</h6>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- 公司介紹 -->
<div class="company_info_container">
    <div class="container">
        <section class="my-5">
            <div class="company_info ml-5">
                <div class="info_title mb-3"><h2>{{ $intro->title }}</h2></div>
                <div class="info_content">
                    <div class="zh mb-3">{!! $intro->content !!}</div>
                </div>
            </div>
            @if ($intro->img != '')
            <img src="{{ env('APP_URL').Storage::url($intro->img) }}" alt="">
            @endif
        </section>
    </div>
</div>
@endsection
@section('script')
<script src="{{ URL::asset('dist/js/index.js') }}"></script>
<script>
    
</script>
@endsection