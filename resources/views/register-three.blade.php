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
    <form action="{{ route('register', ['step'=>4]) }}" method="POST">
    @csrf
        <input type="hidden" name="index" id="index" value="1">
        <div class="login_page_container">
            <div class="login_inputs_container">
                <div class="bill_info_bg_title_wrap">
                    <div class="register_title mb-4">
                        <h3>{{ __("page.register.invoice_info") }}</h3>
                    </div>
                    <div class="same_contact_info_wrap">
                        <input class="me-2" type="checkbox" name="same_contact_info" id="same_contact_info">
                        <label for="same_contact_info">{{ __('page.register.same_to_contact') }}</label>
                    </div>
                </div>
                <div class="bill_info_all_wrap" id="content">
                    

                </div>
                <div class="add_one_bill_info_wrap mt-3">
                    <div class="add_one_bill_info_btn" onclick="add_invoice();">{{ __('page.register.add_invoice') }}</div>
                </div>
                <div class="login_btn_wrap mt-5">
                    <button class="to_resgister_four_btn orange_btn get_bigger_btn">{{ __('page.register.next') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>

@endsection
@section('script')
<script>
    var index = 0;
    $(document).ready(function(e){
        add_invoice();

        $("#same_contact_info").on('change', function(e){
            if ($(this).is(":checked")) {
                $("input[name=company1]").val('{{ $user->company }}');
                $("input[name=tax_id1]").val('{{ $user->tax_id }}');
                $("input[name=username1]").val('{{ $user->username }}');
                $("input[name=phone1]").val('{{ $user->phone }}');
                $("input[name=ext1]").val('{{ $user->ext }}');
                $("input[name=email1]").val('{{ $user->email }}');
            }else{
                $("input[name=company1]").val('');
                $("input[name=tax_id1]").val('');
                $("input[name=username1]").val('');
                $("input[name=phone1]").val('');
                $("input[name=ext1]").val('');
                $("input[name=email1]").val('');
            }
        });
    });
    
    function add_invoice(){
        var formData = new FormData();
        formData.append('index', parseInt(index) + 1);
        fetch(document.querySelector('base').href+document.querySelector('html').lang+'/register_invoice', {
            method: "POST",
            headers: {
                // 'Content-Type': 'application/json',
                "X-CSRF-Token": '{{ csrf_token() }}'
            },
            body: formData
        })
        .then((response) => {
            return response.json();
        })
        .then((data) => {
            if (data.status) {
                index = data.index;
                $("#index").val(index);

                $("#content").append(data.html);
            }
        })
        .catch((error) => {
            
        })
    }

    function del_invoice(index){
        $(".bill_info_wrap[data-index="+index+"]").fadeTo('fast', 0, function(e){
            $(this).remove();
        });
    }
</script>
@endsection