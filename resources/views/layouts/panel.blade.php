<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.png') }}">
    <title>@yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/font/bootstrap-icons.css') }}">
    <link rel="preload" href="{{ asset('css/theme.min.css') }}" data-hs-appearance="default" as="style">
    <link rel="preload" href="{{ asset('css/theme-dark.min.css') }}" data-hs-appearance="dark" as="style">
    @yield('styles')

    <style data-hs-appearance-onload-styles>
        * {
            transition: unset !important;
        }

        body {
            opacity: 1 !important;
        }

        body> :not(#loading-spinner) {
            opacity: 0 !important;
        }
    </style>

    <!-- ========== PRELOADER ========== -->
    <style>
        .preloader-overlay {
            background-color: rgba(var(--bs-body-bg-rgb), 0.8) !important;
        }
    </style>
    <div id="loading-spinner"
        class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center preloader-overlay"
        style="z-index: 9999;">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <script>
        (function () {
            var preloader = document.getElementById('loading-spinner');

            function showLoader() {
                if (preloader) {
                    preloader.style.transition = 'none';
                    preloader.style.opacity = '1';
                    preloader.style.display = 'flex';
                    preloader.style.pointerEvents = 'auto';
                }
            }

            window.addEventListener('load', function () {
                if (preloader) {
                    preloader.style.transition = 'opacity 0.3s ease-out';
                    preloader.style.opacity = '0';
                    preloader.style.pointerEvents = 'none';
                    setTimeout(function () {
                        if (preloader.style.opacity === '0') {
                            preloader.style.display = 'none';
                        }
                    }, 300);
                }
            });

            window.addEventListener('pageshow', function (event) {
                if (event.persisted && preloader) {
                    preloader.style.transition = 'none';
                    preloader.style.opacity = '1';
                    preloader.style.display = 'flex';
                    preloader.style.pointerEvents = 'auto';

                    setTimeout(function () {
                        preloader.style.transition = 'opacity 0.3s ease-out';
                        preloader.style.opacity = '0';
                        preloader.style.pointerEvents = 'none';
                        setTimeout(function () {
                            if (preloader.style.opacity === '0') {
                                preloader.style.display = 'none';
                            }
                        }, 300);
                    }, 50);
                }
            });

            document.addEventListener('click', function (e) {
                var link = e.target.closest('a');
                if (link &&
                    link.getAttribute('href') &&
                    !link.getAttribute('href').startsWith('#') &&
                    !link.getAttribute('href').startsWith('javascript:') &&
                    link.getAttribute('target') !== '_blank' &&
                    !e.ctrlKey && !e.metaKey && !e.shiftKey) {
                    showLoader();
                }
            });

            document.addEventListener('submit', function (e) {
                if (!e.target.closest('.js-step-form')) {
                    // Evitar que el loader se active para forms de SweetAlert2 no confirmados
                    let form = e.target.closest('.requires-confirmation');
                    if (form && form.getAttribute('data-swal-confirmed') !== 'true') {
                        return; // Dejar pasar para que actúe SweetAlert
                    }
                    showLoader();
                }
            });
        })();
    </script>
    <!-- ========== END PRELOADER ========== -->

    <script>
        window.hs_config = {
            "autopath": "@@autopath",
            "deleteLine": "hs-builder:delete",
            "deleteLine:build": "hs-builder:build-delete",
            "deleteLine:dist": "hs-builder:dist-delete",
            "previewMode": false,
            "startPath": "/index.html",
            "vars": {
                "themeFont": "https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap",
                "version": "?v=1.0"
            },
            "layoutBuilder": {
                "extend": {
                    "switcherSupport": true
                },
                "header": {
                    "layoutMode": "default",
                    "containerMode": "container-fluid"
                },
                "sidebarLayout": "default"
            },
            "themeAppearance": {
                "layoutSkin": "default",
                "sidebarSkin": "default",
                "styles": {
                    "colors": {
                        "primary": "#377dff",
                        "transparent": "transparent",
                        "white": "#fff",
                        "dark": "132144",
                        "gray": {
                            "100": "#f9fafc",
                            "900": "#1e2022"
                        }
                    },
                    "font": "Inter"
                }
            },
            "languageDirection": {
                "lang": "en"
            },
            "skipFilesFromBundle": {
                "dist": ["assets/js/hs.theme-appearance.js", "assets/js/hs.theme-appearance-charts.js",
                    "assets/js/demo.js"
                ],
                "build": ["assets/css/theme.css",
                    "assets/vendor/hs-navbar-vertical-aside/dist/hs-navbar-vertical-aside-mini-cache.js",
                    "assets/js/demo.js", "assets/css/theme-dark.css", "assets/css/docs.css",
                    "assets/vendor/icon-set/style.css", "assets/js/hs.theme-appearance.js",
                    "assets/js/hs.theme-appearance-charts.js",
                    "node_modules/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js",
                    "assets/js/demo.js"
                ]
            },
            "minifyCSSFiles": ["assets/css/theme.css", "assets/css/theme-dark.css"],
            "copyDependencies": {
                "dist": {
                    "*assets/js/theme-custom.js": ""
                },
                "build": {
                    "*assets/js/theme-custom.js": "",
                    "node_modules/bootstrap-icons/font/*fonts/**": "assets/css"
                }
            },
            "buildFolder": "",
            "replacePathsToCDN": {},
            "directoryNames": {
                "src": "./src",
                "dist": "./dist",
                "build": "./build"
            },
            "fileNames": {
                "dist": {
                    "js": "theme.min.js",
                    "css": "theme.min.css"
                },
                "build": {
                    "css": "theme.min.css",
                    "js": "theme.min.js",
                    "vendorCSS": "vendor.min.css",
                    "vendorJS": "vendor.min.js"
                }
            },
            "fileTypes": "jpg|png|svg|mp4|webm|ogv|json"
        }
        window.hs_config.gulpRGBA = (p1) => {
            const options = p1.split(',')
            const hex = options[0].toString()
            const transparent = options[1].toString()

            var c;
            if (/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)) {
                c = hex.substring(1).split('');
                if (c.length == 3) {
                    c = [c[0], c[0], c[1], c[1], c[2], c[2]];
                }
                c = '0x' + c.join('');
                return 'rgba(' + [(c >> 16) & 255, (c >> 8) & 255, c & 255].join(',') + ',' + transparent + ')';
            }
            throw new Error('Bad Hex');
        }
        window.hs_config.gulpDarken = (p1) => {
            const options = p1.split(',')

            let col = options[0].toString()
            let amt = -parseInt(options[1])
            var usePound = false

            if (col[0] == "#") {
                col = col.slice(1)
                usePound = true
            }
            var num = parseInt(col, 16)
            var r = (num >> 16) + amt
            if (r > 255) {
                r = 255
            } else if (r < 0) {
                r = 0
            }
            var b = ((num >> 8) & 0x00FF) + amt
            if (b > 255) {
                b = 255
            } else if (b < 0) {
                b = 0
            }
            var g = (num & 0x0000FF) + amt
            if (g > 255) {
                g = 255
            } else if (g < 0) {
                g = 0
            }
            return (usePound ? "#" : "") + (g | (b << 8) | (r << 16)).toString(16)
        }
        window.hs_config.gulpLighten = (p1) => {
            const options = p1.split(',')

            let col = options[0].toString()
            let amt = parseInt(options[1])
            var usePound = false

            if (col[0] == "#") {
                col = col.slice(1)
                usePound = true
            }
            var num = parseInt(col, 16)
            var r = (num >> 16) + amt
            if (r > 255) {
                r = 255
            } else if (r < 0) {
                r = 0
            }
            var b = ((num >> 8) & 0x00FF) + amt
            if (b > 255) {
                b = 255
            } else if (b < 0) {
                b = 0
            }
            var g = (num & 0x0000FF) + amt
            if (g > 255) {
                g = 255
            } else if (g < 0) {
                g = 0
            }
            return (usePound ? "#" : "") + (g | (b << 8) | (r << 16)).toString(16)
        }
    </script>
</head>

<body class="has-navbar-vertical-aside navbar-vertical-aside-show-xl footer-offset">
    <script src="{{ asset('js/hs.theme-appearance.js') }}"></script>
    <script src="{{ asset('vendor/hs-navbar-vertical-aside/dist/hs-navbar-vertical-aside-mini-cache.js') }}"></script>

    @include('includes.panel.userOptions')

    @include('includes.panel.menu')

    @yield('content')

    @include('includes.panel.activity')

    <script src="{{ asset('vendor/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/hs-navbar-vertical-aside/dist/hs-navbar-vertical-aside.min.js') }}"></script>
    <script src="{{ asset('vendor/hs-form-search/dist/hs-form-search.min.js') }}"></script>
    <script src="{{ asset('js/theme.min.js') }}"></script>

    <script>
        (function () {
            @if (!Auth:: check() || !Auth:: user() -> theme_preference)
            localStorage.removeItem('hs_theme')
            @endif

            window.onload = function () {
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

                const setActiveStyle = function () {
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

                // Agregar listeners y marcar estado inicial
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

                // Escuchar cambios externos en el tema
                window.addEventListener('on-hs-appearance-change', setActiveStyle)

                return true
            }
        })()
    </script>

    <script>
        $(function () {
            const ESTADOS = {
                disponible: {
                    color: 'success',
                    label: 'Disponible'
                },
                ocupado: {
                    color: 'danger',
                    label: 'Ocupado'
                },
                ausente: {
                    color: 'warning',
                    label: 'Ausente'
                },
                privado: {
                    color: 'secondary',
                    label: 'Privado'
                }
            };

            const $toggle = $('#navSubmenuPagesAccountDropdown1');
            const $legend = $toggle.find('.legend-indicator');
            const $avatar = $('#avatar-status-indicator');
            const $dropdown = $('.navbar-dropdown-sub-menu');

            const BG_CLASSES = 'bg-success bg-danger bg-warning bg-warning-custom bg-secondary';
            const AVATAR_CLASSES =
                'avatar-status-success avatar-status-danger avatar-status-warning avatar-status-warning-custom avatar-status-secondary';

            function updateEstadoUI(estado) {
                const cfg = ESTADOS[estado];

                $legend
                    .removeClass(BG_CLASSES)
                    .addClass(`bg-${cfg.color}`);

                $toggle.find('span:last').text(cfg.label);

                $avatar
                    .removeClass(AVATAR_CLASSES)
                    .addClass(`avatar-status-${cfg.color}`);

                $('.estado-option')
                    .removeClass('active')
                    .find('i').remove();

                $(`.estado-option[data-estado="${estado}"]`)
                    .addClass('active')
                    .append('<i class="bi-check-lg float-end"></i>');
            }

            function cambiarEstado(estado) {
                $.post('{{ route('user.estado') }}', {
                    estado,
                    _token: '{{ csrf_token() }}'
                })
                    .done(r => {
                        if (r.success) {
                            updateEstadoUI(r.estado || estado);
                            $dropdown.removeClass('show');
                        }
                    })
                    .fail(err => {
                        console.error(err);
                        alert('Error al actualizar el estado.');
                    });
            }

            $('.estado-option').on('click', function (e) {
                e.preventDefault();
                cambiarEstado($(this).data('estado'));
            });

            $('#restablecer-estado').on('click', e => {
                e.preventDefault();
                cambiarEstado('disponible');
            });

        });

        $(document).on('avatar-updated', (_, url) => {
            $('#navbar-avatar-img, #dropdown-avatar-img').attr('src', url).show();
            $('#navbar-avatar-initials, #dropdown-avatar-initials').hide();
        });
    </script>

    @auth
    <!-- Toast de Advertencia de Inactividad -->
    <div class="toast-container position-fixed top-0 end-0 p-3 mt-5 mt-md-0" style="z-index: 10600;">
        <div id="sessionTimeoutToast" class="toast custom-toast bg-warning text-dark border-0" role="alert"
            aria-live="assertive" aria-atomic="true" data-bs-autohide="false">
            <div class="custom-toast-icon-wrapper">
                <div class="custom-toast-icon-bg">
                    <i class="bi-exclamation-triangle text-warning"></i>
                </div>
            </div>
            <div class="custom-toast-content">
                <div class="custom-toast-title">Aviso de Inactividad</div>
                <div class="custom-toast-message">
                    Tu sesión expirará en <strong id="session-timeout-countdown" class="text-dark">05:00</strong> si no
                    detectamos actividad. <br>
                    <small>Mueve el mouse para continuar.</small>
                </div>
            </div>
            <button type="button" class="custom-toast-close text-dark" data-bs-dismiss="toast" aria-label="Close"
                id="stay-logged-in-btn">
                <i class="bi-x-lg"></i>
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            (function () {
                const SESSION_LIFETIME = {{ (int) config('session.lifetime')
            }} * 60; // En segundos
        const WARNING_THRESHOLD = 300; // 5 minutos fijos

        let warningTimer = null;
        let countdownInterval = null;
        let lastPing = Date.now();
        const PING_INTERVAL = 5 * 60 * 1000; // Ping cada 5 minutos de actividad

        const toastElement = document.getElementById('sessionTimeoutToast');
        const countdownDisplay = document.getElementById('session-timeout-countdown');
        const stayLoggedInBtn = document.getElementById('stay-logged-in-btn');

        if (!toastElement) return;

        let timeoutToast = null;
        try {
            timeoutToast = new bootstrap.Toast(toastElement);
        } catch (e) {
            console.error('Error inicializando toast de sesión:', e);
        }

        function resetTimer() {
            if (toastElement.classList.contains('show')) return;

            clearTimeout(warningTimer);
            clearInterval(countdownInterval);

            let waitTime = (SESSION_LIFETIME - WARNING_THRESHOLD);
            if (waitTime < 0) waitTime = 1;

            warningTimer = setTimeout(showWarning, waitTime * 1000);
        }

        function showWarning() {
            if (timeoutToast) {
                timeoutToast.show();
            } else {
                try {
                    timeoutToast = new bootstrap.Toast(toastElement);
                    timeoutToast.show();
                } catch (e) { }
            }

            let countdownSeconds = WARNING_THRESHOLD;
            updateCountdownDisplay(countdownSeconds);

            countdownInterval = setInterval(() => {
                countdownSeconds--;
                updateCountdownDisplay(countdownSeconds);

                if (countdownSeconds <= 0) {
                    clearInterval(countdownInterval);
                    logout();
                }
            }, 1000);
        }

        function updateCountdownDisplay(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = Math.floor(seconds % 60);
            if (countdownDisplay) {
                countdownDisplay.textContent = `${mins}:${secs.toString().padStart(2, '0')}`;
            }
        }

        function stayLoggedIn() {
            fetch('{{ route('session.ping') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => {
                if (response.ok) {
                    lastPing = Date.now();
                    if (timeoutToast) timeoutToast.hide();
                    clearTimeout(warningTimer);
                    clearInterval(countdownInterval);
                    resetTimer();
                } else {
                    logout();
                }
            }).catch(() => logout());
        }

        function silentPing() {
            fetch('{{ route('session.ping') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => {
                if (response.ok) {
                    lastPing = Date.now();
                }
            });
        }

        function logout() {
            fetch('{{ route('logout') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).finally(() => {
                window.location.href = '/login';
            });
        }

        // Eventos que reinician el temporizador
        const resetEvents = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];

        let lastReset = 0;
        const debouncedReset = () => {
            const now = Date.now();

            // Si el Toast de inactividad está visible y el usuario mueve el mouse/teclea, ocultarlo automáticamente y renovar.
            if (toastElement.classList.contains('show')) {
                if (timeoutToast) timeoutToast.hide();
                stayLoggedIn();
                return;
            }

            if (now - lastReset > 10000) { // Cada 10 segundos de actividad
                lastReset = now;
                resetTimer();

                // Si ha pasado suficiente tiempo, enviar ping silencioso para mantener sesión en el servidor
                if (now - lastPing > PING_INTERVAL) {
                    silentPing();
                }
            }
        };

        resetEvents.forEach(event => {
            document.addEventListener(event, debouncedReset, { passive: true });
        });

        if (stayLoggedInBtn) {
            stayLoggedInBtn.addEventListener('click', stayLoggedIn);
        }

        resetTimer();
            }) ();
        });
    </script>
    @endauth

    @auth
    <style>
        .custom-toast {
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            overflow: hidden;
            min-width: 300px;
            max-width: 100%;
        }

        .custom-toast-icon-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            background-color: rgba(0, 0, 0, 0.1);
        }

        .custom-toast-icon-bg {
            background-color: white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .custom-toast-content {
            padding: 12px 15px;
            flex-grow: 1;
        }

        .custom-toast-title {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 2px;
        }

        .custom-toast-message {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .custom-toast-close {
            background: none;
            border: none;
            color: white;
            opacity: 0.7;
            font-size: 1.2rem;
            padding: 12px 15px;
            cursor: pointer;
            display: flex;
            align-items: flex-start;
        }

        .custom-toast-close:hover {
            opacity: 1;
        }
    </style>

    @if(session('notification'))
    @php
    $type = session('notification.alert-type', 'info');
    $typeLower = strtolower(trim($type));

    $toastClass = 'bg-info text-white';
    $iconClass = 'bi-info-circle text-info';
    $title = 'Información';

    if ($typeLower === 'success' || str_contains($typeLower, 'creacion') || str_contains($typeLower, 'creaci')) {
    $toastClass = 'bg-success text-white';
    $iconClass = 'bi-check-lg text-success';
    $title = 'Éxito';
    } elseif ($typeLower === 'error' || $typeLower === 'danger' || str_contains($typeLower, 'desactiv') ||
    str_contains($typeLower, 'elimin')) {
    $toastClass = 'bg-danger text-white';
    $iconClass = 'bi-exclamation-octagon text-danger';
    $title = 'Atención';
    } elseif ($typeLower === 'warning' || str_contains($typeLower, 'adver')) {
    $toastClass = 'bg-warning text-dark';
    $iconClass = 'bi-exclamation-triangle text-warning';
    $title = 'Aviso';
    } elseif ($typeLower === 'info' || str_contains($typeLower, 'actualiz')) {
    $toastClass = 'bg-info text-white';
    $iconClass = 'bi-arrow-repeat text-info';
    $title = 'Actualización';
    }
    @endphp

    <style>
        .custom-toast {
            border-radius: 4px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            overflow: hidden;
            color: #fff;
            min-width: 300px;
            max-width: 100%;
        }

        .custom-toast-success {
            background-color: #4CAF50;
        }

        .custom-toast-error {
            background-color: #F44336;
        }

        .custom-toast-info {
            background-color: #2196F3;
        }

        .custom-toast-warning {
            background-color: #FF9800;
        }

        .custom-toast-icon-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            background-color: rgba(0, 0, 0, 0.1);
        }

        .custom-toast-icon-bg {
            background-color: white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        .custom-toast-content {
            padding: 12px 15px;
            flex-grow: 1;
        }

        .custom-toast-title {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 2px;
        }

        .custom-toast-message {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .custom-toast-close {
            background: none;
            border: none;
            color: white;
            opacity: 0.7;
            font-size: 1.2rem;
            padding: 12px 15px;
            cursor: pointer;
            display: flex;
            align-items: flex-start;
        }

        .custom-toast-close:hover {
            opacity: 1;
        }
    </style>

    <!-- Global Toast Notification -->
    <div class="toast-container position-fixed top-0 end-0 p-3 mt-5 mt-md-0" style="z-index: 10500;">
        <div id="globalToast" class="toast custom-toast {{ $toastClass }} border-0" role="alert" aria-live="assertive"
            aria-atomic="true">
            <div class="custom-toast-icon-wrapper">
                @if($typeLower !== 'info' && !str_contains($typeLower, 'actualiz'))
                <div class="custom-toast-icon-bg">
                    <i class="{{ $iconClass }}"></i>
                </div>
                @else
                <i class="{{ $iconClass }} fs-3"></i>
                @endif
            </div>
            <div class="custom-toast-content">
                <div class="custom-toast-title">{{ $title }}</div>
                <div class="custom-toast-message">{{ session('notification.message') }}</div>
            </div>
            <button type="button" class="custom-toast-close" data-bs-dismiss="toast" aria-label="Close">
                <i class="bi-x-lg"></i>
            </button>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var toastEl = document.getElementById('globalToast');
            if (toastEl) {
                // Aquí defines los milisegundos. 10000 ms = 10 segundos
                var toast = new bootstrap.Toast(toastEl, { delay: 10000 });
                toast.show();
            }
        });
    </script>
    @endif
    @endauth

    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Interceptar formularios que requieren confirmación
            document.querySelectorAll('form.requires-confirmation').forEach(function (form) {
                form.addEventListener('submit', function (e) {

                    // Si ya fue confirmado, permitir que pase
                    if (form.getAttribute('data-swal-confirmed') === 'true') {
                        return;
                    }

                    e.preventDefault();

                    // Ocultar forzosamente el loader global que haya saltado
                    let preloader = document.getElementById('loading-spinner');
                    if (preloader) {
                        preloader.style.transition = 'none';
                        preloader.style.opacity = '0';
                        preloader.style.display = 'none';
                        preloader.style.pointerEvents = 'none';
                    }

                    let msg = form.getAttribute('data-message') || '¿Estás seguro de continuar?';
                    let isRestore = msg.toLowerCase().includes('reactivar');

                    Swal.fire({
                        title: isRestore ? '¿Reactivar registro?' : '¿Desactivar registro?',
                        text: msg,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: isRestore ? '#3085d6' : '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Sí, continuar',
                        cancelButtonText: 'Cancelar',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Marcar como confirmado
                            form.setAttribute('data-swal-confirmed', 'true');

                            // Mostrar manual el loader antes de submitir por JS
                            if (preloader) {
                                preloader.style.transition = 'none';
                                preloader.style.opacity = '1';
                                preloader.style.display = 'flex';
                                preloader.style.pointerEvents = 'auto';
                            }

                            form.submit();
                        }
                    });
                });
            });
        });
    </script>

    @stack('scripts')
</body>

</html>