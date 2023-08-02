<!doctype html >
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light" data-sidebar="light" data-sidebar-size="lg">

<head>
    <meta charset="utf-8" />
    <title>@yield('title')| {{ env('BACKEND_NAME') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="{{ env('BACKEND_DESCRIPTION') }}" name="description" />
    <meta content="{{ env('APP_NAME') }}" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.ico')}}">
    @include('mgr.layouts.head-css')
</head>

@section('body')
    @include('mgr.layouts.body')
@show
    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('mgr.layouts.topbar')
        @include('mgr.layouts.sidebar')

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            @include('mgr.layouts.footer')
        </div>
    </div>
    
    @include('mgr.layouts.vendor-scripts')
</body>

</html>
