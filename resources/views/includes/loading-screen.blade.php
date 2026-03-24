<!-- ========== GLOBAL PRELOADER "SINCRONIZANDO" ========== -->
<div id="global-sync-loader"
    class="position-fixed top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center"
    style="background-color: #0B1B3D; z-index: 99999; display: flex !important; opacity: 1; transition: opacity 0.5s ease; pointer-events: auto;">
    <div class="text-center">
        <img src="{{ asset('img/logotipo-white.svg') }}" alt="Logo" class="img-fluid mb-4" style="max-width: 300px;">
        <div class="mt-4">
            <div class="spinner-border text-white" role="status" style="width: 2.5rem; height: 2.5rem;">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="text-white mt-3 fs-3 fw-light">Sincronizando...</p>
        </div>
        <div class="progress mt-4 mx-auto" style="height: 6px; width: 250px; background-color: rgba(255,255,255,0.1);">
            <div class="progress-bar bg-white" id="global-sync-progress-bar" role="progressbar"
                style="width: 0%; transition: width 2s linear;"></div>
        </div>
    </div>
</div>

<script>
    (function() {
        const loader = document.getElementById('global-sync-loader');
        const progressBar = document.getElementById('global-sync-progress-bar');

        window.showGlobalLoader = function(duration = 2000) {
            if (!loader) return;

            // Reset bar
            if (progressBar) {
                progressBar.style.transition = 'none';
                progressBar.style.width = '0%';
                setTimeout(() => {
                    progressBar.style.transition = `width ${duration}ms linear`;
                    progressBar.style.width = '100%';
                }, 50);
            }

            loader.style.transition = 'none';
            loader.style.opacity = '1';
            loader.style.display = 'flex';
            loader.style.pointerEvents = 'auto';

            // Forzar opacidad en el body para que no se vea el contenido debajo mientras carga
            document.body.style.overflow = 'hidden';
        };

        window.hideGlobalLoader = function() {
            if (!loader) return;
            // Damos un pequeño respiro para asegurar que el renderizado inicial terminó
            setTimeout(() => {
                loader.style.transition = 'opacity 0.6s ease-out';
                loader.style.opacity = '0';
                loader.style.pointerEvents = 'none';
                document.body.style.overflow = '';
                setTimeout(() => {
                    if (loader.style.opacity === '0') {
                        loader.style.display = 'none';
                    }
                }, 700);
            }, 100); 
        };

        // Auto-show bar on initial load
        if (progressBar) {
            progressBar.style.width = '100%';
        }

        // Auto-hide on window load
        window.addEventListener('load', hideGlobalLoader);

        // Intercept links (Simple Interception)
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link &&
                link.getAttribute('href') &&
                !link.getAttribute('href').startsWith('#') &&
                !link.getAttribute('href').startsWith('javascript:') &&
                link.getAttribute('target') !== '_blank' &&
                !e.ctrlKey && !e.metaKey && !e.shiftKey) {
                showGlobalLoader(1500);
            }
        });

        // Intercept forms
        document.addEventListener('submit', function(e) {
            if (!e.target.closest('.js-step-form')) {
                showGlobalLoader(1000); 
            }
        });

        // Pageshow handles back/forward cache
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                hideGlobalLoader();
            }
        });
    })();
</script>
<!-- ========== END GLOBAL PRELOADER ========== -->
