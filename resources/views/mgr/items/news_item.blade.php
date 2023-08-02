<tr data-id="{{$item->id}}">
    <td>{{ $item->id }}</td>
    <td>
        @if ($item->cover != '')
        <img src="{{ env('APP_URL').Storage::url($item->cover) }}" style="width: 120px;">
        
        @endif
    </td>
    <td>
        @if ($item->thumb != '')
        <img src="{{ env('APP_URL').Storage::url($item->thumb) }}" style="width: 120px;">
        
        @endif
    </td>
    <td style="max-width:250px;">
        <div>
            {{ $item->date }}<br>
            <strong>{{ $item->title }}</strong><br>
            <p class="fw-light">
            {{
                (mb_strlen($item->summary)>100)?mb_substr($item->summary,0 ,100)."...":$item->summary
            }}
            </p>
        </div>
    </td>
    <td>
        @if (strtotime($item->offline_date) < time())
            <span class="text text-danger">已下架</span>
        @elseif (strtotime($item->online_date) < time())
            <span class="text text-success">上架中</span>
        @else
            <span class="text text-warning">排程中</span>
        @endif
        <br>
        起: {{ $item->online_date }}<br>
        迄: {{ $item->offline_date }}
    </td>
    <td>
        <button class="btn btn-sm btn-primary edit-item-btn">編輯</button>
        
        <button class="btn btn-sm btn-danger del-item-btn">刪除</button>
    </td>
</tr>