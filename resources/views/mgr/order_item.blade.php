<tr data-id="{{ $item->id }}">
    <td>{{ $item->id }}</td>
    <td>
        @if ($priv_view)
        <a href="{{ route('mgr.order.detail', ['id'=>$item->order_no]) }}">
            {!! $item->order_no !!}
        </a>
        @else
        {!! $item->order_no !!}
        @endif

        @if (($role == 'super' || $role == 'mgr' || $role == 'director' || ($role == 'saler' && $item->user->iam)))
            @if ($saleslip_no == '')
            <br><small class="text text-danger" style="font-size:11px;">尚未輸入銷貨單</small>
            @else
            <br><small class="text text-seconday" style="font-size:11px;">{!! str_replace(', ','<br>',$saleslip_no) !!}</small>
            @endif
        @endif

        @if ($role == 'assistant')
            @if ($saleslip_no == '')
            <br><small class="text text-danger" style="font-size:11px;">尚未輸入銷貨單</small>
            @else
            <br><small class="text text-seconday" style="font-size:11px;">{{ $saleslip_no }}</small>
            @endif
        @endif
    </td>
    <td>
        {!! $item->user->username.'<br>'.$item->user->company !!}
        @if (!$credit)
        <br>
        <span class="badge rounded-pill bg-danger mb-3">信用額度不足</span>
        @endif
        @if ($role == 'saler' && $item->user->iam)
        <br>
        <span class="badge rounded-pill bg-danger">業務負責</span>
        @endif
    </td>
    <td style="width: 400px; white-space:initial;">
        @foreach ($item->cart->items as $p)
            @if ($role == 'assistant' && $p->product->assistant != Auth::guard('mgr')->user()->id) @continue @endif
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

                @if ($p->iam && $item->status == 'pending' && ($p->status == 'pending') )
                    <button style="margin-top:10px;" type="button" class="btn btn-danger btn-animation waves-effect waves-light" data-text="點我審核" data-bs-toggle="modal" data-bs-target="#review_product{{ $item->id }}_{{ $p->id }}"><span>審核價格</span></button>
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
                
                @elseif ($p->status == 'bargain')
                <span class="badge rounded-pill bg-danger">審核不通過</span>
                @elseif($p->status == 'pending')
                <span class="badge badge-soft-danger">尚未審核</span>
                @elseif ($p->status == 'confirmed')
                <span class="badge rounded-pill bg-success">審核通過</span>
                @elseif ($p->status == 'bargain')
                <span class="badge rounded-pill bg-danger">審核不通過</span>
                @elseif ($p->status == 'not_enough')
                <span class="badge rounded-pill bg-warning">庫存不足</span>
                @elseif ($p->status == 'bargain')
                <span class="badge rounded-pill bg-danger">退回議價</span>
                @endif
            </div>
        </div>
        @endforeach
    </td>
    <td>
        ${!! number_format($item->price) !!}
    </td>
    <td>
        @if ($item->status == 'reject')
            @if (count($item->logs) > 0 && ($item->logs[0]->member->role == 'super' || $item->logs[0]->member->role == 'mgr'))
            <span class="badge badge-soft-danger">主管審核退回</span>
            @else
            <span class="badge badge-soft-danger">審核退回</span>
            @endif
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
            @elseif ($item->status == 'director_review')
                @if ($role == 'director' || $role == 'super')
                <br>
                <button 
                    type="button" 
                    class="btn btn-primary btn-animation waves-effect waves-light" 
                    data-text="執行審核" 
                    data-bs-toggle="modal" 
                    data-bs-target="#director_review_bill{{ $item->id }}">
                    <span>審核訂單</span>
                </button>
                <div class="modal fade" id="director_review_bill{{ $item->id }}" tabindex="-1" aria-labelledby="director_review_bill{{ $item->id }}Label" aria-modal="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="director_review_bill{{ $item->id }}Label">大主管/總監審核訂單</h5>
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
                                                    <option value="order_director_pass">通過</option>
                                                    <option value="order_director_invalid">拒絕並退回</option>
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
                    <span class="badge badge-soft-primary">大主管/總監審核中</span>
                @endif
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

        @if ($item->is_exchange == 1)
        <br>
            <span class="badge badge-soft-danger">客戶申請換貨</span>
        @endif

        @if ($item->is_return == 1)
        <br>
            <span class="badge badge-soft-danger">客戶申請退貨</span>
        @endif
    </td>
    <td>
        {!! $item->payment_status_show() !!}
    </td>
    <td>
        {!! $item->shipping_status_show() !!}
    </td>
    <td>{!! str_replace(' ', '<br>', $item->created_at) !!}</td>
    <td class="no-search">
        <!-- <button class="mb-2 btn btn-sm btn-primary">編輯訂單</button> -->
        @if ($item->status == 'new' || $item->status == 'reject')
            @if ( $role == 'mgr' || $role == 'super' || ($role == 'saler' && $item->user->iam) )
            <br>
            <button class="mb-2 btn btn-sm btn-warning" onclick="action('{{ $item->id }}', 'pending')">提交審核</button>
            @endif
        @elseif ($item->status == 'pending')

        @endif
        @if ( $role == 'mgr' || $role == 'super' || ($role == 'saler' && $item->user->iam) )
            @if ($item->status != 'cancel' && $item->status != 'success' && $item->status != 'complete')
            <br>
            <button class="mb-2 btn btn-sm btn-light" onclick="action('{{ $item->id }}', 'cancel')">取消訂單</button>
            @endif
        @endif
        @if ($role == 'super')
            <br>
            <button class="mb-2 btn btn-sm btn-danger" onclick="action('{{ $item->id }}', 'del')">刪除訂單</button>
        @endif

        @if ($role == 'assistant' && $saleslip_no == '')
            @if ($item->status == 'success' || $item->status == 'complete')
            <br>
            <button class="mb-2 btn btn-sm btn-primary btn-saleslip_no">輸入銷貨單號</button>
            @endif
        @endif
    </td>
</tr>