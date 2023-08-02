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
           
            @if(isset($item['privilege_id'])  && $item['privilege_id'] !=3)
                @if(isset($item['member_department']))
                    {{-- @foreach ($item['member_department'] as $m_department) --}}
                        {{-- @if($m_department != $item['my_department']) --}}
                         {{-- <button class="btn btn-sm btn-primary edit-item-btn">編輯</button> --}}
                         {{-- @else --}}
                            <button class="btn btn-sm btn-primary edit-item-btn">編輯</button>
                            @if (!isset($item['priv_edit_academics']) || $item['priv_edit_academics'])
                                 <button class="btn btn-sm btn-primary specialty_list-item-btn" style="color: #fff;background-color: green; border-color: green;">學門專長</button>
                                <button class="btn btn-sm btn-primary academics_list-item-btn" style="color: #fff;background-color: orange; border-color: orange;">學術專長</button>
                            @endif
                        {{-- @endif --}}
                    {{-- @endforeach --}}
                @else
                     <button class="btn btn-sm btn-primary edit-item-btn">編輯</button>
                @endif
            @elseif(isset($item['item_member_id'])  && $item['item_member_id'] != $item['member_id'])
                @if(isset($item['member_department']))
                    @foreach ($item['member_department'] as $m_department)
                        @if($m_department != $item['my_department'])
                         {{-- <button class="btn btn-sm btn-primary edit-item-btn">編輯123</button> --}}
                         @else
                            <button class="btn btn-sm btn-primary edit-item-btn">編輯</button>
                            @if (!isset($item['priv_edit_academics']) || $item['priv_edit_academics'])
                                 <button class="btn btn-sm btn-primary specialty_list-item-btn" style="color: #fff;background-color: green; border-color: green;">學門專長</button>
                                <button class="btn btn-sm btn-primary academics_list-item-btn" style="color: #fff;background-color: orange; border-color: orange;">學術專長</button>
                            @endif
                        @endif
                    @endforeach
                @else
                     <button class="btn btn-sm btn-primary edit-item-btn">編輯</button>
                @endif
            @elseif(isset($item['item_member_id'])  && $item['item_member_id'] != $item['member_id'])
                {{-- <span class="btn btn-sm btn-primary" style="color: #fff;background-color: grey; border-color: grey;">編輯</span> --}}
                @if(isset($item['academic_w_id']))
                @foreach ($item['academic_w_id'] as $academic_w_id)
                    @if (!isset($item['priv_edit_academics']) || $item['priv_edit_academics'])
                        @if($academic_w_id==$item['member_id'])
                                    <button class="btn btn-sm btn-primary specialty_list-item-btn" style="color: #fff;background-color: green; border-color: green;">學門專長</button>
                                    <button class="btn btn-sm btn-primary academics_list-item-btn" style="color: #fff;background-color: orange; border-color: orange;">學術專長</button>
                         @endif
                    @endif
                @endforeach
                @endif
            @else
                @if(isset($item['member_department']))
                    @foreach ($item['member_department'] as $m_department)
                        @if($m_department != $item['my_department'])
                         {{-- <button class="btn btn-sm btn-primary edit-item-btn">編輯123</button> --}}
                         @else
                            <button class="btn btn-sm btn-primary edit-item-btn">編輯</button>
                            @if (!isset($item['priv_edit_academics']) || $item['priv_edit_academics'])
                                <button class="btn btn-sm btn-primary academics_list-item-btn" style="color: #fff;background-color: orange; border-color: orange;">學術專長</button>
                            @endif
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

        

    </td>
</tr>