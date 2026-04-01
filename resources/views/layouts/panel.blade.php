<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta id="theme-color-meta" name="theme-color" content="#ffffff">
    @include('includes.loading-screen')
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.png') }}">
    <title>@yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/vendor.min.css') }}">
    <link rel="prefetch" href="{{ asset('css/theme.min.css') }}" data-hs-appearance="default" as="style">
    <link rel="prefetch" href="{{ asset('css/theme-dark.min.css') }}" data-hs-appearance="dark" as="style">
    <link rel="preload" href="{{ asset('js/theme.min.js') }}" as="script">
    <link rel="preload" href="{{ asset('js/vendor.min.js') }}" as="script">
    @yield('styles')

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
</head>

<body class="has-navbar-vertical-aside navbar-vertical-aside-show-xl footer-offset">
    <script src="{{ asset('js/hs.theme-appearance.js') }}"></script>
    <script src="{{ asset('vendor/hs-navbar-vertical-aside/dist/hs-navbar-vertical-aside-mini-cache.js') }}"></script>

    @include('includes.panel.UserOptions')

    @include('includes.panel.menu')

    @yield('content')

    @include('includes.panel.Activity')

    <script src="{{ asset('js/vendor.min.js') }}"></script>
    <script src="{{ asset('js/theme.min.js') }}"></script>

    <script>
        (function() {
            @if (!Auth::check() || !Auth::user()->theme_preference)
                localStorage.removeItem('hs_theme')
            @endif

            window.onload = function() {
                new HSSideNav('.js-navbar-vertical-aside').init()
                new HSFormSearch('.js-form-search')
                HSBsDropdown.init()
                initThemeDropdown()
            }

            function initThemeDropdown() {
                const $dropdownBtn = document.getElementById('selectThemeDropdown')
                if (!$dropdownBtn || typeof HSThemeAppearance === 'undefined') {
                    if (!$dropdownBtn) return false
                    setTimeout(initThemeDropdown, 100)
                    return false
                }

                const $variants = document.querySelectorAll(`[aria-labelledby="selectThemeDropdown"] [data-icon]`)
                if (!$variants.length) return false

                const setActiveStyle = function() {
                    const originalTheme = HSThemeAppearance.getOriginalAppearance() || 'default'

                    $variants.forEach($item => {
                        const itemValue = $item.getAttribute('data-value')

                        if (itemValue === originalTheme) {
                            const icon = $item.getAttribute('data-icon')
                            if (icon && $dropdownBtn) {
                                $dropdownBtn.innerHTML = `<i class="${icon}"></i>`
                            }
                            $item.classList.add('active')
                        } else {
                            $item.classList.remove('active')
                        }
                    })
                }

                $variants.forEach($item => {
                    if (!$item.hasAttribute('data-theme-listener')) {
                        $item.setAttribute('data-theme-listener', 'true')
                        $item.addEventListener('click', (e) => {
                            e.preventDefault()
                            const themeValue = $item.getAttribute('data-value')
                            if (themeValue) {
                                HSThemeAppearance.setAppearance(themeValue)
                            }
                        })
                    }
                })

                setActiveStyle()

                window.addEventListener('on-hs-appearance-change', setActiveStyle)

                return true
            }
        })()
    </script>

    @include ('includes.notification-toast')

    @stack('scripts')
    <script>
        // Función para cambiar el color del header
        function updateMetaThemeColor() {
            const metaThemeColor = document.getElementById('theme-color-meta');
            if (!metaThemeColor || typeof HSThemeAppearance === 'undefined') return;

            // Obtiene el tema activo ('default' o 'dark')
            const currentTheme = HSThemeAppearance.getAppearance();

            if (currentTheme === 'dark') {
                // Color para el tema oscuro (puedes ajustarlo si tu fondo es distinto)
                metaThemeColor.setAttribute('content', '#1e2022');
            } else {
                // Color para el tema claro
                metaThemeColor.setAttribute('content', '#ffffff');
            }
        }

        // 1. Ejecutar al cargar la página
        updateMetaThemeColor();

        // 2. Escuchar cada vez que el usuario cambia el tema en el menú
        window.addEventListener('on-hs-appearance-change', updateMetaThemeColor);
    </script>
</body>

</html>
