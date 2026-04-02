@php
    $user = auth()->user();
    $isAdmin = $user && $user->role_id == 1;
    $primerNombre = $user->first_name ?? '';
    $primerApellido = $user->first_last_name ?? '';
    $nombreUsuario = $primerNombre ?: 'Usuario';
    $nombreMostrar = trim($primerNombre . ' ' . $primerApellido) ?: ($user->email ?? 'Usuario');
    $avatarUrl = ($user && $user->profile_photo_path) ? asset('storage/' . $user->profile_photo_path) : '';
    $initials = 'U';
    if ($user) {
        $initials = strtoupper(substr($primerNombre, 0, 1) . substr($primerApellido, 0, 1));
    }
@endphp
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasChatISAAC" aria-labelledby="offcanvasChatISAACLabel" data-user-name="{{ $nombreMostrar }}" data-is-admin="{{ $isAdmin ? 'true' : 'false' }}" data-avatar-url="{{ $avatarUrl }}" data-initials="{{ $initials }}" data-chat-route="{{ route('isaac.chat') }}">
  <div class="offcanvas-header justify-content-between border-bottom">
    <h4 id="offcanvasChatISAACLabel" class="mb-0 d-flex align-items-center">
      <div class="avatar avatar-xs avatar-circle me-2">
        <span class="avatar-initials bg-primary text-white"><i class="bi-robot"></i></span>
      </div>
      ISAAC <i class="bi-patch-check-fill text-primary ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Inteligencia Artificial"></i>
    </h4>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column p-0">
    <div class="flex-grow-1 overflow-auto p-3" id="chatWindow">
      <div class="d-flex mb-3">
        <div class="flex-shrink-0">
          <div class="avatar avatar-sm avatar-circle">
            <span class="avatar-initials bg-primary text-white"><i class="bi-robot"></i></span>
          </div>
        </div>
        <div class="flex-grow-1 ms-3">
          <div class="border rounded p-2 text-body">
            <p class="mb-0" style="font-size: 0.875rem;">Hola <strong>{{ $nombreUsuario }}</strong>, soy <strong>ISAAC</strong>, la Inteligencia Artificial del sistema HOSPROGRESO. Es un gusto hablar contigo hoy. ¿En qué puedo ayudarte?</p>
          </div>
        </div>
      </div>
    </div>
    <div id="isaacStatus" class="px-3 py-1 small text-muted d-none" style="font-size: 0.75rem;">
      <i class="bi-robot me-1"></i> ISAAC está pensando...
    </div>
    <div class="p-3 border-top">
      <div class="border rounded-pill p-1 d-flex align-items-end position-relative">
        <textarea id="chatInput" class="form-control border-0 bg-transparent shadow-none px-3 py-2" rows="1" placeholder="Escribe a ISAAC..." style="resize: none; overflow-y: hidden; min-height: 40px;"></textarea>
        <button type="button" id="sendChatBtn" class="btn btn-primary btn-icon rounded-circle flex-shrink-0 mb-1 me-1">
          <i class="bi-send-fill" id="sendChatIcon"></i>
          <span class="spinner-border spinner-border-sm d-none" id="sendChatSpinner" role="status" aria-hidden="true"></span>
        </button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const container = document.getElementById('offcanvasChatISAAC');
  const chatInput = document.getElementById('chatInput');
  const sendChatBtn = document.getElementById('sendChatBtn');
  const chatWindow = document.getElementById('chatWindow');
  const sendChatIcon = document.getElementById('sendChatIcon');
  const sendChatSpinner = document.getElementById('sendChatSpinner');
  const isaacStatus = document.getElementById('isaacStatus');
  const userName = container.dataset.userName;
  const isAdmin = container.dataset.isAdmin === 'true';
  const avatarUrl = container.dataset.avatarUrl;
  const initials = container.dataset.initials;
  const chatRoute = container.dataset.chatRoute;

  const userAvatarHtml = avatarUrl
    ? `<img class="avatar avatar-sm avatar-circle" src="${avatarUrl}" alt="${userName}" style="object-fit: cover;">`
    : `<div class="avatar avatar-sm avatar-circle avatar-soft-primary"><span class="avatar-initials">${initials}</span></div>`;

  chatInput.addEventListener('input', function() {
    this.style.height = '';
    this.style.height = this.scrollHeight + 'px';
  });

  function appendUserMessage(text) {
    const badgeHtml = isAdmin ? '<i class="bi-patch-check-fill text-primary" style="font-size: 0.65rem;"></i>' : '';
    const safeText = text.replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/\n/g, '<br>'); // Prevención básica XSS
    
    const msgHtml = `
      <div class="d-flex mb-3 flex-row-reverse">
        <div class="flex-shrink-0">${userAvatarHtml}</div>
        <div class="flex-grow-1 me-2 text-end">
          <div class="bg-primary text-white rounded p-2 d-inline-block" style="text-align: left;">
            <p class="mb-0" style="font-size: 0.85rem;">${safeText}</p>
          </div>
          <div class="mt-1" style="font-size: 0.7rem; opacity: 0.8;">${userName} ${badgeHtml}</div>
        </div>
      </div>`;
    chatWindow.insertAdjacentHTML('beforeend', msgHtml);
    scrollToBottom();
  }

  function typeWriter(element, text, speed = 20) {
    let i = 0;
    element.innerHTML = '';
    function type() {
      if (i < text.length) {
        let char = text.charAt(i);
        if (char === '\n') {
           element.innerHTML += '<br>';
        } else {
           element.innerHTML += char;
        }
        i++;
        scrollToBottom();
        setTimeout(type, speed);
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
      </div>`;
    chatWindow.insertAdjacentHTML('beforeend', msgHtml);
    typeWriter(document.getElementById(messageId), text);
  }

  function scrollToBottom() {
    chatWindow.scrollTop = chatWindow.scrollHeight;
  }

  function toggleLoading(isLoading) {
    sendChatBtn.disabled = isLoading;
    chatInput.disabled = isLoading;
    sendChatIcon.classList.toggle('d-none', isLoading);
    sendChatSpinner.classList.toggle('d-none', !isLoading);
    isaacStatus.classList.toggle('d-none', !isLoading);
    
    if (isLoading) scrollToBottom();
    else chatInput.focus();
  }

  function sendMessage() {
    const message = chatInput.value.trim();
    if (!message) return;

    appendUserMessage(message);
    chatInput.value = '';
    chatInput.style.height = '40px';
    toggleLoading(true);

    fetch(chatRoute, {
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
      appendIsaacMessage(data.success ? data.reply : `<span class="text-danger"><i class="bi-exclamation-triangle"></i> ${data.reply || 'Error de servidor.'}</span>`);
    })
    .catch(err => {
      toggleLoading(false);
      appendIsaacMessage('<span class="text-danger"><i class="bi-x-octagon"></i> Existe un error de conexión.</span>');
    });
  }

  sendChatBtn.addEventListener('click', sendMessage);
  chatInput.addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      sendMessage();
    }
  });
});
</script>