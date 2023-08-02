@extends('components.master')
@section('title') {{ $title }} @endsection
@section('description') {{ $description }} @endsection
@section('css')
<style>
    @media screen and (max-width: 1023px) {
        img{
            width: 100%;
        }
    }
    h2{
        font-size: 30px;
        font-weight: 500;
        color: #1B3862;
    }
    intro_content{
        font-size: 18px;
        font-weight: 300;
    }
</style>
@endsection
@section('content')
<div class="container">
    <div class="about_page_container">
        @foreach ($data as $item)
        <div class="intro_container">
            <h2>{!! $item->title !!}</h2>
            <div class="intro_content">
                {!! $item->content !!}
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
@section('script')

@endsection