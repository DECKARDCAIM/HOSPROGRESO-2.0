<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasChatISAAC" aria-labelledby="offcanvasChatISAACLabel">
  <div class="offcanvas-header justify-content-between border-bottom">
    <h4 id="offcanvasChatISAACLabel" class="mb-0 d-flex align-items-center">
      <div class="avatar avatar-xs avatar-circle me-2">
        <span class="avatar-initials bg-primary text-white"><i class="bi-robot"></i></span>
      </div>
      ISAAC <i class="bi-patch-check-fill text-primary ms-1" data-bs-toggle="tooltip" data-bs-placement="top"
        title="Inteligencia Artificial"></i>
    </h4>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>

  <div class="offcanvas-body d-flex flex-column p-0">
    <div class="flex-grow-1 overflow-auto p-3" id="chatWindow">
      <!-- Greeting -->
      <div class="d-flex mb-3">
        <div class="flex-shrink-0">
          <div class="avatar avatar-sm avatar-circle">
            <span class="avatar-initials bg-primary text-white"><i class="bi-robot"></i></span>
          </div>
        </div>
        <div class="flex-grow-1 ms-3">
          <div class="border rounded p-2 text-body">
            @php
            $nombreUsuario = auth()->user() ? auth()->user()->first_name : 'Usuario';
            @endphp
            <p class="mb-0" style="font-size: 0.875rem;">Hola <strong>{{ $nombreUsuario }}</strong>, soy
              <strong>ISAAC</strong>, la Inteligencia Artificial del sistema médico HOSPROGRESO. Es un gusto hablar
              contigo hoy. ¿En qué puedo ayudarte?
            </p>
          </div>
        </div>
      </div>
    </div> <!-- end chatWindow -->

    <div id="isaacStatus" class="px-3 py-1 small text-muted d-none" style="font-size: 0.75rem;">
      <i class="bi-robot me-1"></i> ISAAC está pensando...
    </div>

    <div class="p-3 border-top">
      <div class="border rounded-pill p-1 d-flex align-items-end position-relative">
        <div class="dropdown">
          <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm rounded-circle mb-1 ms-1" id="attachBtn">
            <i class="bi-paperclip fs-4"></i>
          </button>
          <div class="dropdown-menu shadow-lg border p-2" id="attachMenu"
            style="position: absolute; bottom: 100%; left: 0; display: none; margin-bottom: 10px; min-width: 160px;">
            <a class="dropdown-item d-flex align-items-center py-2 rounded" href="javascript:;">
              <i class="bi-image-fill me-2 text-primary"></i> Foto
            </a>
            <a class="dropdown-item d-flex align-items-center py-2 rounded" href="javascript:;">
              <i class="bi-file-earmark-text-fill me-2 text-success"></i> Documento
            </a>
          </div>
        </div>

        <textarea id="chatInput" class="form-control border-0 bg-transparent shadow-none px-3 py-2" rows="1"
          placeholder="Escribe a ISAAC..." style="resize: none; overflow-y: hidden; min-height: 40px;"
          oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"></textarea>

        <button type="button" id="sendChatBtn" class="btn btn-primary btn-icon rounded-circle flex-shrink-0 mb-1 me-1">
          <i class="bi-send-fill" id="sendChatIcon"></i>
          <span class="spinner-border spinner-border-sm d-none" id="sendChatSpinner" role="status"
            aria-hidden="true"></span>
        </button>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const chatInput = document.getElementById('chatInput');
    const sendChatBtn = document.getElementById('sendChatBtn');
    const chatWindow = document.getElementById('chatWindow');
    const sendChatIcon = document.getElementById('sendChatIcon');
    const sendChatSpinner = document.getElementById('sendChatSpinner');

    @php
    $user = auth() -> user();
    $fullName = $user ? trim("{$user->first_name} {$user->second_name} {$user->third_name} {$user->first_last_name} {$user->second_last_name} {$user->married_last_name}") : 'Usuario';
    $isAdmin = ($user && $user -> role_id == 1); // Asumiendo que 1 es Administrador, ajusta si es necesario
    $avatarUrl = ($user && $user -> profile_photo_path) ? asset('storage/'.$user -> profile_photo_path) : null;
    $initials = $user ? strtoupper(substr($user -> first_name, 0, 1)) : 'U';
    @endphp

    const userName = "{{ $fullName }}";
    const isAdmin = {{ $isAdmin ? 'true' : 'false' }};
  const avatarUrl = "{{ $avatarUrl }}";
  const initials = "{{ $initials }}";

  const userAvatarHtml = avatarUrl
    ? `<img class="avatar avatar-sm avatar-circle" src="${avatarUrl}" alt="${userName}" style="object-fit: cover;">`
    : `<div class="avatar avatar-sm avatar-circle avatar-soft-primary"><span class="avatar-initials">${initials}</span></div>`;

  const isaacStatus = document.getElementById('isaacStatus');
  const attachBtn = document.getElementById('attachBtn');
  const attachMenu = document.getElementById('attachMenu');

  function appendUserMessage(text) {
    const badgeHtml = isAdmin ? '<i class="bi-patch-check-fill text-primary" style="font-size: 0.65rem;"></i>' : '';
    const msgHtml = `
            <div class="d-flex mb-3 flex-row-reverse">
                <div class="flex-shrink-0">
                    ${userAvatarHtml}
                </div>
                <div class="flex-grow-1 me-2 text-end">
                    <div class="bg-primary text-white rounded p-2 d-inline-block" style="text-align: left;">
                        <p class="mb-0" style="font-size: 0.85rem;">${text.replace(/\n/g, '<br>')}</p>
                    </div>
                    <div class="mt-1" style="font-size: 0.7rem; opacity: 0.8;">
                        ${userName} ${badgeHtml}
                    </div>
                </div>
            </div>
        `;
    chatWindow.insertAdjacentHTML('beforeend', msgHtml);
    scrollToBottom();
  }

  // Efecto de máquina de escribir
  function typeWriter(element, text, speed = 20) {
    let i = 0;
    const formattedText = text.replace(/\n/g, '<br>');
    // Para manejar etiquetas <br> correctamente durante la escritura
    const parts = text.split('\n');
    let currentLine = 0;
    let currentChar = 0;

    function type() {
      if (currentLine < parts.length) {
        if (currentChar < parts[currentLine].length) {
          element.innerHTML += parts[currentLine].charAt(currentChar);
          currentChar++;
          scrollToBottom();
          setTimeout(type, speed);
        } else {
          if (currentLine < parts.length - 1) {
            element.innerHTML += '<br>';
          }
          currentLine++;
          currentChar = 0;
          setTimeout(type, speed);
        }
      }
    }
    type();
  }

  function appendIsaacMessage(text) {
    const messageId = 'isaac-msg-' + Date.now();
    const msgHtml = `
            <div class="d-flex mb-3">
                <div class="flex-shrink-0">
                    <div class="avatar avatar-sm avatar-circle">
                        <span class="avatar-initials bg-primary text-white"><i class="bi-robot"></i></span>
                    </div>
                </div>
                <div class="flex-grow-1 ms-2">
                    <div class="border rounded p-2 text-body d-inline-block">
                        <p class="mb-0" id="${messageId}" style="font-size: 0.85rem; min-height: 1.25rem;"></p>
                    </div>
                    <div class="mt-1" style="font-size: 0.7rem; opacity: 0.8;">
                        ISAAC <i class="bi-patch-check-fill text-primary" style="font-size: 0.65rem;"></i>
                    </div>
                </div>
            </div>
        `;
    chatWindow.insertAdjacentHTML('beforeend', msgHtml);
    const element = document.getElementById(messageId);
    typeWriter(element, text);
  }

  function scrollToBottom() {
    chatWindow.scrollTop = chatWindow.scrollHeight;
  }

  function toggleLoading(isLoading) {
    if (isLoading) {
      sendChatIcon.classList.add('d-none');
      sendChatSpinner.classList.remove('d-none');
      sendChatBtn.disabled = true;
      chatInput.disabled = true;
      isaacStatus.classList.remove('d-none');
      scrollToBottom();
    } else {
      sendChatSpinner.classList.add('d-none');
      sendChatIcon.classList.remove('d-none');
      sendChatBtn.disabled = false;
      chatInput.disabled = false;
      isaacStatus.classList.add('d-none');
      chatInput.focus();
    }
  }

  function sendMessage() {
    const message = chatInput.value.trim();
    if (!message) return;

    appendUserMessage(message);
    chatInput.value = '';
    chatInput.style.height = '40px';

    toggleLoading(true);

    fetch('{{ route('isaac.chat') }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json'
      },
      body: JSON.stringify({ message: message })
    })
      .then(response => response.json())
      .then(data => {
        toggleLoading(false);
        if (data.success) {
          appendIsaacMessage(data.reply);
        } else {
          appendIsaacMessage('<span class="text-danger"><i class="bi-exclamation-triangle"></i> ' + (data.reply || 'No pude contactar a mi servidor neuronal.') + '</span>');
        }
      })
      .catch(err => {
        toggleLoading(false);
        appendIsaacMessage('<span class="text-danger"><i class="bi-x-octagon"></i> Existe un error de conexión con mi cerebro artificial.</span>');
        console.error(err);
      });
  }

  // Menú de adjuntos
  attachBtn.addEventListener('click', function (e) {
    e.stopPropagation();
    const isVisible = attachMenu.style.display === 'block';
    attachMenu.style.display = isVisible ? 'none' : 'block';
  });

  document.addEventListener('click', function (e) {
    if (!attachBtn.contains(e.target) && !attachMenu.contains(e.target)) {
      attachMenu.style.display = 'none';
    }
  });

  sendChatBtn.addEventListener('click', sendMessage);

  chatInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      sendMessage();
    }
  });
});
</script>