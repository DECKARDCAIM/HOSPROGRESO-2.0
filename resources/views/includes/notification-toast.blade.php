@auth
    <div class="toast-container position-fixed top-0 end-0 p-3 mt-5 mt-md-0" style="z-index: 10700;">
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- GLOBAL TOAST FUNCTION (Simplified and Solid) ---
            window.showToast = function(title, message, type = 'info', sticky = false) {
                const container = document.querySelector('.toast-container');
                if (!container) return;

                // Reproducir sonido de alerta
                const alertAudio = new Audio('{{ asset("sound/alerta-toast.mp3") }}');
                alertAudio.volume = 0.8;
                alertAudio.play().catch(e => console.log('Audio play prevented', e));

                const toastId = 'toast-' + Math.random().toString(36).substr(2, 9);
                const systemLogo = '{{ asset("img/logo.png") }}';
                
                const finalMessage = title && title !== 'Sistema' && title !== 'Atención' && title !== 'Error' && title !== 'Éxito' && title !== 'Información' && title !== 'HOSPROGRESO'
                    ? `<strong>${title}</strong><br>${message}` 
                    : message;

                const toastHTML = `
                <!-- Toast -->
                <div id="${toastId}" class="toast toast-show fade show" role="alert" aria-live="assertive" aria-atomic="true" style="backdrop-filter: none !important; -webkit-backdrop-filter: none !important; box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);">
                  <div class="toast-header">
                    <div class="d-flex align-items-center flex-grow-1">
                      <div class="flex-shrink-0">
                        <img class="avatar avatar-sm avatar-circle" src="${systemLogo}" alt="HOSPROGRESO">
                      </div>
                      <div class="flex-grow-1 ms-3">
                        <h5 class="mb-0">HOSPROGRESO</h5>
                        <small class="ms-auto">Justo ahora</small>
                      </div>
                      <div class="text-end">
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                      </div>
                    </div>
                  </div>
                  <div class="toast-body">
                    ${finalMessage}
                  </div>
                </div>
                <!-- End Toast -->
                `;

                container.insertAdjacentHTML('beforeend', toastHTML);
                const toastEl = document.getElementById(toastId);

                const bsToast = new bootstrap.Toast(toastEl, {
                    delay: 8000,
                    autohide: !sticky
                });
                bsToast.show();

                toastEl.addEventListener('hidden.bs.toast', () => {
                    toastEl.remove();
                });
            };



            // --- USER VARIABLES FOR SYSTEM TOASTS ---
            const currentUserName = "{{ Auth::check() ? trim(Auth::user()->first_name . ' ' . Auth::user()->first_last_name) : 'Sistema' }}";
            const currentUserAvatar = "{{ Auth::check() && Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : asset('img/logo.png') }}";

            // --- AUTOMATIC NOTIFICATIONS (Session & Errors) ---
            @if (session('notification'))
                @php
                    $notif = session('notification');
                    $title = 'Información';
                @endphp
                window.showToast('{{ $title }}', '{{ $notif['message'] }}', 'info', false);
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    window.showToast('Atención', '{{ $error }}', 'warning', false);
                @endforeach
            @endif

            // --- REAL-TIME POLLING FOR NOTIFICATIONS ---
            let lastNotificationCheck = sessionStorage.getItem('lastNotificationCheck') || "{{ now()->toDateTimeString() }}";

            function pollNotifications() {
                $.get("{{ route('releases.get-unread') }}", {
                    since: lastNotificationCheck
                }, function(data) {
                    if (data.notifications && data.notifications.length > 0) {
                        // Solo mostramos la primera notificación recibida para evitar spam
                        const notif = data.notifications[0];
                        window.showToast(notif.title, notif.message, notif.type, true);

                        if (window.refreshNotificationDropdown) {
                            window.refreshNotificationDropdown();
                        }
                    }

                    if (data.server_time) {
                        lastNotificationCheck = data.server_time;
                        sessionStorage.setItem('lastNotificationCheck', lastNotificationCheck);
                    }
                }).fail(function(xhr, status, error) {
                    console.error("Notification poll failed:", error);
                });
            }

            // Poll inicial después de 3 segundos
            setTimeout(pollNotifications, 3000);

            // Iniciar polling regular cada 60 segundos
            setInterval(pollNotifications, 60000);
        });
    </script>
@endauth
