<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.js"></script>
<script src="<?php echo e(URL::asset('dist/plugins/owl.carousel.min.js')); ?>"></script>
<script src="<?php echo e(URL::asset('dist/js/script.js')); ?>?v=<?php echo e(rand(100,999)); ?>"></script>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/6310200554f06e12d8921022/1gbrfq5o6';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
    })();
    $(".gotopbtn").click(function (e) {
        $("html,body").stop().animate({
                scrollTop: 0
            }, 0, 'ease'
        );
    });

    $(document).ready(function () {
        $(".cart_badge").on('DOMSubtreeModified', function () {
            if (parseInt($(this).html()) > 0) {
                $(this).show();
            }else{
                $(this).hide();
            }
        }).trigger('DOMSubtreeModified');
        $(".carousel-control-next").on('click', function () {
            $("#bannerCarousel").carousel('next');
        });
        $(".carousel-control-prev").on('click', function () {
            $("#bannerCarousel").carousel('prev');
        });

        <?php if(Auth::check()): ?>
        $(".quick_buy_btn").on('click', function () {
            $.ajax({
                type: "POST",
                url: '<?php echo e(route('cart.copy')); ?>',
                data: {
                    _token: "<?php echo e(csrf_token()); ?>",
                    uid: '<?php echo e(Auth::user()->id); ?>'
                },
                dataType: "json",
                success: function(data){
                    $("#quick_add_cart_modal").modal('show');
                    $("#quick_add_cart_modal .modal_title").html(data.msg);
                },
                failure: function(errMsg) {}
            }); 
        });
        <?php endif; ?>
    });
</script>
<?php echo $__env->yieldContent('js'); ?><?php /**PATH C:\xampp\htdocs\NTU_02\resources\views/components/scripts.blade.php ENDPATH**/ ?>