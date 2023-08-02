@extends('components.master')
@section('title') {{ $title }} @endsection
@section('description') {{ $description }} @endsection
@section('css')

@endsection
@section('content')
<div class="container">
    <div class="login_register_caption">
        <p>{{ __('page.register.has_sent_verify_code') }}</p>
    </div>
    <form action="{{ route('register', ['step'=>3]) }}" method="POST">
    @csrf
        <div class="login_page_container">
            <div class="login_inputs_container">
                <div class="verify_wrap login_inputs_wrap">
                    <label for="verify">{{ __('page.register.verify_code') }}</label>
                    <input class="login_inputs" type="text" id="verify" name="code">
                </div>
                <div class="resend_verify_wrap mt-3" style="display:none;">
                    {{ __('page.register.dont_receive_verify_code') }} 
                    <a class="resend_verify_btn" href="{{ route('register', ['step'=>2]) }}">{{ __('page.register.resend') }}</a>
                </div>
                <div class="login_btn_wrap mt-5">
                    <button type="button" onclick="location.href='{{ route('register') }}';" class="white_btn me-4 get_bigger_btn">{{ __("page.register.prev") }}</button>
                    <button type="submit" class="orange_btn get_bigger_btn">{{ __("page.register.next") }}</button>
                </div>
            </div>
        </div>
    </form>
</div>
            
@endsection
@section('script')

@endsection