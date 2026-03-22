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
    <!-- Modal de tiempo de espera de sesión -->
    <div class="modal fade" id="sessionTimeoutModal" tabindex="-1" role="dialog" aria-labelledby="sessionTimeoutModalLabel" aria-hidden="true" data-bs-backdrop="static" style="z-index: 9999;">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sessionTimeoutModalLabel">Sesión a punto de expirar</h5>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <i class="bi-exclamation-triangle-fill text-warning fs-1"></i>
                    </div>
                    <p>Su sesión está a punto de expirar debido a la inactividad. ¿Desea continuar conectado?</p>
                    <div class="fs-4 fw-bold text-danger" id="session-timeout-countdown">05:00</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary w-100" id="stay-logged-in-btn">Mantener sesión iniciada</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            (function() {
                const SESSION_LIFETIME = {{ (int) config('session.lifetime') }} * 60; // En segundos
                const WARNING_THRESHOLD = 300; // 5 minutos fijos
                
                let warningTimer = null;
                let countdownInterval = null;
                let lastPing = Date.now();
                const PING_INTERVAL = 5 * 60 * 1000; // Ping cada 5 minutos de actividad
                
                const modalElement = document.getElementById('sessionTimeoutModal');
                const countdownDisplay = document.getElementById('session-timeout-countdown');
                const stayLoggedInBtn = document.getElementById('stay-logged-in-btn');
                
                if (!modalElement) return;

                let timeoutModal = null;
                try {
                    timeoutModal = new bootstrap.Modal(modalElement);
                } catch (e) {
                    console.error('Error inicializando modal de sesión:', e);
                }

                function resetTimer() {
                    // Si el modal está visible, NO reiniciar los temporizadores automáticamente con el mouse
                    if (modalElement.classList.contains('show')) return;

                    clearTimeout(warningTimer);
                    clearInterval(countdownInterval);
                    
                    // Configurar temporizador para la advertencia
                    let waitTime = (SESSION_LIFETIME - WARNING_THRESHOLD);
                    if (waitTime < 0) waitTime = 1;

                    warningTimer = setTimeout(showWarning, waitTime * 1000);
                }

                function showWarning() {
                    if (timeoutModal) {
                        timeoutModal.show();
                    } else {
                        try {
                            timeoutModal = new bootstrap.Modal(modalElement);
                            timeoutModal.show();
                        } catch (e) {}
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
                            if (timeoutModal) timeoutModal.hide();
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
                    // Solo reiniciar si el modal no está visible
                    if (!modalElement.classList.contains('show')) {
                        if (now - lastReset > 10000) { // Cada 10 segundos de actividad
                            lastReset = now;
                            resetTimer();
                            
                            // Si ha pasado suficiente tiempo, enviar ping silencioso para mantener sesión en el servidor
                            if (now - lastPing > PING_INTERVAL) {
                                silentPing();
                            }
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
            })();
        });
    </script>
    @endauth

    @stack('scripts')
</body>

</html>