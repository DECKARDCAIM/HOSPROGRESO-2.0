<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta id="theme-color-meta" name="theme-color" content="#ffffff">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.png') }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/vendor.min.css') }}">
    <link rel="preload" href="{{ asset('css/theme.min.css') }}" data-hs-appearance="default" as="style">
    <link rel="prefetch" href="{{ asset('css/theme-dark.min.css') }}" data-hs-appearance="dark" as="style">

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

    <script src="{{ asset('js/hs-config.js') }}"></script>
    @include('includes.loading-screen')
</head>

<body class="d-flex align-items-center min-h-100">
    <script src="{{ asset('js/hs.theme-appearance.js') }}"></script>

    @yield('content')

    <script src="{{ asset('js/vendor.min.js') }}"></script>
    <script src="{{ asset('js/theme.min.js') }}"></script>
    <script>
        function updateMetaThemeColor() {
            const metaThemeColor = document.getElementById('theme-color-meta');
            if (!metaThemeColor || typeof HSThemeAppearance === 'undefined') return;
            const currentTheme = HSThemeAppearance.getAppearance();

            if (currentTheme === 'dark') {
                metaThemeColor.setAttribute('content', '#1e2022');
            } else {
                metaThemeColor.setAttribute('content', '#ffffff');
            }
        }

        updateMetaThemeColor();
        window.addEventListener('on-hs-appearance-change', updateMetaThemeColor);
    </script>
</body>

</html>
