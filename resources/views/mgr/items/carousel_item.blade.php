<tr data-id="{{$item->id}}">
    <td>{{ $item->id }}</td>
    <td>
        @if ($item->path != '')
        <img src="{{ env('APP_URL').Storage::url($item->path) }}" style="width: 200px;">
        
        @endif
    </td>
    <td>
        @if ($item->link_txt != '')
        <a href='{{ $item->link }}'>{{ $item->link_txt }}</a>
        @endif
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
    <td>{{ $item->created_at }}</td>
    <td>
        <button class="btn btn-sm btn-primary edit-item-btn">編輯</button>

        <button class="btn btn-sm btn-danger del-item-btn">刪除</button>
    </td>
</tr>