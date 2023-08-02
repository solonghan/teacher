<div class="bill_info_two_wrap bill_info_wrap" data-index="{{ $index }}">
    <div class="bill_info_title_wrap mb-3">
        <h5>{{ __('page.register.invoice_info') }}{{ $index }}</h5>
        @if ($index > 1)
        <div class="remove_bill_btn" onclick="del_invoice('{{ $index }}');">
            <i class="fa-solid fa-circle-minus"></i>
            <span>{{ __('page.delete') }}</span>
        </div>
        @endif
    </div>
    <div class="mail_wrap login_inputs_wrap">
        <label for="bill_company_name">{{ __('page.register.invoice_company') }}<span class="require_icon">*</span></label>
        <input class="login_inputs" type="text" id="bill_company_name" name="company{{ $index }}" required>
    </div>
    <div class="mail_wrap login_inputs_wrap">
        <label for="bill_company_uniform">{{ __('page.register.taxid') }}<span class="require_icon">*</span></label>
        <input class="login_inputs" type="text" id="bill_company_uniform" name="tax_id{{ $index }}" required>
    </div>
    <div class="mail_wrap login_inputs_wrap">
        <label for="bill_name">{{ __('page.register.receiver') }}<span class="require_icon">*</span></label>
        <input class="login_inputs" type="text" id="bill_name" name="username{{ $index }}" required>
    </div>
    <div class="mail_wrap login_inputs_wrap">
        <label for="bill_address">{{ __('page.register.receiver_address') }}<span class="require_icon">*</span></label>
        <input class="login_inputs" type="text" id="bill_address" name="address{{ $index }}" required>
    </div>
    <div class="mail_wrap login_inputs_wrap">
        <label for="bill_receive_phone">{{ __('page.register.phone') }}<span class="require_icon">*</span></label>
        <input class="login_inputs" type="tel" id="bill_receive_phone" name="phone{{ $index }}" required>
    </div>
    <div class="mail_wrap login_inputs_wrap">
        <label for="bill_phone_minor">{{ __('page.register.ext') }}</label>
        <input class="login_inputs" type="tel" id="bill_phone_minor" name="ext{{ $index }}">
    </div>
    <div class="mail_wrap login_inputs_wrap">
        <label for="bill_receive_mail">{{ __('page.register.email') }}<span class="require_icon">*</span></label>
        <input class="login_inputs" type="email" id="bill_receive_mail" name="email{{ $index }}" required>
    </div>
</div>
