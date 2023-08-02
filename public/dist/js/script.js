const token = localStorage.getItem('token')

if (token) {
    $('.needToken').show()
    $('.noNeedToken').hide()
} else {
    $('.needToken').hide()
    $('.noNeedToken').show()
}
function geturlQuery(key) {
    let url_string = location.href;
    let url = new URL(url_string);
    return url.searchParams.get(key); 
};
// 搜尋關鍵字
function goSearchKeyword(keyword) {
    console.log("k: "+keyword)
    if (keyword === '') return
    $('.search_mask').hide()
    
    location.href = window.location.origin + `/products/search/${keyword}`
}

// 導向最新消息標籤頁(標籤)
function goNewsTag(tag) {
    console.log(111);
    location.href = `tag-page.php?tag=${tag}`
}
// 導向最新內容標籤頁(分類)
function goNewsClassify(classify) {
    location.href = `tag-page.php?classify=${classify}`
}
// 數字input控制
function onlyNumber(num) {
    var n = String(parseInt(num))
    // 先把非数字的都替换掉，除了数字和.
    n = n.replace(/[^\d\.]/g, '')
    // 必须保证第一个为数字而不是.
    n = n.replace(/^\./g, '')
    // 保证只有出现一个.而没有多个.
    n = n.replace(/\.{2,}/g, '.')
    // 保证.只出现一次，而不能出现两次以上
    n = n.replace('.', '$#$').replace(/\./g, '').replace('$#$', '.')
    n = n.replace('-', '')
    if (n === NaN || n < 1) {
        n = 1
    }
    return n
}
// console.log(geturlParam('test'));
$(document).ready(function(){
    // if ($(location).attr('pathname') === '/') {
    //     $("footer").load("./components/footer.html");
    // } else {
    //     $("header").load("./components/header.html");
    //     $("footer").load("./components/footer.html");
    // }
    $(".gotopbtn").hide();
    if ($(document).scrollTop() > 0) $(".gotopbtn").show();
    $(window).scroll(() => {
        if ($(document).scrollTop() > 0) {
            // $('.header_top_common').css('position', 'fixed')
            $('#header').removeClass('header_top_common')
            $('#header').addClass('header_top_fix')
            $(".gotopbtn").show();
        } else {
            $(".gotopbtn").hide();
            $('#header').addClass('header_top_common')
            $('#header').removeClass('header_top_fix')
            // $('.header_top_common').css('position', 'unset')
        }
    })
    // $('.shopping_car').click(() => {
    //     if (token) {
    //         location.href = 'cart-one.php'
    //     } else {
    //         location.href = 'login.php'
    //     }
    // })
    $('.menu_btn').click(() => {
        $('.side_bar').show()
        $('body').css('overflow', 'hidden')
    })
    $('#close_side_bar_btn').click(() =>{
        $('.side_bar').hide()
        $('body').css('overflow', 'auto')
    })
    $('.search_btn').click((e) => {
        e.stopPropagation()
        $('.search_mask').show()
        $('body').css('overflow', 'hidden')
    })
    $('#close_search_mask_btn').click(() => {
        $('.search_mask').hide()
        $('body').css('overflow', 'auto')
    })
    $('#search_mask_btn').click(function() {
        goSearchKeyword($(this).prev().val())
    })
    $('#search_mobile').click(function() {
        goSearchKeyword($(this).prev().val())
    })
    $('.open_minor_classify').click(function() {
        $(this).parent().next().slideToggle('fast')
        $(this).toggleClass('rotate')
    })
    $('#row_version').click(function() {
        $(this).addClass('active')
        $('#col_version').removeClass('active')
        $('.product_list_row').show()
        $('.product_list_col').hide()
    })
    $('#col_version').click(function() {
        $(this).addClass('active')
        $('#row_version').removeClass('active')
        $('.product_list_row').hide()
        $('.product_list_col').show()
    })
    
    
    // 關閉cancel_order_modal
    $('.confirm_cancel_order').click(function() {
        $('.cancel_btn').click()
    })
    
    $('.language_select').click(function() {
        $('.language_select_wrap').slideToggle()
    })
    $('input[name="search_input"]').on("keydown", function (e) {
        if (e.keyCode == 13) {
            goSearchKeyword($(this).val())
        }
    });
    $('.keyword_tag_btn').click(function () {
        goSearchKeyword($(this).find('.tag_text').text())
    })
    
    // 購買總數input 控制
    $('.amount_input_wrap input').blur(function () {
        $(this).val(onlyNumber($(this).val()))
    })
})