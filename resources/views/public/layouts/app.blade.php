<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8"/>
    <title> {{ config('app.name') }} | @yield('title') </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('assets/images/favicon.ico') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    @vite(['resources/scss/app.scss', 'resources/scss/bootstrap.scss'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body data-sidebar="dark">
<!-- Begin page -->
<div class="page-content">
    <div class="container-fluid">
        @yield('content')
    </div>
    <!-- container-fluid -->
    <!-- End Page-content -->
</div>
<!-- end main content-->
<!-- END layout-wrapper -->

<!-- App js -->
@vite(['resources/js/app.js', 'resources/js/parsley.js'])
@include('admin.includes._alert')
@stack('scripts')

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script type="module">
    $(document).ready(function () {
        new bootstrap.Tooltip(document.body, {
            selector: '[data-bs-toggle="tooltip"]',
        });
    });
</script>
</body>
</html>
