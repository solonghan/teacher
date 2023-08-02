<div class="receive_data_wrap" id="bill_data{{ $invoice->id }}">
    <div class="receive_data_table">
        <div class="member_data_title_wrap">
            <h4>發票資料{{ ($index + 1) }}</h4>
            @if ($cnt > 1)
            <div class="member_bill_actions">
                <div class="remove_bill_btn bill_btn me-4" data-id="{{ $invoice->id }}" data-bs-toggle="modal" data-bs-target="#remove_bill_modal"><i class="fa-solid fa-trash-can"></i>刪除</div>
                <div class="edit_bill_btn bill_btn" data-id="{{ $invoice->id }}" data-bs-toggle="modal" data-bs-target="#bill{{ $invoice->id }}_data_modal"><i class="fa-solid fa-pencil"></i>編輯</div>
            </div>
            @endif
        </div>
        <div class="table_row">
            <div class="table_title">發票公司全名</div>
            <div class="table_content">{{ $invoice->company }}</div>
        </div>
        <div class="table_row">
            <div class="table_title">發票統編</div>
            <div class="table_content">{{ $invoice->tax_id }}</div>
        </div>
        <div class="table_row">
            <div class="table_title">發票收件人姓名</div>
            <div class="table_content">{{ $invoice->username }}</div>
        </div>
        <div class="table_row">
            <div class="table_title">發票收件地址</div>
            <div class="table_content">{{ $invoice->address }}</div>
        </div>
        <div class="table_row">
            <div class="table_title">發票收件聯繫電話</div>
            <div class="table_content">{{ $invoice->phone }}</div>
        </div>
        <div class="table_row">
            <div class="table_title">發票收件電話分機</div>
            <div class="table_content">{{ $invoice->ext }}</div>
        </div>
        <div class="table_row">
            <div class="table_title">收件人 E-Mail</div>
            <div class="table_content">{{ $invoice->email }}</div>
        </div>
    </div>
    <div class="modal fade" id="bill{{ $invoice->id }}_data_modal" tabindex="-1" aria-labelledby="add_new_bill_data_modal_label" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('member.edit_invoice') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $invoice->id }}">
                        <div class="add_bill_modal_container">
                            <div class="login_inputs_container">
                                <div class="bill_info_all_wrap">
                                    <div class="bill_info_one_wrap bill_info_wrap">
                                        <div class="mail_wrap login_inputs_wrap">
                                            <label for="bill_company_name">發票公司全名<span class="require_icon">*</span></label>
                                            <input class="login_inputs" type="text" name="company" value="{{ $invoice->company }}">
                                        </div>
                                        <div class="mail_wrap login_inputs_wrap">
                                            <label for="bill_company_uniform">發票統編<span class="require_icon">*</span></label>
                                            <input class="login_inputs" type="text" name="tax_id" value="{{ $invoice->tax_id }}">
                                        </div>
                                        <div class="mail_wrap login_inputs_wrap">
                                            <label for="bill_name">發票收件人姓名<span class="require_icon">*</span></label>
                                            <input class="login_inputs" type="text" name="username" value="{{ $invoice->username }}">
                                        </div>
                                        <div class="mail_wrap login_inputs_wrap">
                                            <label for="bill_address">發票收件地址<span class="require_icon">*</span></label>
                                            <input class="login_inputs" type="text" name="address" value="{{ $invoice->address }}">
                                        </div>
                                        <div class="mail_wrap login_inputs_wrap">
                                            <label for="bill_receive_phone">發票收件聯繫電話<span class="require_icon">*</span></label>
                                            <input class="login_inputs" type="tel" name="phone" value="{{ $invoice->phone }}">
                                        </div>
                                        <div class="mail_wrap login_inputs_wrap">
                                            <label for="bill_phone_minor">發票電話分機</label>
                                            <input class="login_inputs" type="tel" name="ext" value="{{ $invoice->ext }}">
                                        </div>
                                        <div class="mail_wrap login_inputs_wrap">
                                            <label for="bill_receive_mail">發票E-mail<span class="require_icon">*</span></label>
                                            <input class="login_inputs" type="email" name="email" value="{{ $invoice->email }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="login_btn_wrap">
                                    <button type="submit" class="orange_btn get_bigger_btn" data-info="" data-bs-dismiss="modal">儲存</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>