@extends('layouts.panel')
@section('title', ' Mi Perfil')

@section('content')
<main id="content" role="main" class="main">
    <!-- Content -->
    <div class="content container-fluid">
        <div class="row justify-content-lg-center">
            <div class="col-lg-10">
                <div class="profile-cover">
                    <div class="profile-cover-img-wrapper">

                        <img id="profileCoverImg" class="profile-cover-img"
                            src="{{ $user->banner_photo_path ? asset('storage/' . $user->banner_photo_path) : asset('img/1920x400/img2.jpg') }}"
                            data-src="{{ $user->banner_photo_path ? asset('storage/' . $user->banner_photo_path) : asset('img/1920x400/img2.jpg') }}"
                            alt="Image Description" onerror="this.onerror=null; retryImageLoad(this);">
                    </div>
                </div>

                <!-- Profile Header -->
                <div class="text-center mb-5">
                    @php
                    $nombreCompleto =
                    trim($user->first_name . ' ' . trim($user->second_name . ' ' . $user->third_name)) .
                    ' ' .
                    trim(
                    $user->first_last_name .
                    ' ' .
                    $user->second_last_name .
                    ' ' .
                    $user->married_last_name,
                    );
                    $iniciales = '';
                    if (!empty($user->first_name)) {
                    $iniciales .= strtoupper(substr($user->first_name, 0, 1));
                    }
                    if (!empty($user->first_last_name)) {
                    $iniciales .= strtoupper(substr($user->first_last_name, 0, 1));
                    }
                    if (empty($iniciales)) {
                    $iniciales = 'U';
                    }
                    @endphp

                    <div class="avatar avatar-xxl avatar-circle profile-cover-avatar"
                        style="position: relative; border: none; background-color: #fff;">

                        @if ($user->profile_photo_path)
                        <img class="avatar-img" id="editAvatarImgModal"
                            src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Foto de perfil">
                        @else
                        <span class="avatar-soft-primary"
                            style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
                            <span class="avatar-initials">{{ $iniciales }}</span>
                        </span>
                        <img class="avatar-img d-none" id="editAvatarImgModal" src="" alt="Previsualización de foto">
                        @endif
                    </div>
                    <h1 class="page-header-title">{{ $nombreCompleto }} <i class="bi-patch-check-fill fs-2 text-primary"
                            data-bs-toggle="tooltip" data-bs-placement="top" title="Administrador"></i></h1>
                </div>
                <!-- End Profile Header -->

                <!-- Nav -->
                <div class="js-nav-scroller hs-nav-scroller-horizontal mb-5">
                    <span class="hs-nav-scroller-arrow-prev" style="display: none;">
                        <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                            <i class="bi-chevron-left"></i>
                        </a>
                    </span>

                    <span class="hs-nav-scroller-arrow-next" style="display: none;">
                        <a class="hs-nav-scroller-arrow-link" href="javascript:;">
                            <i class="bi-chevron-right"></i>
                        </a>
                    </span>

                    <ul class="nav nav-tabs align-items-center">
                        <li class="nav-item">
                            <a class="nav-link active disabled" href="#">Mi Perfil</a>
                        </li>

                        <li class="nav-item ms-auto">
                            <div class="d-flex gap-2">
                                <a class="btn btn-white btn-sm" href="{{ route('profile.edit') }}">
                                    <i class="bi-person-plus-fill me-1"></i> Editar perfil
                                </a>

                                <div class="dropdown nav-scroller-dropdown">
                                    <button type="button" class="btn btn-white btn-icon btn-sm" id="profileDropdown"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi-three-dots-vertical"></i>
                                    </button>

                                    <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="profileDropdown">
                                        <span class="dropdown-header">Configuración</span>

                                        <a class="dropdown-item" href="#">
                                            <i class="bi-share-fill dropdown-item-icon"></i> Compartir perfil
                                        </a>

                                        <div class="dropdown-divider"></div>

                                        <span class="dropdown-header">Feedback</span>

                                        <a class="dropdown-item" href="#">
                                            <i class="bi-flag dropdown-item-icon"></i> Reportar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <!-- End Nav -->

                <div class="row">
                    <div class="col-lg-4">
                        <!-- Card -->
                        <div class="card mb-3 mb-lg-5">
                            <!-- Header -->
                            <div class="card-header card-header-content-between">
                                <h4 class="card-header-title">Información personal</h4>
                            </div>
                            <!-- End Header -->

                            <!-- Body -->
                            <div class="card-body">
                                <ul class="list-unstyled list-py-2 text-dark mb-0">
                                    <li class="pb-0"><span class="card-subtitle">Acerca de</span></li>
                                    <li><i class="bi-person dropdown-item-icon"></i> {{ $nombreCompleto }}</li>
                                    <li><i class="bi-briefcase dropdown-item-icon"></i>
                                        {{ $user->workDepartment ? $user->workDepartment->name : ($user->unityExecution
                                        ? $user->unityExecution->name : 'Sin departamento') }}</li>
                                    @if ($user->birth_date)
                                    <li><i class="bi-calendar dropdown-item-icon"></i> Nacimiento: {{ date('d/m/Y',
                                        strtotime($user->birth_date)) }}</li>
                                    @endif
                                    @if ($user->gender)
                                    <li><i class="bi-gender-ambiguous dropdown-item-icon"></i> Género: {{
                                        ucfirst($user->gender) }}</li>
                                    @endif
                                    @if ($user->marital_status)
                                    <li><i class="bi-heart dropdown-item-icon"></i> Estado civil: {{
                                        ucfirst(str_replace('_', ' ', $user->marital_status)) }}</li>
                                    @endif

                                    <li class="pt-4 pb-0"><span class="card-subtitle">Contacto</span></li>
                                    <li><i class="bi-at dropdown-item-icon"></i> {{ $user->email }}</li>
                                    @if ($user->phone)
                                    <li><i class="bi-phone dropdown-item-icon"></i> {{ $user->phone }}</li>
                                    @endif
                                    @if ($user->address)
                                    <li><i class="bi-geo-alt dropdown-item-icon"></i> {{ $user->address }}</li>
                                    @endif

                                    <li class="pt-4 pb-0"><span class="card-subtitle">Documentos</span></li>
                                    @if ($user->cui)
                                    <li><i class="bi-card-heading dropdown-item-icon"></i> CUI / DPI: {{ $user->cui }}
                                    </li>
                                    @endif
                                    @if ($user->nit)
                                    <li><i class="bi-card-text dropdown-item-icon"></i> NIT: {{ $user->nit }}</li>
                                    @endif

                                    <li class="pt-4 pb-0"><span class="card-subtitle">Miembros del departamento</span>
                                    </li>

                                    @forelse ($departamentMembers as $member)
                                    @php
                                    $memPrimerNombre = $member->first_name ?? '';
                                    $memPrimerApellido = $member->first_last_name ?? '';
                                    $memNombreMostrar = trim($memPrimerNombre . ' ' . $memPrimerApellido) ?:
                                    ($member->email ?? 'Usuario');
                                    $memIniciales = '';
                                    if (!empty($memPrimerNombre)) $memIniciales .= strtoupper(substr($memPrimerNombre,
                                    0, 1));
                                    if (!empty($memPrimerApellido)) $memIniciales .=
                                    strtoupper(substr($memPrimerApellido, 0, 1));
                                    if (empty($memIniciales)) $memIniciales = 'U';
                                    @endphp
                                    <li class="pt-2">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0" style="position: relative;">
                                                <div class="avatar avatar-sm avatar-circle"
                                                    style="border: 2px solid #28a745;">
                                                    @if ($member->profile_photo_path)
                                                    <img class="avatar-img"
                                                        src="{{ asset('storage/' . $member->profile_photo_path) }}"
                                                        alt="{{ $memNombreMostrar }}">
                                                    @else
                                                    <div class="avatar-img avatar-soft-primary"
                                                        style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
                                                        <span class="avatar-initials">{{ $memIniciales }}</span>
                                                    </div>
                                                    @endif
                                                </div>
                                                @if($member->estado === 'disponible')
                                                <span
                                                    class="avatar-status avatar-sm-status avatar-status-success"></span>
                                                @elseif($member->estado === 'ocupado')
                                                <span
                                                    class="avatar-status avatar-sm-status avatar-status-danger"></span>
                                                @elseif($member->estado === 'ausente')
                                                <span
                                                    class="avatar-status avatar-sm-status avatar-status-warning"></span>
                                                @elseif($member->estado === 'privado')
                                                <span class="avatar-status avatar-sm-status avatar-status-dark"></span>
                                                @elseif($member->estado === 'desconectado')
                                                <span class="avatar-status avatar-sm-status avatar-status-secondary"></span>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <span class="text-dark">{{ $memNombreMostrar }}</span>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="dropdown">
                                                    <button type="button"
                                                        class="btn btn-ghost-secondary btn-icon btn-sm rounded-circle"
                                                        id="memberDropdown{{ $member->id }}" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <i class="bi-three-dots-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end mt-1"
                                                        aria-labelledby="memberDropdown{{ $member->id }}">
                                                        <a class="dropdown-item" href="#">
                                                            <i class="bi-chat-left-dots dropdown-item-icon"></i>
                                                            Chatear
                                                        </a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('users.profile', $member->id) }}">
                                                            <i class="bi-person dropdown-item-icon"></i> Ver perfil
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    @empty
                                    <li class="pt-2 text-muted small">No hay otros miembros en tu departamento.</li>
                                    @endforelse
                                    <!-- End Body -->
                                </ul>
                            </div>
                        </div>
                        <!-- End Card -->

                        <!-- Card -->
                        <div class="card card-lg mb-3 mb-lg-5">
                            <div class="card-body text-center">
                                <div class="mb-4">
                                    <img class="avatar avatar-xl avatar-4x3"
                                        src="{{ asset('svg/illustrations/oc-unlock.svg') }}" alt="Image Description"
                                        data-hs-theme-appearance="default">
                                    <img class="avatar avatar-xl avatar-4x3"
                                        src="{{ asset('svg/illustrations-light/oc-unlock.svg') }}"
                                        alt="Image Description" data-hs-theme-appearance="dark">
                                </div>

                                <div class="mb-3">
                                    <h3>No comparta su contraseña</h3>
                                    <p>Su contraseña es privada. Si alguien se la solicita, repórtelo de inmediato.</p>
                                </div>

                                <a class="btn btn-primary" href="#">Reportar</a>
                            </div>
                        </div>
                        <!-- End Card -->
                    </div>
                    <!-- End Col -->

                    <div class="col-lg-8">
                        <div class="card card-centered mb-3 mb-lg-5">
                            <div class="card-header card-header-content-between">
                                <h4 class="card-header-title">Actividades Recientes</h4>

                                <div class="dropdown">
                                    <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm rounded-circle"
                                        id="contentActivityStreamDropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="bi-three-dots-vertical"></i>
                                    </button>

                                    <div class="dropdown-menu dropdown-menu-end mt-1"
                                        aria-labelledby="contentActivityStreamDropdown">
                                        <span class="dropdown-header">Configuración</span>

                                        <a class="dropdown-item" href="#">
                                            <i class="bi-activity dropdown-item-icon"></i> Ver Actividades
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body card-body-height">
                                <img class="avatar avatar-xxl mb-3" src="{{ asset('svg/illustrations/oc-error.svg') }}"
                                    alt="Image Description" data-hs-theme-appearance="default">
                                <img class="avatar avatar-xxl mb-3"
                                    src="{{ asset('svg/illustrations-light/oc-error.svg') }}" alt="Image Description"
                                    data-hs-theme-appearance="dark">
                                <p class="card-text">No hay actividades para mostrar</p>
                                <a class="btn btn-white btn-sm" href="./#">Iniciar Actividades</a>
                            </div>
                        </div>
                        <div class="card card-centered mb-3 mb-lg-5">
                            <div class="card-header card-header-content-between">
                                <h4 class="card-header-title">Dispositivos</h4>

                                <div class="dropdown">
                                    <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm rounded-circle"
                                        id="projectReportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi-three-dots-vertical"></i>
                                    </button>

                                    <div class="dropdown-menu dropdown-menu-end mt-1"
                                        aria-labelledby="projectReportDropdown">
                                        <span class="dropdown-header">Configuración</span>

                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#sessionHistoryModal">
                                            <i class="bi-clock-history dropdown-item-icon"></i> Ver registros
                                        </a>

                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#logoutOtherBrowserSessionsModal">
                                            <i class="bi-box-arrow-right dropdown-item-icon"></i> Cerrar sesiónes
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="card-text text-start">Puedes cerrar sesión en todos tus otros dispositivos si
                                    lo deseas. A continuación se muestran tus sesiones Activas.</p>

                                @if (count($activeSessions) > 0)
                                <ul
                                    class="list-group list-group-flush list-group-no-gutters d-flex align-items-start text-start">
                                    @foreach ($activeSessions as $session)
                                    <li class="list-group-item w-100">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                @if ($session->agent['is_desktop'])
                                                <i class="bi-display fs-2 text-muted"></i>
                                                @else
                                                <i class="bi-phone fs-2 text-muted"></i>
                                                @endif
                                            </div>

                                            <div class="flex-grow-1 ms-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h5 class="mb-0">
                                                        {{ $session->agent['platform'] ? $session->agent['platform'] :
                                                        'Desconocido' }}
                                                        -
                                                        {{ $session->agent['browser'] ? $session->agent['browser'] :
                                                        'Desconocido' }}

                                                        @if ($session->is_active)
                                                        <span
                                                            class="badge bg-soft-success text-success ms-2">Activa</span>
                                                        @else
                                                        <span
                                                            class="badge bg-soft-secondary text-secondary ms-2">Cerrada</span>
                                                        @endif
                                                    </h5>
                                                </div>
                                                <ul class="list-inline list-separator small text-muted mb-0 mt-1">
                                                    <li class="list-inline-item">{{ $session->ip_address }}
                                                    </li>
                                                    <li class="list-inline-item" data-bs-toggle="tooltip"
                                                        title="Fecha de inicio de sesión">
                                                        <i class="bi-calendar-event me-1"></i>
                                                        {{ $session->login_at }}
                                                    </li>
                                                    <li class="list-inline-item">
                                                        @if ($session->is_current_device)
                                                        <span class="text-success fw-semibold">Este
                                                            dispositivo</span>
                                                        @else
                                                        Última act.: {{ $session->last_active }}
                                                        @endif
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- End Col -->
                </div>
                <!-- End Row -->
            </div>
            <!-- End Col -->
        </div>
        <!-- End Row -->
    </div>
    <!-- End Content -->

    <!-- Modal Cerrar Sesiones -->
    <div class="modal fade" id="logoutOtherBrowserSessionsModal" tabindex="-1"
        aria-labelledby="logoutOtherBrowserSessionsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutOtherBrowserSessionsModalLabel">Cerrar Otras Sesiones del
                        Navegador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('profile.sessions.destroy') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body">
                        <p>Introduce tu contraseña para confirmar que deseas cerrar sesión en tus otros dispositivos en
                            todos los navegadores.</p>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required
                                autocomplete="current-password" placeholder="Tu contraseña actual">
                            @error('password')
                            <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Cerrar otras sesiones</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Historial de Sesiones -->
    <div class="modal fade" id="sessionHistoryModal" tabindex="-1" aria-labelledby="sessionHistoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sessionHistoryModalLabel">Historial de Sesiones</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="sessionHistoryLoader" class="text-center p-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </div>

                    <ul id="sessionHistoryList" class="list-group list-group-flush list-group-no-gutters d-none">
                        <!-- Items dinámicos -->
                    </ul>
                </div>
                <div class="modal-footer justify-content-between">
                    <span id="sessionHistoryPaginationInfo" class="small text-muted"></span>
                    <div class="btn-group">
                        <button type="button" id="sessionHistoryPrevBtn" class="btn btn-white btn-sm" disabled>
                            <i class="bi-chevron-left"></i> Anterior
                        </button>
                        <button type="button" id="sessionHistoryNextBtn" class="btn btn-white btn-sm" disabled>
                            Siguiente <i class="bi-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal Historial de Sesiones -->
</main>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let currentHistoryPage = 1;
        const historyModal = document.getElementById('sessionHistoryModal');
        const loader = document.getElementById('sessionHistoryLoader');
        const list = document.getElementById('sessionHistoryList');
        const prevBtn = document.getElementById('sessionHistoryPrevBtn');
        const nextBtn = document.getElementById('sessionHistoryNextBtn');
        const paginationInfo = document.getElementById('sessionHistoryPaginationInfo');

        function loadHistory(page = 1) {
            loader.classList.remove('d-none');
            list.classList.add('d-none');
            prevBtn.disabled = true;
            nextBtn.disabled = true;

            fetch(`/profile/sessions/history?page=${page}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    renderHistoryList(data.data);

                    paginationInfo.textContent =
                        `Mostrando ${data.from || 0} a ${data.to || 0} de ${data.total} entradas`;
                    currentHistoryPage = data.current_page;

                    prevBtn.disabled = !data.prev_page_url;
                    nextBtn.disabled = !data.next_page_url;

                    loader.classList.add('d-none');
                    list.classList.remove('d-none');
                })
                .catch(error => {
                    console.error('Error fetching history:', error);
                    loader.innerHTML = '<p class="text-danger">Hubo un error al cargar el historial.</p>';
                });
        }

        function renderHistoryList(sessions) {
            list.innerHTML = '';

            if (sessions.length === 0) {
                list.innerHTML =
                    '<div class="p-4 text-center"><p class="text-muted">No hay registros de historial.</p></div>';
                return;
            }

            sessions.forEach(session => {
                const icon = session.is_desktop ? '<i class="bi-display fs-2 text-muted"></i>' :
                    '<i class="bi-phone fs-2 text-muted"></i>';
                const statusBadge = session.is_active ?
                    '<span class="badge bg-soft-success text-success ms-2">Activa</span>' :
                    '<span class="badge bg-soft-secondary text-secondary ms-2">Cerrada</span>';

                const currentDeviceBadge = session.is_current_device ?
                    '<span class="text-success fw-semibold">Este dispositivo</span>' :
                    `Última act.: ${session.last_active}`;

                const item = `
                        <li class="list-group-item p-4 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    ${icon}
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">
                                            ${session.platform} - ${session.browser}
                                            ${statusBadge}
                                        </h5>
                                    </div>
                                    <ul class="list-inline list-separator small text-muted mb-0 mt-1">
                                        <li class="list-inline-item">${session.ip_address}</li>
                                        <li class="list-inline-item" data-bs-toggle="tooltip" title="Fecha de inicio de sesión">
                                            <i class="bi-calendar-event me-1"></i> ${session.login_at}
                                        </li>
                                        <li class="list-inline-item">
                                            ${currentDeviceBadge}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    `;
                list.insertAdjacentHTML('beforeend', item);
            });
        }

        // Listeners for pagination
        prevBtn.addEventListener('click', () => loadHistory(currentHistoryPage - 1));
        nextBtn.addEventListener('click', () => loadHistory(currentHistoryPage + 1));

        // Load on modal open
        historyModal.addEventListener('show.bs.modal', function () {
            loadHistory(1);
        });
    });
</script>
@if ($errors->has('password'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var modal = new bootstrap.Modal(document.getElementById('logoutOtherBrowserSessionsModal'));
        modal.show();
    });
</script>
@endif
<script src="{{ asset('vendor/hs-nav-scroller/dist/hs-nav-scroller.min.js') }}"></script>
<script src="{{ asset('vendor/hs-sticky-block/dist/hs-sticky-block.min.js') }}"></script>
<script src="{{ asset('vendor/hs-file-attach/dist/hs-file-attach.min.js') }}"></script>
@endpush