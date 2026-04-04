<aside class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered bg-white">
    <div class="navbar-vertical-container">
        <div class="navbar-vertical-footer-offset">
            <a class="navbar-brand" href="/" aria-label="Front">
                <img class="navbar-brand-logo" src="{{ asset('dist/img/Logotipo.svg') }}" alt="Logo" data-hs-theme-appearance="default">
                <img class="navbar-brand-logo" src="{{ asset('dist/img/Logotipo-white.svg') }}" alt="Logo" data-hs-theme-appearance="dark">
                <img class="navbar-brand-logo-mini" src="{{ asset('dist/img/Logotipo.svg') }}" alt="Logo" data-hs-theme-appearance="default">
                <img class="navbar-brand-logo-mini" src="{{ asset('dist/img/Logotipo-white.svg') }}" alt="Logo" data-hs-theme-appearance="dark">
            </a>
            <button type="button" class="js-navbar-vertical-aside-toggle-invoker navbar-aside-toggler">
                <i class="bi-arrow-bar-left navbar-toggler-short-align" data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>' data-bs-toggle="tooltip" data-bs-placement="right" title="Collapse"></i>
                <i class="bi-arrow-bar-right navbar-toggler-full-align" data-bs-template='<div class="tooltip d-none d-md-block" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>' data-bs-toggle="tooltip" data-bs-placement="right" title="Expand"></i>
            </button>
            
            <div class="navbar-vertical-content" style="scrollbar-gutter: stable;">
                <div id="navbarVerticalMenu" class="nav nav-pills nav-vertical card-navbar-nav">
                    <span class="dropdown-header mt-4">Módulos</span>
                    <small class="bi-three-dots nav-subtitle-replacer"></small>
                    
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuMetricas" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuMetricas" aria-expanded="false" aria-controls="navbarVerticalMenuMetricas">
                            <i class="bi-graph-up nav-icon"></i>
                            <span class="nav-link-title">Métricas</span>
                        </a>
                        <div id="navbarVerticalMenuMetricas" class="nav-collapse collapse" data-bs-parent="#navbarVerticalMenu">
                            <a class="nav-link" href="{{ route('metrics.system.index') }}"><i class="bi-pc-display me-2"></i>Sistema</a>
                            <a class="nav-link" href="javascript:void(0);"><i class="bi-heart-pulse me-2"></i>Salud</a>
                            <a class="nav-link" href="javascript:void(0);"><i class="bi-file-earmark-bar-graph me-2"></i>Reportes</a>
                        </div>
                    </div>
                    
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuPacientes" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuPacientes" aria-expanded="false" aria-controls="navbarVerticalMenuPacientes">
                            <i class="bi-people nav-icon"></i>
                            <span class="nav-link-title">Pacientes</span>
                        </a>
                        <div id="navbarVerticalMenuPacientes" class="nav-collapse collapse" data-bs-parent="#navbarVerticalMenu">
                            <a class="nav-link" href="{{ route('patients.create') }}"><i class="bi-person-plus me-2"></i>Nuevo Paciente</a>
                            <a class="nav-link" href="{{ route('patients.index') }}"><i class="bi-list-ul me-2"></i>Listado</a>
                            <a class="nav-link" href="{{ route('patient-relatives.index') }}"><i class="bi-people me-2"></i>Familiares</a>
                        </div>
                    </div>
                    
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuExpedientes" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuExpedientes" aria-expanded="false" aria-controls="navbarVerticalMenuExpedientes">
                            <i class="bi-file-earmark-medical nav-icon"></i>
                            <span class="nav-link-title">Expediente Clínicos</span>
                        </a>
                        <div id="navbarVerticalMenuExpedientes" class="nav-collapse collapse" data-bs-parent="#navbarVerticalMenu">
                            <a class="nav-link" href="/expedientes"><i class="bi-search me-2"></i>Consultar</a>
                            <a class="nav-link" href="/expedientes/crear"><i class="bi-file-earmark-plus me-2"></i>Nuevo Expediente</a>
                            <a class="nav-link" href="/expedientes/historial"><i class="bi-clock-history me-2"></i>Historial</a>
                        </div>
                    </div>
                    
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuCitas" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuCitas" aria-expanded="false" aria-controls="navbarVerticalMenuCitas">
                            <i class="bi-calendar-check nav-icon"></i>
                            <span class="nav-link-title">Citas Médicas</span>
                        </a>
                        <div id="navbarVerticalMenuCitas" class="nav-collapse collapse" data-bs-parent="#navbarVerticalMenu">
                            <a class="nav-link" href="/citas"><i class="bi-calendar3 me-2"></i>Calendario</a>
                            <a class="nav-link" href="/citas/crear"><i class="bi-calendar-plus me-2"></i>Nueva Cita</a>
                            <a class="nav-link" href="/citas/pendientes"><i class="bi-hourglass-split me-2"></i>Pendientes</a>
                            <a class="nav-link" href="/citas/historial"><i class="bi-clock-history me-2"></i>Historial</a>
                        </div>
                    </div>
                    
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuHospitalizacion" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuHospitalizacion" aria-expanded="false" aria-controls="navbarVerticalMenuHospitalizacion">
                            <i class="bi-hospital nav-icon"></i>
                            <span class="nav-link-title">Hospitalización</span>
                        </a>
                        <div id="navbarVerticalMenuHospitalizacion" class="nav-collapse collapse" data-bs-parent="#navbarVerticalMenu">
                            <a class="nav-link" href="/hospitalizacion"><i class="bi-box-arrow-in-right me-2"></i>Ingresos</a>
                            <a class="nav-link" href="/hospitalizacion/crear"><i class="bi-person-plus me-2"></i>Nuevo Ingreso</a>
                            <a class="nav-link" href="/hospitalizacion/camas"><i class="bi-columns me-2"></i>Gestión de Camas</a>
                            <a class="nav-link" href="/hospitalizacion/altas"><i class="bi-box-arrow-right me-2"></i>Altas</a>
                        </div>
                    </div>

                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuServicios" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuServicios" aria-expanded="false" aria-controls="navbarVerticalMenuServicios">
                            <i class="bi-activity nav-icon"></i>
                            <span class="nav-link-title">Servicios de Apoyo</span>
                        </a>
                        <div id="navbarVerticalMenuServicios" class="nav-collapse collapse" data-bs-parent="#navbarVerticalMenu">
                            
                            <!-- Laboratorio -->
                            <div class="nav-item">
                                <a class="nav-link dropdown-toggle" href="#navSubLaboratorio" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="navSubLaboratorio">
                                    <i class="bi-eyedropper me-2"></i> Laboratorio
                                </a>
                                <div id="navSubLaboratorio" class="nav-collapse collapse">
                                    <a class="nav-link" href="javascript:void(0);"><i class="bi-receipt me-2"></i>Ordenes</a>
                                    <a class="nav-link" href="javascript:void(0);"><i class="bi-clock-history me-2"></i>Historial</a>
                                </div>
                            </div>

                            <!-- Rayos X -->
                            <div class="nav-item">
                                <a class="nav-link dropdown-toggle" href="#navSubRayosX" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="navSubRayosX">
                                    <i class="bi-person-bounding-box me-2"></i> Rayos X
                                </a>
                                <div id="navSubRayosX" class="nav-collapse collapse">
                                    <a class="nav-link" href="javascript:void(0);"><i class="bi-receipt me-2"></i>Ordenes</a>
                                    <a class="nav-link" href="javascript:void(0);"><i class="bi-clock-history me-2"></i>Historial</a>
                                </div>
                            </div>

                            <!-- Cardiología -->
                            <div class="nav-item">
                                <a class="nav-link dropdown-toggle" href="#navSubCardiologia" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="navSubCardiologia">
                                    <i class="bi-heart-pulse me-2"></i> Cardiología
                                </a>
                                <div id="navSubCardiologia" class="nav-collapse collapse">
                                    <a class="nav-link" href="javascript:void(0);"><i class="bi-receipt me-2"></i>Ordenes</a>
                                    <a class="nav-link" href="javascript:void(0);"><i class="bi-clock-history me-2"></i>Historial</a>
                                </div>
                            </div>

                            <div class="nav-item">
                                <a class="nav-link dropdown-toggle" href="#navSubFarmacia" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="navSubFarmacia">
                                    <i class="bi-capsule me-2"></i> Farmacia
                                </a>
                                <div id="navSubFarmacia" class="nav-collapse collapse">
                                    <a class="nav-link" href="javascript:void(0);"><i class="bi-receipt me-2"></i>Ordenes</a>
                                    <a class="nav-link" href="javascript:void(0);"><i class="bi-clock-history me-2"></i>Historial</a>
                                    <a class="nav-link" href="javascript:void(0);"><i class="bi-box-seam me-2"></i>Inventario</a>
                                </div>
                            </div>

                            <div class="nav-item">
                                <a class="nav-link dropdown-toggle" href="#navSubCocina" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="navSubCocina">
                                    <i class="bi-egg-fried me-2"></i> Cocina
                                </a>
                                <div id="navSubCocina" class="nav-collapse collapse">
                                    <a class="nav-link" href="javascript:void(0);"><i class="bi-receipt me-2"></i>Ordenes</a>
                                    <a class="nav-link" href="javascript:void(0);"><i class="bi-clock-history me-2"></i>Historial</a>
                                    <a class="nav-link" href="javascript:void(0);"><i class="bi-card-list me-2"></i>Dietas</a>
                                </div>
                            </div>

                        </div>
                    </div>
                    
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuAdministracion" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuAdministracion" aria-expanded="false" aria-controls="navbarVerticalMenuAdministracion">
                            <i class="bi-gear nav-icon"></i>
                            <span class="nav-link-title">Administración</span>
                        </a>
                        <div id="navbarVerticalMenuAdministracion" class="nav-collapse collapse" data-bs-parent="#navbarVerticalMenu">
                            <a class="nav-link" href="{{ route('users.index') }}"><i class="bi-person-lines-fill me-2"></i>Usuarios</a>
                            <a class="nav-link" href="{{ route('releases.index') }}"><i class="bi-megaphone me-2"></i>Comunicados</a>
                        </div>
                    </div>
                    
                    <div class="nav-item">
                        <a class="nav-link dropdown-toggle" href="#navbarVerticalMenuMantenimiento" role="button" data-bs-toggle="collapse" data-bs-target="#navbarVerticalMenuMantenimiento" aria-expanded="false" aria-controls="navbarVerticalMenuMantenimiento">
                            <i class="bi-tools nav-icon"></i>
                            <span class="nav-link-title">Mantenimiento</span>
                        </a>
                        <div id="navbarVerticalMenuMantenimiento" class="nav-collapse collapse" data-bs-parent="#navbarVerticalMenu">
                            
                            <div class="nav-item">
                                <a class="nav-link dropdown-toggle" href="#navSubUbicaciones" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="navSubUbicaciones">
                                    <i class="bi-geo-alt me-2"></i> Ubicaciones
                                </a>
                                <div id="navSubUbicaciones" class="nav-collapse collapse">
                                    <a class="nav-link" href="{{ route('countries.index') }}"><i class="bi-globe me-2"></i>Países</a>
                                    <a class="nav-link" href="{{ route('departments.index') }}"><i class="bi-map me-2"></i>Departamentos</a>
                                    <a class="nav-link" href="{{ route('municipalities.index') }}"><i class="bi-geo me-2"></i>Municipios</a>
                                </div>
                            </div>

                            <div class="nav-item">
                                <a class="nav-link dropdown-toggle" href="#navSubPaciente" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="navSubPaciente">
                                    <i class="bi-person-badge me-2"></i> Paciente
                                </a>
                                <div id="navSubPaciente" class="nav-collapse collapse">
                                    <a class="nav-link" href="{{ route('allergies.index') }}"><i class="bi-shield-exclamation me-2"></i>Alergias</a>
                                    <a class="nav-link" href="{{ route('civil-statuses.index') }}"><i class="bi-person-badge me-2"></i>Estado Civil</a>
                                    <a class="nav-link" href="{{ route('genders.index') }}"><i class="bi-gender-ambiguous me-2"></i>Género</a>
                                    <a class="nav-link" href="{{ route('ethnicities.index') }}"><i class="bi-people me-2"></i>Etnia</a>
                                    <a class="nav-link" href="{{ route('linguistic-communities.index') }}"><i class="bi-translate me-2"></i>Idiomas</a>
                                    <a class="nav-link" href="{{ route('disabilities.index') }}"><i class="bi-universal-access me-2"></i>Discapacidad</a>
                                    <a class="nav-link" href="{{ route('relationship-types.index') }}"><i class="bi-diagram-2 me-2"></i>Tipos Relación</a>
                                </div>
                            </div>

                            <div class="nav-item">
                                <a class="nav-link dropdown-toggle" href="#navSubTrabajador" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="navSubTrabajador">
                                    <i class="bi-briefcase me-2"></i> Personal
                                </a>
                                <div id="navSubTrabajador" class="nav-collapse collapse">
                                    <a class="nav-link" href="{{ route('schedules.index') }}"><i class="bi-clock me-2"></i>Turnos</a>
                                    <a class="nav-link" href="{{ route('specialties.index') }}"><i class="bi-patch-check me-2"></i>Especialidades</a>
                                    <a class="nav-link" href="{{ route('work-departments.index') }}"><i class="bi-building me-2"></i>Departamentos</a>
                                    <a class="nav-link" href="{{ route('unity-executions.index') }}"><i class="bi-diagram-3 me-2"></i>Unidades</a>
                                    <a class="nav-link" href="{{ route('roles.index') }}"><i class="bi-shield-lock me-2"></i>Roles</a>
                                </div>
                            </div>

                            <div class="nav-item">
                                <a class="nav-link dropdown-toggle" href="#navSubExamenes" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="navSubExamenes">
                                    <i class="bi-clipboard-pulse me-2"></i> Exámenes
                                </a>
                                <div id="navSubExamenes" class="nav-collapse collapse">
                                    <a class="nav-link" href="javascript:void(0);"><i class="bi-eyedropper me-2"></i>Laboratorio</a>
                                    <a class="nav-link" href="javascript:void(0);"><i class="bi-person-bounding-box me-2"></i>Rayos X</a>
                                    <a class="nav-link" href="javascript:void(0);"><i class="bi-heart-pulse me-2"></i>Ecocardiografía</a>
                                </div>
                            </div>

                            <div class="nav-item">
                                <a class="nav-link dropdown-toggle" href="#navSubMantFarmacia" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="navSubMantFarmacia">
                                    <i class="bi-capsule-pill me-2"></i> Farmacia
                                </a>
                                <div id="navSubMantFarmacia" class="nav-collapse collapse">
                                    <a class="nav-link" href="javascript:void(0);"><i class="bi-rulers me-2"></i>Unidad de Medida</a>
                                    <a class="nav-link" href="javascript:void(0);"><i class="bi-capsule-pill me-2"></i>Tipo Medicamento</a>
                                </div>
                            </div>

                            <div class="nav-item">
                                <a class="nav-link dropdown-toggle" href="#navSubMantCocina" role="button" data-bs-toggle="collapse" aria-expanded="false" aria-controls="navSubMantCocina">
                                    <i class="bi-egg-fried me-2"></i> Cocina
                                </a>
                                <div id="navSubMantCocina" class="nav-collapse collapse">
                                    <a class="nav-link" href="javascript:void(0);"><i class="bi-card-checklist me-2"></i>Tipos de Dieta</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="navbar-vertical-footer">
                <ul class="navbar-vertical-footer-list">
                    <li class="navbar-vertical-footer-list-item">
                        <div class="dropdown dropup">
                            <button type="button" class="btn btn-ghost-secondary btn-icon rounded-circle" id="selectThemeDropdown" data-bs-toggle="dropdown" aria-expanded="false" data-bs-dropdown-animation>
                                @php
                                    $userTheme = Auth::check() && Auth::user()->theme_preference ? Auth::user()->theme_preference : 'auto';
                                    $iconMap = [
                                        'auto' => 'bi-moon-stars',
                                        'default' => 'bi-brightness-high',
                                        'dark' => 'bi-moon',
                                    ];
                                    $currentIcon = $iconMap[$userTheme] ?? $iconMap['auto'];
                                @endphp
                                <i class="{{ $currentIcon }}"></i>
                            </button>
                            <div class="dropdown-menu navbar-dropdown-menu navbar-dropdown-menu-borderless" aria-labelledby="selectThemeDropdown">
                                @php
                                    $userTheme = Auth::check() && Auth::user()->theme_preference ? Auth::user()->theme_preference : 'auto';
                                @endphp
                                <a class="dropdown-item {{ $userTheme === 'auto' ? 'active' : '' }}" href="#" data-icon="bi-moon-stars" data-value="auto">
                                    <i class="bi-moon-stars me-2"></i>
                                    <span class="text-truncate" title="Automático (Sistema Predeterminado)">Automático</span>
                                </a>
                                <a class="dropdown-item {{ $userTheme === 'default' ? 'active' : '' }}" href="#" data-icon="bi-brightness-high" data-value="default">
                                    <i class="bi-brightness-high me-2"></i>
                                    <span class="text-truncate" title="Claro (Modo Light)">Claro</span>
                                </a>
                                <a class="dropdown-item {{ $userTheme === 'dark' ? 'active' : '' }}" href="#" data-icon="bi-moon" data-value="dark">
                                    <i class="bi-moon me-2"></i>
                                    <span class="text-truncate" title="Oscuro (Modo Dark)">Oscuro</span>
                                </a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</aside>