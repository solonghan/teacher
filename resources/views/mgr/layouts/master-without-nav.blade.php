<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-topbar="light">

    <head>
    <meta charset="utf-8" />
    <title>@yield('title') | {{ env('BACKEND_NAME') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="{{ env('BACKEND_DESCRIPTION') }}" name="description" />
    <meta content="{{ env('APP_NAME') }}" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.ico')}}">
        @include('mgr.layouts.head-css')
  </head>

    @yield('body')

    @yield('content')

    @include('mgr.layouts.vendor-scripts')
    </body>
</html>
