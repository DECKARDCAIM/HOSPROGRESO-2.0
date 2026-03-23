<header id="header"
    class="navbar navbar-expand-lg navbar-fixed navbar-height navbar-container navbar-bordered bg-white">
    <div class="navbar-nav-wrap">
        <a class="navbar-brand" href="/" aria-label="Front">
            <img class="navbar-brand-logo" src="{{ asset('img/logotipo.svg') }}" alt="Logo"
                data-hs-theme-appearance="default">
            <img class="navbar-brand-logo" src="{{ asset('img/logotipo-white.svg') }}" alt="Logo"
                data-hs-theme-appearance="dark">
            <img class="navbar-brand-logo-mini" src="{{ asset('img/logotipo.svg') }}" alt="Logo"
                data-hs-theme-appearance="default">
            <img class="navbar-brand-logo-mini" src="{{ asset('img/logotipo-white.svg') }}" alt="Logo"
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
                            <span class="btn-status btn-sm-status btn-status-danger"></span>
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
                                            <a class="dropdown-item" href="#">
                                                <i class="bi-archive dropdown-item-icon"></i> Archivar todas
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                <i class="bi-check2-all dropdown-item-icon"></i> Marcar todas como
                                                leídas
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                <i class="bi-toggle-off dropdown-item-icon"></i> Deshabilitar
                                                notificaciones
                                            </a>
                                            <a class="dropdown-item" href="#">
                                                <i class="bi-gift dropdown-item-icon"></i> Que hay de nuevo?
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
                                            aria-controls="notificationNavOne" aria-selected="true">Mensajes (3)</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#notificationNavTwo" id="notificationNavTwo-tab"
                                            data-bs-toggle="tab" data-bs-target="#notificationNavTwo" role="tab"
                                            aria-controls="notificationNavTwo" aria-selected="false">Archivados</a>
                                    </li>
                                </ul>

                                <div class="card-body-height">
                                    <div class="tab-content" id="notificationTabContent">
                                        <div class="tab-pane fade show active" id="notificationNavOne" role="tabpanel"
                                            aria-labelledby="notificationNavOne-tab">
                                            <ul class="list-group list-group-flush navbar-card-list-group">
                                                <li class="list-group-item form-check-select">
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <div class="d-flex align-items-center">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="" id="notificationCheck1" checked>
                                                                    <label class="form-check-label"
                                                                        for="notificationCheck1"></label>
                                                                    <span class="form-check-stretched-bg"></span>
                                                                </div>
                                                                <img class="avatar avatar-sm avatar-circle"
                                                                    src="{{ asset('img/160x160/img3.jpg') }}"
                                                                    alt="Image Description">
                                                            </div>
                                                        </div>

                                                        <div class="col ms-n2">
                                                            <h5 class="mb-1">Brian Warner</h5>
                                                            <p class="text-body fs-5">changed an issue from "In
                                                                Progress" to <span
                                                                    class="badge bg-success">Review</span></p>
                                                        </div>

                                                        <small class="col-auto text-muted text-cap">2hr</small>
                                                    </div>

                                                    <a class="stretched-link" href="#"></a>
                                                </li>
                                                <li class="list-group-item form-check-select">
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <div class="d-flex align-items-center">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="" id="notificationCheck2" checked>
                                                                    <label class="form-check-label"
                                                                        for="notificationCheck2"></label>
                                                                    <span class="form-check-stretched-bg"></span>
                                                                </div>
                                                                <div
                                                                    class="avatar avatar-sm avatar-soft-dark avatar-circle">
                                                                    <span class="avatar-initials">K</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col ms-n2">
                                                            <h5 class="mb-1">Klara Hampton</h5>
                                                            <p class="text-body fs-5">mentioned you in a comment</p>
                                                            <blockquote class="blockquote blockquote-sm">
                                                                Nice work, love! You really nailed it. Keep it up!
                                                            </blockquote>
                                                        </div>

                                                        <small class="col-auto text-muted text-cap">10hr</small>
                                                    </div>

                                                    <a class="stretched-link" href="#"></a>
                                                </li>
                                                <li class="list-group-item form-check-select">
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <div class="d-flex align-items-center">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="" id="notificationCheck3" checked>
                                                                    <label class="form-check-label"
                                                                        for="notificationCheck3"></label>
                                                                    <span class="form-check-stretched-bg"></span>
                                                                </div>
                                                                <div class="avatar avatar-sm avatar-circle">
                                                                    <img class="avatar-img"
                                                                        src="{{ asset('img/160x160/img10.jpg') }}"
                                                                        alt="Image Description">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col ms-n2">
                                                            <h5 class="mb-1">Ruby Walter</h5>
                                                            <p class="text-body fs-5">joined the Slack group HS Team
                                                            </p>
                                                        </div>

                                                        <small class="col-auto text-muted text-cap">3dy</small>
                                                    </div>

                                                    <a class="stretched-link" href="#"></a>
                                                </li>
                                                <li class="list-group-item form-check-select">
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <div class="d-flex align-items-center">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="" id="notificationCheck4">
                                                                    <label class="form-check-label"
                                                                        for="notificationCheck4"></label>
                                                                    <span class="form-check-stretched-bg"></span>
                                                                </div>
                                                                <div class="avatar avatar-sm avatar-circle">
                                                                    <img class="avatar-img"
                                                                        src="{{ asset('svg/brands/google-icon.svg') }}"
                                                                        alt="Image Description">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col ms-n2">
                                                            <h5 class="mb-1">from Google</h5>
                                                            <p class="text-body fs-5">Start using forms to capture the
                                                                information of prospects visiting your Google website
                                                            </p>
                                                        </div>

                                                        <small class="col-auto text-muted text-cap">17dy</small>
                                                    </div>

                                                    <a class="stretched-link" href="#"></a>
                                                </li>
                                                <li class="list-group-item form-check-select">
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <div class="d-flex align-items-center">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="" id="notificationCheck5">
                                                                    <label class="form-check-label"
                                                                        for="notificationCheck5"></label>
                                                                    <span class="form-check-stretched-bg"></span>
                                                                </div>
                                                                <div class="avatar avatar-sm avatar-circle">
                                                                    <img class="avatar-img"
                                                                        src="{{ asset('img/160x160/img7.jpg') }}"
                                                                        alt="Image Description">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col ms-n2">
                                                            <h5 class="mb-1">Sara Villar</h5>
                                                            <p class="text-body fs-5">completed <i
                                                                    class="bi-journal-bookmark-fill text-primary"></i>
                                                                FD-7 task</p>
                                                        </div>

                                                        <small class="col-auto text-muted text-cap">2mn</small>
                                                    </div>

                                                    <a class="stretched-link" href="#"></a>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="tab-pane fade" id="notificationNavTwo" role="tabpanel"
                                            aria-labelledby="notificationNavTwo-tab">
                                            <ul class="list-group list-group-flush navbar-card-list-group">
                                                <li class="list-group-item form-check-select">
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <div class="d-flex align-items-center">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="" id="notificationCheck6">
                                                                    <label class="form-check-label"
                                                                        for="notificationCheck6"></label>
                                                                    <span class="form-check-stretched-bg"></span>
                                                                </div>
                                                                <div
                                                                    class="avatar avatar-sm avatar-soft-dark avatar-circle">
                                                                    <span class="avatar-initials">A</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col ms-n2">
                                                            <h5 class="mb-1">Anne Richard</h5>
                                                            <p class="text-body fs-5">accepted your invitation to join
                                                                Notion</p>
                                                        </div>

                                                        <small class="col-auto text-muted text-cap">1dy</small>
                                                    </div>

                                                    <a class="stretched-link" href="#"></a>
                                                </li>
                                                <li class="list-group-item form-check-select">
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <div class="d-flex align-items-center">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="" id="notificationCheck7">
                                                                    <label class="form-check-label"
                                                                        for="notificationCheck7"></label>
                                                                    <span class="form-check-stretched-bg"></span>
                                                                </div>
                                                                <div class="avatar avatar-sm avatar-circle">
                                                                    <img class="avatar-img"
                                                                        src="{{ asset('img/160x160/img5.jpg') }}"
                                                                        alt="Image Description">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col ms-n2">
                                                            <h5 class="mb-1">Finch Hoot</h5>
                                                            <p class="text-body fs-5">left Slack group HS projects</p>
                                                        </div>

                                                        <small class="col-auto text-muted text-cap">1dy</small>
                                                    </div>

                                                    <a class="stretched-link" href="#"></a>
                                                </li>
                                                <li class="list-group-item form-check-select">
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <div class="d-flex align-items-center">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="" id="notificationCheck8">
                                                                    <label class="form-check-label"
                                                                        for="notificationCheck8"></label>
                                                                    <span class="form-check-stretched-bg"></span>
                                                                </div>
                                                                <div class="avatar avatar-sm avatar-dark avatar-circle">
                                                                    <span class="avatar-initials">HS</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col ms-n2">
                                                            <h5 class="mb-1">Htmlstream</h5>
                                                            <p class="text-body fs-5">you earned a "Top endorsed" <i
                                                                    class="bi-patch-check-fill text-primary"></i> badge
                                                            </p>
                                                        </div>

                                                        <small class="col-auto text-muted text-cap">6dy</small>
                                                    </div>

                                                    <a class="stretched-link" href="#"></a>
                                                </li>
                                                <li class="list-group-item form-check-select">
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <div class="d-flex align-items-center">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="" id="notificationCheck9">
                                                                    <label class="form-check-label"
                                                                        for="notificationCheck9"></label>
                                                                    <span class="form-check-stretched-bg"></span>
                                                                </div>
                                                                <div class="avatar avatar-sm avatar-circle">
                                                                    <img class="avatar-img"
                                                                        src="{{ asset('img/160x160/img8.jpg') }}"
                                                                        alt="Image Description">
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col ms-n2">
                                                            <h5 class="mb-1">Linda Bates</h5>
                                                            <p class="text-body fs-5">Accepted your connection</p>
                                                        </div>

                                                        <small class="col-auto text-muted text-cap">17dy</small>
                                                    </div>

                                                    <a class="stretched-link" href="#"></a>
                                                </li>
                                                <li class="list-group-item form-check-select">
                                                    <div class="row">
                                                        <div class="col-auto">
                                                            <div class="d-flex align-items-center">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="" id="notificationCheck10">
                                                                    <label class="form-check-label"
                                                                        for="notificationCheck10"></label>
                                                                    <span class="form-check-stretched-bg"></span>
                                                                </div>
                                                                <div
                                                                    class="avatar avatar-sm avatar-soft-dark avatar-circle">
                                                                    <span class="avatar-initials">L</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col ms-n2">
                                                            <h5 class="mb-1">Lewis Clarke</h5>
                                                            <p class="text-body fs-5">completed <i
                                                                    class="bi-journal-bookmark-fill text-primary"></i>
                                                                FD-134 task</p>
                                                        </div>

                                                        <small class="col-auto text-muted text-cap">2mts</small>
                                                    </div>

                                                    <a class="stretched-link" href="#"></a>
                                                </li>
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

                <li class="nav-item d-none d-sm-inline-block">
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
                    <div class="dropdown">
                        <a class="navbar-dropdown-account-wrapper" href="javascript:;" id="accountNavbarDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside"
                            data-bs-dropdown-animation>
                            <div class="avatar avatar-sm avatar-circle">
                                @php
                                $user = Auth::user();
                                $estadoActual = $user->estado ?? 'disponible';
                                $estadoAvatarColors = [
                                'disponible' => 'success',
                                'ocupado' => 'danger',
                                'ausente' => 'warning',
                                'privado' => 'secondary',
                                'desconectado' => 'secondary',
                                ];
                                $primerNombre = $user->first_name ?? '';
                                $primerApellido = $user->first_last_name ?? '';
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

                                @if ($user && $user->profile_photo_path)
                                <img class="avatar-img" id="navbar-avatar-img"
                                    src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Image Description"
                                    onerror="this.onerror=null; retryNavbarImage(this);">
                                @else
                                <div class="avatar-img avatar-soft-primary" id="navbar-avatar-initials">
                                    <span class="avatar-initials">{{ $iniciales }}</span>
                                </div>
                                @endif
                                <span
                                    class="avatar-status avatar-sm-status avatar-status-{{ $estadoAvatarColors[$estadoActual] }}"
                                    id="avatar-status-indicator"></span>
                            </div>
                        </a>

                        <div class="dropdown-menu dropdown-menu-end navbar-dropdown-menu navbar-dropdown-menu-borderless navbar-dropdown-account bg-white"
                            aria-labelledby="accountNavbarDropdown" style="width: 16rem;">
                            <div class="dropdown-item-text">
                                <div class="d-flex align-items-center">
                                    @php
                                    $user = Auth::user();
                                    $primerNombre = $user->first_name ?? '';
                                    $primerApellido = $user->first_last_name ?? '';
                                    $nombreMostrar =
                                    trim($primerNombre . ' ' . $primerApellido) ?: $user->email ?? 'Usuario';
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
                                    <div class="avatar avatar-sm avatar-circle">

                                        @if ($user && $user->profile_photo_path)
                                        <img class="avatar-img" id="dropdown-avatar-img" style="max-width: none;"
                                            src="{{ asset('storage/' . $user->profile_photo_path) }}"
                                            alt="Image Description"
                                            onerror="this.onerror=null; retryNavbarImage(this);">
                                        @else
                                        <div class="avatar-img avatar-soft-primary" id="dropdown-avatar-initials">
                                            <span class="avatar-initials">{{ $iniciales }}</span>
                                        </div>
                                        @endif

                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="mb-0">{{ $nombreMostrar }}</h5>
                                        <p class="card-text text-body">{{ $user->email ?? '' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="dropdown-divider"></div>

                            <div class="dropdown">
                                <a class="navbar-dropdown-submenu-item dropdown-item dropdown-toggle"
                                    href="javascript:;" id="navSubmenuPagesAccountDropdown1" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    Estado
                                    @php
                                    $estadoActual = Auth::user()->estado ?? 'disponible';
                                    $estadoLabels = [
                                    'disponible' => 'Disponible',
                                    'ocupado' => 'Ocupado',
                                    'ausente' => 'Ausente',
                                    'privado' => 'Privado',
                                    'desconectado' => 'Desconectado',
                                    ];
                                    $estadoColors = [
                                    'disponible' => 'success',
                                    'ocupado' => 'danger',
                                    'ausente' => 'warning',
                                    'privado' => 'secondary',
                                    'desconectado' => 'secondary',
                                    ];
                                    @endphp
                                    <span class="legend-indicator bg-{{ $estadoColors[$estadoActual] }} ms-2"></span>
                                    <span class="ms-1">{{ $estadoLabels[$estadoActual] }}</span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end navbar-dropdown-menu navbar-dropdown-menu-borderless navbar-dropdown-sub-menu"
                                    aria-labelledby="navSubmenuPagesAccountDropdown1">
                                    <a class="dropdown-item estado-option {{ $estadoActual === 'disponible' ? 'active' : '' }}"
                                        href="javascript:;" data-estado="disponible">
                                        <span class="legend-indicator bg-success me-1"></span> Disponible
                                        @if ($estadoActual === 'disponible')
                                        <i class="bi-check-lg float-end"></i>
                                        @endif
                                    </a>
                                    <a class="dropdown-item estado-option {{ $estadoActual === 'ocupado' ? 'active' : '' }}"
                                        href="javascript:;" data-estado="ocupado">
                                        <span class="legend-indicator bg-danger me-1"></span> Ocupado
                                        @if ($estadoActual === 'ocupado')
                                        <i class="bi-check-lg float-end"></i>
                                        @endif
                                    </a>
                                    <a class="dropdown-item estado-option {{ $estadoActual === 'ausente' ? 'active' : '' }}"
                                        href="javascript:;" data-estado="ausente">
                                        <span class="legend-indicator bg-warning me-1"></span> Ausente
                                        @if ($estadoActual === 'ausente')
                                        <i class="bi-check-lg float-end"></i>
                                        @endif
                                    </a>
                                    <a class="dropdown-item estado-option {{ $estadoActual === 'privado' ? 'active' : '' }}"
                                        href="javascript:;" data-estado="privado">
                                        <span class="legend-indicator bg-secondary me-1"></span> Privado
                                        @if ($estadoActual === 'privado')
                                        <i class="bi-check-lg float-end"></i>
                                        @endif
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="javascript:;" id="restablecer-estado">
                                        Restablecer estado
                                    </a>
                                </div>
                            </div>

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item" href="{{ route('profile.index') }}">Mi Perfil</a>

                            <div class="dropdown-divider"></div>

                            <form method="POST" action="{{ route('logout') }}"
                                onsubmit="this.querySelector('button').disabled=true;">
                                @csrf
                                <button type="submit" class="dropdown-item"
                                    style="border: none; background: none; width: 100%; text-align: left; padding: 0.5rem 1rem; cursor: pointer;">
                                    Cerrar sesión
                                </button>
                            </form>

                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>