$(document).on('change', 'select[name=action]', function () {
    let form_id = $(this).closest('form').attr('id');
    $("#"+form_id+" input[name=price]").val($("#"+form_id+" input[name=price]").data('original'));
    if ($(this).val() == 'product_invalid') {
        $("#"+form_id+" input[name=price]").prop('disabled', false);
    }else{
        $("#"+form_id+" input[name=price]").prop('disabled', true);
    }
});

$(document).on('click', '.btn-saleslip_no', function () {
    let id = $(this).closest('tr').data('id');
    let saleslip_no = prompt('請輸入銷貨單號');

    $.ajax({
        type: "POST",
        url: '<?php echo e(env("APP_URL")); ?>/mgr/<?php echo e($controller??""); ?>/action',
        data: {
            _token: '<?php echo e(csrf_token()); ?>',
            id: id,
            action: 'saleslip',
            saleslip_no: saleslip_no
        },
        dataType: "json",
        success: function(data){
            if (data.status){
                Toastify({
                    gravity: "top",
                    position: "center",
                    text: data.msg,
                    className: "success",
                }).showToast();
                load_data();
            }else{
                Toastify({
                    gravity: "top",
                    position: "center",
                    text: data.msg,
                    className: "danger",
                }).showToast();
            }
        },
        failure: function(errMsg) {
            alert(errMsg);
        }
    });
});<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/easchem/resources/views/mgr/order_js.blade.php ENDPATH**/ ?>