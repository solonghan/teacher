@extends('components.master')
@section('title') {{ $title }} @endsection
@section('description') {{ $description }} @endsection
@section('css')

@endsection
@section('content')
<div class="container">
    <div class="login_page_container">
        <div class="login_inputs_container">
            <div class="register_success_text">
                <h6>{{ __('page.register.thankyou') }}</h6>
            </div>
            <div class="login_btn_wrap mt-3">
                <button class="orange_btn get_bigger_btn" onclick="location.href='{{ route('home') }}';">{{ __('page.goto.home') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
@endsection