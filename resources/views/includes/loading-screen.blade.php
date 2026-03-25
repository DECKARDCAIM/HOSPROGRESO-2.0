<div id="global-sync-loader"
    class="position-fixed top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center"
    style="background-color: #0B1B3D; z-index: 99999; display: flex !important; opacity: 1; transition: opacity 0.5s ease; pointer-events: auto;">
    <div class="text-center">
        <img src="{{ asset('img/logotipo-white.svg') }}" alt="Logo" class="img-fluid mb-4" style="max-width: 300px;">
        <div class="mt-4">
            <div class="spinner-border text-white" role="status" style="width: 2.5rem; height: 2.5rem;">
                <span class="visually-hidden">Cargando...</span>
            </div>
            <p id="global-sync-text" class="text-white mt-3 fs-3 fw-light">Iniciando...</p>
        </div>
        <div class="progress mt-4 mx-auto" style="height: 6px; width: 250px; background-color: rgba(255,255,255,0.1);">
            <div class="progress-bar bg-white" id="global-sync-progress-bar" role="progressbar"
                style="width: 0%; transition: width 0.3s ease;"></div>
        </div>
    </div>
</div>

<script>
    (function () {
        const loader = document.getElementById('global-sync-loader');
        const progressBar = document.getElementById('global-sync-progress-bar');
        const syncText = document.getElementById('global-sync-text');

        let totalResources = 0;
        let loadedResources = 0;

        // Función que calcula el progreso matemáticamente
        window.updateRealProgress = function () {
            loadedResources++;
            let percentage = Math.floor((loadedResources / totalResources) * 100);
            if (percentage > 100) percentage = 100;

            if (progressBar) progressBar.style.width = percentage + '%';

            // Textos dinámicos basados en el avance REAL, no en tiempo
            if (syncText) {
                if (percentage < 30) syncText.textContent = "Sincronizando archivos del sistema...";
                else if (percentage < 70) syncText.textContent = "Optimizando bases de datos y caché...";
                else if (percentage < 100) syncText.textContent = "Cargando interfaz...";
                else syncText.textContent = "¡Sincronizado!";
            }

            // Si ya cargó todo, ocultamos
            if (loadedResources >= totalResources) {
                window.hideGlobalLoader();
            }
        };

        window.startRealLoader = function () {
            if (!loader) return;
            loader.style.opacity = '1';
            loader.style.display = 'flex';
            loader.style.pointerEvents = 'auto';
            document.body.style.overflow = 'hidden';

            // 1. Buscar todos los archivos que toman tiempo en descargar
            const elements = document.querySelectorAll('img, script[src], link[rel="stylesheet"], link[rel="preload"]');
            totalResources = elements.length;
            loadedResources = 0;

            if (totalResources === 0) {
                window.updateRealProgress(); // Si no hay nada, terminar de inmediato
                return;
            }

            // 2. Escuchar cuándo termina de descargar cada archivo
            elements.forEach(el => {
                // Las imágenes a veces ya están en caché y completas instantáneamente
                if (el.tagName.toLowerCase() === 'img' && el.complete) {
                    window.updateRealProgress();
                } else {
                    // Contamos tanto 'load' (éxito) como 'error' para que el loader no se quede trabado si un archivo falla
                    el.addEventListener('load', window.updateRealProgress);
                    el.addEventListener('error', window.updateRealProgress);
                }
            });
        };

        window.hideGlobalLoader = function () {
            if (!loader) return;
            setTimeout(() => {
                loader.style.transition = 'opacity 0.6s ease-out';
                loader.style.opacity = '0';
                loader.style.pointerEvents = 'none';
                document.body.style.overflow = '';
                setTimeout(() => {
                    if (loader.style.opacity === '0') loader.style.display = 'none';
                }, 700);
            }, 300); // Pequeño respiro visual al llegar al 100%
        };

        // Arrancamos el escáner de recursos reales
        document.addEventListener('DOMContentLoaded', window.startRealLoader);

        // Fallback de seguridad: El evento 'load' de window se dispara cuando TODO absolutamente todo está listo.
        // Forzamos el 100% aquí por si algún evento se nos escapó.
        window.addEventListener('load', () => {
            loadedResources = totalResources;
            window.updateRealProgress();
        });

        // Función para mostrar el loader manualmente con un texto personalizado
        window.showManualLoader = function (text = "Cargando...") {
            if (!loader) return;
            loader.style.transition = 'none';
            loader.style.opacity = '1';
            loader.style.display = 'flex';
            loader.style.pointerEvents = 'auto';
            if (progressBar) progressBar.style.width = '100%';
            if (syncText) syncText.textContent = text;
            document.body.style.overflow = 'hidden';
        };

        // Intercepción de enlaces para navegación entre páginas
        document.addEventListener('click', function (e) {
            const link = e.target.closest('a');
            if (link && link.getAttribute('href') && !link.getAttribute('href').startsWith('#') &&
                !link.getAttribute('href').startsWith('javascript:') && link.getAttribute('target') !== '_blank' &&
                !e.ctrlKey && !e.metaKey && !e.shiftKey) {

                // Evitamos mostrar el loader si es el botón de cerrar sesión (que ya tiene su propio onsubmit)
                if (link.closest('form')) return;

                window.showManualLoader("Cargando...");
            }
        });

        // Intercepción global de formularios para mostrar el loader al enviar
        document.addEventListener('submit', function (e) {
            const form = e.target;
            // No mostramos loader si el formulario tiene un target o si es una búsqueda rápida (opcional)
            if (form.getAttribute('target') === '_blank') return;

            // Si es el formulario de login o logout, o cualquier POST principal
            window.showManualLoader("Procesando...");
        });

        window.addEventListener('pageshow', function (event) {
            if (event.persisted) window.hideGlobalLoader();
        });
    })();
</script>