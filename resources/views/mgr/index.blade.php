@extends('mgr.layouts.master')
@section('title') Dashboard @endsection
@section('css')
<!-- <link href="assets/libs/jsvectormap/jsvectormap.min.css" rel="stylesheet" type="text/css" /> -->
<!-- <link href="assets/libs/swiper/swiper.min.css" rel="stylesheet" type="text/css" /> -->
@endsection
@section('content')
@component('mgr.components.breadcrumb')
@slot('li_1_url')  @endslot
@slot('li_1')  @endslot
@slot('title') @endslot
@endcomponent
<div class="row">
    
</div>
@endsection
@section('script')
<!-- apexcharts -->
<!-- <script src="{{ URL::asset('/assets/libs/apexcharts/apexcharts.min.js') }}"></script> -->
<!-- <script src="{{ URL::asset('/assets/libs/jsvectormap/jsvectormap.min.js') }}"></script> -->
<!-- <script src="{{ URL::asset('assets/libs/swiper/swiper.min.js')}}"></script> -->
<!-- dashboard init -->
<!-- <script src="{{ URL::asset('/assets/js/pages/dashboard-ecommerce.init.js') }}"></script> -->
<!-- <script src="{{ URL::asset('/assets/js/app.min.js') }}"></script> -->
@endsection
