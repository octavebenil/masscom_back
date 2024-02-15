<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8"/>
    <title> {{ config('app.name') }} | @yield('title') </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.ico') }}">

    @vite(['resources/scss/app.scss', 'resources/scss/bootstrap.scss'])
</head>
<body>
<!-- Begin page -->
@yield('content')
<!-- END layout-wrapper -->

<!-- App js -->
@vite(['resources/js/app.js', 'resources/js/parsley.js'])
@include('admin.includes._alert')

@stack('script')

</body>

</html>
