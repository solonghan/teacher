@extends('components.master')
@section('title') {{ $title }} @endsection
@section('description') {{ $description }} @endsection
@section('css')

@endsection
@section('content')
<div class="container">
    <div class="login_register_caption">
        <p>{{ __('page.register.hint') }}</p>
    </div>
    <form action="{{ route('register', ['step'=>2]) }}" method="POST">
        @csrf
        <div class="login_page_container">
            <div class="login_inputs_container">
                <div class="register_title mb-3">
                    <h3>{{ __('page.register.contact_info') }}</h3>
                </div>
                <div class="mail_wrap login_inputs_wrap">
                    <label for="contact_company_name">{{ __('page.register.company') }}<span class="require_icon">*</span></label>
                    <input class="login_inputs" type="text" id="contact_company_name" name="company" value="{{ $user->company }}" required>
                </div>
                <div class="mail_wrap login_inputs_wrap">
                    <label for="contact_company_uniform">{{ __('page.register.taxid') }}<span class="require_icon">*</span></label>
                    <input class="login_inputs" type="text" id="contact_company_uniform" name="tax_id" value="{{ $user->tax_id }}" required>
                </div>
                <div class="mail_wrap login_inputs_wrap">
                    <label for="contact_name">{{ __('page.register.username') }}<span class="require_icon">*</span></label>
                    <input class="login_inputs" type="text" id="contact_name" name="username" value="{{ $user->username }}" required>
                </div>
                <div class="mail_wrap login_inputs_wrap">
                    <label for="contact_phone">{{ __('page.register.phone') }}<span class="require_icon">*</span></label>
                    <input class="login_inputs" type="tel" id="contact_phone" name="phone" value="{{ $user->phone }}" required>
                </div>
                <div class="mail_wrap login_inputs_wrap">
                    <label for="contact_phone_minor">{{ __('page.register.ext') }}</label>
                    <input class="login_inputs" type="tel" id="contact_phone_minor" name="ext" value="{{ $user->ext }}">
                </div>
                <div class="mail_wrap login_inputs_wrap">
                    <label for="contact_mail">{{ __('page.register.email') }}<span class="require_icon">*</span></label>
                    <input class="login_inputs" type="email" id="contact_mail" name="email" value="{{ $user->email }}" required>
                </div>
                <div class="password_wrap login_inputs_wrap">
                    <label for="contact_password">{{ __('page.register.password') }}</label>
                    <input class="login_inputs" type="password" id="contact_password" name="password" required autocomplete="off">
                </div>
                <div class="password_wrap login_inputs_wrap">
                    <label for="contact_password_check">{{ __('page.register.password_confirm') }}</label>
                    <input class="login_inputs" type="password" id="contact_password_check" name="password_confirm" required autocomplete="off">
                </div>
                <div class="login_btn_wrap mt-5">
                    <button type="submit" class="orange_btn get_bigger_btn">{{ __('page.register.next') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@section('script')

@endsection