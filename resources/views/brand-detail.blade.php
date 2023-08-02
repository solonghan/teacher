@extends('components.master')
@section('title') {{ $title }} @endsection
@section('description') {{ $description }} @endsection
@section('css')

@endsection
@section('content')
<div class="container">
    <div class="brand_detail_container">
        <div class="main_top">
            <div class="brand_card_img">
                <img src="{{ env('APP_URL').Storage::url($data->logo) }}" alt="{{ $data->name }}" style="width:auto; height: 220px;">
            </div>
            <div class="brand_intro">
                <p>{{ $data->summary }}</p>
            </div>
            <div class="route_btn_container">
                <div class="router_btns">
                    @if ($data->website != '')
                    <a class="router_btn" href="{{ $data->website }}">{{ __('page.goto.website') }} <i class="fa-solid fa-arrow-right"></i></a>
                    @endif
                    <a class="router_btn" href="{{ route('products') }}?brand={{ $data->id }}">{{ __('page.goto.products') }} <i class="fa-solid fa-arrow-right"></i></a>
                    <a class="router_btn" href="{{ route('home') }}">{{ __('page.goto.prev_page') }} <i class="fa-solid fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="main_mid">
            <div class="company_intro">
                <div class="intro_content">
                    {!! $data->content !!}
                </div>
            </div>
        </div>
        <div class="main_bottom">
            <div class="row">
                @foreach ($data->pics as $pic)
                <div class="col-md-6 col-lg-4 col-xl-3 mb-2">
                    <a data-fancybox="image" href="{{ env('APP_URL').Storage::url($pic->path) }}">
                        <img src="{{ env('APP_URL').Storage::url($pic->path) }}" alt="" style="width:100%;">
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')

@endsection