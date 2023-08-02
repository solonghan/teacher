<div class="bill_info_one_wrap  bill_info_wrap" data-index="{{ $index }}">
    <div class="bill_info_title_wrap mb-3">
        @if ($index > 1)
        <div class="remove_bill_btn" onclick="del_recipient('{{ $index }}');">
            <i class="fa-solid fa-circle-minus"></i>
            <span>{{ __('page.delete') }}</span>
        </div>
        @endif
    </div>
    <div class="mail_wrap login_inputs_wrap">
        <label for="receive_name">{{ __('page.register.recipient_username') }}<span class="require_icon">*</span></label>
        <input class="login_inputs" type="text" id="bill_company_name" name="username{{ $index }}" required>
    </div>
    <div class="mail_wrap login_inputs_wrap">
        <label for="receive_address">{{ __('page.register.recipient_address') }}<span class="require_icon">*</span></label>
        <input class="login_inputs" type="text" id="receive_address" name="address{{ $index }}" required>
    </div>
    <div class="mail_wrap login_inputs_wrap">
        <label for="receive_phone">{{ __('page.register.recipient_phone') }}<span class="require_icon">*</span></label>
        <input class="login_inputs" type="tel" id="receive_phone" name="phone{{ $index }}" required>
    </div>
    <div class="mail_wrap login_inputs_wrap">
        <label for="receive_phone_minor">{{ __('page.register.recipient_ext') }}</label>
        <input class="login_inputs" type="tel" id="receive_phone_minor" name="ext{{ $index }}">
    </div>
    <div class="mail_wrap login_inputs_wrap">
        <label for="receive_mail">{{ __('page.register.recipient_email') }}<span class="require_icon">*</span></label>
        <input class="login_inputs" type="email" id="receive_mail" name="email{{ $index }}" required>
    </div>
</div>
