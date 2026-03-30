<header id="header"
    class="navbar navbar-expand-lg navbar-fixed navbar-height navbar-container navbar-bordered bg-white">
    <div class="navbar-nav-wrap">
        <a class="navbar-brand" href="{{ route('home') }}" aria-label="Front">
            <img class="navbar-brand-logo" src="{{ asset('img/Logotipo.svg') }}" alt="Logo"
                data-hs-theme-appearance="default">
            <img class="navbar-brand-logo" src="{{ asset('img/Logotipo-white.svg') }}" alt="Logo"
                data-hs-theme-appearance="dark">
            <img class="navbar-brand-logo-mini" src="{{ asset('img/Logotipo.svg') }}" alt="Logo"
                data-hs-theme-appearance="default">
            <img class="navbar-brand-logo-mini" src="{{ asset('img/Logotipo-white.svg') }}" alt="Logo"
                data-hs-theme-appearance="dark">
        </a>

        <div class="navbar-nav-wrap-content-start">
            <button type="button" class="js-navbar-vertical-aside-toggle-invoker navbar-aside-toggler">
                <i class="bi-arrow-bar-left navbar-toggler-short-align"
                    data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                    data-bs-toggle="tooltip" data-bs-placement="right" title="Collapse"></i>
                <i class="bi-arrow-bar-right navbar-toggler-full-align"
                    data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                    data-bs-toggle="tooltip" data-bs-placement="right" title="Expand"></i>
            </button>

            <div class="dropdown ms-2">
                <div class="d-none d-lg-block">
                    <div
                        class="input-group input-group-merge input-group-borderless input-group-hover-light navbar-input-group">
                        <div class="input-group-prepend input-group-text">
                            <i class="bi-search"></i>
                        </div>
                        <input type="search" class="js-form-search form-control" placeholder="Buscar"
                            aria-label="Buscar"
                            data-hs-form-search-options='{"clearIcon": "#clearSearchResultsIcon", "dropMenuElement": "#searchDropdownMenu", "dropMenuOffset": 20, "toggleIconOnFocus": true, "activeClass": "focus" }'>
                        <a class="input-group-append input-group-text" href="javascript:;">
                            <i id="clearSearchResultsIcon" class="bi-x-lg" style="display: none;"></i>
                        </a>
                    </div>
                </div>

                <button
                    class="js-form-search js-form-search-mobile-toggle btn btn-ghost-secondary btn-icon rounded-circle d-lg-none"
                    type="button"
                    data-hs-form-search-options='{"clearIcon": "#clearSearchResultsIcon", "dropMenuElement": "#searchDropdownMenu", "dropMenuOffset": 20, "toggleIconOnFocus": true, "activeClass": "focus"}'>
                    <i class="bi-search"></i>
                </button>

                <div id="searchDropdownMenu"
                    class="hs-form-search-menu-content dropdown-menu dropdown-menu-form-search navbar-dropdown-menu-borderless bg-white">
                    <div class="card">
                        <div class="card-body-height">
                            <div class="d-lg-none">
                                <div class="input-group input-group-merge navbar-input-group mb-5">
                                    <div class="input-group-prepend input-group-text">
                                        <i class="bi-search"></i>
                                    </div>
                                    <input type="search" class="form-control" placeholder="Buscar" aria-label="Buscar">
                                    <a class="input-group-append input-group-text" href="javascript:;"> <i
                                            class="bi-x-lg"></i></a>
                                </div>
                            </div>

                            <span class="dropdown-header">Busquedas recientes</span>

                            <div class="dropdown-item bg-transparent text-wrap">
                                <a class="btn btn-soft-dark btn-xs rounded-pill" href="/">
                                    Busqueda 1 <i class="bi-search ms-1"></i>
                                </a>
                                <a class="btn btn-soft-dark btn-xs rounded-pill" href="/">
                                    Panel de notificaciones <i class="bi-search ms-1"></i>
                                </a>
                                <a class="btn btn-soft-dark btn-xs rounded-pill" href="/">
                                    Busqueda 3 <i class="bi-search ms-1"></i>
                                </a>
                            </div>

                            <div class="dropdown-divider"></div>

                            <span class="dropdown-header">Tutoriales</span>

                            <a class="dropdown-item" href="/">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <span class="icon icon-soft-dark icon-xs icon-circle">
                                            <i class="bi-sliders"></i>
                                        </span>
                                    </div>

                                    <div class="flex-grow-1 text-truncate ms-2">
                                        <span>Soporte técnico</span>
                                    </div>
                                </div>
                            </a>

                            <div class="dropdown-divider"></div>

                            <span class="dropdown-header">Miembros</span>

                            <a class="dropdown-item" href="/">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <img class="avatar avatar-xs avatar-circle"
                                            src="{{ asset('img/160x160/img10.jpg') }}" alt="Image Description">
                                    </div>
                                    <div class="flex-grow-1 text-truncate ms-2">
                                        <span>Amanda Harvey <i class="tio-verified text-primary" data-toggle="tooltip"
                                                data-placement="top" title="Top endorsed"></i></span>
                                    </div>
                                </div>
                            </a>

                            <a class="dropdown-item" href="/">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <img class="avatar avatar-xs avatar-circle"
                                            src="{{ asset('img/160x160/img3.jpg') }}" alt="Image Description">
                                    </div>
                                    <div class="flex-grow-1 text-truncate ms-2">
                                        <span>David Harrison</span>
                                    </div>
                                </div>
                            </a>

                            <a class="dropdown-item" href="/">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <div class="avatar avatar-xs avatar-soft-info avatar-circle">
                                            <span class="avatar-initials">A</span>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 text-truncate ms-2">
                                        <span>Anne Richard</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <a class="card-footer text-center" href="/">
                            Ver todos los resultados <i class="bi-chevron-right small"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>










        <div class="navbar-nav-wrap-content-end">
            <ul class="navbar-nav">
                <li class="nav-item d-none d-sm-inline-block">

                    <div class="dropdown">
                        <button type="button" class="btn btn-ghost-secondary btn-icon rounded-circle"
                            id="navbarNotificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                            data-bs-auto-close="outside" data-bs-dropdown-animation>
                            <i class="bi-bell"></i>
                            <span
                                class="btn-status btn-sm-status btn-status-danger {{ $unreadCount > 0 ? '' : 'd-none' }}"
                                id="unread-badge"></span>
                        </button>

                        <div class="dropdown-menu dropdown-menu-end dropdown-card navbar-dropdown-menu navbar-dropdown-menu-borderless"
                            aria-labelledby="navbarNotificationsDropdown" style="width: 25rem;">
                            <div class="card">
                                <div class="card-header card-header-content-between">
                                    <h4 class="card-title mb-0">Notificaciones</h4>

                                    <div class="dropdown">
                                        <button type="button"
                                            class="btn btn-icon btn-sm btn-ghost-secondary rounded-circle"
                                            id="navbarNotificationsDropdownSettings" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <i class="bi-three-dots-vertical"></i>
                                        </button>

                                        <div class="dropdown-menu dropdown-menu-end navbar-dropdown-menu navbar-dropdown-menu-borderless"
                                            aria-labelledby="navbarNotificationsDropdownSettings">
                                            <span class="dropdown-header">Configuraciones</span>
                                            <a class="dropdown-item" href="javascript:;" id="mark-all-read">
                                                <i class="bi-check2-all dropdown-item-icon"></i> Marcar todas como
                                                leídas
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
                                        <a class="nav-link active" href="#notificationNavOne"
                                            id="notificationNavOne-tab" data-bs-toggle="tab"
                                            data-bs-target="#notificationNavOne" role="tab"
                                            aria-controls="notificationNavOne" aria-selected="true">
                                            Mensajes ({{ $latestComunicados->count() }})
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#notificationNavTwo" id="notificationNavTwo-tab"
                                            data-bs-toggle="tab" data-bs-target="#notificationNavTwo" role="tab"
                                            aria-controls="notificationNavTwo" aria-selected="false">
                                            Actualizaciones ({{ $latestActualizaciones->count() }})
                                        </a>
                                    </li>
                                </ul>

                                <div class="card-body-height">
                                    <div class="tab-content" id="notificationTabContent">

                                        <div class="tab-pane fade show active" id="notificationNavOne"
                                            role="tabpanel" aria-labelledby="notificationNavOne-tab">
                                            <ul class="list-group list-group-flush navbar-card-list-group">
                                                @forelse($latestComunicados as $rel)
                                                    <li class="list-group-item form-check-select notification-item"
                                                        data-id="{{ $rel->id }}">
                                                        <div class="row">
                                                            <div class="col-auto">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input mark-read-check"
                                                                            type="checkbox" value=""
                                                                            id="notificationCheck{{ $rel->id }}"
                                                                            data-id="{{ $rel->id }}"
                                                                            {{ !$rel->is_read ? 'checked' : '' }}>
                                                                        <label class="form-check-label"
                                                                            for="notificationCheck{{ $rel->id }}"></label>
                                                                        <span class="form-check-stretched-bg"></span>
                                                                    </div>
                                                                    <div class="avatar avatar-sm avatar-circle">
                                                                        @if ($rel->author && $rel->author->profile_photo_path)
                                                                            <img class="avatar-img"
                                                                                src="{{ asset('storage/' . $rel->author->profile_photo_path) }}"
                                                                                alt="Avatar">
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
                                                                            <div
                                                                                class="avatar-img avatar-soft-primary">
                                                                                <span
                                                                                    class="avatar-initials">{{ $authorInitials }}</span>
                                                                            </div>
                                                                        @else
                                                                            <div
                                                                                class="avatar-img avatar-soft-primary">
                                                                                <span class="avatar-initials">U</span>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col ms-n2">
                                                                <h5 class="mb-1">{{ $rel->title }}</h5>
                                                                <p class="text-body fs-5 text-truncate"
                                                                    style="max-width: 250px;">
                                                                    {{ strip_tags($rel->content) }}
                                                                </p>
                                                            </div>
                                                            <small
                                                                class="col-auto text-muted text-cap">{{ $rel->published_at->locale('es')->diffForHumans() }}</small>
                                                        </div>
                                                        <a class="stretched-link notification-link"
                                                            href="{{ route('releases.show', $rel->id) }}"
                                                            data-id="{{ $rel->id }}"></a>
                                                    </li>
                                                @empty
                                                    <li class="list-group-item">
                                                        <div class="text-center p-4">
                                                            <img class="mb-3"
                                                                src="{{ asset('svg/illustrations/oc-error.svg') }}"
                                                                alt="Sin mensajes" style="width: 7rem;"
                                                                data-hs-theme-appearance="default">
                                                            <p class="mb-0">No hay mensajes recientes</p>
                                                        </div>
                                                    </li>
                                                @endforelse
                                            </ul>
                                        </div>

                                        <div class="tab-pane fade" id="notificationNavTwo" role="tabpanel"
                                            aria-labelledby="notificationNavTwo-tab">
                                            <ul class="list-group list-group-flush navbar-card-list-group">
                                                @forelse($latestActualizaciones as $act)
                                                    <li class="list-group-item form-check-select notification-item"
                                                        data-id="{{ $act->id }}">
                                                        <div class="row">
                                                            <div class="col-auto">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input mark-read-check"
                                                                            type="checkbox" value=""
                                                                            id="notificationCheckAct{{ $act->id }}"
                                                                            data-id="{{ $act->id }}"
                                                                            {{ !$act->is_read ? 'checked' : '' }}>
                                                                        <label class="form-check-label"
                                                                            for="notificationCheckAct{{ $act->id }}"></label>
                                                                        <span class="form-check-stretched-bg"></span>
                                                                    </div>
                                                                    <div class="avatar avatar-sm avatar-circle">
                                                                        @if ($act->author && $act->author->profile_photo_path)
                                                                            <img class="avatar-img"
                                                                                src="{{ asset('storage/' . $act->author->profile_photo_path) }}"
                                                                                alt="Avatar">
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
                                                                            <div
                                                                                class="avatar-img avatar-soft-primary">
                                                                                <span
                                                                                    class="avatar-initials">{{ $authorInitials }}</span>
                                                                            </div>
                                                                        @else
                                                                            <div
                                                                                class="avatar-img avatar-soft-primary">
                                                                                <span class="avatar-initials">U</span>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col ms-n2">
                                                                <h5 class="mb-1">{{ $act->title }}</h5>
                                                                <p class="text-body fs-5 text-truncate"
                                                                    style="max-width: 250px;">
                                                                    {{ strip_tags($act->content) }}
                                                                </p>
                                                            </div>
                                                            <small
                                                                class="col-auto text-muted text-cap">{{ $act->published_at->locale('es')->diffForHumans() }}</small>
                                                        </div>
                                                        <a class="stretched-link notification-link"
                                                            href="{{ route('releases.show', $act->id) }}"
                                                            data-id="{{ $act->id }}"></a>
                                                    </li>
                                                @empty
                                                    <li class="list-group-item">
                                                        <div class="text-center p-4">
                                                            <img class="mb-3"
                                                                src="{{ asset('svg/illustrations/oc-error.svg') }}"
                                                                alt="Sin actualizaciones" style="width: 7rem;"
                                                                data-hs-theme-appearance="default">
                                                            <p class="mb-0">No hay actualizaciones recientes</p>
                                                        </div>
                                                    </li>
                                                @endforelse
                                            </ul>
                                        </div>

                                    </div>
                                </div>

                                <a class="card-footer text-center" href="#">
                                    Ver todas las notificaciones <i class="bi-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>










                <li class="nav-item d-none d-sm-inline-block">
                    <div class="dropdown">
                        <button type="button" class="btn btn-icon btn-ghost-secondary rounded-circle"
                            id="navbarAppsDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                            data-bs-dropdown-animation>
                            <i class="bi-app-indicator"></i>
                        </button>

                        <div class="dropdown-menu dropdown-menu-end dropdown-card navbar-dropdown-menu navbar-dropdown-menu-borderless"
                            aria-labelledby="navbarAppsDropdown" style="width: 25rem;">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Aplicaciones web &amp; servicios</h4>
                                </div>
                                <div class="card-body card-body-height">
                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img class="avatar avatar-xs avatar-4x3"
                                                    src="{{ asset('svg/brands/atlassian-icon.svg') }}"
                                                    alt="Image Description">
                                            </div>
                                            <div class="flex-grow-1 text-truncate ms-3">
                                                <h5 class="mb-0">Atlassian</h5>
                                                <p class="card-text text-body">Seguridad y control en la nube</p>
                                            </div>
                                        </div>
                                    </a>

                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img class="avatar avatar-xs avatar-4x3"
                                                    src="{{ asset('svg/brands/slack-icon.svg') }}"
                                                    alt="Image Description">
                                            </div>
                                            <div class="flex-grow-1 text-truncate ms-3">
                                                <h5 class="mb-0">Slack <span
                                                        class="badge bg-primary rounded-pill text-uppercase ms-1">Try</span>
                                                </h5>
                                                <p class="card-text text-body">Software de colaboración de email</p>
                                            </div>
                                        </div>
                                    </a>

                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img class="avatar avatar-xs avatar-4x3"
                                                    src="{{ asset('svg/brands/google-webdev-icon.svg') }}"
                                                    alt="Image Description">
                                            </div>
                                            <div class="flex-grow-1 text-truncate ms-3">
                                                <h5 class="mb-0">Google webdev</h5>
                                                <p class="card-text text-body">Trabajo involucrado en el desarrollo de
                                                    un sitio web
                                                </p>
                                            </div>
                                        </div>
                                    </a>

                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img class="avatar avatar-xs avatar-4x3"
                                                    src="{{ asset('svg/brands/frontapp-icon.svg') }}"
                                                    alt="Image Description">
                                            </div>
                                            <div class="flex-grow-1 text-truncate ms-3">
                                                <h5 class="mb-0">Frontapp</h5>
                                                <p class="card-text text-body">El buzón para equipos</p>
                                            </div>
                                        </div>
                                    </a>

                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <img class="avatar avatar-xs avatar-4x3"
                                                    src="{{ asset('svg/illustrations/review-rating-shield.svg') }}"
                                                    alt="Image Description">
                                            </div>
                                            <div class="flex-grow-1 text-truncate ms-3">
                                                <h5 class="mb-0">HS Support</h5>
                                                <p class="card-text text-body">Servicio al cliente y soporte</p>
                                            </div>
                                        </div>
                                    </a>

                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avatar avatar-sm avatar-soft-dark">
                                                    <span class="avatar-initials"><i class="bi-grid"></i></span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 text-truncate ms-3">
                                                <h5 class="mb-0">Más productos Front</h5>
                                                <p class="card-text text-body">Revisa más productos HS</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <a class="card-footer text-center" href="#">
                                    Ver todas las aplicaciones <i class="bi-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item">
                    <button class="btn btn-ghost-secondary btn-icon rounded-circle" type="button"
                        data-bs-toggle="offcanvas" data-bs-target="#offcanvasChatISAAC"
                        aria-controls="offcanvasChatISAAC">

                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-openai" viewBox="0 0 16 16">
                            <path
                                d="M14.949 6.547a3.94 3.94 0 0 0-.348-3.273 4.11 4.11 0 0 0-4.4-1.934A4.1 4.1 0 0 0 8.423.2 4.15 4.15 0 0 0 6.305.086a4.1 4.1 0 0 0-1.891.948 4.04 4.04 0 0 0-1.158 1.753 4.1 4.1 0 0 0-1.563.679A4 4 0 0 0 .554 4.72a3.99 3.99 0 0 0 .502 4.731 3.94 3.94 0 0 0 .346 3.274 4.11 4.11 0 0 0 4.402 1.933c.382.425.852.764 1.377.995.526.231 1.095.35 1.67.346 1.78.002 3.358-1.132 3.901-2.804a4.1 4.1 0 0 0 1.563-.68 4 4 0 0 0 1.14-1.253 3.99 3.99 0 0 0-.506-4.716m-6.097 8.406a3.05 3.05 0 0 1-1.945-.694l.096-.054 3.23-1.838a.53.53 0 0 0 .265-.455v-4.49l1.366.778q.02.011.025.035v3.722c-.003 1.653-1.361 2.992-3.037 2.996m-6.53-2.75a2.95 2.95 0 0 1-.36-2.01l.095.057L5.29 12.09a.53.53 0 0 0 .527 0l3.949-2.246v1.555a.05.05 0 0 1-.022.041L6.473 13.3c-1.454.826-3.311.335-4.15-1.098m-.85-6.94A3.02 3.02 0 0 1 3.07 3.949v3.785a.51.51 0 0 0 .262.451l3.93 2.237-1.366.779a.05.05 0 0 1-.048 0L2.585 9.342a2.98 2.98 0 0 1-1.113-4.094zm11.216 2.571L8.747 5.576l1.362-.776a.05.05 0 0 1 .048 0l3.265 1.86a3 3 0 0 1 1.173 1.207 2.96 2.96 0 0 1-.27 3.2 3.05 3.05 0 0 1-1.36.997V8.279a.52.52 0 0 0-.276-.445m1.36-2.015-.097-.057-3.226-1.855a.53.53 0 0 0-.53 0L6.249 6.153V4.598a.04.04 0 0 1 .019-.04L9.533 2.7a3.07 3.07 0 0 1 3.257.139c.474.325.843.778 1.066 1.303.223.526.289 1.103.191 1.664zM5.503 8.575 4.139 7.8a.05.05 0 0 1-.026-.037V4.049c0-.57.166-1.127.476-1.607s.752-.864 1.275-1.105a3.08 3.08 0 0 1 3.234.41l-.096.054-3.23 1.838a.53.53 0 0 0-.265.455zm.742-1.577 1.758-1 1.762 1v2l-1.755 1-1.762-1z" />
                        </svg>

                    </button>
                </li>






                <li class="nav-item">
                    @php
                        $user = Auth::user();

                        // 1. Datos del usuario
                        $primerNombre = $user->first_name ?? '';
                        $primerApellido = $user->first_last_name ?? '';
                        $nombreMostrar = trim($primerNombre . ' ' . $primerApellido) ?: $user->email ?? 'Usuario';

                        // 2. Cálculo de iniciales
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

                        // 3. Gestión de Estados y Colores (Mapa Hexadecimal)
                        $estadoActual = $user->estado ?? 'disponible';
                        $hexMap = [
                            'disponible' => '#01C3A2', // Verde
                            'ocupado' => '#E64A76', // Rojo
                            'ausente' => '#F5CA99', // Amarillo
                            'privado' => '#6A7178', // Gris
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

                    <div class="dropdown">
                        <a class="navbar-dropdown-account-wrapper" href="javascript:;" id="accountNavbarDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside"
                            data-bs-dropdown-animation>
                            <div class="avatar avatar-sm avatar-circle">
                                @if ($user && $user->profile_photo_path)
                                    <img class="avatar-img" id="navbar-avatar-img"
                                        src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Avatar"
                                        onerror="this.onerror=null; retryNavbarImage(this);">
                                @else
                                    <div class="avatar-img avatar-soft-primary" id="navbar-avatar-initials">
                                        <span class="avatar-initials">{{ $iniciales }}</span>
                                    </div>
                                @endif
                                <span class="avatar-status avatar-sm-status avatar-status-{{ $colorActual }}"
                                    id="avatar-status-indicator"></span>
                            </div>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end navbar-dropdown-menu navbar-dropdown-menu-borderless navbar-dropdown-account bg-white"
                            aria-labelledby="accountNavbarDropdown" style="width: 16rem;">

                            <div class="dropdown-item-text">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm avatar-circle">
                                        @if ($user && $user->profile_photo_path)
                                            <img class="avatar-img" id="dropdown-avatar-img" style="max-width: none;"
                                                src="{{ asset('storage/' . $user->profile_photo_path) }}"
                                                alt="Avatar">
                                        @else
                                            <div class="avatar-img avatar-soft-primary" id="dropdown-avatar-initials">
                                                <span class="avatar-initials">{{ $iniciales }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-0">{{ $nombreMostrar }}</h5>
                                        <p class="card-text text-body" style="font-size: 0.85rem;">
                                            {{ $user->email ?? '' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="dropdown-divider"></div>

                            <div class="dropdown">
                                <a class="navbar-dropdown-submenu-item dropdown-item dropdown-toggle"
                                    href="javascript:;" id="navSubmenuPagesAccountDropdown1"
                                    data-bs-toggle="dropdown">
                                    Estado
                                    <span class="legend-indicator ms-2" id="current-status-dot"
                                        style="background-color: {{ $hexActual }} !important; border-color: {{ $hexActual }} !important;">
                                    </span>
                                    <span class="ms-1">{{ $labelActual }}</span>
                                </a>

                                <div
                                    class="dropdown-menu dropdown-menu-end navbar-dropdown-menu navbar-dropdown-menu-borderless navbar-dropdown-sub-menu">
                                    @foreach (['disponible', 'ocupado', 'ausente', 'privado'] as $est)
                                        @php $thisHex = $hexMap[$est]; @endphp
                                        <a class="dropdown-item estado-option {{ $estadoActual === $est ? 'active' : '' }}"
                                            href="javascript:;" data-estado="{{ $est }}">
                                            <span class="legend-indicator me-1"
                                                style="background-color: {{ $thisHex }} !important; border-color: {{ $thisHex }} !important;"></span>
                                            {{ $estadoConfig[$est]['label'] }}
                                            @if ($estadoActual === $est)
                                                <i class="bi-check-lg float-end"></i>
                                            @endif
                                        </a>
                                    @endforeach

                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="javascript:;" id="restablecer-estado">Restablecer
                                        estado</a>
                                </div>
                            </div>

                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="{{ route('profile.index') }}">Mi Perfil</a>
                            <div class="dropdown-divider"></div>

                            <form method="POST" action="{{ route('logout') }}"
                                onsubmit="if(window.showManualLoader) window.showManualLoader('Cerrando sesión...');">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"
                                    style="border: none; background: none; width: 100%; text-align: left; padding: 0.5rem 1rem;">
                                    <i class="bi-box-arrow-right me-2"></i> Cerrar sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>

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

                // 1. Forzar color en el punto del Submenú (JavaScript)
                $legend.css({
                    'background-color': cfg.hex,
                    'border-color': cfg.hex
                }).attr('style', function(i, s) {
                    return 'background-color: ' + cfg.hex + ' !important; border-color: ' + cfg.hex +
                        ' !important;';
                });

                $toggle.find('span:last').text(cfg.label);

                // 2. Actualiza el puntito del avatar principal (Usa clase nativa)
                $avatar.removeClass(AVATAR_CLASSES).addClass(`avatar-status-${cfg.color}`);

                // 3. Manejo de Check (Palomita)
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

            // --- Lógica de Notificaciones ---

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
                // Laravel compila un ID válido, y luego JS lo reemplaza por el ID real
                let url = "{{ route('releases.mark-as-read', 99999) }}".replace('99999', id);
                const isChecked = $(this).is(':checked');

                if (!isChecked) {
                    // Marcar como leído
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
