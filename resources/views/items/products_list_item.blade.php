<div class="product_item" onclick="location.href = `{{ route('products.detail', ['id'=>$id]) }}`">
    <img class="mb-3" src="{{ env('APP_URL').Storage::url($cover) }}" alt="{{ $name }}">
    <div class="product_content">
        <h6 class="product_title mb-4">
        {{ $name }}
        </h6>
        <div class="new_content">
        {{ $summary }}
        </div>
    </div>
</div>