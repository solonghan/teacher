<div class="col-lg-12 multi_file_item" id="file_{{ $file_id }}">
    <button type="button" class="btn btn-sm btn-danger" onclick="delete_file('{{ $field }}', '{{ $file_id }}', '{{ $path }}');">
        <i class="ri-delete-bin-2-line"></i>
    </button>
    <a href="{{ env('APP_URL').'/file/download/'.$file_id }}">
        {{ $filename }}
    </a>
</div>