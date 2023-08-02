$('.header_top_fix').hide()
$(document).ready(function(){
    $(window).scroll(() => {
        if ($(document).scrollTop() > 0) {
            $('#header').removeClass('header_top_index')
            $('#header').addClass('header_top_fix')
            $('#header_logo_img').attr('src', './dist/assets/image/logo_black_text.png')
        } else {
            $('#header').addClass('header_top_index')
            $('#header').removeClass('header_top_fix')
            $('#header_logo_img').attr('src', './dist/assets/image/logo_white_text.png')
        }
    })
    $('.brand_carousel .owl-carousel').owlCarousel({
        items:6,
        loop:true,
        margin:10,
        nav: false,
        dots:false,
        autoplay:true,
        autoplayTimeout:3000,
        autoplayHoverPause:true,
        responsive:{
            0:{
                items:2
            },
            800:{
                items:4
            }
        }
    });
    $('.news_carousel .owl-carousel').owlCarousel({
        items:3,
        loop:true,
        margin:20,
        nav:true,
        dots:false,
        responsive:{
            0:{
                items:1
            },
            750:{
                items:3
            }
        }
    })
})