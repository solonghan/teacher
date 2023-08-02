@extends('components.master')
@section('title') {{ $title }} @endsection
@section('description') {{ $description }} @endsection
@section('css')

@endsection
@section('content')
            <div class="search_result_container">
                <div class="container">
                    <div class="brand_list">
                        <div class="row">
                            @foreach ($data as $item)
                            <div class="brand_card col-sm-12 col-md-6 col-lg-4" onclick="location.href='{{ route('products.detail', ['id'=>$item->id]) }}';">
                                <img src="{{ env('APP_URL').Storage::url($item->cover) }}">
                                <div class="card_content">
                                    <h5 class="card-title">{{ $item->name }}</h5>
                                    <!-- <p class="card-text limit_two">主要銷售於化學及塑膠產業之不同應用及加工原料產品。</p> -->
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="route_btn_container">
                        <div class="router_btns">
                            <a class="router_btn" href="{{ route('home') }}">{{ __('page.goto.prev_page') }} <i class="fa-solid fa-arrow-right"></i></a>
                            <a class="router_btn" href="{{ route('products') }}">{{ __('page.goto.products') }} <i class="fa-solid fa-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            
@endsection
@section('script')

@endsection