 <aside
     class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered bg-white  ">


     <div class="navbar-vertical-container">
         <div class="navbar-vertical-footer-offset">

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
             <button type="button" class="js-navbar-vertical-aside-toggle-invoker navbar-aside-toggler">
                 <i class="bi-arrow-bar-left navbar-toggler-short-align"
                     data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                     data-bs-toggle="tooltip" data-bs-placement="right" title="Collapse"></i>
                 <i class="bi-arrow-bar-right navbar-toggler-full-align"
                     data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
                     data-bs-toggle="tooltip" data-bs-placement="right" title="Expand"></i>
             </button>


             <div class="navbar-vertical-content">
                 <div id="navbarVerticalMenu" class="nav nav-pills nav-vertical card-navbar-nav">
                     <span class="dropdown-header mt-4">Módulos</span>
                     <small class="bi-three-dots nav-subtitle-replacer"></small>

























                     <!-- Módulo: Métricas -->
                     <div class="nav-item">
                         <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuMetricas" role="button"
                             data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuMetricas"
                             aria-expanded="false" aria-controls="navbarVerticalMenuMetricas">
                             <i class="bi-graph-up nav-icon"></i>
                             <span class="nav-link-title">Métricas</span>
                         </a>

                         <div id="navbarVerticalMenuMetricas" class="nav-collapse collapse"
                             data-bs-parent="#navbarVerticalMenu">
                             <a class="nav-link" href="/metricas">Dashboard</a>
                             <a class="nav-link" href="/metricas/reportes">Reportes</a>
                             <a class="nav-link" href="/metricas/estadisticas">Estadísticas</a>
                         </div>
                     </div>

                     <!-- Módulo: Pacientes -->
                     <div class="nav-item">
                         <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuPacientes" role="button"
                             data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuPacientes"
                             aria-expanded="false" aria-controls="navbarVerticalMenuPacientes">
                             <i class="bi-people nav-icon"></i>
                             <span class="nav-link-title">Pacientes</span>
                         </a>

                         <div id="navbarVerticalMenuPacientes" class="nav-collapse collapse"
                             data-bs-parent="#navbarVerticalMenu">
                             <a class="nav-link" href="{{ route('patients.create') }}">Nuevo Paciente</a>
                             <a class="nav-link" href="{{ route('patients.index') }}">Listado</a>
                         </div>
                     </div>

                     <!-- Módulo: Expediente Clínicos -->
                     <div class="nav-item">
                         <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuExpedientes" role="button"
                             data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuExpedientes"
                             aria-expanded="false" aria-controls="navbarVerticalMenuExpedientes">
                             <i class="bi-file-earmark-medical nav-icon"></i>
                             <span class="nav-link-title">Expediente Clínicos</span>
                         </a>

                         <div id="navbarVerticalMenuExpedientes" class="nav-collapse collapse"
                             data-bs-parent="#navbarVerticalMenu">
                             <a class="nav-link" href="/expedientes">Consultar</a>
                             <a class="nav-link" href="/expedientes/crear">Nuevo Expediente</a>
                             <a class="nav-link" href="/expedientes/historial">Historial</a>
                         </div>
                     </div>

                     <!-- Módulo: Citas Médicas -->
                     <div class="nav-item">
                         <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuCitas" role="button"
                             data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuCitas" aria-expanded="false"
                             aria-controls="navbarVerticalMenuCitas">
                             <i class="bi-calendar-check nav-icon"></i>
                             <span class="nav-link-title">Citas Médicas</span>
                         </a>

                         <div id="navbarVerticalMenuCitas" class="nav-collapse collapse"
                             data-bs-parent="#navbarVerticalMenu">
                             <a class="nav-link" href="/citas">Calendario</a>
                             <a class="nav-link" href="/citas/crear">Nueva Cita</a>
                             <a class="nav-link" href="/citas/pendientes">Pendientes</a>
                             <a class="nav-link" href="/citas/historial">Historial</a>
                         </div>
                     </div>

                     <!-- Módulo: Hospitalización -->
                     <div class="nav-item">
                         <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuHospitalizacion" role="button"
                             data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuHospitalizacion"
                             aria-expanded="false" aria-controls="navbarVerticalMenuHospitalizacion">
                             <i class="bi-hospital nav-icon"></i>
                             <span class="nav-link-title">Hospitalización</span>
                         </a>

                         <div id="navbarVerticalMenuHospitalizacion" class="nav-collapse collapse"
                             data-bs-parent="#navbarVerticalMenu">
                             <a class="nav-link" href="/hospitalizacion">Ingresos</a>
                             <a class="nav-link" href="/hospitalizacion/crear">Nuevo Ingreso</a>
                             <a class="nav-link" href="/hospitalizacion/camas">Gestión de Camas</a>
                             <a class="nav-link" href="/hospitalizacion/altas">Altas</a>
                         </div>
                     </div>

                     <!-- Módulo: Administración -->
                     <div class="nav-item">
                         <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuAdministracion" role="button"
                             data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuAdministracion"
                             aria-expanded="false" aria-controls="navbarVerticalMenuAdministracion">
                             <i class="bi-gear nav-icon"></i>
                             <span class="nav-link-title">Administración</span>
                         </a>

                         <div id="navbarVerticalMenuAdministracion" class="nav-collapse collapse"
                             data-bs-parent="#navbarVerticalMenu">
                             <a class="nav-link" href="{{ route('users.index') }}">Usuarios</a>
                         </div>
                     </div>










                     <!-- Módulo: Mantenimiento -->
                     <div class="nav-item">
                         <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuMantenimiento" role="button"
                             data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuMantenimiento"
                             aria-expanded="false" aria-controls="navbarVerticalMenuMantenimiento">
                             <i class="bi-tools nav-icon"></i>
                             <span class="nav-link-title">Mantenimiento</span>
                         </a>

                         <div id="navbarVerticalMenuMantenimiento" class="nav-collapse collapse"
                             data-bs-parent="#navbarVerticalMenu">

                             {{-- Sub-módulo: Gestionar Ubicaciones --}}
                             <small class="nav-subtitle">Gestionar Ubicaciones</small>
                             <a class="nav-link" href="{{ route('countries.index') }}">
                                 <i class="bi-globe2 nav-icon"></i> Países
                             </a>
                             <a class="nav-link" href="{{ route('departments.index') }}">
                                 <i class="bi-map nav-icon"></i> Departamentos
                             </a>
                             <a class="nav-link" href="{{ route('municipalities.index') }}">
                                 <i class="bi-geo-alt nav-icon"></i> Municipios
                             </a>

                             {{-- Sub-módulo: Gestionar Paciente --}}
                             <small class="nav-subtitle mt-2">Gestionar Paciente</small>
                             <a class="nav-link" href="#">
                                 <i class="bi-exclamation-triangle nav-icon"></i> Alergias
                             </a>
                             <a class="nav-link" href="#">
                                 <i class="bi-heart nav-icon"></i> Estado Civil
                             </a>
                             <a class="nav-link" href="#">
                                 <i class="bi-gender-ambiguous nav-icon"></i> Género
                             </a>
                             <a class="nav-link" href="#">
                                 <i class="bi-people nav-icon"></i> Etnia
                             </a>
                             <a class="nav-link" href="#">
                                 <i class="bi-translate nav-icon"></i> Comunidad Lingüística
                             </a>
                         </div>
                     </div>




                     <div class="navbar-vertical-footer">
                         <ul class="navbar-vertical-footer-list">
                             <li class="navbar-vertical-footer-list-item">
                                 <div class="dropdown dropup">
                                     <button type="button" class="btn btn-ghost-secondary btn-icon rounded-circle"
                                         id="selectThemeDropdown" data-bs-toggle="dropdown" aria-expanded="false"
                                         data-bs-dropdown-animation>
                                         @php
                                             $userTheme =
                                                 Auth::check() && Auth::user()->theme_preference
                                                     ? Auth::user()->theme_preference
                                                     : 'auto';
                                             $iconMap = [
                                                 'auto' => 'bi-moon-stars',
                                                 'default' => 'bi-brightness-high',
                                                 'dark' => 'bi-moon',
                                             ];
                                             $currentIcon = $iconMap[$userTheme] ?? $iconMap['auto'];
                                         @endphp
                                         <i class="{{ $currentIcon }}"></i>
                                     </button>

                                     <div class="dropdown-menu navbar-dropdown-menu navbar-dropdown-menu-borderless"
                                         aria-labelledby="selectThemeDropdown">
                                         @php
                                             $userTheme =
                                                 Auth::check() && Auth::user()->theme_preference
                                                     ? Auth::user()->theme_preference
                                                     : 'auto';
                                         @endphp
                                         <a class="dropdown-item {{ $userTheme === 'auto' ? 'active' : '' }}"
                                             href="#" data-icon="bi-moon-stars" data-value="auto">
                                             <i class="bi-moon-stars me-2"></i>
                                             <span class="text-truncate"
                                                 title="Automático (Sistema Predeterminado)">Automático (Sistema
                                                 Predeterminado)
                                             </span>
                                         </a>
                                         <a class="dropdown-item {{ $userTheme === 'default' ? 'active' : '' }}"
                                             href="#" data-icon="bi-brightness-high" data-value="default">
                                             <i class="bi-brightness-high me-2"></i>
                                             <span class="text-truncate" title="Claro (Modo Light)">Claro (Modo Light)
                                             </span>
                                         </a>
                                         <a class="dropdown-item {{ $userTheme === 'dark' ? 'active' : '' }}"
                                             href="#" data-icon="bi-moon" data-value="dark">
                                             <i class="bi-moon me-2"></i>
                                             <span class="text-truncate" title="Oscuro (Modo Dark)">Oscuro (Modo Dark)
                                             </span>
                                         </a>
                                     </div>
                                 </div>
                             </li>
                         </ul>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </aside>
