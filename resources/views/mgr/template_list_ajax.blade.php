@extends('mgr.layouts.master')
@section('title') {{$title}} @endsection
@section('css')
<style>
mark{
    background-color: #ee900c !important;
    border-radius: 3px !important;
}
li.disabled .page{
    color: #AAA !important;
}
</style>
@endsection
@section('content')
    @component('mgr.components.breadcrumb', ['btns' => $btns??array()])
    @slot('li_1_url') {{$parent_url}} @endslot
    @slot('li_1') {{$parent}} @endslot
    @slot('title') {{$title}} @endslot
    @endcomponent
    <div class="row">
        @if (isset($bar2_btns))
            <div class="col-12 row mb-2">
                @foreach ($bar2_btns as $btn)
                <div class="col-{{ $btn[3] }}">
                    <button type="button" class="btn btn-{{ $btn[2] }} btn-animation waves-effect waves-light" onclick="{{ $btn[1] }}">{{ $btn[0] }}</button>
                </div>
                @endforeach
            </div>
        @endif
        <div class="col-12">
            <div class="row">
                <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div id="customerList">
                            <div class="row g-4 mb-3">
                                
                                <div class="col-sm">
                                    <div class="d-flex justify-content-sm-end">
                                        @if (!isset($is_search) || $is_search)
                                        <div class="input-group search-box ms-2 mb-2" style="width: 30%;">
                                            <input type="text" class="form-control search" style="border-color:#878a99;"placeholder="關鍵字搜尋">
                                            <button class="btn btn-outline-secondary search_action" type="button">搜尋</button>
                                            <button class="btn btn-outline-secondary search_clear_action" type="button" style="border-radius: 0 4px 4px 0;">清除</button>
                                            <i class="ri-search-line search-icon" style="z-index:99;"></i>
                                        </div>
                                        @endif

                                        <!-- <div class="search-box ms-2 mb-2">
                                            <input type="text" class="form-control search" placeholder="Search...">
                                            <i class="ri-search-line search-icon"></i>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="table-responsive table-card mb-1">
                                        <table class="table align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    @foreach ($th_title as $th)
                                                    <th scope="col" style="min-width:{{$th['width']}};">{!! $th['title'] !!}</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody id="content">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <div class="pagination-wrap hstack gap-2" id="pagination">
                                            <ul class="pagination listjs-pagination mb-0">
                                                
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>      
            </div>

        </div>
        @if (isset($bar_btns))
            <div class="col-12 row mb-2">
                @foreach ($bar_btns as $btn)
                <div class="col-{{ $btn[3] }}">
                    <button type="button" class="btn btn-{{ $btn[2] }} btn-animation waves-effect waves-light" onclick="{{ $btn[1] }}">{{ $btn[0] }}</button>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="close-modal"></button>
                </div>
                <form>
                    <div class="modal-body" id="detail_content">

                    </div>
                    <div class="modal-footer">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">關閉</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <!-- <script src="{{ URL::asset('js/app.min.js') }}"></script> -->

    <script src="{{ URL::asset('assets/libs/prismjs/prismjs.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/list.js/list.js.min.js') }}"></script>
    <script src="{{ URL::asset('assets/libs/list.pagination.js/list.pagination.js.min.js') }}"></script>

    <!-- listjs init -->
    <!-- <script src="{{ URL::asset('assets/js/pages/listjs.init.js') }}"></script> -->

    <script>
        let page = 1;
         let page_count = {{ $page_count??100 }};
        let search = '';
        let can_order_column_indedx = {{ (isset($can_order_fields))?json_encode($can_order_fields):'[]' }};
        let default_order_column = {{ ($default_order_column)??0 }};
        let order_direction = '{{ ($default_order_direction)??"DESC" }}';
        $(document).ready(function(e){
            load_data(1);
            generate_order();
            {!! $custom_js_on_ready??'' !!}

            $(document).on('click', '.show_detail', function(event) {
                $.ajax({
                    type: "GET",
                    url: '{{env('APP_URL')}}/mgr/{{$controller??''}}/detail/' + $(this).data('id'),
                    data: {
                    },
                    dataType: "json",
                    success: function(data){
                        if (data.status){
                            $("#detail_content").html(data.html);
                            $("#showModal").modal('show');
                        }
                    },
                    failure: function(errMsg) {
                        alert(errMsg);
                    }
                });
            });

            $(document).on('click', '.edit-item-btn', function(event) {
                var id = $(this).closest('tr').data('id');
                location.href = '{{env('APP_URL')}}/mgr/{{$controller??''}}/edit/' + id;
            });

            $(document).on('click', '.del-item-btn', function(event) {
                if (!confirm("確定刪除此筆資料?")) return;

                var id = $(this).closest('tr').data('id');
                $.ajax({
                    type: "POST",
                    url: '{{env('APP_URL')}}/mgr/{{$controller??''}}/del',
                    data: {
                        'id': id
                    },
                    dataType: "json",
                    success: function(data){
                        if (data.status){
                            $("tr[data-id="+id+"]").fadeTo('fast', 0, function(e){
                                $(this).remove();
                            });
                        }
                    },
                    failure: function(errMsg) {
                        alert(errMsg);
                    }
                });
            });
            $(".search").on('keypress', function (ev) {
                var keycode = (ev.keyCode ? ev.keyCode : ev.which);
                if (keycode == '13') {
                    search = $(this).val();
                    load_data();
                }
            });
            $(".search_action").on('click', function () {
                search = $('input.search').val();
                load_data();
            });
            $('.search_clear_action').on('click', function () {
                search = '';
                $(".search").val('');
                load_data();
            });
             $(`select[name=page_count]`).on('change', function () {
                page_count = $(this).val();
                load_data(1);
            });
            $(".switch_toggle").on('change', function () {
                let status = 'on';
                if (!$(this).is(':checked')) status = 'off';
                $.ajax({
                    type: "POST",
                    url: '{{env('APP_URL')}}/mgr/{{$controller??''}}/switch_toggle',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: $(this).data('id'),
                        status: status
                    },
                    dataType: "json",
                    success: function(data){
                        if (data.status){
                            if (status == 'on') {
                                Toastify({
                                    gravity: "top",
                                    position: "center",
                                    text: "已開啟",
                                    className: "success",
                                }).showToast();
                            }else{
                                Toastify({
                                    gravity: "top",
                                    position: "center",
                                    text: "已關閉",
                                    className: "danger",
                                }).showToast();
                            }
                        }
                    },
                    failure: function(errMsg) {
                        alert(errMsg);
                    }
                });
            });
        });

        function load_data(goToPage = false){
            if (goToPage != false) page = goToPage;

            let data = {
                _token: '{{ csrf_token() }}',
                page: page,
                page_count: page_count,
                search: search,
                status: '{{ $status??'' }}',
                order: default_order_column,
                direction: order_direction,
            };
            $.ajax({
                type: "POST",
                url: '{{env('APP_URL')}}/mgr/{{$controller??''}}/data',
                {{-- url: "<?= (isset($custom_data_url)) ? $custom_data_url : $action . 'data' ?>", --}}
                data: data,
                dataType: "json",
                success: function(data){
                     console.log(data)
                    if (data.status){
                        $("#content").html(data.html);

                        if (data.page !== undefined) {
                            $("#pagination").show();
                            generate_page(data.page, data.total_page);
                        }else{
                            $("#pagination").hide();
                        }
                    }

                    if (search != '') {
                        $.each( search.split(' '), function( index, s ) {
                            $("#content").find('td').each(function(index, el) {
                                if (!$(this).hasClass('no-search')) {
                                    var html = $(this).html();
                                    $(this).html(html.replace(s, '<mark data-markjs="true">'+s+'</mark>'));
                                }
                            });
                        });
                    }
                },
                failure: function(errMsg) {
                    alert(errMsg);
                }
            });
        }
        function load_data_test(goToPage = false, action = 'normal', param = ''){
            if (goToPage != false) page = goToPage;

            let data = {
                _token: '{{ csrf_token() }}',
                page: page,
                page_count: page_count,
                search: search,
                status: status,
                order: default_order_column,
                direction: order_direction,
                action: action,
                start_date: start_date,
                end_date: end_date,
                query: query,
                param: param
            };
            $(document).find('.filter').each(function (index, element) {
                let name = $(this).attr('name');
                data[name] = $(this).val();
            });

            $(document).find('.filter_multi').each(function (index, element) {
                let name = $(this).data('name');
                if (data[name] == undefined || data[name] == null || data[name] == '') {
                    data[name] = new Array();
                }
                console.log(data[name])
                if ($(this).is(":checked")) data[name].push($(this).val());
            });
            
            if (action == 'export') {
                let data_str = $("#form").serialize()+`&action=export`;
                $.each(data, function (key, val) { 
                    data_str += `&${key}=${val}`;
                });
                // let ids = $("#form input:checkbox:checked").map(function(){
                //     return parseInt($(this).val());
                // }).get();
                // data_str += `&ids=${JSON.stringify(ids)}`;
                data = data_str;
            }
            $.ajax({
                type: "POST",
                url: '{{env('APP_URL')}}/mgr/{{$controller??''}}/data{{ ((isset($tab))?"/".$tab:'') }}',
                data: data,
                dataType: "json",
                success: function(data){
                    if (data.status){
                        if (action == 'export') {
                            window.open(data.url);
                            return;
                        }
                        if (data.html == '') {
                            $("#content").html(`<tr><td class="text-center text-secondary" colspan="{{ count($th_title) }}">查無資料</td></tr>`);
                        }else{
                            $("#content").html(data.html);
                        }
                        
                        if (data.total != undefined && data.page_count != undefined) {
                            $(`.footer_summary`).removeClass('d-none');
                            $(`.total_cnt`).html(data.total);
                            $(`.page_count`).val(data.page_count);
                            $(`.footer_summary`).parent('').removeClass('justify-content-end')
                                                           .addClass('justify-content-between');
                        }else{
                            $(`.footer_summary`).addClass('d-none');
                            $(`.footer_summary`).parent('').addClass('justify-content-end')
                                                           .removeClass('justify-content-between');
                        }
                        
                        $(".checkAll").prop('checked', false);

                        if (data.page == undefined || (data.page == 1 && data.page == data.total_page) || data.total_page == 0) {
                            $("#pagination").hide();
                        }else{
                            $("#pagination").show();
                            generate_page(data.page, data.total_page);
                        }
                    }

                    if (search != '') {
                        $.each( search.split(' '), function( index, s ) {
                            $("#content").find('td').each(function(index, el) {
                                if (!$(this).hasClass('no-search')) {
                                    if ($(this).children().length == 0) {
                                        var html = $(this).html();
                                        $(this).html(html.replace(s, '<mark data-markjs="true">'+s+'</mark>'));    
                                    }else{
                                        $(this).children().each(function (index, element) {
                                            if ($(this).children().length == 0) {
                                                var html = $(this).html();
                                                $(this).html(html.replace(s, '<mark data-markjs="true">'+s+'</mark>'));    
                                            }
                                        });
                                    }
                                }
                            });
                        });
                    }
                },
                failure: function(errMsg) {
                    alert(errMsg);
                }
            });
        }

        const page_range = 10;
        function generate_page(page, total_page){
            page = parseInt(page);
            var html = "";
            var first = Math.floor((page-1)/page_range) * page_range + 1;
            
            if (page == 1) {
                html = '<li class="previous disabled"><a class="page" href="javascript:;">Previous</a></li>';
            }else{
                html ='<li class="previous"><a class="page" href="javascript:load_data('+(page-1)+');">Previous</a></li>';
            }

            for (var i = first; i < first + page_range && i <= total_page ; i++) {
                html += '<li class="';
                if(i == page) html += ' active';
                html += '"><a class="page" href="javascript:load_data('+i+');">'+i+'</a></li>';
            }

            if (page == total_page) {
                html += '<li class="next disabled"><a class="page" href="javascript:;">Next</a></li>';
            }else{
                html += '<li class="next"><a class="page" href="javascript:load_data('+(page+1)+');">Next</a></li>';
            }

            // if (page != total_page) {
                html += '<li class="last"><a class="page" href="javascript:load_data('+(total_page)+');">Last('+total_page+')</a></li>';
            // }

            // html += '<li class="last"><a class="page" href="javascript:;" style="padding:0;"><input type="text" class="form-control curpage" style="width:56px; height:30px; border:0; text-align:center;" value="1"></a></li>';

            $("#pagination .pagination").html(html);
        }

        function generate_order(){
            $("table").find('th').each(function(index, el) {
                if (can_order_column_indedx.indexOf(index) != -1) {
                    if (index == default_order_column) {
                        $(this).append(
                            $("<button/>").addClass('btn order_btn btn-sm').css({
                                width: '25px',
                                height: '25px',
                                padding: 0
                            })
                            .html('<span class="ri-sort-'+((order_direction == "DESC")?'desc':'asc')+'"></span>')
                            .attr("data-index", index)
                        );
                    }else{
                        $(this).append(
                            $("<button/>").addClass('btn order_btn btn-sm').css({
                                width: '25px',
                                height: '25px',
                                padding: 0
                            }).html('<span class="ri-menu-line"></span>')
                            .attr("data-index", index)
                        );
                    }
                } 
            });
        }

        $(document).on('click', '.order_btn', function(event) {
            var selected_index = $(this).attr("data-index");
            if (selected_index == default_order_column) {
                if (order_direction == "DESC") {
                    order_direction = "ASC";
                }else{
                    order_direction = "DESC";
                }
            }else{
                default_order_column = selected_index;
                order_direction = "DESC";
            }
            $("table").find('th').each(function(index, el) {
                if (can_order_column_indedx.indexOf(index) != -1) {
                    if (index == default_order_column) {
                        if (order_direction == "DESC") {
                            $(this).find("button").html('<span class="ri-sort-desc"></span>');
                        }else{
                            $(this).find("button").html('<span class="ri-sort-asc"></span>');
                        }
                    }else{
                        $(this).find("button").html('<span class="ri-menu-line"></span>');
                    }
                } 
            });
            load_data(page);
        });

        function action(id, action){
            if (action == 'del' && !confirm('確定刪除?')) return;
            if (action == 'cancel' && !confirm('確定取消?')) return;
            $.ajax({
                type: "POST",
                url: '{{env('APP_URL')}}/mgr/{{$controller??''}}/action',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    action: action
                },
                dataType: "json",
                success: function(data){
                    if (data.status){
                        if (data.action == 'reload') {
                            window.location.reload();
                        }else if (data.action == 'redirect') {
                            location.href = data.url;
                        }else{
                            load_data();
                            if (data.msg != ''){
                                Toastify({
                                    // destination: "",
                                    gravity: "top", // `top` or `bottom`
                                    position: "center", // `left`, `center` or `right`
                                    text: data.msg,
                                    className: "success",
                                }).showToast();
                            }
                        }
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
        }

        {!! $custom_js??'' !!}
    </script>
@endsection
