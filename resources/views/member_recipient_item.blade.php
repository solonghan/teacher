<div class="receive_data_table">
    <div class="member_data_title_wrap">
        <h4>收貨人資料{{ ($index + 1) }}</h4>
        @if ($cnt > 1)
        <div class="member_bill_actions">
            <div class="remove_receive_btn bill_btn me-4" data-id="{{ $recipient->id }}" data-bs-toggle="modal" data-bs-target="#remove_receive_modal"><i class="fa-solid fa-trash-can"></i>刪除</div>
            <div class="edit_receive_btn bill_btn" data-id="{{ $recipient->id }}" data-bs-toggle="modal" data-bs-target="#receive{{ $recipient->id }}_data_modal"><i class="fa-solid fa-pencil"></i>編輯</div>
        </div>
        @endif
    </div>
    <div class="table_row">
        <div class="table_title"> 收貨人姓名</div>
        <div class="table_content">{{ $recipient->username }}</div>
    </div>
    <div class="table_row">
        <div class="table_title"> 收貨地址</div>
        <div class="table_content">{{ $recipient->address }}</div>
    </div>
    <div class="table_row">
        <div class="table_title"> 收貨人聯繫電話</div>
        <div class="table_content">{{ $recipient->phone }}</div>
    </div>
    <div class="table_row">
        <div class="table_title"> 收貨人電話分機</div>
        <div class="table_content">{{ $recipient->ext }}</div>
    </div>
    <div class="table_row">
        <div class="table_title"> 收貨人 E-Mail</div>
        <div class="table_content">{{ $recipient->email }}</div>
    </div>
</div>
<div class="modal fade" id="receive{{ $recipient->id }}_data_modal" tabindex="-1" aria-labelledby="add_new_receive_data_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('member.edit_recipient') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $recipient->id }}">
                    <div class="add_bill_modal_container">
                        <div class="login_inputs_container">
                            <div class="bill_info_all_wrap">
                                <div class="bill_info_one_wrap bill_info_wrap">
                                    <div class="mail_wrap login_inputs_wrap">
                                        <label for="receive_name">收貨人姓名<span class="require_icon">*</span></label>
                                        <input class="login_inputs" type="text" name="username" value="{{ $recipient->username }}">
                                    </div>
                                    <div class="mail_wrap login_inputs_wrap">
                                        <label for="receive_address">收貨人地址<span class="require_icon">*</span></label>
                                        <input class="login_inputs" type="text" name="address" value="{{ $recipient->address }}">
                                    </div>
                                    <div class="mail_wrap login_inputs_wrap">
                                        <label for="receive_phone">收貨人聯繫電話<span class="require_icon">*</span></label>
                                        <input class="login_inputs" type="tel" name="phone" value="{{ $recipient->phone }}">
                                    </div>
                                    <div class="mail_wrap login_inputs_wrap">
                                        <label for="receive_phone_minor">收貨人電話分機</label>
                                        <input class="login_inputs" type="tel" name="ext" value="{{ $recipient->ext }}">
                                    </div>
                                    <div class="mail_wrap login_inputs_wrap">
                                        <label for="receive_mail">收貨人 E-mail<span class="require_icon">*</span></label>
                                        <input class="login_inputs" type="email" name="email" value="{{ $recipient->email }}">
                                    </div>
                                </div>
                            </div>
                            <div class="login_btn_wrap">
                                <button type="submit" class="save_receive_data_btn orange_btn get_bigger_btn" data-info="" data-bs-dismiss="modal">儲存</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>