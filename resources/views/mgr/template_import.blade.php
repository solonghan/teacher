@extends('mgr.layouts.master')
@section('title') {{$title}} @endsection
@section('css')
<link href="{{ URL::asset('assets/libs/quill/quill.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ URL::asset('css/cropper.min.css') }}" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="dist/cropper.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.1.20/jquery.fancybox.min.css" />

@endsection
@section('content')
@component('mgr.components.breadcrumb')
@slot('li_1_url') {{$parent_url}} @endslot
@slot('li_1') {{$parent}} @endslot
@slot('title') {{$title}} @endslot
@endcomponent
<div class="card row">
    <div class="card-body">
        <form action="{{ $form_action }}" method="POST" enctype="multipart/form-data" id="form">
            @csrf
            @if ($action == 'default')
            <button type="button" class="btn btn-sm btn-info" onclick="file.click();">上傳Excel</button>
            {{-- @if ($sample_file != '')
            <button type="button" class="btn btn-sm btn-success" onclick="window.open('{{ $sample_file }}');">{{ $sample_title??'下載範本' }}</button>
            @endif --}}
            <input type="file" name="file" id="file" style="position: absoulte; top:-100px; left:-100px; width:1px; height:1px;">
            <input type="hidden" name="action" value="default">
            @else
            <div class="table-responsive table-card mb-1">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            @foreach ($th_title as $item)
                            @if($item[1] == '') @continue @endif
                            <th scope="col" style="@if($item[2]!='') min-width:{{$item[2]}}px; @endif">{!! $item[0] !!}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                        <tr>
                            @foreach ($th_title as $f)
                            @if ($f[1] == '') @continue @endif
                            <td>
                                @if (is_array($item[$f[1]]))
                                    @foreach ($item[$f[1]] as $sub)
                                    {!! $sub !!}<br>
                                    @endforeach
                                @else
                                {!! $item[$f[1]] !!}
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button type="submit" class="btn btn-sm btn-primary">確認匯入</button>
            <input type="hidden" name="action" value="check">
            @endif
        </form>
    </div>
</div>

@endsection
@section('script')
<script>
$(document).ready(function(e) {
    $("#file").on('change', function () {
        $("#form").submit();
    });
});
</script>
@endsection