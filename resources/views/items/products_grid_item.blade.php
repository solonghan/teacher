<div class="col-sm-12 col-md-6 col-lg-4">
    <a href="{{ route('products.detail', ['id'=>$id]) }}">
        <div class="product_item">
            <img class="mb-3" src="{{ env('APP_URL').Storage::url($cover) }}" alt="{{ $name }}">
            <h6 class="product_title" style="color:initial;">
                {{ $name }}
            </h6>
        </div>
    </a>
</div>