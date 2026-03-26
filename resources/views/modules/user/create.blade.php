@extends('layouts.panel')
@section('title', 'Crear Usuario')

@section('content')
    <main id="content" role="main" class="main">
        <div class="content container-fluid">

            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-end">
                    <div class="col-sm mb-2 mb-sm-0">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-no-gutter">
                                <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Inicio</a></li>
                                <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('users.index') }}">Usuarios</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Crear</li>
                            </ol>
                        </nav>
                        <h1 class="page-header-title">Crear usuario</h1>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('users.index') }}" class="btn btn-primary">
                            <i class="bi-arrow-left"></i> Regresar
                        </a>
                    </div>
                </div>
            </div>
            <!-- End Page Header -->

            <!-- Step Form -->
            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data"
                autocomplete="off"
                class="js-step-form py-md-5"
                data-hs-step-form-options='{
                  "progressSelector": "#addUserStepFormProgress",
                  "stepsSelector":   "#addUserStepFormContent",
                  "endSelector":     "#addUserFinishBtn",
                  "isValidate":      false
                }'>
                @csrf
                <div class="row justify-content-lg-center">
                    <div class="col-lg-8">

                        <!-- Steps Progress -->
                        <ul id="addUserStepFormProgress"
                            class="js-step-progress step step-sm step-icon-sm step step-inline step-item-between mb-3 mb-md-5">
                            <li class="step-item">
                                <a class="step-content-wrapper" href="javascript:;"
                                    data-hs-step-form-next-options='{ "targetSelector": "#addUserStepProfile" }'>
                                    <span class="step-icon step-icon-soft-dark">1</span>
                                    <div class="step-content"><span class="step-title">Perfil</span></div>
                                </a>
                            </li>
                            <li class="step-item">
                                <a class="step-content-wrapper" href="javascript:;"
                                    data-hs-step-form-next-options='{ "targetSelector": "#addUserStepProfessional" }'>
                                    <span class="step-icon step-icon-soft-dark">2</span>
                                    <div class="step-content"><span class="step-title">Profesional</span></div>
                                </a>
                            </li>
                            <li class="step-item">
                                <a class="step-content-wrapper" href="javascript:;"
                                    data-hs-step-form-next-options='{ "targetSelector": "#addUserStepLocation" }'>
                                    <span class="step-icon step-icon-soft-dark">3</span>
                                    <div class="step-content"><span class="step-title">Ubicación</span></div>
                                </a>
                            </li>
                            <li class="step-item">
                                <a class="step-content-wrapper" href="javascript:;"
                                    data-hs-step-form-next-options='{ "targetSelector": "#addUserStepConfirmation" }'>
                                    <span class="step-icon step-icon-soft-dark">4</span>
                                    <div class="step-content"><span class="step-title">Confirmación</span></div>
                                </a>
                            </li>
                        </ul>
                        <!-- End Steps Progress -->

                        <div id="addUserStepFormContent">

                            {{-- ========================================================== --}}
                            {{-- PASO 1: PERFIL --}}
                            {{-- ========================================================== --}}
                            <div id="addUserStepProfile" class="card card-lg active">
                                <div class="card-body">

                                    <!-- Avatar -->
                                    <div class="row mb-4">
                                        <label class="col-sm-3 col-form-label form-label">Foto de Perfil</label>
                                        <div class="col-sm-9">
                                            <div class="d-flex align-items-center">
                                                <label class="avatar avatar-xl avatar-circle avatar-uploader me-5" for="avatarUploader">
                                                    <img id="avatarImg" class="avatar-img" src="{{ asset('img/160x160/img1.jpg') }}" alt="Avatar">
                                                    <input type="file" name="profile_photo"
                                                        class="js-file-attach avatar-uploader-input"
                                                        id="avatarUploader"
                                                        autocomplete="off"
                                                        data-hs-file-attach-options='{
                                                            "textTarget": "#avatarImg",
                                                            "mode": "image",
                                                            "targetAttr": "src",
                                                            "resetTarget": ".js-file-attach-reset-img",
                                                            "resetImg": "{{ asset('img/160x160/img1.jpg') }}",
                                                            "allowTypes": [".png", ".jpeg", ".jpg"]
                                                        }'>
                                                    <span class="avatar-uploader-trigger">
                                                        <i class="bi-pencil avatar-uploader-icon shadow-sm"></i>
                                                    </span>
                                                </label>
                                                <button type="button" class="js-file-attach-reset-img btn btn-white">Eliminar</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-4"><div class="col-sm-12"><hr></div></div>

                                    <!-- Nombres -->
                                    <div class="row mb-4">
                                        <label class="col-sm-3 col-form-label form-label">Nombres</label>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-sm-vertical">
                                                <input type="text" class="form-control" name="first_name" id="firstNameLabel"
                                                    autocomplete="off" placeholder="Primer nombre">
                                                <input type="text" class="form-control" name="second_name" id="secondNameLabel"
                                                    autocomplete="off" placeholder="Segundo nombre">
                                                <input type="text" class="form-control" name="third_name" id="thirdNameLabel"
                                                    autocomplete="off" placeholder="Tercer nombre">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Apellidos -->
                                    <div class="row mb-4">
                                        <label class="col-sm-3 col-form-label form-label">Apellidos</label>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-sm-vertical">
                                                <input type="text" class="form-control" name="first_last_name" id="firstLastNameLabel"
                                                    autocomplete="off" placeholder="Primer apellido">
                                                <input type="text" class="form-control" name="second_last_name" id="secondLastNameLabel"
                                                    autocomplete="off" placeholder="Segundo apellido">
                                                <input type="text" class="form-control" name="married_last_name" id="marriedLastNameLabel"
                                                    autocomplete="off" placeholder="Apellido de casada">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-4"><div class="col-sm-12"><hr></div></div>

                                    <!-- CUI / NIT / Estado Civil -->
                                    <div class="row mb-4">
                                        <div class="col-md-4 mb-2">
                                            <label for="cuiLabel" class="form-label">CUI</label>
                                            <input type="text" class="form-control" name="cui" id="cuiLabel"
                                                autocomplete="off" placeholder="CUI">
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="nitLabel" class="form-label">NIT</label>
                                            <input type="text" class="form-control" name="nit" id="nitLabel"
                                                autocomplete="off" placeholder="NIT">
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="maritalStatusLabel" class="form-label">Estado Civil</label>
                                            <select class="form-select" name="marital_status" id="maritalStatusLabel" autocomplete="off">
                                                <option value="">Seleccione</option>
                                                <option value="soltero">Soltero(a)</option>
                                                <option value="casado">Casado(a)</option>
                                                <option value="divorciado">Divorciado(a)</option>
                                                <option value="viudo">Viudo(a)</option>
                                                <option value="union_libre">Unión Libre</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Fecha / Género / Teléfono -->
                                    <div class="row mb-4">
                                        <div class="col-md-4 mb-2">
                                            <label for="birthDateLabel" class="form-label">Fecha de Nacimiento</label>
                                            <input type="date" class="form-control" name="birth_date" id="birthDateLabel"
                                                autocomplete="off">
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="genderLabel" class="form-label">Género</label>
                                            <select class="form-select" name="gender" id="genderLabel" autocomplete="off">
                                                <option value="">Seleccione</option>
                                                <option value="M">Masculino</option>
                                                <option value="F">Femenino</option>
                                                <option value="O">Otro</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="phoneLabel" class="form-label">Teléfono</label>
                                            <input type="text" class="form-control" name="phone" id="phoneLabel"
                                                autocomplete="off" placeholder="Ej. +12345678">
                                        </div>
                                    </div>

                                    <div class="row mb-4"><div class="col-sm-12"><hr></div></div>

                                    <!-- Email / Contraseña -->
                                    <div class="row mb-4">
                                        <div class="col-sm-6 mb-2">
                                            <label for="emailLabel" class="form-label">Correo Electrónico</label>
                                            <input type="email" class="form-control" name="email" id="emailLabel"
                                                autocomplete="off" placeholder="correo@ejemplo.com">
                                        </div>
                                        <div class="col-sm-6 mb-2">
                                            <label for="passwordLabel" class="form-label">Contraseña</label>
                                            <input type="password" class="form-control" name="password" id="passwordLabel"
                                                autocomplete="new-password" placeholder="********">
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer d-flex justify-content-end align-items-center">
                                    <button type="button" class="btn btn-primary"
                                        data-hs-step-form-next-options='{ "targetSelector": "#addUserStepProfessional" }'>
                                        Siguiente paso <i class="bi-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                            {{-- End Paso 1 --}}

                            {{-- ========================================================== --}}
                            {{-- PASO 2: PROFESIONAL + ESTADO --}}
                            {{-- ========================================================== --}}
                            <div id="addUserStepProfessional" class="card card-lg" style="display: none;">
                                <div class="card-body">

                                    <!-- Rol -->
                                    <div class="row mb-4">
                                        <div class="col-sm-12 mb-4">
                                            <label for="roleIdLabel" class="form-label">Rol en el Sistema</label>
                                            <select class="form-select" name="role_id" id="roleIdLabel" autocomplete="off">
                                                <option value="">Seleccione Rol</option>
                                                @foreach ($roles ?? [] as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-6 mb-4">
                                            <label for="unityExecutionIdLabel" class="form-label">Unidad Ejecutora</label>
                                            <select class="form-select" name="unity_execution_id" id="unityExecutionIdLabel" autocomplete="off">
                                                <option value="">Seleccione Unidad</option>
                                                @foreach ($unityExecutions ?? [] as $unity)
                                                    <option value="{{ $unity->id }}">{{ $unity->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-6 mb-4">
                                            <label for="workDepartmentIdLabel" class="form-label">Departamento de Trabajo</label>
                                            <select class="form-select" name="work_department_id" id="workDepartmentIdLabel" autocomplete="off">
                                                <option value="">Seleccione Departamento</option>
                                                @foreach ($workDepartments ?? [] as $wd)
                                                    <option value="{{ $wd->id }}">{{ $wd->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-sm-6 mb-2">
                                            <label for="specialtyLabel" class="form-label">Especialidad (Si es Médico)</label>
                                            <select class="form-select" name="specialty_id" id="specialtyLabel" autocomplete="off">
                                                <option value="">Seleccione Especialidad</option>
                                                @foreach ($specialties ?? [] as $specialty)
                                                    <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-6 mb-2">
                                            <label for="scheduleIdLabel" class="form-label">Asignar Horario de Trabajo</label>
                                            <select class="form-select" name="schedule_id" id="scheduleIdLabel" autocomplete="off">
                                                <option value="">Seleccione Horario</option>
                                                @foreach ($schedules ?? [] as $schedule)
                                                    <option value="{{ $schedule->id }}">{{ $schedule->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-4"><div class="col-sm-12"><hr></div></div>

                                    <!-- Estado -->
                                    <div class="row mb-4">
                                        <div class="col-sm-12">
                                            <label for="isActiveLabel" class="form-label">Estado del Usuario</label>
                                            <select class="form-select" name="is_active" id="isActiveLabel" autocomplete="off">
                                                <option value="1" selected>Activo – puede iniciar sesión</option>
                                                <option value="0">Inactivo – no puede iniciar sesión</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer d-flex align-items-center">
                                    <button type="button" class="btn btn-ghost-secondary"
                                        data-hs-step-form-prev-options='{ "targetSelector": "#addUserStepProfile" }'>
                                        <i class="bi-chevron-left"></i> Paso anterior
                                    </button>
                                    <div class="ms-auto">
                                        <button type="button" class="btn btn-primary"
                                            data-hs-step-form-next-options='{ "targetSelector": "#addUserStepLocation" }'>
                                            Siguiente paso <i class="bi-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            {{-- End Paso 2 --}}

                            {{-- ========================================================== --}}
                            {{-- PASO 3: UBICACIÓN --}}
                            {{-- ========================================================== --}}
                            <div id="addUserStepLocation" class="card card-lg" style="display: none;">
                                <div class="card-body">

                                    <div class="row mb-4">
                                        <label for="countryIdLabel" class="col-sm-3 col-form-label form-label">País</label>
                                        <div class="col-sm-9">
                                            <select class="form-select" name="country_id" id="countryIdLabel" autocomplete="off">
                                                <option value="">Seleccione un país</option>
                                                @foreach ($countries ?? [] as $country)
                                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label for="departmentIdLabel" class="col-sm-3 col-form-label form-label">Departamento</label>
                                        <div class="col-sm-9">
                                            <select class="form-select" name="department_id" id="departmentIdLabel" autocomplete="off" disabled>
                                                <option value="">Seleccione primero un país</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label for="municipalityIdLabel" class="col-sm-3 col-form-label form-label">Municipio</label>
                                        <div class="col-sm-9">
                                            <select class="form-select" name="municipality_id" id="municipalityIdLabel" autocomplete="off" disabled>
                                                <option value="">Seleccione primero un departamento</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label for="addressLabel" class="col-sm-3 col-form-label form-label">Dirección Exacta</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="address" id="addressLabel"
                                                autocomplete="off" placeholder="Ej. 1ra Avenida 2-33 Zona 1">
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer d-flex align-items-center">
                                    <button type="button" class="btn btn-ghost-secondary"
                                        data-hs-step-form-prev-options='{ "targetSelector": "#addUserStepProfessional" }'>
                                        <i class="bi-chevron-left"></i> Paso anterior
                                    </button>
                                    <div class="ms-auto">
                                        <button type="button" class="btn btn-primary"
                                            data-hs-step-form-next-options='{ "targetSelector": "#addUserStepConfirmation" }'>
                                            Siguiente paso <i class="bi-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            {{-- End Paso 3 --}}

                            {{-- ========================================================== --}}
                            {{-- PASO 4: CONFIRMACIÓN --}}
                            {{-- ========================================================== --}}
                            <div id="addUserStepConfirmation" class="card card-lg" style="display: none;">

                                <!-- Profile Cover -->
                                <div class="profile-cover">
                                    <div class="profile-cover-img-wrapper">
                                        <img class="profile-cover-img" src="{{ asset('img/1920x400/img1.jpg') }}" alt="Portada">
                                    </div>
                                </div>
                                <!-- End Profile Cover -->

                                <!-- Avatar -->
                                <label class="avatar avatar-xxl avatar-circle avatar-border-lg profile-cover-avatar"
                                    for="avatarUploaderConfirm" data-bs-toggle="tooltip" data-bs-placement="right"
                                    title="Foto de perfil">
                                    <img id="confirmAvatarImg" class="avatar-img" src="{{ asset('img/160x160/img1.jpg') }}" alt="Avatar">
                                    <span class="avatar-uploader-trigger">
                                        <i class="bi-pencil avatar-uploader-icon shadow-sm"></i>
                                    </span>
                                </label>
                                <!-- End Avatar -->

                                <!-- Body -->
                                <div class="card-body">
                                    <dl class="row">
                                        <dt class="col-sm-6 text-sm-end">Nombre completo:</dt>
                                        <dd class="col-sm-6" id="confirm-fullName">—</dd>

                                        <dt class="col-sm-6 text-sm-end">CUI:</dt>
                                        <dd class="col-sm-6" id="confirm-cui">—</dd>

                                        <dt class="col-sm-6 text-sm-end">NIT:</dt>
                                        <dd class="col-sm-6" id="confirm-nit">—</dd>

                                        <dt class="col-sm-6 text-sm-end">Estado Civil:</dt>
                                        <dd class="col-sm-6" id="confirm-maritalStatus">—</dd>

                                        <dt class="col-sm-6 text-sm-end">Fecha de Nacimiento:</dt>
                                        <dd class="col-sm-6" id="confirm-birthDate">—</dd>

                                        <dt class="col-sm-6 text-sm-end">Género:</dt>
                                        <dd class="col-sm-6" id="confirm-gender">—</dd>

                                        <dt class="col-sm-6 text-sm-end">Teléfono:</dt>
                                        <dd class="col-sm-6" id="confirm-phone">—</dd>

                                        <dt class="col-sm-6 text-sm-end">Correo Electrónico:</dt>
                                        <dd class="col-sm-6" id="confirm-email">—</dd>

                                        <dt class="col-sm-6 text-sm-end"><hr class="my-2 w-100"></dt>
                                        <dd class="col-sm-6"><hr class="my-2 w-100"></dd>

                                        <dt class="col-sm-6 text-sm-end">Rol en el Sistema:</dt>
                                        <dd class="col-sm-6" id="confirm-roleId">—</dd>

                                        <dt class="col-sm-6 text-sm-end">Unidad Ejecutora:</dt>
                                        <dd class="col-sm-6" id="confirm-unityExecution">—</dd>

                                        <dt class="col-sm-6 text-sm-end">Depto. de Trabajo:</dt>
                                        <dd class="col-sm-6" id="confirm-workDepartment">—</dd>

                                        <dt class="col-sm-6 text-sm-end">Especialidad:</dt>
                                        <dd class="col-sm-6" id="confirm-specialty">—</dd>

                                        <dt class="col-sm-6 text-sm-end">Horario:</dt>
                                        <dd class="col-sm-6" id="confirm-scheduleId">—</dd>

                                        <dt class="col-sm-6 text-sm-end">Estado:</dt>
                                        <dd class="col-sm-6" id="confirm-isActive">—</dd>

                                        <dt class="col-sm-6 text-sm-end"><hr class="my-2 w-100"></dt>
                                        <dd class="col-sm-6"><hr class="my-2 w-100"></dd>

                                        <dt class="col-sm-6 text-sm-end">País:</dt>
                                        <dd class="col-sm-6" id="confirm-countryId">—</dd>

                                        <dt class="col-sm-6 text-sm-end">Departamento:</dt>
                                        <dd class="col-sm-6" id="confirm-departmentId">—</dd>

                                        <dt class="col-sm-6 text-sm-end">Municipio:</dt>
                                        <dd class="col-sm-6" id="confirm-municipalityId">—</dd>

                                        <dt class="col-sm-6 text-sm-end">Dirección:</dt>
                                        <dd class="col-sm-6" id="confirm-address">—</dd>
                                    </dl>
                                </div>
                                <!-- End Body -->

                                <!-- Footer -->
                                <div class="card-footer d-sm-flex align-items-sm-center">
                                    <button type="button" class="btn btn-ghost-secondary mb-2 mb-sm-0"
                                        data-hs-step-form-prev-options='{ "targetSelector": "#addUserStepLocation" }'>
                                        <i class="bi-chevron-left"></i> Paso anterior
                                    </button>
                                    <div class="ms-auto">
                                        <button id="addUserFinishBtn" type="button" class="btn btn-primary">
                                            <i class="bi-person-plus-fill me-1"></i> Crear usuario
                                        </button>
                                    </div>
                                </div>
                                <!-- End Footer -->
                            </div>
                            {{-- End Paso 4 --}}

                            <!-- Message Body -->
                            @if (session('success'))
                                <div id="successMessageContent">
                                    <div class="text-center">
                                        <img class="img-fluid mb-3" src="{{ asset('svg/illustrations/oc-hi-five.svg') }}"
                                            alt="Image Description" data-hs-theme-appearance="default" style="max-width: 15rem;">
                                        <img class="img-fluid mb-3" src="{{ asset('svg/illustrations-light/oc-hi-five.svg') }}"
                                            alt="Image Description" data-hs-theme-appearance="dark" style="max-width: 15rem;">
                                        <div class="mb-4">
                                            <h2>¡Usuario Creado!</h2>
                                            <p>{{ session('success') }}</p>
                                        </div>
                                        <div class="d-flex justify-content-center">
                                            <a class="btn btn-white me-3" href="{{ route('users.index') }}">
                                                <i class="bi-chevron-left ms-1"></i> Volver a usuarios
                                            </a>
                                            <a class="btn btn-primary" href="{{ route('users.create') }}">
                                                <i class="bi-person-plus-fill me-1"></i> Nuevo usuario
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    window.addEventListener('load', function() {
                                        document.getElementById("addUserStepFormProgress").style.display = 'none';
                                        document.getElementById("addUserStepFormContent").style.display = 'none';
                                    });
                                </script>
                            @endif
                            <!-- End Message Body -->

                        </div>
                        <!-- End Content Step Form -->
                    </div>
                </div>
            </form>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/hs-step-form/dist/hs-step-form.min.js') }}"></script>
    <script src="{{ asset('vendor/hs-file-attach/dist/hs-file-attach.min.js') }}"></script>
    <script>
        (function() {
            window.onload = function() {

                // =====================================================
                // HELPERS DE CONFIRMACIÓN
                // =====================================================
                const getVal  = id => { const e = document.getElementById(id); return e ? e.value.trim() : ''; };
                const getText = id => {
                    const e = document.getElementById(id);
                    return (e && e.options && e.selectedIndex >= 0 && e.value !== '') ? e.options[e.selectedIndex].text : '';
                };
                const set = (id, val) => {
                    const el = document.getElementById(id);
                    if (el) el.textContent = val || '—';
                };

                function updateConfirmation() {
                    const fn  = getVal('firstNameLabel');
                    const fln = getVal('firstLastNameLabel');
                    const fullName = [
                        fn,
                        getVal('secondNameLabel'),
                        getVal('thirdNameLabel'),
                        fln,
                        getVal('secondLastNameLabel'),
                        getVal('marriedLastNameLabel')
                    ].filter(Boolean).join(' ');

                    set('confirm-fullName',       fullName);
                    set('confirm-cui',            getVal('cuiLabel'));
                    set('confirm-nit',            getVal('nitLabel'));
                    set('confirm-maritalStatus',  getText('maritalStatusLabel'));
                    set('confirm-birthDate',      getVal('birthDateLabel'));
                    set('confirm-gender',         getText('genderLabel'));
                    set('confirm-phone',          getVal('phoneLabel'));
                    set('confirm-email',          getVal('emailLabel'));
                    set('confirm-roleId',         getText('roleIdLabel'));
                    set('confirm-unityExecution', getText('unityExecutionIdLabel'));
                    set('confirm-workDepartment', getText('workDepartmentIdLabel'));
                    set('confirm-specialty',      getText('specialtyLabel'));
                    set('confirm-scheduleId',     getText('scheduleIdLabel'));
                    set('confirm-isActive',       getText('isActiveLabel'));
                    set('confirm-countryId',      getText('countryIdLabel'));
                    set('confirm-departmentId',   getText('departmentIdLabel'));
                    set('confirm-municipalityId', getText('municipalityIdLabel'));
                    set('confirm-address',        getVal('addressLabel'));

                    // Sync avatar preview
                    const src = document.getElementById('avatarImg')?.src;
                    const confirmImg = document.getElementById('confirmAvatarImg');
                    if (confirmImg && src) confirmImg.src = src;
                }

                // =====================================================
                // STEP FORM
                // =====================================================
                new HSStepForm('.js-step-form', {
                    finish: () => document.querySelector('.js-step-form').submit(),
                    onNextStep: function() { updateConfirmation(); scrollToTop(); },
                    onPrevStep: function() { scrollToTop(); }
                });

                // FILE ATTACH
                if (typeof HSFileAttach !== 'undefined') {
                    new HSFileAttach('.js-file-attach');
                }

                // Avatar fallback preview
                const avatarInput = document.getElementById('avatarUploader');
                const avatarImg   = document.getElementById('avatarImg');
                if (avatarInput && avatarImg) {
                    avatarInput.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = ev => { avatarImg.src = ev.target.result; };
                            reader.readAsDataURL(file);
                        }
                    });
                }

                function scrollToTop(el = '.js-step-form') {
                    const element = document.querySelector(el);
                    if (element) {
                        window.scrollTo({ top: (element.getBoundingClientRect().top + window.scrollY) - 30, behavior: 'smooth' });
                    }
                }

                // =====================================================
                // CASCADING DROPDOWNS: País → Departamento → Municipio
                // =====================================================
                const countrySelect      = document.getElementById('countryIdLabel');
                const departmentSelect   = document.getElementById('departmentIdLabel');
                const municipalitySelect = document.getElementById('municipalityIdLabel');

                function resetSelect(select, placeholder) {
                    select.innerHTML = `<option value="">${placeholder}</option>`;
                    select.disabled = true;
                }
                function populateSelect(select, items, placeholder, selectedId = null) {
                    select.innerHTML = `<option value="">${placeholder}</option>`;
                    items.forEach(item => {
                        const opt = document.createElement('option');
                        opt.value = item.id;
                        opt.textContent = item.name;
                        if (selectedId && item.id == selectedId) opt.selected = true;
                        select.appendChild(opt);
                    });
                    select.disabled = false;
                }

                if (countrySelect) {
                    countrySelect.addEventListener('change', function() {
                        const countryId = this.value;
                        resetSelect(departmentSelect, 'Seleccione primero un país');
                        resetSelect(municipalitySelect, 'Seleccione primero un departamento');
                        if (!countryId) return;
                        departmentSelect.innerHTML = '<option value="">Cargando...</option>';
                        fetch(`{{ route('patients.get-departments-by-country') }}?country_id=${countryId}`, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                        }).then(r => r.json()).then(data => {
                            data.length === 0
                                ? resetSelect(departmentSelect, 'Sin departamentos disponibles')
                                : populateSelect(departmentSelect, data, 'Seleccione un departamento');
                        }).catch(() => resetSelect(departmentSelect, 'Error al cargar'));
                    });
                }

                if (departmentSelect) {
                    departmentSelect.addEventListener('change', function() {
                        const deptId = this.value;
                        resetSelect(municipalitySelect, 'Seleccione primero un departamento');
                        if (!deptId) return;
                        municipalitySelect.innerHTML = '<option value="">Cargando...</option>';
                        fetch(`{{ route('patients.get-municipalities-by-department') }}?department_id=${deptId}`, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                        }).then(r => r.json()).then(data => {
                            data.length === 0
                                ? resetSelect(municipalitySelect, 'Sin municipios disponibles')
                                : populateSelect(municipalitySelect, data, 'Seleccione un municipio');
                        }).catch(() => resetSelect(municipalitySelect, 'Error al cargar'));
                    });
                }

            };
        })();
    </script>
@endpush
