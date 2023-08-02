
// $("header").load(`${base_url}/components/header.html`);
// $("footer").load(`${base_url}/components/footer.html`);
$(document).ready(function(){
    $(window).scroll(() => {
        if ($(document).scrollTop() > 0) {
            // $('.header_top_common').css('position', 'fixed')
            $('#header').removeClass('header_top_common')
            $('#header').addClass('header_top_fix')
        } else {
            $('#header').addClass('header_top_common')
            $('#header').removeClass('header_top_fix')
            // $('.header_top_common').css('position', 'unset')
        }
    })
    // 拿取query
    // const qq = geturlParam('test');
    // console.log(qq)
    // console.log(add);
})