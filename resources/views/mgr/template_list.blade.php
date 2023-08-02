@extends('mgr.layouts.master')
@section('title') {{$title}} @endsection
@section('css')

@endsection
@section('content')
    @component('mgr.components.breadcrumb', ['btns' => $btns??array()])
    @slot('li_1_url') {{$parent_url}} @endslot
    @slot('li_1') {{$parent}} @endslot
    @slot('title') {{$title}} @endslot
    @endcomponent
    <div class="row">
        <div class="col-12">
        @if ($type=='search')
             <p>查詢條件  姓名:{{$search_name}}， 服務單位:{{$search_now_unit}} {{$have}}，職稱:{{$search_title}}，專門專長:{{$search_specialty}}，學術專長:{{$search_academic}}  ，最後異動時間:{{$last_date}}</p>
        @elseif($type=='output')   
            <p>查詢人: XXX，查詢條件  姓名:XXX， 服務單位:公立學校 不含曾任，職稱:教授、研究員，專長:化學，學術專長:  ，最後異動時間:2023/4/1，列印結果未保存到伺服器中</p>
            <p style="text-align: right;">列印時間：2023/4/12 11:00</p>
        @elseif($type=='search_have')
                <h4><p>系統已存在此專家，如需新增其他專長，請按下新增專長按鈕</p></h4>
        @endif
       
        
            <div class="row">
                <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div id="customerList">
                            <div class="row g-4 mb-3">
                                {{-- <div class="col-sm">
                                    <div class="d-flex justify-content-sm-end">
                                        @if (!isset($is_search) || $is_search)
                                        <div class="input-group search-box ms-2 mb-2" style="width: 30%;">
                                            <input type="text" class="form-control search" style="border-color:#878a99;">
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
                                </div> --}}
                                <!-- <div class="col-sm">
                                    <div class="d-flex justify-content-sm-end">
                                        <div class="search-box ms-2">
                                            <input type="text" class="form-control search" placeholder="Search...">
                                            <i class="ri-search-line search-icon"></i>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="table-responsive table-card mb-1">
                                        <table class="table align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    @foreach ($th_title as $th)
                                                    <th scope="col" style="width:{{$th['width']}};">{{$th['title']}}</th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data as $item)
                                                @component($template_item, ['item'=>$item, 'th_title'=>$th_title])
                                                @endcomponent
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="d-flex justify-content-end">
                                        <div class="pagination-wrap hstack gap-2">
                                            <a class="page-item pagination-prev disabled" href="#">
                                                Previous
                                            </a>
                                            <ul class="pagination listjs-pagination mb-0"></ul>
                                            <a class="page-item pagination-next disabled" href="#">
                                                Next
                                            </a>
                                        </div>
                                    </div>
                                </div><!--end col-->

                            </div><!--end row-->
                        </div>
                    </div><!-- end card-body -->
                </div><!-- end card -->
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
        $(document).ready(function(e){
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

            $(document).on('click', '.edit_department-item-btn', function(event) {
                var id = $(this).closest('tr').data('id');
                location.href = '{{env('APP_URL')}}/mgr/{{$controller??''}}/department_edit/' + id;
            });

            $(document).on('click', '.view_department-item-btn', function(event) {
                var id = $(this).closest('tr').data('id');
                location.href = '{{env('APP_URL')}}/mgr/{{$controller??''}}/department_view/' + id;
            });

            $(document).on('click', '.academics-item-btn', function(event) {
                var id = $(this).closest('tr').data('id');
                location.href = '{{env('APP_URL')}}/mgr/{{$controller??''}}/academics/' + id;
            });

            $(document).on('click', '.edit_academics-item-btn', function(event) {
                var id = $(this).closest('tr').data('id');
                location.href = '{{env('APP_URL')}}/mgr/{{$controller??''}}/edit_academics/' + id;
            });

             $(document).on('click', '.specialty-item-btn', function(event) {
                var id = $(this).closest('tr').data('id');
                location.href = '{{env('APP_URL')}}/mgr/{{$controller??''}}/specialty/' + id;
            });
             $(document).on('click', '.specialty_list-item-btn', function(event) {
                var id = $(this).closest('tr').data('id');
                location.href = '{{env('APP_URL')}}/mgr/{{$controller??''}}/specialty_list/' + id;
            });

            $(document).on('click', '.academics_list-item-btn', function(event) {
                var id = $(this).closest('tr').data('id');
                location.href = '{{env('APP_URL')}}/mgr/{{$controller??''}}/academics_list/' + id;
            });

            $(document).on('click', '.edit_specialty-item-btn', function(event) {
                var id = $(this).closest('tr').data('id');
                location.href = '{{env('APP_URL')}}/mgr/{{$controller??''}}/edit_specialty/' + id;
            });

            $(document).on('click', '.del_academics-item-btn', function(event) {
                if (!confirm("確定刪除此筆資料?")) return;

                var id = $(this).closest('tr').data('id');
                $.ajax({
                    type: "POST",
                    url: '{{env('APP_URL')}}/mgr/{{$controller??''}}/del_academics',
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

            $(document).on('click', '.del_specialty-item-btn', function(event) {
                if (!confirm("確定刪除此筆資料?")) return;

                var id = $(this).closest('tr').data('id');
                $.ajax({
                    type: "POST",
                    url: '{{env('APP_URL')}}/mgr/{{$controller??''}}/del_specialty',
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

            $(document).on('click', '.edit-item-btn', function(event) {
                var id = $(this).closest('tr').data('id');
                location.href = '{{env('APP_URL')}}/mgr/{{$controller??''}}/edit/' + id;
            });

            //新增專長
            $(document).on('click', '.add-specialty-item-btn', function(event) {
                var id = $(this).closest('tr').data('id');
                location.href = '{{env('APP_URL')}}/mgr/{{$controller??''}}/add_specialty/' + id;
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
            $(".search_action").on('click', function () {
                search = $('input.search').val();
                load_data();
            });
            $('.search_clear_action').on('click', function () {
                search = '';
                $(".search").val('');
                load_data();
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
    </script>
@endsection
