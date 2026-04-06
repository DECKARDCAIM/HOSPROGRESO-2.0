@extends('layouts.panel')
@section('title', 'Crear Usuario')
@section('content')
    <main id="content" role="main" class="main">
        <div class="content container-fluid">

            <div class="page-header">
                <div class="row align-items-end">
                    <div class="col-sm mb-2 mb-sm-0">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-no-gutter">
                                <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Inicio</a>
                                </li>
                                <li class="breadcrumb-item"><a class="breadcrumb-link"
                                        href="{{ route('users.index') }}">Usuarios</a></li>
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
            @if ($errors->any())
                <div class="alert alert-danger text-white mb-4" role="alert">
                    <strong>¡Ups! Ha ocurrido un problema:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif




            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off"
                class="js-step-form py-md-5"
                data-hs-step-form-options='{ "progressSelector": "#addUserStepFormProgress", "stepsSelector":   "#addUserStepFormContent", "endSelector":     "#addUserFinishBtn", "isValidate":      false }'>
                @csrf
                <div class="row justify-content-lg-center">
                    <div class="col-lg-8">
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
                        <div id="addUserStepFormContent">
                            <div id="addUserStepProfile" class="card card-lg active">
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <label class="col-sm-3 col-form-label form-label">Foto de Perfil</label>
                                        <div class="col-sm-9">
                                            <div class="d-flex align-items-center">
                                                <label class="avatar avatar-xl avatar-circle avatar-uploader me-5"
                                                    for="avatarUploader">
                                                    <img id="avatarImg" class="avatar-img"
                                                        src="{{ asset('dist/img/160x160/img1.jpg') }}" alt="Avatar">
                                                    <input type="file" name="profile_photo"
                                                        value="{{ old('profile_photo') }}"
                                                        class="js-file-attach avatar-uploader-input @error('profile_photo') is-invalid @enderror"
                                                        id="avatarUploader" autocomplete="off"
                                                        data-hs-file-attach-options='{ "textTarget": "#avatarImg", "mode": "image", "targetAttr": "src", "resetTarget": ".js-file-attach-reset-img", "resetImg": "{{ asset('dist/img/160x160/img1.jpg') }}", "allowTypes": [".png", ".jpeg", ".jpg"] }'>
                                                    <span class="avatar-uploader-trigger">
                                                        <i class="bi-pencil avatar-uploader-icon shadow-sm"></i>
                                                    </span>
                                                </label>
                                                <button type="button"
                                                    class="js-file-attach-reset-img btn btn-white">Eliminar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-sm-12">
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="col-sm-3 col-form-label form-label">Nombres</label>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-sm-vertical">
                                                <input type="text"
                                                    class="form-control @error('first_name') is-invalid @enderror"
                                                    name="first_name" value="{{ old('first_name') }}" id="firstNameLabel"
                                                    autocomplete="off" placeholder="Primer nombre">
                                                <input type="text"
                                                    class="form-control @error('second_name') is-invalid @enderror"
                                                    name="second_name" value="{{ old('second_name') }}"
                                                    id="secondNameLabel" autocomplete="off" placeholder="Segundo nombre">
                                                <input type="text"
                                                    class="form-control @error('third_name') is-invalid @enderror"
                                                    name="third_name" value="{{ old('third_name') }}"
                                                    id="thirdNameLabel" autocomplete="off" placeholder="Tercer nombre">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="col-sm-3 col-form-label form-label">Apellidos</label>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-sm-vertical">
                                                <input type="text"
                                                    class="form-control @error('first_last_name') is-invalid @enderror"
                                                    name="first_last_name" value="{{ old('first_last_name') }}"
                                                    id="firstLastNameLabel" autocomplete="off"
                                                    placeholder="Primer apellido">
                                                <input type="text"
                                                    class="form-control @error('second_last_name') is-invalid @enderror"
                                                    name="second_last_name" value="{{ old('second_last_name') }}"
                                                    id="secondLastNameLabel" autocomplete="off"
                                                    placeholder="Segundo apellido">
                                                <input type="text"
                                                    class="form-control @error('married_last_name') is-invalid @enderror"
                                                    name="married_last_name" value="{{ old('married_last_name') }}"
                                                    id="marriedLastNameLabel" autocomplete="off"
                                                    placeholder="Apellido de casada">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-sm-12">
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-4 mb-2">
                                            <label for="cuiLabel" class="form-label">CUI</label>
                                            <input type="text" class="form-control @error('cui') is-invalid @enderror"
                                                name="cui" value="{{ old('cui') }}" id="cuiLabel"
                                                autocomplete="off" placeholder="CUI">
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="nitLabel" class="form-label">NIT</label>
                                            <input type="text" class="form-control @error('nit') is-invalid @enderror"
                                                name="nit" value="{{ old('nit') }}" id="nitLabel"
                                                autocomplete="off" placeholder="NIT">
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="civilStatusLabel" class="form-label">Estado Civil</label>
                                            <div class="tom-select-custom">
                                                <select
                                                    class="js-select form-select @error('civil_status_id') is-invalid @enderror"
                                                    name="civil_status_id" id="civilStatusLabel" autocomplete="off"
                                                    data-hs-tom-select-options='{ "placeholder": "Seleccione..." }'>
                                                    <option value="">Seleccione</option>
                                                    @foreach ($civilStatuses ?? [] as $status)
                                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-4 mb-2">
                                            <label for="birthDateLabel" class="form-label">Fecha de Nacimiento</label>
                                            <input type="date"
                                                class="form-control @error('birth_date') is-invalid @enderror"
                                                name="birth_date" value="{{ old('birth_date') }}" id="birthDateLabel"
                                                autocomplete="off">
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="genderLabel" class="form-label">Género</label>
                                            <div class="tom-select-custom">
                                                <select
                                                    class="js-select form-select @error('gender_id') is-invalid @enderror"
                                                    name="gender_id" id="genderLabel" autocomplete="off"
                                                    data-hs-tom-select-options='{ "placeholder": "Seleccione..." }'>
                                                    <option value="">Seleccione</option>
                                                    @foreach ($genders ?? [] as $gender)
                                                        <option value="{{ $gender->id }}">{{ $gender->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-2">
                                            <label for="phoneLabel" class="form-label">Teléfono</label>
                                            <input type="text"
                                                class="form-control @error('phone') is-invalid @enderror" name="phone"
                                                value="{{ old('phone') }}" id="phoneLabel" autocomplete="off"
                                                placeholder="Ej. +12345678">
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-sm-12">
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-sm-6 mb-2">
                                            <label for="emailLabel" class="form-label">Correo Electrónico</label>
                                            <input type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email') }}" id="emailLabel" autocomplete="off"
                                                placeholder="correo@ejemplo.com">
                                        </div>
                                        <div class="col-sm-6 mb-2">
                                            <label for="passwordLabel" class="form-label">Contraseña</label>
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password" value="{{ old('password') }}" id="passwordLabel"
                                                autocomplete="new-password" placeholder="********">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-end align-items-center">
                                    <button type="button" class="btn btn-primary"
                                        data-hs-step-form-next-options='{ "targetSelector": "#addUserStepProfessional" }'>
                                        Siguiente paso <i class="bi-chevron-right"></i></button>
                                </div>
                            </div>
                            <div id="addUserStepProfessional" class="card card-lg" style="display: none;">
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <div class="col-sm-12 mb-4">
                                            <label for="roleIdLabel" class="form-label">Rol en el Sistema</label>
                                            <div class="tom-select-custom">
                                                <select
                                                    class="js-select form-select @error('role_id') is-invalid @enderror"
                                                    name="role_id" id="roleIdLabel" autocomplete="off"
                                                    data-hs-tom-select-options='{ "placeholder": "Seleccione Rol..." }'>
                                                    <option value="">Seleccione Rol</option>
                                                    @foreach ($roles ?? [] as $role)
                                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mb-4">
                                            <label for="unityExecutionIdLabel" class="form-label">Unidad Ejecutora</label>
                                            <div class="tom-select-custom">
                                                <select
                                                    class="js-select form-select @error('unity_execution_id') is-invalid @enderror"
                                                    name="unity_execution_id" id="unityExecutionIdLabel"
                                                    autocomplete="off"
                                                    data-hs-tom-select-options='{ "placeholder": "Seleccione Unidad..." }'>
                                                    <option value="">Seleccione Unidad</option>
                                                    @foreach ($unityExecutions ?? [] as $unity)
                                                        <option value="{{ $unity->id }}">{{ $unity->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mb-4">
                                            <label for="workDepartmentIdLabel" class="form-label">Departamento de
                                                Trabajo</label>
                                            <div class="tom-select-custom">
                                                <select
                                                    class="js-select form-select @error('work_department_id') is-invalid @enderror"
                                                    name="work_department_id" id="workDepartmentIdLabel"
                                                    autocomplete="off"
                                                    data-hs-tom-select-options='{ "placeholder": "Seleccione Departamento..." }'>
                                                    <option value="">Seleccione Departamento</option>
                                                    @foreach ($workDepartments ?? [] as $wd)
                                                        <option value="{{ $wd->id }}">{{ $wd->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-sm-6 mb-2">
                                            <label for="specialtyLabel" class="form-label">Especialidad (Si es
                                                Médico)</label>
                                            <div class="tom-select-custom">
                                                <select
                                                    class="js-select form-select @error('specialty_id') is-invalid @enderror"
                                                    name="specialty_id" id="specialtyLabel" autocomplete="off"
                                                    data-hs-tom-select-options='{ "placeholder": "Seleccione Especialidad..." }'>
                                                    <option value="">Seleccione Especialidad</option>
                                                    @foreach ($specialties ?? [] as $specialty)
                                                        <option value="{{ $specialty->id }}">{{ $specialty->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 mb-2">
                                            <label for="collegiateNumberLabel" class="form-label">Número de
                                                Colegiado</label>
                                            <input type="text"
                                                class="form-control @error('collegiate_number') is-invalid @enderror"
                                                name="collegiate_number" value="{{ old('collegiate_number') }}"
                                                id="collegiateNumberLabel" autocomplete="off"
                                                placeholder="Número de colegiado">
                                        </div>
                                        <div class="col-sm-6 mb-2">
                                            <label for="scheduleIdLabel" class="form-label">Asignar Horario de
                                                Trabajo</label>
                                            <div class="tom-select-custom">
                                                <select
                                                    class="js-select form-select @error('schedule_id') is-invalid @enderror"
                                                    name="schedule_id" id="scheduleIdLabel" autocomplete="off"
                                                    data-hs-tom-select-options='{ "placeholder": "Seleccione Horario..." }'>
                                                    <option value="">Seleccione Horario</option>
                                                    @foreach ($schedules ?? [] as $schedule)
                                                        <option value="{{ $schedule->id }}">{{ $schedule->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-sm-12">
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-sm-12">
                                            <label for="isActiveLabel" class="form-label">Estado del Usuario</label>
                                            <div class="tom-select-custom">
                                                <select
                                                    class="js-select form-select @error('is_active') is-invalid @enderror"
                                                    name="is_active" id="isActiveLabel" autocomplete="off"
                                                    data-hs-tom-select-options='{ "placeholder": "Seleccione Estado..." }'>
                                                    <option value="1" selected>Activo – puede iniciar sesión</option>
                                                    <option value="0">Inactivo – no puede iniciar sesión</option>
                                                </select>
                                            </div>
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
                            <div id="addUserStepLocation" class="card card-lg" style="display: none;">
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <label for="countryIdLabel"
                                            class="col-sm-3 col-form-label form-label">País</label>
                                        <div class="col-sm-9">
                                            <div class="tom-select-custom">
                                                <select
                                                    class="js-select form-select @error('country_id') is-invalid @enderror"
                                                    name="country_id" id="countryIdLabel" autocomplete="off"
                                                    data-hs-tom-select-options='{ "placeholder": "Seleccione un país..." }'>
                                                    <option value="">Seleccione un país</option>
                                                    @foreach ($countries ?? [] as $country)
                                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="departmentIdLabel"
                                            class="col-sm-3 col-form-label form-label">Departamento</label>
                                        <div class="col-sm-9">
                                            <div class="tom-select-custom">
                                                <select
                                                    class="js-select form-select @error('department_id') is-invalid @enderror"
                                                    name="department_id" id="departmentIdLabel" autocomplete="off"
                                                    disabled
                                                    data-hs-tom-select-options='{ "placeholder": "Seleccione un departamento..." }'>
                                                    <option value="">Seleccione primero un país</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="municipalityIdLabel"
                                            class="col-sm-3 col-form-label form-label">Municipio</label>
                                        <div class="col-sm-9">
                                            <div class="tom-select-custom">
                                                <select
                                                    class="js-select form-select @error('municipality_id') is-invalid @enderror"
                                                    name="municipality_id" id="municipalityIdLabel" autocomplete="off"
                                                    disabled
                                                    data-hs-tom-select-options='{ "placeholder": "Seleccione un municipio..." }'>
                                                    <option value="">Seleccione primero un departamento</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="addressLabel" class="col-sm-3 col-form-label form-label">Dirección
                                            Exacta</label>
                                        <div class="col-sm-9">
                                            <input type="text"
                                                class="form-control @error('address') is-invalid @enderror"
                                                name="address" value="{{ old('address') }}" id="addressLabel"
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
                                            Siguiente paso <i class="bi-chevron-right"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div id="addUserStepConfirmation" class="card card-lg" style="display: none;">
                                <div class="profile-cover">
                                    <div class="profile-cover-img-wrapper">
                                        <img class="profile-cover-img" src="{{ asset('dist/img/1920x400/img1.jpg') }}"
                                            alt="Portada">
                                    </div>
                                </div>
                                <label class="avatar avatar-xxl avatar-circle avatar-border-lg profile-cover-avatar"
                                    for="avatarUploaderConfirm" data-bs-toggle="tooltip" data-bs-placement="right"
                                    title="Foto de perfil">
                                    <img id="confirmAvatarImg" class="avatar-img"
                                        src="{{ asset('dist/img/160x160/img1.jpg') }}" alt="Avatar">
                                    <span class="avatar-uploader-trigger">
                                        <i class="bi-pencil avatar-uploader-icon shadow-sm"></i>
                                    </span>
                                </label>
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
                                        <dt class="col-sm-6 text-sm-end">
                                            <hr class="my-2 w-100">
                                        </dt>
                                        <dd class="col-sm-6">
                                            <hr class="my-2 w-100">
                                        </dd>
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
                                        <dt class="col-sm-6 text-sm-end">
                                            <hr class="my-2 w-100">
                                        </dt>
                                        <dd class="col-sm-6">
                                            <hr class="my-2 w-100">
                                        </dd>
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
                            </div>
                            @if (session('success'))
                                <div id="successMessageContent">
                                    <div class="text-center">
                                        <img class="img-fluid mb-3"
                                            src="{{ asset('dist/svg/illustrations/oc-hi-five.svg') }}"
                                            alt="Image Description" data-hs-theme-appearance="default"
                                            style="max-width: 15rem;">
                                        <img class="img-fluid mb-3"
                                            src="{{ asset('dist/svg/illustrations-light/oc-hi-five.svg') }}"
                                            alt="Image Description" data-hs-theme-appearance="dark"
                                            style="max-width: 15rem;">
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
                                <script type="module">
                                    window.addEventListener('load', function() {
                                        document.getElementById("addUserStepFormProgress").style.display = 'none';
                                        document.getElementById("addUserStepFormContent").style.display = 'none';
                                    });
                                </script>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
@endsection
@push('scripts')
    <script type="module">
        (function() {
            window.onload = function() {
                const getVal = id => {
                    const e = document.getElementById(id);
                    return e ? e.value.trim() : '';
                };
                const getText = id => {
                    const e = document.getElementById(id);
                    if (!e) return '';
                    if (e.tomselect) {
                        const items = e.tomselect.getValue();
                        if (Array.isArray(items)) {
                            return items.map(val => {
                                const opt = e.tomselect.options[val];
                                return opt ? opt.text : val;
                            }).join(', ');
                        }
                        const selected = e.tomselect.getItem(items);
                        return selected ? selected.textContent.trim() : '';
                    }
                    return (e && e.options && e.selectedIndex >= 0 && e.value !== '') ? e.options[e
                        .selectedIndex].text : '';
                };
                const set = (id, val) => {
                    const el = document.getElementById(id);
                    if (el) el.textContent = val || '—';
                };

                function updateConfirmation() {
                    const fn = getVal('firstNameLabel');
                    const fln = getVal('firstLastNameLabel');
                    const fullName = [
                        fn,
                        getVal('secondNameLabel'),
                        getVal('thirdNameLabel'),
                        fln,
                        getVal('secondLastNameLabel'),
                        getVal('marriedLastNameLabel')
                    ].filter(Boolean).join(' ');

                    set('confirm-fullName', fullName);
                    set('confirm-cui', getVal('cuiLabel'));
                    set('confirm-nit', getVal('nitLabel'));
                    set('confirm-maritalStatus', getText('civilStatusLabel'));
                    set('confirm-birthDate', getVal('birthDateLabel'));
                    set('confirm-gender', getText('genderLabel'));
                    set('confirm-phone', getVal('phoneLabel'));
                    set('confirm-email', getVal('emailLabel'));
                    set('confirm-roleId', getText('roleIdLabel'));
                    set('confirm-unityExecution', getText('unityExecutionIdLabel'));
                    set('confirm-workDepartment', getText('workDepartmentIdLabel'));
                    set('confirm-specialty', getText('specialtyLabel'));
                    set('confirm-collegiateNumber', getVal('collegiateNumberLabel'));
                    set('confirm-scheduleId', getText('scheduleIdLabel'));
                    set('confirm-isActive', getText('isActiveLabel'));
                    set('confirm-countryId', getText('countryIdLabel'));
                    set('confirm-departmentId', getText('departmentIdLabel'));
                    set('confirm-municipalityId', getText('municipalityIdLabel'));
                    set('confirm-address', getVal('addressLabel'));

                    const src = document.getElementById('avatarImg')?.src;
                    const confirmImg = document.getElementById('confirmAvatarImg');
                    if (confirmImg && src) confirmImg.src = src;
                }
                new HSStepForm('.js-step-form', {
                    finish: () => document.querySelector('.js-step-form').submit(),
                    onNextStep: function() {
                        updateConfirmation();
                        scrollToTop();
                    },
                    onPrevStep: function() {
                        scrollToTop();
                    }
                });
                HSCore.components.HSTomSelect.init('.js-select')
                if (typeof HSFileAttach !== 'undefined') {
                    new HSFileAttach('.js-file-attach');
                }
                const avatarInput = document.getElementById('avatarUploader');
                const avatarImg = document.getElementById('avatarImg');
                if (avatarInput && avatarImg) {
                    avatarInput.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = ev => {
                                avatarImg.src = ev.target.result;
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                }

                function scrollToTop(el = '.js-step-form') {
                    const element = document.querySelector(el);
                    if (element) {
                        window.scrollTo({
                            top: (element.getBoundingClientRect().top + window.scrollY) - 30,
                            behavior: 'smooth'
                        });
                    }
                }
                const countrySelect = document.getElementById('countryIdLabel');
                const departmentSelect = document.getElementById('departmentIdLabel');
                const municipalitySelect = document.getElementById('municipalityIdLabel');

                function syncTS(select) {
                    const ts = select.tomselect;
                    if (ts) {
                        ts.sync();
                        ts.refreshOptions(false);
                    }
                }

                function resetSelect(select, placeholder) {
                    select.innerHTML = `<option value="">${placeholder}</option>`;
                    select.disabled = true;
                    select.value = '';

                    const ts = select.tomselect;
                    if (ts) {
                        ts.clearOptions();
                        ts.addOption({
                            value: '',
                            text: placeholder
                        });
                        ts.addItem('', true);
                        ts.sync();
                        ts.disable();
                        ts.refreshOptions(false);
                    }
                }

                function populateSelect(select, items, placeholder) {
                    select.innerHTML = `<option value="">${placeholder}</option>`;
                    items.forEach(item => {
                        const opt = document.createElement('option');
                        opt.value = item.id;
                        opt.textContent = item.name;
                        select.appendChild(opt);
                    });
                    select.disabled = false;

                    const ts = select.tomselect;
                    if (ts) {
                        ts.clearOptions();
                        ts.addOptions([{
                            value: '',
                            text: placeholder
                        }].concat(
                            items.map(i => ({
                                value: i.id,
                                text: i.name
                            }))
                        ));
                        ts.addItem('', true);
                        ts.sync();
                        ts.enable();
                        ts.refreshOptions(false);
                    }
                }

                if (countrySelect) {
                    countrySelect.addEventListener('change', function() {
                        const countryId = this.value;
                        resetSelect(departmentSelect, 'Seleccione primero un país');
                        resetSelect(municipalitySelect, 'Seleccione primero un departamento');
                        if (!countryId) return;

                        departmentSelect.innerHTML = '<option value="">Cargando...</option>';
                        syncTS(departmentSelect);

                        fetch(`{{ route('patients.get-departments-by-country') }}?country_id=${countryId}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        }).then(r => r.json()).then(data => {
                            data.length === 0 ?
                                resetSelect(departmentSelect, 'Sin departamentos disponibles') :
                                populateSelect(departmentSelect, data,
                                    'Seleccione un departamento');
                        }).catch(() => resetSelect(departmentSelect, 'Error al cargar'));
                    });
                }

                if (departmentSelect) {
                    departmentSelect.addEventListener('change', function() {
                        const deptId = this.value;
                        resetSelect(municipalitySelect, 'Seleccione primero un departamento');
                        if (!deptId) return;

                        municipalitySelect.innerHTML = '<option value="">Cargando...</option>';
                        syncTS(municipalitySelect);

                        fetch(`{{ route('patients.get-municipalities-by-department') }}?department_id=${deptId}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        }).then(r => r.json()).then(data => {
                            data.length === 0 ?
                                resetSelect(municipalitySelect, 'Sin municipios disponibles') :
                                populateSelect(municipalitySelect, data, 'Seleccione un municipio');
                        }).catch(() => resetSelect(municipalitySelect, 'Error al cargar'));
                    });
                }

            };
        })();
    </script>
@endpush
