@extends('components.master')
@section('title') {{ $title }} @endsection
@section('description') {{ $description }} @endsection
@section('css')

@endsection
@section('content')
<div class="container">
    <form action="{{ route('signin') }}" method="POST">
        @csrf
        <div class="login_page_container">
            <div class="login_inputs_container">
                @if (isset($error))
                <span class="text text-danger">{{ $error }}</span>
                @endif
                <div class="mail_wrap login_inputs_wrap">
                    <label for="mail">{{ __('page.email') }}</label>
                    <input class="login_inputs" type="email" id="mail" name="email">
                </div>
                <div class="password_wrap login_inputs_wrap">
                    <label for="password">{{ __('page.password') }}</label>
                    <input class="login_inputs" type="password" id="password" name="password">
                </div>
                <div class="forget_password_wrap">
                    <a href="{{ route('forgetpwd') }}">{{ __('page.forget_pwd') }}</a>
                </div>
                <div class="login_btn_wrap">
                    <button class="login_btn orange_btn get_bigger_btn">{{ __('page.login_btn') }}</button>
                </div>
                <div class="go_register_wrap">
                    {{ __('page.no_account') }} 
                    <a href="{{ route('register') }}">{{ __('page.goto.register') }}</a>
                </div>
            
            </div>
        </div>
    </form>
</div>
@endsection
@section('script')
@endsection
