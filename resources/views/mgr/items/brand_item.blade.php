<tr data-id="{{$item->id}}">
    <td>{{ $item->id }}</td>
    <td>{{ $item->name }}<br>{{ $item->name_en }}</td>
    <td>
        @if ($item->logo != '')
        <img src="{{ env('APP_URL').Storage::url($item->logo) }}" style="width: 120px;">
        
        @endif
    </td>
    <td>
        {{
            (mb_strlen($item->summary)>100)?mb_substr($item->summary,0 ,100)."...":$item->summary
        }}
    </td>
    <td>{{ $item->created_at }}</td>
    <td>
        <button class="btn btn-sm btn-primary edit-item-btn">編輯</button>
        
        <button class="btn btn-sm btn-danger del-item-btn">刪除</button>
    </td>
</tr>