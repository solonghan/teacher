$(".old_discount").on('keyup', function(e){
    price_calc();
});
$(".new_discount").on('keyup', function(e){
    price_calc();
});
$(".price").on('keyup', function(e){
    price_calc();
});

function price_calc(){
    if ($(".old_discount").val() != '' && parseFloat($(".old_discount").val()) > 0 && parseFloat($(".old_discount").val()) <= 1) {
        var length = $(document).find('.price').length;
        for (let i = 1; i <= length; i++) {
            if ($(".price"+i).val() != '' && parseInt($(".price"+i).val()) > 0) {
                price_old = Math.round(parseFloat($(".old_discount").val()) * parseInt($(".price"+i).val()));
                $(".price_old"+i).val(price_old);
            }
        }
    }
    if ($(".new_discount").val() != '' && parseFloat($(".new_discount").val()) > 0 && parseFloat($(".new_discount").val()) <= 1) {
        var length = $(document).find('.price').length;
        for (let i = 1; i <= length; i++) {
            if ($(".price"+i).val() != '' && parseInt($(".price"+i).val()) > 0) {
                price_new = Math.round(parseFloat($(".new_discount").val()) * parseInt($(".price"+i).val()));
            $(".price_new"+i).val(price_new);
            }
        }
    }
}<?php /**PATH C:\xampp\htdocs\NTU_02\resources\views/mgr/custom_js/product_calc.blade.php ENDPATH**/ ?>