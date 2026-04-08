<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta id="theme-color-meta" name="theme-color" content="#ffffff">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('dist/img/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('dist/img/logo.png') }}">
    <title>@yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('dist/css/vendor.min.css') }}">
    <link rel="preload" href="{{ asset('dist/css/theme.min.css') }}" data-hs-appearance="default" as="style">
    <link rel="prefetch" href="{{ asset('dist/css/theme-dark.min.css') }}" data-hs-appearance="dark" as="style">
    <style data-hs-appearance-onload-styles>
        * {
            transition: unset !important;
        }

        body {
            opacity: 1 !important;
        }

        body> :not(#global-sync-loader) {
            opacity: 0 !important;
        }
    </style>
    <script src="{{ asset('dist/js/hs-config.js') }}"></script>
    @include('includes.Loading-screen')
</head>
<body class="d-flex align-items-center min-h-100">
    <script src="{{ asset('dist/js/hs.theme-appearance.js') }}"></script>
    @yield('content')
    <script src="{{ asset('dist/js/vendor-core.min.js') }}"></script>
    <script src="{{ asset('dist/js/vendor-ui.min.js') }}"></script>
    <script src="{{ asset('dist/js/theme.min.js') }}"></script>
    <script src="{{ asset('dist/js/hs.theme-appearance-helper.js') }}"></script>
</body>
</html>