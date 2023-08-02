<tr data-id="{{ $item['id'] }}">
    @foreach ($item['data'] as $index => $obj)
    <td
        @if (isset($th_title[$index]) && $th_title[$index]['width'] != '')
        style = "max-width: {{ $th_title[$index]['width'] }}"
        @endif
    >{!! $obj !!}</td>
    @endforeach
   
</tr>