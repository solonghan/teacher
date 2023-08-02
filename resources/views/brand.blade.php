@extends('components.master')
@section('title') {{ $title }} @endsection
@section('description') {{ $description }} @endsection
@section('css')

@endsection
@section('content')
<div class="brand_container">
    <div class="container">
        <!-- <div class="brand_description">
            <p>伊士肯化學除了獲得美國伊士曼公司多項化學及塑膠原料的大中華區經銷權之外，亦是 SunChemical 公司，Dow Chemical公司，ASCEND公司，SI Group 公司及 Coil 公司等多家知名品牌經銷商。</p>
        </div> -->
        <div class="brand_intro_container">
            <div class="row">
                @foreach ($data as $item)
                <div class="col-md-12 col-lg-6 col-xl-4 mb-5">
                    <div class="card brand_intro_card" onclick="location.href='{{ route('brand.detail', ['id'=>$item->id]) }}';">
                        <img src="{{ env('APP_URL').Storage::url($item->logo) }}" class="card-img-top" alt="{{ $item->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $item->name }}</h5>
                            <p class="card-text limit_five">{{ $item->summary }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')

@endsection