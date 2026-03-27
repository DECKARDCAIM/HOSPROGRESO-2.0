@auth
<style>
    :root {
        --toast-success: hsl(152, 69%, 31%);
        --toast-error: hsl(354, 70%, 54%);
        --toast-warning: hsl(45, 100%, 45%);
        --toast-info: hsl(188, 78%, 41%);
        
        --toast-success-bg: hsla(152, 69%, 31%, 0.1);
        --toast-error-bg: hsla(354, 70%, 54%, 0.1);
        --toast-warning-bg: hsla(45, 100%, 45%, 0.1);
        --toast-info-bg: hsla(188, 78%, 41%, 0.1);
    }

    .modern-toast {
        background-color: var(--bs-body-bg, rgba(255, 255, 255, 0.85));
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        color: var(--bs-body-color, #212529);
        border: 1px solid var(--bs-border-color, rgba(0, 0, 0, 0.08));
        border-radius: 12px;
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
        display: flex;
        overflow: hidden;
        min-width: 320px;
        max-width: 400px;
        position: relative;
        border-left: 6px solid transparent;
        opacity: 0;
        transform: translateX(20px);
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .modern-toast.force-show {
        opacity: 1 !important;
        display: flex !important;
        transform: translateX(0);
    }

    .modern-toast.toast-success { border-left-color: var(--toast-success); }
    .modern-toast.toast-error { border-left-color: var(--toast-error); }
    .modern-toast.toast-warning { border-left-color: var(--toast-warning); }
    .modern-toast.toast-info { border-left-color: var(--toast-info); }

    .modern-toast .toast-icon-box {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .toast-success .toast-icon-box { color: var(--toast-success); background: var(--toast-success-bg); }
    .toast-error .toast-icon-box { color: var(--toast-error); background: var(--toast-error-bg); }
    .toast-warning .toast-icon-box { color: var(--toast-warning); background: var(--toast-warning-bg); }
    .toast-info .toast-icon-box { color: var(--toast-info); background: var(--toast-info-bg); }

    .modern-toast .toast-content {
        padding: 16px 16px 16px 4px;
        flex-grow: 1;
        margin-left: 12px;
    }

    .modern-toast .toast-title {
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 4px;
        color: var(--bs-heading-color, #1e2022);
    }

    .modern-toast .toast-message {
        font-size: 0.875rem;
        line-height: 1.5;
        margin: 0;
        opacity: 0.85;
    }

    .modern-toast .toast-close-btn {
        background: transparent;
        border: none;
        padding: 12px;
        font-size: 1rem;
        color: inherit;
        opacity: 0.4;
        cursor: pointer;
        transition: opacity 0.2s;
        align-self: flex-start;
    }

    .modern-toast .toast-close-btn:hover { opacity: 1; }

    .toast-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        width: 100%;
        transform-origin: left;
        transform: scaleX(1);
    }

    .toast-success .toast-progress { background-color: var(--toast-success); }
    .toast-error .toast-progress { background-color: var(--toast-error); }
    .toast-warning .toast-progress { background-color: var(--toast-warning); }
    .toast-info .toast-progress { background-color: var(--toast-info); }
</style>

<div class="toast-container position-fixed top-0 end-0 p-3 mt-5 mt-md-0" style="z-index: 10700;">
    {{-- Toast para Inactividad (Ya existente) --}}
    <div id="sessionTimeoutToast" class="toast modern-toast toast-warning border-0 d-none" role="alert"
        aria-live="assertive" aria-atomic="true">
        <div class="toast-icon-box">
            <i class="bi-exclamation-triangle-fill"></i>
        </div>
        <div class="toast-content">
            <div class="toast-title">Aviso de Inactividad</div>
            <p class="toast-message">
                Tu sesión expirará en <strong id="session-timeout-countdown" style="font-weight: 900;">05:00</strong> si
                no detectamos actividad.
            </p>
        </div>
        <div class="toast-progress" id="session-timeout-progress"></div>
    </div>
</div>

{{-- Audio para notificaciones --}}
<audio id="notification-bell" preload="auto">
    <source src="{{ asset('sound/notification.wav') }}" type="audio/wav">
</audio>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- GLOBAL TOAST FUNCTION ---
        window.showToast = function(title, message, type = 'info', sticky = false, playSound = false) {
            const container = document.querySelector('.toast-container');
            if (!container) return;

            if (playSound) {
                const bell = document.getElementById('notification-bell');
                if (bell) {
                    bell.currentTime = 0;
                    bell.play().catch(e => console.log("Audio play blocked by browser. Interaction required."));
                }
            }

            const toastId = 'toast-' + Math.random().toString(36).substr(2, 9);
            const iconMap = {
                'success': 'bi-check-circle-fill',
                'error': 'bi-x-octagon-fill',
                'warning': 'bi-exclamation-triangle-fill',
                'info': 'bi-info-circle-fill'
            };
            const iconClass = iconMap[type] || iconMap['info'];
            const themeClass = `toast-${type}`;

            const toastHTML = `
                <div id="${toastId}" class="toast modern-toast ${themeClass} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-icon-box">
                        <i class="${iconClass}"></i>
                    </div>
                    <div class="toast-content">
                        <div class="toast-title">${title}</div>
                        <p class="toast-message">${message}</p>
                    </div>
                    <button type="button" class="toast-close-btn" data-bs-dismiss="toast" aria-label="Close">
                        <i class="bi-x-lg"></i>
                    </button>
                    ${!sticky ? '<div class="toast-progress"></div>' : ''}
                </div>
            `;

            container.insertAdjacentHTML('beforeend', toastHTML);
            const toastEl = document.getElementById(toastId);
            
            // Forzar reflow para la animación de entrada
            setTimeout(() => toastEl.classList.add('force-show'), 10);

            const DURATION = 8000;
            const bsToast = new bootstrap.Toast(toastEl, { 
                delay: DURATION, 
                autohide: !sticky 
            });
            bsToast.show();

            if (!sticky) {
                const progressEl = toastEl.querySelector('.toast-progress');
                // Progress Bar Logic
                let remaining = DURATION;
                let lastTime = performance.now();
                let isPaused = false;
                let animationFrameId;

                function updateProgress(currentTime) {
                    if (!isPaused) {
                        const delta = currentTime - lastTime;
                        remaining -= delta;
                        let pct = Math.max(0, remaining / DURATION);
                        if (progressEl) progressEl.style.transform = `scaleX(${pct})`;
                        if (remaining <= 0) {
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
                    toastEl.remove();
                });
            } else {
                toastEl.addEventListener('hidden.bs.toast', () => {
                    toastEl.remove();
                });
            }
        };

        // --- SESSION TIMEOUT LOGIC ---
        const SESSION_LIFETIME = {{ (int) config('session.lifetime') }} * 60;
        const WARNING_DURATION = 300;
        let GRACE_PERIOD = Math.max(5, SESSION_LIFETIME - WARNING_DURATION);
        const PING_INTERVAL = 5 * 60 * 1000;

        let idleTimer = null;
        let countdownInterval = null;
        let isWarningVisible = false;
        let lastPing = Date.now();

        const timeoutToast = document.getElementById('sessionTimeoutToast');
        const countdownDisplay = document.getElementById('session-timeout-countdown');

        function startIdleTimer() {
            if (!timeoutToast) return;
            clearTimers();
            isWarningVisible = false;
            timeoutToast.classList.remove('force-show', 'show');
            setTimeout(() => timeoutToast.classList.add('d-none'), 400);
            idleTimer = setTimeout(showWarning, GRACE_PERIOD * 1000);
        }

        function showWarning() {
            isWarningVisible = true;
            timeoutToast.classList.remove('d-none');
            setTimeout(() => timeoutToast.classList.add('force-show'), 50);

            let countdownSeconds = WARNING_DURATION;
            updateCountdown(countdownSeconds);

            countdownInterval = setInterval(() => {
                countdownSeconds--;
                updateCountdown(countdownSeconds);
                if (countdownSeconds <= 0) {
                    clearTimers();
                    logout();
                }
            }, 1000);
        }

        function updateCountdown(seconds) {
            const mins = Math.floor(seconds / 60);
            const secs = seconds % 60;
            if (countdownDisplay) countdownDisplay.textContent = `${mins}:${secs.toString().padStart(2, '0')}`;
            const prog = document.getElementById('session-timeout-progress');
            if (prog) prog.style.transform = `scaleX(${seconds / WARNING_DURATION})`;
        }

        function clearTimers() {
            if (idleTimer) clearTimeout(idleTimer);
            if (countdownInterval) clearInterval(countdownInterval);
        }

        function logout() {
            window.location.href = '/login';
        }

        const resetEvents = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
        const handleUserAction = () => {
            if (isWarningVisible) {
                fetch('{{ route('session.ping') }}', { 
                    method: 'POST', 
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } 
                }).then(() => startIdleTimer());
                return;
            }
            if (Date.now() - lastPing > PING_INTERVAL) {
                fetch('{{ route('session.ping') }}', { 
                    method: 'POST', 
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } 
                });
                lastPing = Date.now();
            }
            startIdleTimer();
        };

        resetEvents.forEach(e => document.addEventListener(e, handleUserAction, { passive: true }));
        startIdleTimer();

        // --- AUTOMATIC NOTIFICATIONS (Session & Errors) ---
        @if(session('notification'))
            @php
                $notif = session('notification');
                $type = strtolower($notif['alert-type'] ?? 'info');
                $finalType = 'info';
                $title = 'Información';

                if (str_contains($type, 'success') || str_contains($type, 'creacion')) {
                    $finalType = 'success'; $title = 'Éxito';
                } elseif (str_contains($type, 'error') || str_contains($type, 'danger') || str_contains($type, 'elimin')) {
                    $finalType = 'error'; $title = 'Atención';
                } elseif (str_contains($type, 'warn') || str_contains($type, 'adver')) {
                    $finalType = 'warning'; $title = 'Aviso';
                }
            @endphp
            window.showToast('{{ $title }}', '{{ $notif['message'] }}', '{{ $finalType }}', true, true);
        @endif

        @if($errors->any())
            @foreach($errors->all() as $error)
                window.showToast('Atención', '{{ $error }}', 'warning', true, true);
            @endforeach
        @endif

        // --- REAL-TIME POLLING FOR NOTIFICATIONS ---
        let lastNotificationCheck = "2000-01-01 00:00:00"; 
        let isFirstPoll = true;

        function pollNotifications() {
            $.get("{{ route('releases.get-unread') }}", { since: lastNotificationCheck }, function(data) {
                if (data.notifications && data.notifications.length > 0) {
                    if (isFirstPoll) {
                        window.showToast('Nuevos Comunicados', 'Tienes mensajes nuevos sin leer en tu bandeja. Por favor, revísalos.', 'info', true, true);
                        isFirstPoll = false;
                    }

                    data.notifications.forEach(notif => {
                        window.showToast(notif.title, notif.message, notif.type, true, true);
                    });
                    
                    if (window.refreshNotificationDropdown) {
                        window.refreshNotificationDropdown();
                    }
                } else {
                    isFirstPoll = false;
                }
                
                if (data.server_time) {
                    lastNotificationCheck = data.server_time;
                }
            }).fail(function(xhr, status, error) {
                console.error("Notification poll failed:", error);
                isFirstPoll = false;
            });
        }

        // Poll inicial después de 1 segundo
        setTimeout(pollNotifications, 1000);

        // Iniciar polling regular cada 30 segundos
        setInterval(pollNotifications, 30000);
    });
</script>
@endauth