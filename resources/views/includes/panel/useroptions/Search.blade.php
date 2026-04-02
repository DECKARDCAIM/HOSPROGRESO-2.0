<div class="dropdown ms-2">
    <div class="d-none d-lg-block">
        <div class="input-group input-group-merge input-group-borderless input-group-hover-light navbar-input-group">
            <div class="input-group-prepend input-group-text">
                <i class="bi-search"></i>
            </div>
            <input type="search" class="js-form-search form-control" placeholder="Buscar" aria-label="Buscar" data-hs-form-search-options='{"clearIcon": "#clearSearchResultsIcon", "dropMenuElement": "#searchDropdownMenu", "dropMenuOffset": 20, "toggleIconOnFocus": true, "activeClass": "focus" }'>
            <a class="input-group-append input-group-text" href="javascript:;">
                <i id="clearSearchResultsIcon" class="bi-x-lg" style="display: none;"></i>
            </a>
        </div>
    </div>
    <button class="js-form-search js-form-search-mobile-toggle btn btn-ghost-secondary btn-icon rounded-circle d-lg-none" type="button" data-hs-form-search-options='{"clearIcon": "#clearSearchResultsIcon", "dropMenuElement": "#searchDropdownMenu", "dropMenuOffset": 20, "toggleIconOnFocus": true, "activeClass": "focus"}'>
        <i class="bi-search"></i>
    </button>
    <div id="searchDropdownMenu" class="hs-form-search-menu-content dropdown-menu dropdown-menu-form-search navbar-dropdown-menu-borderless bg-white">
        <div class="card">
            <div class="card-body-height">
                <div class="d-lg-none">
                    <div class="input-group input-group-merge navbar-input-group mb-5">
                        <div class="input-group-prepend input-group-text">
                            <i class="bi-search"></i>
                        </div>
                        <input type="search" class="form-control" placeholder="Buscar" aria-label="Buscar">
                        <a class="input-group-append input-group-text" href="javascript:;"> <i class="bi-x-lg"></i></a>
                    </div>
                </div>
                <span class="dropdown-header">Busquedas recientes</span>
                <div class="dropdown-item bg-transparent text-wrap">
                    <a class="btn btn-soft-dark btn-xs rounded-pill" href="/">Busqueda 1 <i class="bi-search ms-1"></i></a>
                    <a class="btn btn-soft-dark btn-xs rounded-pill" href="/">Panel de notificaciones <i class="bi-search ms-1"></i></a>
                    <a class="btn btn-soft-dark btn-xs rounded-pill" href="/">Busqueda 3 <i class="bi-search ms-1"></i></a>
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
                            <img class="avatar avatar-xs avatar-circle" src="{{ asset('img/160x160/img10.jpg') }}" alt="Image Description">
                        </div>
                        <div class="flex-grow-1 text-truncate ms-2">
                            <span>Amanda Harvey <i class="tio-verified text-primary" data-toggle="tooltip" data-placement="top" title="Top endorsed"></i></span>
                        </div>
                    </div>
                </a>
                <a class="dropdown-item" href="/">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <img class="avatar avatar-xs avatar-circle" src="{{ asset('img/160x160/img3.jpg') }}" alt="Image Description">
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
            <a class="card-footer text-center" href="/">Ver todos los resultados <i class="bi-chevron-right small"></i></a>
        </div>
    </div>
</div>