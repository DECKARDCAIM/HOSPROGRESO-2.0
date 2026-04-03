(function () {
    // ───── 1. Helper de Color Meta (Header) ─────
    window.updateMetaThemeColor = function () {
        const metaThemeColor = document.getElementById('theme-color-meta');
        if (!metaThemeColor || typeof HSThemeAppearance === 'undefined') return;
        const currentTheme = HSThemeAppearance.getAppearance();

        if (currentTheme === 'dark') {
            metaThemeColor.setAttribute('content', '#1e2022');
        } else {
            metaThemeColor.setAttribute('content', '#ffffff');
        }
    };

    // Ejecutar inmediatamente al cargar el script (no esperar onload)
    // HSThemeAppearance ya corrió desde el <body> antes que este script
    updateMetaThemeColor();

    // ───── 2. Inicialización Global ─────
    window.onload = function () {
        // Solo inicializamos si los componentes existen
        if (typeof HSSideNav !== 'undefined' && document.querySelector('.js-navbar-vertical-aside')) {
            new HSSideNav('.js-navbar-vertical-aside').init();
        }

        if (typeof HSFormSearch !== 'undefined' && document.querySelector('.js-form-search')) {
            new HSFormSearch('.js-form-search');
        }

        if (typeof HSBsDropdown !== 'undefined') {
            HSBsDropdown.init();
        }

        if (typeof initThemeDropdown === 'function') {
            initThemeDropdown();
        }

        // Re-aplicar por si acaso
        updateMetaThemeColor();
    };

    // ───── 3. Dropdown de Selección de Tema ─────
    window.initThemeDropdown = function () {
        const $dropdownBtn = document.getElementById('selectThemeDropdown');
        if (!$dropdownBtn || typeof HSThemeAppearance === 'undefined') {
            if (!$dropdownBtn) return false;
            setTimeout(initThemeDropdown, 100);
            return false;
        }

        const $variants = document.querySelectorAll(`[aria-labelledby="selectThemeDropdown"] [data-icon]`);
        if (!$variants.length) return false;

        const setActiveStyle = function () {
            const originalTheme = HSThemeAppearance.getOriginalAppearance() || 'default';

            $variants.forEach($item => {
                const itemValue = $item.getAttribute('data-value');

                if (itemValue === originalTheme) {
                    const icon = $item.getAttribute('data-icon');
                    if (icon && $dropdownBtn) {
                        $dropdownBtn.innerHTML = `<i class="${icon}"></i>`;
                    }
                    $item.classList.add('active');
                } else {
                    $item.classList.remove('active');
                }
            });
        };

        $variants.forEach($item => {
            if (!$item.hasAttribute('data-theme-listener')) {
                $item.setAttribute('data-theme-listener', 'true');
                $item.addEventListener('click', (e) => {
                    e.preventDefault();
                    const themeValue = $item.getAttribute('data-value');
                    if (themeValue) {
                        HSThemeAppearance.setAppearance(themeValue);

                        // Sincronizar con el servidor (persiste en DB)
                        const csrfToken = document.querySelector('meta[name="csrf-token"]');
                        if (csrfToken) {
                            fetch('/user/update-theme', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({ theme: themeValue }),
                            }).catch(function (err) {
                                console.warn('[Theme] No se pudo sincronizar con el servidor:', err);
                            });
                        }
                    }
                });
            }
        });

        setActiveStyle();
        window.addEventListener('on-hs-appearance-change', setActiveStyle);
        return true;
    };

    // Listeners de cambios de apariencia
    window.addEventListener('on-hs-appearance-change', function () {
        if (typeof updateMetaThemeColor === 'function') {
            updateMetaThemeColor();
        }
    });

})();
