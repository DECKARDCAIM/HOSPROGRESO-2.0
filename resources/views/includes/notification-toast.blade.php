@auth
<style>
    .modern-toast {
        background-color: var(--bs-body-bg, #ffffff);
        color: var(--bs-body-color, #212529);
        border: 1px solid var(--bs-border-color, rgba(0, 0, 0, 0.1));
        border-radius: 8px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        display: flex;
        overflow: hidden;
        min-width: 320px;
        max-width: 100%;
        position: relative;
        border-left: 6px solid transparent;
        /* Por defecto, lo forzamos oculto con opacidad para transiciones suaves */
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    /* Esta clase la usaremos por JS para forzar su aparición */
    .modern-toast.force-show {
        opacity: 1 !important;
        display: flex !important;
    }

    .modern-toast.toast-success {
        border-left-color: #198754;
    }

    .modern-toast.toast-error {
        border-left-color: #dc3545;
    }

    .modern-toast.toast-warning {
        border-left-color: #ffc107;
    }

    .modern-toast.toast-info {
        border-left-color: #0dcaf0;
    }

    .modern-toast .toast-icon-box {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 65px;
        font-size: 1.6rem;
    }

    .toast-success .toast-icon-box {
        color: #198754;
        background: rgba(25, 135, 84, 0.1);
    }

    .toast-error .toast-icon-box {
        color: #dc3545;
        background: rgba(220, 53, 69, 0.1);
    }

    .toast-warning .toast-icon-box {
        color: #ffc107;
        background: rgba(255, 193, 7, 0.1);
    }

    .toast-info .toast-icon-box {
        color: #0dcaf0;
        background: rgba(13, 202, 240, 0.1);
    }

    .modern-toast .toast-content {
        padding: 14px 16px 16px 0;
        flex-grow: 1;
        margin-left: 15px;
    }

    .modern-toast .toast-title {
        font-weight: 700;
        font-size: 1.05rem;
        margin-bottom: 2px;
        color: inherit;
    }

    .modern-toast .toast-message {
        font-size: 0.9rem;
        line-height: 1.4;
        margin: 0;
        opacity: 0.8;
    }

    .modern-toast .toast-close-btn {
        background: transparent;
        border: none;
        padding: 14px 16px;
        font-size: 1.2rem;
        color: inherit;
        opacity: 0.5;
        cursor: pointer;
        transition: opacity 0.2s;
    }

    .modern-toast .toast-close-btn:hover {
        opacity: 1;
    }

    .toast-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 4px;
        width: 100%;
        transform-origin: left;
        transform: scaleX(1);
    }

    .toast-success .toast-progress {
        background-color: #198754;
    }

    .toast-error .toast-progress {
        background-color: #dc3545;
    }

    .toast-warning .toast-progress {
        background-color: #ffc107;
    }

    .toast-info .toast-progress {
        background-color: #0dcaf0;
    }
</style>

<div class="toast-container position-fixed top-0 end-0 p-3 mt-5 mt-md-0" style="z-index: 10600;">
    <div id="sessionTimeoutToast" class="toast modern-toast toast-warning border-0 d-none" role="alert"
        aria-live="assertive" aria-atomic="true">
        <div class="toast-icon-box">
            <i class="bi-exclamation-triangle-fill"></i>
        </div>
        <div class="toast-content">
            <div class="toast-title">Aviso de Inactividad</div>
            <p class="toast-message">
                Tu sesión expirará en <strong id="session-timeout-countdown" style="font-weight: 900;">05:00</strong> si
                no detectamos actividad.<br>
                Mueve el mouse para continuar.
            </p>
        </div>
        <div class="toast-progress" id="session-timeout-progress"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- CONFIGURACIÓN DE TIEMPOS (MATEMÁTICA REAL) ---
        // 1. Traemos tu tiempo total desde Laravel (Ej: Si le pones 6 min = 360 segundos)
        const SESSION_LIFETIME = {{ (int) config('session.lifetime')
    }} * 60;

    // 2. Tiempo de advertencia: 300 segundos (5 MINUTOS EXACTOS)
    const WARNING_DURATION = 300;

    // 3. Calculamos el tiempo de gracia (Tiempo oculto y silencioso)
    let GRACE_PERIOD = SESSION_LIFETIME - WARNING_DURATION;

    // Protección: Si tu sesión es de 5 min y la advertencia de 5 min, forzamos al menos 5 segundos de gracia para que no se buguee al instante.
    if (GRACE_PERIOD <= 0) GRACE_PERIOD = 5;

    const PING_INTERVAL = 5 * 60 * 1000;

    let idleTimer = null;
    let countdownInterval = null;
    let isWarningVisible = false;
    let lastPing = Date.now();

    const toastElement = document.getElementById('sessionTimeoutToast');
    const countdownDisplay = document.getElementById('session-timeout-countdown');
    const progressBar = document.getElementById('session-timeout-progress');

    if (!toastElement) return;

    // Iniciar el ciclo de vigilancia (El tiempo oculto)
    function startIdleTimer() {
        clearTimers();
        isWarningVisible = false;

        toastElement.classList.remove('force-show', 'show');
        toastElement.classList.add('d-none');

        if (progressBar) progressBar.style.transform = `scaleX(1)`;

        // Arranca el tiempo de gracia oculto
        idleTimer = setTimeout(showWarning, GRACE_PERIOD * 1000);
    }

    // Mostrar la advertencia de inactividad (Los 5 minutos)
    function showWarning() {
        isWarningVisible = true;

        toastElement.classList.remove('d-none');
        setTimeout(() => toastElement.classList.add('force-show'), 50);

        let countdownSeconds = WARNING_DURATION;
        updateCountdownDisplay(countdownSeconds);

        if (progressBar) progressBar.style.transform = `scaleX(1)`;

        countdownInterval = setInterval(() => {
            countdownSeconds--;
            updateCountdownDisplay(countdownSeconds);

            if (progressBar) {
                let pct = countdownSeconds / WARNING_DURATION;
                progressBar.style.transform = `scaleX(${pct})`;
            }

            // Si llega a cero, te cierra la sesión de verdad
            if (countdownSeconds <= 0) {
                clearTimers();
                logout();
            }
        }, 1000);
    }

    function clearTimers() {
        if (idleTimer) clearTimeout(idleTimer);
        if (countdownInterval) clearInterval(countdownInterval);
    }

    function updateCountdownDisplay(seconds) {
        const mins = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        if (countdownDisplay) countdownDisplay.textContent = `${mins}:${secs.toString().padStart(2, '0')}`;
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
                startIdleTimer(); // Reiniciamos el ciclo oculto
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
            if (response.ok) lastPing = Date.now();
        });
    }

    function logout() {
        if (window.showManualLoader) window.showManualLoader("Cerrando sesión por inactividad...");
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

    // --- MANEJO DE EVENTOS DEL USUARIO ---
    const resetEvents = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
    let lastActionTime = Date.now();

    const handleUserAction = () => {
        const now = Date.now();

        // Si el Toast está visible (está contando los 5 minutos) y te mueves, te salvamos
        if (isWarningVisible) {
            stayLoggedIn();
            return;
        }

        // Si no estaba visible, solo reseteamos el cronómetro interno si pasó 1 segundo desde tu último movimiento
        if (now - lastActionTime > 1000) {
            lastActionTime = now;
            startIdleTimer();

            if (now - lastPing > PING_INTERVAL) silentPing();
        }
    };

    resetEvents.forEach(event => document.addEventListener(event, handleUserAction, { passive: true }));

    startIdleTimer();
    });
</script>

@if(session('notification'))
@php
$type = session('notification.alert-type', 'info');
$typeLower = strtolower(trim($type));

$themeClass = 'toast-info';
$iconClass = 'bi-info-circle-fill';
$title = 'Información';

if ($typeLower === 'success' || str_contains($typeLower, 'creacion') || str_contains($typeLower, 'creaci')) {
$themeClass = 'toast-success';
$iconClass = 'bi-check-circle-fill';
$title = 'Éxito';
} elseif ($typeLower === 'error' || $typeLower === 'danger' || str_contains($typeLower, 'desactiv') ||
str_contains($typeLower, 'elimin')) {
$themeClass = 'toast-error';
$iconClass = 'bi-x-octagon-fill';
$title = 'Atención';
} elseif ($typeLower === 'warning' || str_contains($typeLower, 'adver')) {
$themeClass = 'toast-warning';
$iconClass = 'bi-exclamation-triangle-fill';
$title = 'Aviso';
}
@endphp

<div class="toast-container position-fixed top-0 end-0 p-3 mt-5 mt-md-0" style="z-index: 10500;">
    <div id="globalToast" class="toast modern-toast {{ $themeClass }} border-0 force-show" role="alert"
        aria-live="assertive" aria-atomic="true" data-bs-autohide="true">
        <div class="toast-icon-box">
            <i class="{{ $iconClass }}"></i>
        </div>
        <div class="toast-content">
            <div class="toast-title">{{ $title }}</div>
            <p class="toast-message">{{ session('notification.message') }}</p>
        </div>
        <button type="button" class="toast-close-btn" data-bs-dismiss="toast" aria-label="Close">
            <i class="bi-x-lg"></i>
        </button>
        <div class="toast-progress" id="globalToastProgress"></div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toastEl = document.getElementById('globalToast');
        const progressEl = document.getElementById('globalToastProgress');

        if (toastEl) {
            const DURATION = 8000;
            let remaining = DURATION;
            let lastTime = performance.now();
            let isPaused = false;
            let animationFrameId;

            const bsToast = new bootstrap.Toast(toastEl, { delay: DURATION });
            bsToast.show();

            function updateProgress(currentTime) {
                if (!isPaused) {
                    const delta = currentTime - lastTime;
                    remaining -= delta;

                    let pct = remaining / DURATION;
                    if (pct < 0) pct = 0;

                    progressEl.style.transform = `scaleX(${pct})`;

                    if (remaining <= 0) {
                        cancelAnimationFrame(animationFrameId);
                        // Forzamos ocultar para consistencia
                        toastEl.classList.remove('force-show');
                        return;
                    }
                }
                lastTime = currentTime;
                animationFrameId = requestAnimationFrame(updateProgress);
            }

            animationFrameId = requestAnimationFrame(updateProgress);

            toastEl.addEventListener('mouseenter', () => { isPaused = true; });
            toastEl.addEventListener('mouseleave', () => {
                isPaused = false;
                lastTime = performance.now();
            });
            toastEl.addEventListener('hidden.bs.toast', () => {
                cancelAnimationFrame(animationFrameId);
                toastEl.classList.remove('force-show');
            });
        }
    });
</script>
@endif
@endauth