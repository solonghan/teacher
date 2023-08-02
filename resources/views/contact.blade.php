@extends('components.master')
@section('title') {{ $title }} @endsection
@section('description') {{ $description }} @endsection
@section('css')
<style>
    input, textarea{
        border: 2px solid #CCC !important;
        /* color: #CCC !important; */
    }
    ::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
        color: #CCC !important;
        opacity: 1; /* Firefox */
    }

    :-ms-input-placeholder { /* Internet Explorer 10-11 */
        color: #CCC !important;
    }

    ::-ms-input-placeholder { /* Microsoft Edge */
        color: #CCC !important;
    }
</style>
@endsection
@section('content')
            <div class="container">
                <div class="contact_page_container">
                    <div class="contact_descirption">
                        <p>{{ __('page.contact_form.des') }}</p>
                    </div>
                    <div class="contact_title mb-4">
                        <h3>{{ __('page.contact_form.title') }}</h3>
                    </div>
                    <form action="{{ route('contact') }}" method="POST" id="form">
                        @csrf
                        <div class="msg_to_us_container">
                            <div class="msg_inputs_wrap row">
                                <div class="col-md-6 inputs">
                                    <label for="ask_name">{{ __('page.contact_form.username') }}<span class="require_icon">*</span></label>
                                    <input class="login_inputs" type="text" name="username" required placeholder="Name">
                                </div>
                                <div class="col-md-6 inputs">
                                    <label for="ask_mail">{{ __('page.contact_form.email') }}<span class="require_icon">*</span></label>
                                    <input class="login_inputs" type="email" name="email" required placeholder="server@easchem.com.tw">
                                </div>
                                <div class="col-md-2 inputs">
                                    <label for="ask_phone">{{ __('page.contact_form.area_code') }}</label>
                                    <input class="login_inputs" type="text" name="area_code" placeholder="02">
                                </div>
                                <div class="col-md-4 inputs">
                                    <label for="ask_phone">{{ __('page.contact_form.phone') }}<span class="require_icon">*</span></label>
                                    <input class="login_inputs" type="text" name="phone" required placeholder="25450099">
                                </div>
                                <div class="col-md-6 inputs">
                                    <label for="ask_phone">{{ __('page.contact_form.mobile') }}</label>
                                    <input class="login_inputs" type="text" name="mobile" placeholder="Phone">
                                </div>
                                <div class="col-md-6 inputs">
                                    <label for="ask_company_name">{{ __('page.contact_form.company') }}<span class="require_icon">*</span></label>
                                    <input class="login_inputs" type="text" name="company" required placeholder="Company">
                                </div>
                                <div class="col-md-6 inputs">
                                    <label for="ask_company_branch">{{ __('page.contact_form.department') }}<span class="require_icon">*</span></label>
                                    <input class="login_inputs" type="text" name="department" required placeholder="Dept.">
                                </div>
                                <div class="col-md-12 inputs">
                                    <label for="ask_company_address">{{ __('page.contact_form.address') }}<span class="require_icon">*</span></label>
                                    <input class="login_inputs" type="text" name="address" required placeholder="Addr">
                                </div>
                                <div class="col ask_content_wrap">
                                    <label for="ask_content">{{ __('page.contact_form.content') }}<span class="require_icon">*</span></label>
                                    <textarea name="content" cols="30" rows="6" required placeholder="Question"></textarea>
                                </div>
                                <div class="submit_msg_wrap mt-5">
                                    <!-- <button type="submit" class="orange_btn">{{ __('page.contact_form.submit') }}</button> -->
                                    <button class="submit_msg_btn orange_btn get_bigger_btn g-recaptcha" 
                                        data-sitekey="6LdYrssiAAAAAH6ZG0m-tLhPivt0J14OmtVVH4vR" 
                                        data-callback='onSubmit' 
                                        data-action='submit'>{{ __('page.contact_form.submit') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    @foreach ($company as $item)
                    <div class="company_location_container">
                        <div class="location_info_wrap">
                            <div class="location_info_text">
                                <h4 class="mb-4">{{ $item->company }}</h4>
                                @if ($item->mobile != '')
                                <div class="phone info_div">
                                    <img src="{{ env('APP_URL')}}/dist/assets/icon/phone_black.png" alt="">
                                    <div class="number limit_two">
                                        <a style="color:#333;" href="tel:{{ $item->mobile }}">{{ $item->mobile }}</a>
                                    </div>
                                </div>
                                @endif
                                @if ($item->tel != '')
                                <div class="telephone info_div">
                                    <div class="img_container">
                                        <img src="{{ env('APP_URL')}}/dist/assets/icon/telephone_black.png" alt="">
                                    </div>
                                    <div class="number limit_two">
                                        <a style="color:#333;" href="tel:{{ $item->tel }}">{{ $item->tel }}</a>
                                    </div>
                                </div>
                                @endif
                                @if ($item->email != '')
                                <div class="mail info_div">
                                    <div class="img_container">
                                        <img src="{{ env('APP_URL')}}/dist/assets/icon/mail_black.png" style="width:13px;height:9px;" alt="">
                                    </div>
                                    <div class="number limit_two">
                                    <a style="color:#333;" href="mailto:{{ $item->email }}">{{ $item->email }}</a>
                                    </div>
                                </div>
                                @endif
                                @if ($item->address != '')
                                <div class="position info_div">
                                    <div class="img_container">
                                        <img src="{{ env('APP_URL')}}/dist/assets/icon/position_black.png" style="width:9px;height:13px;" alt="">
                                    </div>
                                    <div class="number limit_two">
                                    <a style="color:#333;" href="https://www.google.com/maps/dir/?api=1&origin={{ $item->address }}">{{ $item->address }}</a>
                                    </div>
                                </div>
                                @endif
                            </div>
                            {!! $item->embed_map !!}
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
@endsection
@section('script')
<script src="https://www.google.com/recaptcha/api.js"></script>
<script>
   function onSubmit(token) {
     document.getElementById("form").submit();
   }
 </script>
@endsection