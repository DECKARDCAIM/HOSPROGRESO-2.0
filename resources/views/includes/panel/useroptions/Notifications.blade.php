<li class="nav-item d-none d-sm-inline-block">
    <div class="dropdown">
        <button type="button" class="btn btn-ghost-secondary btn-icon rounded-circle" id="navbarNotificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside" data-bs-dropdown-animation>
            <i class="bi-bell"></i>
            <span class="btn-status btn-sm-status btn-status-danger {{ $unreadCount > 0 ? '' : 'd-none' }}" id="unread-badge"></span>
        </button>
        <div class="dropdown-menu dropdown-menu-end dropdown-card navbar-dropdown-menu navbar-dropdown-menu-borderless" aria-labelledby="navbarNotificationsDropdown" style="width: 25rem;">
            <div class="card">
                <div class="card-header card-header-content-between">
                    <h4 class="card-title mb-0">Notificaciones</h4>
                    <div class="dropdown">
                        <button type="button" class="btn btn-icon btn-sm btn-ghost-secondary rounded-circle" id="navbarNotificationsDropdownSettings" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi-three-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end navbar-dropdown-menu navbar-dropdown-menu-borderless" aria-labelledby="navbarNotificationsDropdownSettings">
                            <span class="dropdown-header">Configuraciones</span>
                            <a class="dropdown-item" href="javascript:;" id="mark-all-read">
                                <i class="bi-check2-all dropdown-item-icon"></i> Marcar todas como leídas
                            </a>
                            <div class="dropdown-divider"></div>
                            <span class="dropdown-header">Retroalimentación</span>
                            <a class="dropdown-item" href="#">
                                <i class="bi-chat-left-dots dropdown-item-icon"></i> Reportar
                            </a>
                        </div>
                    </div>
                </div>
                <ul class="nav nav-tabs nav-justified" id="notificationTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#notificationNavOne" id="notificationNavOne-tab" data-bs-toggle="tab" data-bs-target="#notificationNavOne" role="tab" aria-controls="notificationNavOne" aria-selected="true">
                            Mensajes ({{ $latestComunicados->count() }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#notificationNavTwo" id="notificationNavTwo-tab" data-bs-toggle="tab" data-bs-target="#notificationNavTwo" role="tab" aria-controls="notificationNavTwo" aria-selected="false">
                            Actualizaciones ({{ $latestActualizaciones->count() }})
                        </a>
                    </li>
                </ul>
                <div class="card-body-height">
                    <div class="tab-content" id="notificationTabContent">
                        <div class="tab-pane fade show active" id="notificationNavOne" role="tabpanel" aria-labelledby="notificationNavOne-tab">
                            <ul class="list-group list-group-flush navbar-card-list-group">
                                @forelse($latestComunicados as $rel)
                                    <li class="list-group-item form-check-select notification-item" data-id="{{ $rel->id }}">
                                        <div class="row">
                                            <div class="col-auto">
                                                <div class="d-flex align-items-center">
                                                    <div class="form-check">
                                                        <input class="form-check-input mark-read-check" type="checkbox" value="" id="notificationCheck{{ $rel->id }}" data-id="{{ $rel->id }}" {{ !$rel->is_read ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="notificationCheck{{ $rel->id }}"></label>
                                                        <span class="form-check-stretched-bg"></span>
                                                    </div>
                                                    <div class="avatar avatar-sm avatar-circle">
                                                        @if ($rel->author && $rel->author->profile_photo_path)
                                                            <img class="avatar-img" src="{{ asset('storage/' . $rel->author->profile_photo_path) }}" alt="Avatar">
                                                        @elseif($rel->author)
                                                            @php
                                                                $authorInitials = strtoupper(
                                                                    substr(
                                                                        $rel->author->first_name,
                                                                        0,
                                                                        1,
                                                                    ),
                                                                );
                                                                if (
                                                                    !empty(
                                                                        $rel->author->first_last_name
                                                                    )
                                                                ) {
                                                                    $authorInitials .= strtoupper(
                                                                        substr(
                                                                            $rel->author
                                                                                ->first_last_name,
                                                                            0,
                                                                            1,
                                                                        ),
                                                                    );
                                                                }
                                                            @endphp
                                                            <div class="avatar-img avatar-soft-primary">
                                                                <span class="avatar-initials">{{ $authorInitials }}</span>
                                                            </div>
                                                        @else
                                                            <div class="avatar-img avatar-soft-primary">
                                                                <span class="avatar-initials">U</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col ms-n2">
                                                <h5 class="mb-1">{{ $rel->title }}</h5>
                                                <p class="text-body fs-5 text-truncate" style="max-width: 250px;">{{ strip_tags($rel->content) }}</p>
                                            </div>
                                            <small class="col-auto text-muted text-cap">{{ $rel->published_at->locale('es')->diffForHumans() }}</small>
                                        </div>
                                        <a class="stretched-link notification-link" href="{{ route('releases.show', $rel->id) }}" data-id="{{ $rel->id }}"></a>
                                    </li>
                                @empty
                                    <li class="list-group-item">
                                        <div class="text-center p-4">
                                            <img class="mb-3" src="{{ asset('dist/svg/illustrations/oc-error.svg') }}" alt="Sin mensajes" style="width: 7rem;" data-hs-theme-appearance="default">
                                            <p class="mb-0">No hay mensajes recientes</p>
                                        </div>
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                        <div class="tab-pane fade" id="notificationNavTwo" role="tabpanel" aria-labelledby="notificationNavTwo-tab">
                            <ul class="list-group list-group-flush navbar-card-list-group">
                                @forelse($latestActualizaciones as $act)
                                    <li class="list-group-item form-check-select notification-item" data-id="{{ $act->id }}">
                                        <div class="row">
                                            <div class="col-auto">
                                                <div class="d-flex align-items-center">
                                                    <div class="form-check">
                                                        <input class="form-check-input mark-read-check" type="checkbox" value="" id="notificationCheckAct{{ $act->id }}" data-id="{{ $act->id }}" {{ !$act->is_read ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="notificationCheckAct{{ $act->id }}"></label>
                                                        <span class="form-check-stretched-bg"></span>
                                                    </div>
                                                    <div class="avatar avatar-sm avatar-circle">
                                                        @if ($act->author && $act->author->profile_photo_path)
                                                            <img class="avatar-img" src="{{ asset('storage/' . $act->author->profile_photo_path) }}" alt="Avatar">
                                                        @elseif($act->author)
                                                            @php
                                                                $authorInitials = strtoupper(
                                                                    substr(
                                                                        $act->author->first_name,
                                                                        0,
                                                                        1,
                                                                    ),
                                                                );
                                                                if (
                                                                    !empty(
                                                                        $act->author->first_last_name
                                                                    )
                                                                ) {
                                                                    $authorInitials .= strtoupper(
                                                                        substr(
                                                                            $act->author
                                                                                ->first_last_name,
                                                                            0,
                                                                            1,
                                                                        ),
                                                                    );
                                                                }
                                                            @endphp
                                                            <div class="avatar-img avatar-soft-primary">
                                                                <span class="avatar-initials">{{ $authorInitials }}</span>
                                                            </div>
                                                        @else
                                                            <div class="avatar-img avatar-soft-primary">
                                                                <span class="avatar-initials">U</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col ms-n2">
                                                <h5 class="mb-1">{{ $act->title }}</h5>
                                                <p class="text-body fs-5 text-truncate" style="max-width: 250px;">{{ strip_tags($act->content) }}</p>
                                            </div>
                                            <small class="col-auto text-muted text-cap">{{ $act->published_at->locale('es')->diffForHumans() }}</small>
                                        </div>
                                        <a class="stretched-link notification-link" href="{{ route('releases.show', $act->id) }}" data-id="{{ $act->id }}"></a>
                                    </li>
                                @empty
                                    <li class="list-group-item">
                                        <div class="text-center p-4">
                                            <img class="mb-3" src="{{ asset('dist/svg/illustrations/oc-error.svg') }}" alt="Sin actualizaciones" style="width: 7rem;" data-hs-theme-appearance="default">
                                            <p class="mb-0">No hay actualizaciones recientes</p>
                                        </div>
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                <a class="card-footer text-center" href="#">Ver todas las notificaciones <i class="bi-chevron-right"></i></a>
            </div>
        </div>
    </div>
</li>
@push('scripts')
<script type="module">
$(function() {
    window.updateBadge = function() {
        const count = $('.mark-read-check:checked').length;
        const hasNew = $('#unread-badge').data('has-new') === true;
        if (count > 0 || hasNew) {
            $('#unread-badge').removeClass('d-none');
        } else {
            $('#unread-badge').addClass('d-none');
        }
    }

    window.refreshNotificationDropdown = function() {
        $('#unread-badge').data('has-new', true);
        window.updateBadge();
    };

    $('.mark-read-check').on('change', function() {
        const id = $(this).data('id');
        let url = "{{ route('releases.mark-as-read', 99999) }}".replace('99999', id);
        const isChecked = $(this).is(':checked');

        if (!isChecked) {
            $.post(url, {
                _token: '{{ csrf_token() }}'
            }).done(() => {
                window.updateBadge();
            });
        }
    });

    $('.notification-link').on('click', function(e) {
        const id = $(this).data('id');
        const $check = $(`.mark-read-check[data-id="${id}"]`);

        if ($check.is(':checked')) {
            $check.prop('checked', false).trigger('change');
        }
    });

    $('#mark-all-read').on('click', function(e) {
        e.preventDefault();
        $.post('{{ route('releases.mark-all-as-read') }}', {
            _token: '{{ csrf_token() }}'
        }).done(r => {
            if (r.success) {
                $('.mark-read-check').prop('checked', false);
                $('#unread-badge').data('has-new', false);
                window.updateBadge();
            }
        });
    });
});
</script>
@endpush