<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.png') }}">
    <title>{{ config('app.name') }} - @yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/font/bootstrap-icons.css') }}">
    <link rel="preload" href="{{ asset('css/theme.min.css') }}" data-hs-appearance="default" as="style">
    <link rel="preload" href="{{ asset('css/theme-dark.min.css') }}" data-hs-appearance="dark" as="style">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    @yield('styles')

    <style data-hs-appearance-onload-styles>
      *
      {
        transition: unset !important;
      }

      body
      {
        opacity: 0;
      }
    </style>
    
    <script>
        window.hs_config = {"autopath":"@@autopath","deleteLine":"hs-builder:delete","deleteLine:build":"hs-builder:build-delete","deleteLine:dist":"hs-builder:dist-delete","previewMode":false,"startPath":"/index.html","vars":{"themeFont":"https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap","version":"?v=1.0"},"layoutBuilder":{"extend":{"switcherSupport":true},"header":{"layoutMode":"default","containerMode":"container-fluid"},"sidebarLayout":"default"},"themeAppearance":{"layoutSkin":"default","sidebarSkin":"default","styles":{"colors":{"primary":"#377dff","transparent":"transparent","white":"#fff","dark":"132144","gray":{"100":"#f9fafc","900":"#1e2022"}},"font":"Inter"}},"languageDirection":{"lang":"en"},"skipFilesFromBundle":{"dist":["assets/js/hs.theme-appearance.js","assets/js/hs.theme-appearance-charts.js","assets/js/demo.js"],"build":["assets/css/theme.css","assets/vendor/hs-navbar-vertical-aside/dist/hs-navbar-vertical-aside-mini-cache.js","assets/js/demo.js","assets/css/theme-dark.css","assets/css/docs.css","assets/vendor/icon-set/style.css","assets/js/hs.theme-appearance.js","assets/js/hs.theme-appearance-charts.js","node_modules/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js","assets/js/demo.js"]},"minifyCSSFiles":["assets/css/theme.css","assets/css/theme-dark.css"],"copyDependencies":{"dist":{"*assets/js/theme-custom.js":""},"build":{"*assets/js/theme-custom.js":"","node_modules/bootstrap-icons/font/*fonts/**":"assets/css"}},"buildFolder":"","replacePathsToCDN":{},"directoryNames":{"src":"./src","dist":"./dist","build":"./build"},"fileNames":{"dist":{"js":"theme.min.js","css":"theme.min.css"},"build":{"css":"theme.min.css","js":"theme.min.js","vendorCSS":"vendor.min.css","vendorJS":"vendor.min.js"}},"fileTypes":"jpg|png|svg|mp4|webm|ogv|json"}
        window.hs_config.gulpRGBA = (p1) => {
        const options = p1.split(',')
        const hex = options[0].toString()
        const transparent = options[1].toString()

        var c;
        if(/^#([A-Fa-f0-9]{3}){1,2}$/.test(hex)){
          c= hex.substring(1).split('');
          if(c.length== 3){
            c= [c[0], c[0], c[1], c[1], c[2], c[2]];
          }
          c= '0x'+c.join('');
          return 'rgba('+[(c>>16)&255, (c>>8)&255, c&255].join(',')+',' + transparent + ')';
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
    (function() {
      @if(!Auth::check() || !Auth::user()->theme_preference)
        localStorage.removeItem('hs_theme')
      @endif

      window.onload = function () {
        
        new HSSideNav('.js-navbar-vertical-aside').init()

        new HSFormSearch('.js-form-search')

        HSBsDropdown.init()

        setTimeout(function() {
          initThemeDropdown()
        }, 200)
      }

      // Función para inicializar el dropdown del tema
      function initThemeDropdown() {
        const $dropdownBtn = document.getElementById('selectThemeDropdown')
        if (!$dropdownBtn) {
          return false
        }

        // Esperar a que HSThemeAppearance esté disponible
        if (typeof HSThemeAppearance === 'undefined') {
          setTimeout(initThemeDropdown, 100)
          return false
        }

        const $dropdownMenu = document.querySelector('[aria-labelledby="selectThemeDropdown"]')
        const $variants = document.querySelectorAll(`[aria-labelledby="selectThemeDropdown"] [data-icon]`)
        if (!$variants.length) {
          return false
        }

        const updateDropdownTheme = function() {
          if (!$dropdownMenu) return
          
          let currentTheme = HSThemeAppearance.getOriginalAppearance()
          
          // Si el tema es 'auto', obtener el tema real aplicado
          if (currentTheme === 'auto') {
            currentTheme = HSThemeAppearance.getAppearance()
          }
          
          // Normalizar 'default' para comparación
          if (!currentTheme || currentTheme === 'default') {
            currentTheme = 'default'
          }
          
          // Aplicar estilos según el tema
          if (currentTheme === 'dark') {
            $dropdownMenu.style.setProperty('background-color', '#1e2022', 'important')
            $dropdownMenu.style.setProperty('color', '#fff', 'important')
            $dropdownMenu.classList.add('dropdown-menu-dark')
            $dropdownMenu.classList.remove('bg-white')
            
            // Actualizar colores de los items
            const items = $dropdownMenu.querySelectorAll('.dropdown-item')
            items.forEach(item => {
              item.style.setProperty('color', '#fff', 'important')
              const icon = item.querySelector('i')
              if (icon) {
                icon.style.setProperty('color', '#fff', 'important')
              }
              const span = item.querySelector('span')
              if (span) {
                span.style.setProperty('color', '#fff', 'important')
              }
            })
          } else {
            $dropdownMenu.style.setProperty('background-color', '#fff', 'important')
            $dropdownMenu.style.setProperty('color', '#1e2022', 'important')
            $dropdownMenu.classList.remove('dropdown-menu-dark')
            $dropdownMenu.classList.add('bg-white')
            
            // Actualizar colores de los items
            const items = $dropdownMenu.querySelectorAll('.dropdown-item')
            items.forEach(item => {
              item.style.setProperty('color', '#1e2022', 'important')
              const icon = item.querySelector('i')
              if (icon) {
                icon.style.setProperty('color', '#1e2022', 'important')
              }
              const span = item.querySelector('span')
              if (span) {
                span.style.setProperty('color', '#1e2022', 'important')
              }
            })
          }
        }

        const setActiveStyle = function () {
          // Obtener el tema original seleccionado (puede ser 'auto', 'default', o 'dark')
          const originalTheme = HSThemeAppearance.getOriginalAppearance()
          
          // Obtener el tema real aplicado para actualizar los estilos visuales
          let appliedTheme = originalTheme
          if (originalTheme === 'auto') {
            appliedTheme = HSThemeAppearance.getAppearance()
          }
          
          // Normalizar 'default' para los estilos
          if (!appliedTheme || appliedTheme === 'default') {
            appliedTheme = 'default'
          }
          
          // Actualizar el tema del dropdown basado en el tema aplicado (para los colores)
          updateDropdownTheme()
          
          // Para marcar como activo, usar el tema ORIGINAL, no el aplicado
          const themeToCompare = originalTheme || 'default'
          
          $variants.forEach($item => {
            const itemValue = $item.getAttribute('data-value')
            
            // Comparar con el tema original seleccionado
            if (itemValue === themeToCompare) {
              const icon = $item.getAttribute('data-icon')
              if (icon) {
                $dropdownBtn.innerHTML = `<i class="${icon}"></i>`
              }
              $item.classList.add('active')
            } else {
              $item.classList.remove('active')
            }
          })
        }

        // Agregar listeners a los elementos (solo una vez)
        $variants.forEach(function ($item) {
          // Verificar si ya tiene un listener para evitar duplicados
          if (!$item.hasAttribute('data-theme-listener')) {
            $item.setAttribute('data-theme-listener', 'true')
            $item.addEventListener('click', function (e) {
              e.preventDefault()
              const themeValue = $item.getAttribute('data-value')
              if (themeValue && HSThemeAppearance) {
                HSThemeAppearance.setAppearance(themeValue)
              }
            })
          }
        })

        // Inicializar el estilo activo inmediatamente
        setActiveStyle()

        // Escuchar cambios en el tema
        window.addEventListener('on-hs-appearance-change', function () {
          setActiveStyle()
        })

        // También actualizar cuando se muestra el dropdown
        if ($dropdownBtn) {
          $dropdownBtn.addEventListener('shown.bs.dropdown', function() {
            updateDropdownTheme()
          })
        }

        return true
      }
    })()
  </script>

  <script>
    $(function () {
        const ESTADOS = {
            disponible: { color: 'success', label: 'Disponible' },
            ocupado:    { color: 'danger',  label: 'Ocupado' },
            ausente:    { color: 'warning-custom', label: 'Ausente' },
            privado:    { color: 'secondary', label: 'Privado' }
        };

        const $toggle     = $('#navSubmenuPagesAccountDropdown1');
        const $legend     = $toggle.find('.legend-indicator');
        const $avatar     = $('#avatar-status-indicator');
        const $dropdown   = $('.navbar-dropdown-sub-menu');

        const BG_CLASSES     = 'bg-success bg-danger bg-warning bg-warning-custom bg-secondary';
        const AVATAR_CLASSES = 'avatar-status-success avatar-status-danger avatar-status-warning avatar-status-warning-custom avatar-status-secondary';

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
            $.post('{{ route("user.update-estado") }}', {
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

@stack('scripts')
</body>
</html>