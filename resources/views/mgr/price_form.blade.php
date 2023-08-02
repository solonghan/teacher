

    <div class="col-lg-2 col-sm-12">
        <input type="hidden" name="id" value="{{ $id }}">
    </div>
    <div class="col-lg-3 col-sm-12">
        級距起
    </div>
    <div class="col-lg-3 col-sm-12">
        級距迄        
    </div>
    <div class="col-lg-3 col-sm-12">
        會員價格
    </div>


@for ($i = 1; $i <= 10; $i++)

    <div class="col-lg-2 col-sm-12 mb-2" style="line-height:34px; text-align:center; padding-right:0;">
        級距{{ $i }}
    </div>
    <div class="col-lg-3 col-sm-12 mb-2">
        <input type="number" class="form-control " name="range_start{{ $i }}" placeholder="KG" value="{{ $data['range_start'.$i]??'' }}">
    </div>
    <div class="col-lg-3 col-sm-12 mb-2">
        <input type="number" class="form-control " name="range_end{{ $i }}" placeholder="KG" value="{{ $data['range_end'.$i]??'' }}">
    </div>
    <div class="col-lg-3 col-sm-12 mb-2">
        <input type="number" class="form-control " name="price{{ $i }}" placeholder="$" value="{{ $data['price'.$i]??'' }}">
    </div>

@endfor