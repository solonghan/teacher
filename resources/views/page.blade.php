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
</style>
@endsection
@section('content')
<div class="without_page_banner">
    <div class="container">
        <div class="privacy_page_container">
            <h2 class="mb-4">{{ $data->title }}</h2>
            <p class="privacy_nav">
                <span style="cursor:pointer;" onclick="location.href='{{ route('home') }}';">{{__('page.home')}}</span>
                /
                {{ $data->title }}
            </p>
            <p class="privacy_text">
                {!! $data->content !!}
            </p>
        </div>
    </div>
</div>
@endsection
@section('script')

@endsection