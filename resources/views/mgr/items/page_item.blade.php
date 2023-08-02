<tr data-id="{{ $item->id }}">
    <td>{{ $item->id }}</td>
    <td>{{ $item->title }}</td>
    <td>{{ $item->created_at }}</td>
    <td>
        <button class="btn btn-sm btn-primary edit-item-btn">編輯</button>
        
        <button class="btn btn-sm btn-danger del-item-btn">刪除</button>
    </td>
</tr>