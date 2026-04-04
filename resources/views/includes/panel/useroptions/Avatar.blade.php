@php
    $user = Auth::user();
    $primerNombre = $user->first_name ?? '';
    $primerApellido = $user->first_last_name ?? '';
    $nombreMostrar = trim($primerNombre . ' ' . $primerApellido) ?: $user->email ?? 'Usuario';
    $iniciales = '';
    if (!empty($primerNombre)) {
        $iniciales .= strtoupper(substr($primerNombre, 0, 1));
    }
    if (!empty($primerApellido)) {
        $iniciales .= strtoupper(substr($primerApellido, 0, 1));
    }
    if (empty($iniciales)) {
        $iniciales = 'U';
    }

@endphp
<li class="nav-item">
    <div class="dropdown">
        <a class="navbar-dropdown-account-wrapper" href="javascript:;" id="accountNavbarDropdown" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside" data-bs-dropdown-animation>
            <div class="avatar avatar-sm avatar-circle">
                @if ($user && $user->profile_photo_path)
                    <img class="avatar-img" id="navbar-avatar-img" src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Avatar" onerror="this.onerror=null; retryNavbarImage(this);">
                @else
                    <div class="avatar-img avatar-soft-primary" id="navbar-avatar-initials">
                        <span class="avatar-initials">{{ $iniciales }}</span>
                    </div>
                @endif

            </div>
        </a>
        <div class="dropdown-menu dropdown-menu-end navbar-dropdown-menu navbar-dropdown-menu-borderless navbar-dropdown-account bg-white" aria-labelledby="accountNavbarDropdown" style="width: 16rem;">
            <div class="dropdown-item-text">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-sm avatar-circle">
                        @if ($user && $user->profile_photo_path)
                            <img class="avatar-img" id="dropdown-avatar-img" style="max-width: none;" src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Avatar">
                        @else
                            <div class="avatar-img avatar-soft-primary" id="dropdown-avatar-initials">
                                <span class="avatar-initials">{{ $iniciales }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="mb-0">{{ $nombreMostrar }}</h5>
                        <p class="card-text text-body" style="font-size: 0.85rem;">{{ $user->email ?? '' }}</p>
                    </div>
                </div>
            </div>
            <div class="dropdown-divider"></div>

            <a class="dropdown-item" href="{{ route('profile.index') }}">Mi Perfil</a>
            <div class="dropdown-divider"></div>
            <form method="POST" action="{{ route('logout') }}" onsubmit="if(window.showManualLoader) window.showManualLoader('Cerrando sesión...');">
                @csrf
                <button type="submit" class="dropdown-item text-danger" style="border: none; background: none; width: 100%; text-align: left; padding: 0.5rem 1rem;">
                    <i class="bi-box-arrow-right me-2"></i> Cerrar sesión
                </button>
            </form>
        </div>
    </div>
</li>