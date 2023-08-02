<tr data-id="{{ $item['id'] }}">
    @foreach ($item['data'] as $index => $obj)
    <td
        @if (isset($th_title[$index]) && $th_title[$index]['width'] != '')
        style = "max-width: {{ $th_title[$index]['width'] }}"
        @endif
    >{!! $obj !!}</td>
    @endforeach

    <td>
        @if (isset($item['other_btns']))
            @foreach ($item['other_btns'] as $btn)
            <button class="btn btn-sm {{ $btn['class'] }}" onclick="{{ $btn['action'] }}">{{ $btn['text'] }}</button>
            @endforeach
        @endif

        @if (!isset($item['priv_edit']) || $item['priv_edit'])
        <button class="btn btn-sm btn-primary add-specialty-item-btn">新增專長</button>
        @endif
        
        {{-- @if (!isset($item['priv_del']) || $item['priv_del'])
        <button class="btn btn-sm btn-danger del-item-btn">刪除</button>
        @endif --}}

        {{-- @if (!isset($item['priv_edit_department']) || $item['priv_edit_department'])
        <button class="btn btn-sm btn-primary edit_department-item-btn">編輯</button>
        @endif --}}

    </td>
</tr>