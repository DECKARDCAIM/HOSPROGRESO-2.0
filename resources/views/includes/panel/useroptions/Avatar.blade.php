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
    $estadoActual = $user->estado ?? 'disponible';
    $hexMap = [
        'disponible' => '#01C3A2',
        'ocupado' => '#E64A76',
        'ausente' => '#F5CA99',
        'privado' => '#6A7178',
        'desconectado' => '#6A7178',
    ];
    $estadoConfig = [
        'disponible' => ['color' => 'success', 'label' => 'Disponible'],
        'ocupado' => ['color' => 'danger', 'label' => 'Ocupado'],
        'ausente' => ['color' => 'warning', 'label' => 'Ausente'],
        'privado' => ['color' => 'secondary', 'label' => 'Privado'],
        'desconectado' => ['color' => 'secondary', 'label' => 'Desconectado'],
    ];
    if (!array_key_exists($estadoActual, $estadoConfig)) {
        $estadoActual = 'disponible';
    }
    $colorActual = $estadoConfig[$estadoActual]['color'];
    $labelActual = $estadoConfig[$estadoActual]['label'];
    $hexActual = $hexMap[$estadoActual] ?? '#6A7178';
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
                <span class="avatar-status avatar-sm-status avatar-status-{{ $colorActual }}" id="avatar-status-indicator"></span>
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
            <div class="dropdown">
                <a class="navbar-dropdown-submenu-item dropdown-item dropdown-toggle" href="javascript:;" id="navSubmenuPagesAccountDropdown1" data-bs-toggle="dropdown">
                    Estado
                    <span class="legend-indicator ms-2" id="current-status-dot" style="background-color: {{ $hexActual }} !important; border-color: {{ $hexActual }} !important;">
                    </span>
                    <span class="ms-1">{{ $labelActual }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end navbar-dropdown-menu navbar-dropdown-menu-borderless navbar-dropdown-sub-menu">
                    @foreach (['disponible', 'ocupado', 'ausente', 'privado'] as $est)
                        @php $thisHex = $hexMap[$est]; @endphp
                        <a class="dropdown-item estado-option {{ $estadoActual === $est ? 'active' : '' }}" href="javascript:;" data-estado="{{ $est }}">
                            <span class="legend-indicator me-1" style="background-color: {{ $thisHex }} !important; border-color: {{ $thisHex }} !important;"></span>
                            {{ $estadoConfig[$est]['label'] }}
                            @if ($estadoActual === $est)
                                <i class="bi-check-lg float-end"></i>
                            @endif
                        </a>
                    @endforeach
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="javascript:;" id="restablecer-estado">Restablecer estado</a>
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
@push('scripts')
<script>
$(function() {
    const ESTADOS = {
        disponible: {
            color: 'success',
            label: 'Disponible',
            hex: '#01C3A2'
        },
        ocupado: {
            color: 'danger',
            label: 'Ocupado',
            hex: '#E64A76'
        },
        ausente: {
            color: 'warning',
            label: 'Ausente',
            hex: '#F5CA99'
        },
        privado: {
            color: 'secondary',
            label: 'Privado',
            hex: '#6A7178'
        }
    };

    const $toggle = $('#navSubmenuPagesAccountDropdown1');
    const $legend = $toggle.find('.legend-indicator');
    const $avatar = $('#avatar-status-indicator');
    const $dropdown = $('.navbar-dropdown-sub-menu');

    const AVATAR_CLASSES =
        'avatar-status-success avatar-status-danger avatar-status-warning avatar-status-secondary';

    function updateEstadoUI(estado) {
        const cfg = ESTADOS[estado];
        if (!cfg) return;

        $legend.css({
            'background-color': cfg.hex,
            'border-color': cfg.hex
        }).attr('style', function(i, s) {
            return 'background-color: ' + cfg.hex + ' !important; border-color: ' + cfg.hex +
                ' !important;';
        });

        $toggle.find('span:last').text(cfg.label);
        $avatar.removeClass(AVATAR_CLASSES).addClass(`avatar-status-${cfg.color}`);

        $('.estado-option').removeClass('active').find('i.bi-check-lg').remove();
        const $activeOpt = $(`.estado-option[data-estado="${estado}"]`);
        $activeOpt.addClass('active').append('<i class="bi-check-lg float-end"></i>');
    }

    function cambiarEstado(estado) {
        updateEstadoUI(estado);
        $dropdown.removeClass('show');

        $.post('{{ route('user.estado') }}', {
            estado: estado,
            _token: '{{ csrf_token() }}'
        }).done(r => {
            if (r.success && r.estado && r.estado !== estado) {
                updateEstadoUI(r.estado);
            }
        }).fail(err => {
            console.error("Error:", err);
        });
    }

    $('.estado-option').on('click', function(e) {
        e.preventDefault();
        cambiarEstado($(this).data('estado'));
    });

    $('#restablecer-estado').on('click', e => {
        e.preventDefault();
        cambiarEstado('disponible');
    });
});
</script>
@endpush