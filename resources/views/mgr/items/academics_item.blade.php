<tr data-id="{{ $item['id'] }}">
    @foreach ($item['data'] as $index => $obj)
    <td
        @if (isset($th_title[$index]) && $th_title[$index]['width'] != '')
        style = "max-width: {{ $th_title[$index]['width'] }}"
            {{-- @if($obj==) --}}
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
           

            @if(isset($item['item_member_id'])  && $item['item_member_id'] != $item['member_id'])
                {{-- <p class="btn btn-sm btn-primary">無法編輯</p> --}}
            @else
                @if(isset($item['member_department']))
                    @foreach ($item['member_department'] as $m_department)
                        @if($m_department != $item['my_department'])
                         {{-- <button class="btn btn-sm btn-primary edit-item-btn">編輯</button> --}}
                         @else
                            <button class="btn btn-sm btn-primary edit-item-btn">編輯</button>
                        @endif
                    @endforeach
                @else
                     <button class="btn btn-sm btn-primary edit-item-btn">編輯</button>
                @endif
            @endif
        @endif
        
        @if (!isset($item['priv_del']) || $item['priv_del'])
        <button class="btn btn-sm btn-danger del-item-btn">刪除</button>
        @endif

        @if (!isset($item['priv_edit_academics']) || $item['priv_edit_academics'])
        <button class="btn btn-sm btn-primary edit_academics-item-btn">編輯</button>
        @endif

        @if (!isset($item['priv_del_academics']) || $item['priv_del_academics'])
        <button class="btn btn-sm btn-danger del_academics-item-btn">刪除</button>
        @endif

    </td>
</tr>