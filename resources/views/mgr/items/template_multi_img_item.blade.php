<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6 multi_img_item">
    <a data-fancybox="gallery_{{ $field }}" href="{{ env('APP_URL').Storage::url($pic) }}">
        <img src="{{ env('APP_URL').Storage::url($pic) }}" class="img-thumbnail" style="width:100%;">
    </a>
    <button type="button" class="btn btn-sm btn-danger del-btn" onclick="del_multi_img(this, '{{ $pic }}', '{{ $field }}');">
        <i class="ri-delete-bin-2-line"></i>
    </button>
</div>