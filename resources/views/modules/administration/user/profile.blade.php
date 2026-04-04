@extends('layouts.panel')
@section('title', ' Perfil de ' . $user->first_name)
@section('content')
<main id="content" role="main" class="main">
    <div class="content container-fluid">
        <div class="row justify-content-lg-center">
            <div class="col-lg-10">
                <div class="profile-cover">
                    <div class="profile-cover-img-wrapper">
                        <img id="profileCoverImg" class="profile-cover-img" src="{{ $user->banner_photo_path ? asset('storage/' . $user->banner_photo_path) : asset('dist/img/1920x400/img2.jpg') }}" data-src="{{ $user->banner_photo_path ? asset('storage/' . $user->banner_photo_path) : asset('dist/img/1920x400/img2.jpg') }}" alt="Image Description" onerror="this.onerror=null; retryImageLoad(this);">
                    </div>
                </div>
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
                    <div class="avatar avatar-xxl avatar-circle profile-cover-avatar" style="position: relative; border: none; background-color: #fff;">
                        @if ($user->profile_photo_path)
                        <img class="avatar-img" id="editAvatarImgModal" src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Foto de perfil">
                        @else
                        <span class="avatar-soft-primary" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
                            <span class="avatar-initials">{{ $iniciales }}</span>
                        </span>
                        <img class="avatar-img d-none" id="editAvatarImgModal" src="" alt="Previsualización de foto">
                        @endif
                    </div>
                    <h1 class="page-header-title">{{ $nombreCompleto }} <i class="bi-patch-check-fill fs-2 text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Administrador"></i></h1>
                </div>
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
                            <a class="nav-link active disabled" href="#">Perfil del Usuario</a>
                        </li>
                        <li class="nav-item ms-auto">
                            <div class="d-flex gap-2">
                                <a class="btn btn-white btn-sm" href="{{ route('users.index') }}">
                                    <i class="bi-arrow-left me-1"></i> Regresar
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card mb-3 mb-lg-5">
                            <div class="card-header card-header-content-between">
                                <h4 class="card-header-title">Información personal</h4>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled list-py-2 text-dark mb-0">
                                    <li class="pb-0"><span class="card-subtitle">Acerca de</span></li>
                                    <li><i class="bi-person dropdown-item-icon"></i> {{ $nombreCompleto }}</li>
                                    <li><i class="bi-briefcase dropdown-item-icon"></i> {{ $user->staff->workDepartment->name ?? ($user->staff->unityExecution->name ?? 'Sin departamento') }}</li>
                                    @if ($user->staff->birth_date ?? null)
                                    <li><i class="bi-calendar dropdown-item-icon"></i> Nacimiento: {{ date('d/m/Y', strtotime($user->staff->birth_date)) }}</li>
                                    @endif
                                    @if ($user->staff->gender->name ?? null)
                                    <li><i class="bi-gender-ambiguous dropdown-item-icon"></i> Género: {{ ucfirst($user->staff->gender->name) }}</li>
                                    @endif
                                    @if ($user->staff->marital_status ?? null)
                                    <li><i class="bi-heart dropdown-item-icon"></i> Estado civil: {{ ucfirst(str_replace('_', ' ', $user->staff->marital_status)) }}</li>
                                    @endif
                                    <li class="pt-4 pb-0"><span class="card-subtitle">Contacto</span></li>
                                    <li><i class="bi-at dropdown-item-icon"></i> {{ $user->email }}</li>
                                    @if ($user->staff->phone ?? null)
                                    <li><i class="bi-phone dropdown-item-icon"></i> {{ $user->staff->phone }}</li>
                                    @endif
                                    @if ($user->staff->address ?? null)
                                    <li><i class="bi-geo-alt dropdown-item-icon"></i> {{ $user->staff->address }}</li>
                                    @endif
                                    <li class="pt-4 pb-0"><span class="card-subtitle">Documentos</span></li>
                                    @if ($user->staff->cui ?? null)
                                    <li><i class="bi-card-heading dropdown-item-icon"></i> CUI: {{ $user->staff->cui }}</li>
                                    @endif
                                    @if ($user->staff->nit ?? null)
                                    <li><i class="bi-card-text dropdown-item-icon"></i> NIT: {{ $user->staff->nit }}</li>
                                    @endif
                                    @if ($user->staff->collegiate_number ?? null)
                                    <li><i class="bi-patch-check dropdown-item-icon"></i> Colegiado: {{ $user->staff->collegiate_number }}</li>
                                    @endif
                                    <li class="pt-4 pb-0"><span class="card-subtitle">Miembros del departamento</span></li>
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
                                                <div class="avatar avatar-sm avatar-circle" style="border: 2px solid #28a745;">
                                                    @if ($member->profile_photo_path)
                                                    <img class="avatar-img" src="{{ asset('storage/' . $member->profile_photo_path) }}" alt="{{ $memNombreMostrar }}">
                                                    @else
                                                    <div class="avatar-img avatar-soft-primary" style="display: flex; align-items: center; justify-content: center; width: 100%; height: 100%;">
                                                        <span class="avatar-initials">{{ $memIniciales }}</span>
                                                    </div>
                                                    @endif
                                                </div>

                                            </div>
                                            <div class="flex-grow-1 ms-2">
                                                <span class="text-dark">{{ $memNombreMostrar }}</span>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm rounded-circle" id="memberDropdown{{ $member->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="bi-three-dots-vertical"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="memberDropdown{{ $member->id }}">
                                                        <a class="dropdown-item" href="#">
                                                            <i class="bi-chat-left-dots dropdown-item-icon"></i> Chatear
                                                        </a>
                                                        <a class="dropdown-item" href="{{ route('users.show', $member->id) }}">
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
                                </ul>
                            </div>
                        </div>
                        <div class="card card-lg mb-3 mb-lg-5">
                            <div class="card-body text-center">
                                <div class="mb-4">
                                    <img class="avatar avatar-xl avatar-4x3" src="{{ asset('dist/svg/illustrations/oc-unlock.svg') }}" alt="Image Description" data-hs-theme-appearance="default">
                                    <img class="avatar avatar-xl avatar-4x3" src="{{ asset('dist/svg/illustrations-light/oc-unlock.svg') }}" alt="Image Description" data-hs-theme-appearance="dark">
                                </div>
                                <div class="mb-3">
                                    <h3>No comparta su contraseña</h3>
                                    <p>Su contraseña es privada. Si alguien se la solicita, repórtelo de inmediato.</p>
                                </div>
                                <a class="btn btn-primary" href="#">Reportar</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card card-centered mb-3 mb-lg-5">
                            <div class="card-header card-header-content-between">
                                <h4 class="card-header-title">Actividades Recientes</h4>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm rounded-circle" id="contentActivityStreamDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi-three-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="contentActivityStreamDropdown">
                                        <span class="dropdown-header">Configuración</span>
                                        <a class="dropdown-item" href="#">
                                            <i class="bi-activity dropdown-item-icon"></i> Ver Actividades
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body card-body-height">
                                <img class="avatar avatar-xxl mb-3" src="{{ asset('dist/svg/illustrations/oc-error.svg') }}" alt="Image Description" data-hs-theme-appearance="default">
                                <img class="avatar avatar-xxl mb-3" src="{{ asset('dist/svg/illustrations-light/oc-error.svg') }}" alt="Image Description" data-hs-theme-appearance="dark">
                                <p class="card-text">No hay actividades para mostrar</p>
                                <a class="btn btn-white btn-sm" href="./#">Iniciar Actividades</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush