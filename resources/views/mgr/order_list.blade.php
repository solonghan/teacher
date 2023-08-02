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
            <div class="row">
                <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div id="customerList">
                            <div class="row g-4 mb-3">
                                
                            </div>
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="table-responsive table-card mb-1">
                                        <table class="table align-middle table-nowrap">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col" style="">#</th>
                                                    <th scope="col" style="">訂單編號</th>
                                                    <th scope="col" style="">下訂會員</th>
                                                    <th scope="col" style="">產品</th>
                                                    <th scope="col" style="">總金額(未稅)</th>
                                                    <th scope="col" style="">訂單狀態</th>
                                                    <th scope="col" style="">付款狀態</th>
                                                    <th scope="col" style="">物流狀態</th>
                                                    <th scope="col" style="">下單時間</th>
                                                    <th scope="col" style="">動作</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data as $item)
                                                @php
                                                    $priv_view = false;
                                                    if (in_array($role, ['super', 'mgr', 'depot', 'accounting', 'assistant']) || ($role == 'saler' && $item->user->iam)){
                                                        $priv_view = true;
                                                    }

                                                @endphp
                                                <tr>
                                                    <td>{{ $item->id }}</td>
                                                    <td>
                                                        @if ($priv_view)
                                                        <a href="{{ route('mgr.order.detail', ['id'=>$item->order_no]) }}">
                                                            {!! $item->order_no !!}
                                                        </a>
                                                        @else
                                                        {!! $item->order_no !!}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {!! $item->user->username.'<br>'.$item->user->company !!}
                                                        @if ($role == 'saler' && $item->user->iam)
                                                        <br>
                                                        <span class="badge rounded-pill bg-danger">業務負責</span>
                                                        @endif
                                                    </td>
                                                    <td style="width: 400px; white-space:initial;">
                                                        @foreach ($item->cart->items as $p)
                                                        <div class="row mb-3 border-1 border-start-dotted">
                                                            <!-- <div class="col-sm-4">
                                                                <img class="img-thumbnail" style="width:100%;" src='{{ env('APP_URL').Storage::url($p->cover) }}'>
                                                            </div> -->
                                                            <div class="col-sm-8">
                                                                <strong>
                                                                    {{ $p->name }} {{ $p->quantity }} {{ $p->weight }}
                                                                </strong><br>
                                                                @if ($role == 'mgr' || $role == 'super' || ($role == 'saler' && $p->iam) )
                                                                    <span class="text text-primary">${{ number_format($p->price) }}</span>/{{ $p->weight }}
                                                                        @if ($p->status == 'bargain')
                                                                            <span class="text text-danger">-> ${{ number_format($p->bargain_price) }}/{{ $p->weight }}</span>
                                                                        @endif
                                                                    <br>
                                                                    @if ($p->price_remark == 'custom')
                                                                    <span class="badge rounded-pill bg-warning">需專人洽詢</span><br>
                                                                    @elseif ($p->price_remark == 'exceed')
                                                                    <span class="badge rounded-pill bg-danger">數量超出庫存/最大量</span><br>
                                                                    @endif
                                                                @endif

                                                                @if ($role == 'saler')
                                                                    @if ($item->user->iam)
                                                                    <!-- 會員業務 -->
                                                                        @if ($p->status == 'bargain')
                                                                        <span class="badge rounded-pill bg-danger">審核不通過</span>
                                                                        @endif
                                                                    @endif
                                                                    @if ($p->iam)
                                                                        <!-- 產品業務 -->
                                                                        @if ($p->status == 'confirmed')
                                                                        <span class="badge rounded-pill bg-success">審核通過</span>
                                                                        @elseif ($item->status == 'pending')
                                                                            @if($p->status == 'confimred')
                                                                            <span class="badge rounded-pill bg-success">審核通過</span>
                                                                            @elseif ($p->status == 'not_enough')
                                                                            <span class="badge rounded-pill bg-warning">庫存不足</span>
                                                                            @elseif ($p->status == 'bargain')
                                                                            <span class="badge rounded-pill bg-danger">退回議價</span>
                                                                            @else
                                                                                <button type="button" class="btn btn-danger btn-animation waves-effect waves-light" data-text="點我審核" data-bs-toggle="modal" data-bs-target="#review_product{{ $item->id }}_{{ $p->id }}"><span>審核價格</span></button>
                                                                                <div class="modal fade" id="review_product{{ $item->id }}_{{ $p->id }}" tabindex="-1" aria-labelledby="review_product{{ $item->id }}_{{ $p->id }}Label" aria-modal="true">
                                                                                    <div class="modal-dialog">
                                                                                        <div class="modal-content">
                                                                                            <div class="modal-header">
                                                                                                <h5 class="modal-title" id="review_product{{ $item->id }}_{{ $p->id }}Label">審核價格 {{ $p->name }}</h5>
                                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                            </div>
                                                                                            <div class="modal-body">
                                                                                                <form action="{{ route('mgr.order.action') }}" method="POST" id="form{{ $item->id }}_{{ $p->product_id }}">
                                                                                                    @csrf
                                                                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                                                                    <input type="hidden" name="product_id" value="{{ $p->product_id }}">
                                                                                                    <div class="row g-3">
                                                                                                        <div class="col-xxl-6">
                                                                                                            <div>
                                                                                                                <label for="action" class="form-label">審核結果</label>
                                                                                                                <select name="action" class="form-control">
                                                                                                                    <option value="product_pass">通過</option>
                                                                                                                    <option value="product_invalid">拒絕並退回</option>
                                                                                                                    <option value="product_not_enough">庫存不足</option>
                                                                                                                </select>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-xxl-6">
                                                                                                            <div>
                                                                                                                <label for="price" class="form-label">建議單價</label>
                                                                                                                <input type="number" class="form-control" name="price" value="{{ $p->price }}" data-original="{{ $p->price }}" disabled>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-xxl-12">
                                                                                                            <div>
                                                                                                                <label for="remark" class="form-label">備註</label>
                                                                                                                <textarea  class="form-control" name="remark"></textarea>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-xxl-12">
                                                                                                            <button type="submit" class="btn btn-primary waves-effect waves-light float-end">確認儲存</button>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </form>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                @elseif ($role == 'mgr' || $role == 'super')
                                                                    @if($p->status == 'pending')
                                                                    <span class="badge badge-soft-danger">尚未審核</span>
                                                                    @elseif ($p->status == 'confirmed')
                                                                    <span class="badge rounded-pill bg-success">審核通過</span>
                                                                    @elseif ($p->status == 'bargain')
                                                                    <span class="badge rounded-pill bg-danger">審核不通過</span>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </td>
                                                    <td>${!! number_format($item->price) !!}</td>
                                                    <td>
                                                        @if ($item->status == 'reject')
                                                            <span class="badge badge-soft-danger">審核退回</span>
                                                        @else
                                                            @php
                                                                $status_badge = 'warning';
                                                                if (in_array($item->status, ['success', 'complete'])){
                                                                    $status_badge = 'success';
                                                                }else if (in_array($item->status, ['cancel'])) {
                                                                    $status_badge = 'secondary';
                                                                }
                                                            @endphp
                                                            <span class="badge rounded-pill badge-soft-{{ $status_badge }}">{!! $item->status_show() !!}</span>
                                                            @if ($item->status == 'pending')
                                                            <br>
                                                            <span class="badge badge-soft-warning">產品業務審核中</span>
                                                            @elseif ($item->status == 'inreview')
                                                                @if ($item->iam_manager)
                                                                <br>
                                                                <button 
                                                                    type="button" 
                                                                    class="btn btn-info btn-animation waves-effect waves-light" 
                                                                    data-text="執行審核" 
                                                                    data-bs-toggle="modal" 
                                                                    data-bs-target="#review_bill{{ $item->id }}">
                                                                    <span>審核訂單</span>
                                                                </button>
                                                                <div class="modal fade" id="review_bill{{ $item->id }}" tabindex="-1" aria-labelledby="review_bill{{ $item->id }}Label" aria-modal="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="review_bill{{ $item->id }}Label">審核訂單</h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <form action="{{ route('mgr.order.action') }}" method="POST">
                                                                                    @csrf
                                                                                    <input type="hidden" name="id" value="{{ $item->id }}">
                                                                                    <input type="hidden" name="product_id" value="0">
                                                                                    <div class="row g-3">
                                                                                        <div class="col-xxl-12">
                                                                                            <div>
                                                                                                <label for="action" class="form-label">審核結果</label>
                                                                                                <select name="action" class="form-control">
                                                                                                    <option value="order_pass">通過</option>
                                                                                                    <option value="order_invalid">拒絕並退回</option>
                                                                                                </select>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-xxl-12">
                                                                                            <div>
                                                                                                <label for="remark" class="form-label">備註</label>
                                                                                                <textarea  class="form-control" name="remark"></textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col-xxl-12">
                                                                                            <button type="submit" class="btn btn-primary waves-effect waves-light float-end">確認儲存</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @else
                                                                    <br>
                                                                    <span class="badge badge-soft-info">主管審核中</span>
                                                                @endif
                                                            @elseif ($item->status == 'confirmed')
                                                            <br>
                                                            <span class="badge badge-soft-warning">客戶確認中</span>
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {!! $item->payment_status_show() !!}
                                                    </td>
                                                    <td>
                                                        {!! $item->shipping_status_show() !!}
                                                    </td>
                                                    <td>{!! str_replace(' ', '<br>', $item->created_at) !!}</td>
                                                    <td>
                                                        <!-- <button class="mb-2 btn btn-sm btn-primary">編輯訂單</button> -->
                                                        @if ($item->status == 'new' || $item->status == 'reject')
                                                            @if ( $role == 'mgr' || $role == 'super' || ($role == 'saler' && $item->user->iam) )
                                                            <br>
                                                            <button class="mb-2 btn btn-sm btn-warning" onclick="action('{{ $item->id }}', 'pending')">提交審核</button>
                                                            @endif
                                                        @elseif ($item->status == 'pending')

                                                        @endif
                                                        @if ( $role == 'mgr' || $role == 'super' || ($role == 'saler' && $item->user->iam) )
                                                            @if ($item->status != 'cancel' && $item->status != 'success')
                                                            <br>
                                                            <button class="mb-2 btn btn-sm btn-light" onclick="action('{{ $item->id }}', 'cancel')">取消訂單</button>
                                                            <br>
                                                            <button class="mb-2 btn btn-sm btn-danger" onclick="action('{{ $item->id }}', 'del')">刪除訂單</button>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- <div class="d-flex justify-content-end">
                                        <div class="pagination-wrap hstack gap-2">
                                            <a class="page-item pagination-prev disabled" href="#">
                                                Previous
                                            </a>
                                            <ul class="pagination listjs-pagination mb-0"></ul>
                                            <a class="page-item pagination-next disabled" href="#">
                                                Next
                                            </a>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>      
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
            $(document).on('click', '.edit-item-btn', function(event) {
                var id = $(this).closest('tr').data('id');
                location.href = '{{env('APP_URL')}}/mgr/{{$controller??''}}/edit/' + id;
            });
            $(document).on('change', 'select[name=action]', function () {
                let form_id = $(this).closest('form').attr('id');
                $("#"+form_id+" input[name=price]").val($("#"+form_id+" input[name=price]").data('original'));
                if ($(this).val() == 'product_invalid') {
                    $("#"+form_id+" input[name=price]").prop('disabled', false);
                }else{
                    $("#"+form_id+" input[name=price]").prop('disabled', true);
                }
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
        });
        function action(id, action){
            $.ajax({
                type: "POST",
                url: '{{ route('mgr.order.action') }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    action: action,
                    form: 'ajax'
                },
                dataType: "json",
                success: function(data){
                    if (data.status){
                        if (data.action == 'reload') {
                            window.location.reload();
                        }else if (data.action == 'redirect') {
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
@endsection
