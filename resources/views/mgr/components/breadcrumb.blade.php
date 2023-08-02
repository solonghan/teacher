<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">
                {{ $title }}
                @foreach ($btns??array() as $btn)
                <a href="{{$btn[2]}}" type="button" class="btn btn-sm btn-outline-{{$btn[3]}} btn-icon waves-effect waves-light" data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-html="true" title="{{$btn[1]}}">{!! $btn[0] !!}</a>
                @endforeach
            </h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('mgr.home') }}">Dashboard</a></li>
                    @if (isset($li_1) && $li_1 != '')
                    <li class="breadcrumb-item"><a href="{{ $li_1_url }}">{{ $li_1 }}</a></li>
                    @endif
                    @if(isset($title))
                        <li class="breadcrumb-item active">{{ $title }}</li>
                    @endif
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
