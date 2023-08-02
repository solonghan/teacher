<script src="{{ URL::asset('assets/libs/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/node-waves/node-waves.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/feather-icons/feather-icons.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/pages/lord-icon-2.1.0.js') }}"></script>
<script src="{{ URL::asset('assets/js/plugins.min.js') }}"></script>
<script src="{{ URL::asset('jquery.min.js') }}"></script>
<script src="{{ URL::asset('js/app.min.js') }}?v={{ rand(100,999) }}"></script>
@yield('script')
@yield('script-bottom')
<script>

function notification_read(id){
    $.ajax({
            type: "POST",
            url: "{{ env('APP_URL') }}/util/notification_read",
            data: {
                _token: '{{ csrf_token() }}',
                id: id,
            },
            dataType: "json",
            success: function(data){
                if (data.status){
                    $(".noti.badge").html(data.unread);
                    if (id == 'all') {
                        $("#all-noti-tab").find(".notification-item").each(function (index, element) {
                            $(this).find('.avatar-title')
                                .removeClass('bg-soft-danger text-danger')
                                .addClass('bg-soft-info text-info')
                        });;
                    }else{
                        $("#notification_"+id).find('.avatar-title')
                            .removeClass('bg-soft-danger text-danger')
                            .addClass('bg-soft-info text-info')
                    }
                    
                    if (data.url != '') {
                        location.href = data.url;
                    }
                }
            },
            failure: function(errMsg) {
                alert(errMsg);
            }
        });
}
</script>