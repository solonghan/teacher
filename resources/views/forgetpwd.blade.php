@extends('components.master')
@section('title') {{ $title }} @endsection
@section('description') {{ $description }} @endsection
@section('css')

@endsection
@section('content')
<div class="container">
    <form action="{{ route('forgetpwd') }}" method="POST">
        @csrf
        <div class="login_page_container">
            <div class="login_inputs_container">
                @if (isset($error))
                <span class="text text-danger">{{ $error }}</span>
                @else
                <span class="text text-primary">
                    {{ __('page.register.forgetpwd_hint'); }}
                </span>
                @endif

                <div class="mail_wrap login_inputs_wrap mt-4">
                    <label for="mail">{{ __('page.email') }}</label>
                    <input class="login_inputs" type="email" id="mail" name="email">
                </div>
                <div class="login_btn_wrap">
                    <button class="login_btn orange_btn get_bigger_btn">{{ __('page.submit') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@section('script')
@endsection
