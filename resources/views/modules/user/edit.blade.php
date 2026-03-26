@extends('layouts.panel')
@section('title', 'Editar Usuario')

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
                                <li class="breadcrumb-item active" aria-current="page">Editar</li>
                            </ol>
                        </nav>
                        <h1 class="page-header-title">Editar usuario: {{ $user->first_name }} {{ $user->first_last_name }}
                        </h1>
                    </div>

                    <div class="col-auto">
                        <a href="{{ route('users.index') }}" class="btn btn-primary">
                            <i class="bi-arrow-left"></i> Regresar
                        </a>
                    </div>
                </div>
            </div>
            <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data"
                class="js-step-form py-md-5"
                data-hs-step-form-options='{
              "progressSelector": "#addUserStepFormProgress",
              "stepsSelector": "#addUserStepFormContent",
              "endSelector": "#addUserFinishBtn",
              "isValidate": false
            }'>
                @csrf
                @method('PUT')
                <div class="row justify-content-lg-center">
                    <div class="col-lg-8">
                        <ul id="addUserStepFormProgress"
                            class="js-step-progress step step-sm step-icon-sm step step-inline step-item-between mb-3 mb-md-5">
                            <li class="step-item">
                                <a class="step-content-wrapper" href="javascript:;"
                                    data-hs-step-form-next-options='{ "targetSelector": "#addUserStepProfile" }'>
                                    <span class="step-icon step-icon-soft-dark">1</span>
                                    <div class="step-content">
                                        <span class="step-title">Perfil</span>
                                    </div>
                                </a>
                            </li>
                            <li class="step-item">
                                <a class="step-content-wrapper" href="javascript:;"
                                    data-hs-step-form-next-options='{ "targetSelector": "#addUserStepProfessional" }'>
                                    <span class="step-icon step-icon-soft-dark">2</span>
                                    <div class="step-content">
                                        <span class="step-title">Profesional</span>
                                    </div>
                                </a>
                            </li>
                            <li class="step-item">
                                <a class="step-content-wrapper" href="javascript:;"
                                    data-hs-step-form-next-options='{ "targetSelector": "#addUserStepLocation" }'>
                                    <span class="step-icon step-icon-soft-dark">3</span>
                                    <div class="step-content">
                                        <span class="step-title">Ubicación</span>
                                    </div>
                                </a>
                            </li>
                            <li class="step-item">
                                <a class="step-content-wrapper" href="javascript:;"
                                    data-hs-step-form-next-options='{ "targetSelector": "#addUserStepConfirmation" }'>
                                    <span class="step-icon step-icon-soft-dark">4</span>
                                    <div class="step-content">
                                        <span class="step-title">Confirmación</span>
                                    </div>
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
                                                        src="{{ $user->avatar_url ?? asset('img/160x160/img1.jpg') }}"
                                                        alt="Avatar">

                                                    <input type="file" name="profile_photo"
                                                        class="js-file-attach avatar-uploader-input" id="avatarUploader"
                                                        data-hs-file-attach-options='{
                                                    "textTarget": "#avatarImg",
                                                    "mode": "image",
                                                    "targetAttr": "src",
                                                    "resetTarget": ".js-file-attach-reset-img",
                                                    "resetImg": "{{ $user->avatar_url ?? asset('img/160x160/img1.jpg') }}",
                                                    "allowTypes": [".png", ".jpeg", ".jpg"]
                                                 }'>

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
                                        <div class="col-sm-4 mb-2">
                                            <label for="firstNameLabel" class="form-label">Primer Nombre</label>
                                            <input type="text" class="form-control" name="first_name" id="firstNameLabel"
                                                value="{{ old('first_name', $user->first_name) }}" placeholder="Ej. Juan">
                                        </div>
                                        <div class="col-sm-4 mb-2">
                                            <label for="secondNameLabel" class="form-label">Segundo Nombre</label>
                                            <input type="text" class="form-control" name="second_name"
                                                id="secondNameLabel" value="{{ old('second_name', $user->second_name) }}"
                                                placeholder="Ej. Carlos">
                                        </div>
                                        <div class="col-sm-4 mb-2">
                                            <label for="thirdNameLabel" class="form-label">Tercer Nombre</label>
                                            <input type="text" class="form-control" name="third_name"
                                                id="thirdNameLabel" value="{{ old('third_name', $user->third_name) }}"
                                                placeholder="Ej. Luis">
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-sm-4 mb-2">
                                            <label for="firstLastNameLabel" class="form-label">Primer Apellido</label>
                                            <input type="text" class="form-control" name="first_last_name"
                                                id="firstLastNameLabel"
                                                value="{{ old('first_last_name', $user->first_last_name) }}"
                                                placeholder="Ej. Pérez">
                                        </div>
                                        <div class="col-sm-4 mb-2">
                                            <label for="secondLastNameLabel" class="form-label">Segundo Apellido</label>
                                            <input type="text" class="form-control" name="second_last_name"
                                                id="secondLastNameLabel"
                                                value="{{ old('second_last_name', $user->second_last_name) }}"
                                                placeholder="Ej. Gómez">
                                        </div>
                                        <div class="col-sm-4 mb-2">
                                            <label for="marriedLastNameLabel" class="form-label">Apellido de
                                                Casada</label>
                                            <input type="text" class="form-control" name="married_last_name"
                                                id="marriedLastNameLabel"
                                                value="{{ old('married_last_name', $user->married_last_name) }}"
                                                placeholder="Ej. de López">
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-sm-4 mb-2">
                                            <label for="cuiLabel" class="form-label">CUI</label>
                                            <input type="text" class="form-control" name="cui" id="cuiLabel"
                                                value="{{ old('cui', $user->cui) }}" placeholder="CUI">
                                        </div>
                                        <div class="col-sm-4 mb-2">
                                            <label for="nitLabel" class="form-label">NIT</label>
                                            <input type="text" class="form-control" name="nit" id="nitLabel"
                                                value="{{ old('nit', $user->nit) }}" placeholder="NIT">
                                        </div>
                                        <div class="col-sm-4 mb-2">
                                            <label for="maritalStatusLabel" class="form-label">Estado Civil</label>
                                            <select class="form-select" name="marital_status" id="maritalStatusLabel">
                                                <option value="">Seleccione</option>
                                                <option value="soltero"
                                                    {{ old('marital_status', $user->marital_status) == 'soltero' ? 'selected' : '' }}>
                                                    Soltero(a)</option>
                                                <option value="casado"
                                                    {{ old('marital_status', $user->marital_status) == 'casado' ? 'selected' : '' }}>
                                                    Casado(a)</option>
                                                <option value="divorciado"
                                                    {{ old('marital_status', $user->marital_status) == 'divorciado' ? 'selected' : '' }}>
                                                    Divorciado(a)</option>
                                                <option value="viudo"
                                                    {{ old('marital_status', $user->marital_status) == 'viudo' ? 'selected' : '' }}>
                                                    Viudo(a)</option>
                                                <option value="union_libre"
                                                    {{ old('marital_status', $user->marital_status) == 'union_libre' ? 'selected' : '' }}>
                                                    Unión Libre</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-sm-4 mb-2">
                                            <label for="birthDateLabel" class="form-label">Fecha de Nacimiento</label>
                                            <input type="date" class="form-control" name="birth_date"
                                                id="birthDateLabel" value="{{ old('birth_date', $user->birth_date) }}">
                                        </div>
                                        <div class="col-sm-4 mb-2">
                                            <label for="genderLabel" class="form-label">Género</label>
                                            <select class="form-select" name="gender" id="genderLabel">
                                                <option value="">Seleccione</option>
                                                <option value="M"
                                                    {{ old('gender', $user->gender) == 'M' ? 'selected' : '' }}>Masculino
                                                </option>
                                                <option value="F"
                                                    {{ old('gender', $user->gender) == 'F' ? 'selected' : '' }}>Femenino
                                                </option>
                                                <option value="O"
                                                    {{ old('gender', $user->gender) == 'O' ? 'selected' : '' }}>Otro
                                                </option>
                                            </select>
                                        </div>
                                        <div class="col-sm-4 mb-2">
                                            <label for="phoneLabel" class="form-label">Teléfono</label>
                                            <input type="text" class="form-control" name="phone" id="phoneLabel"
                                                value="{{ old('phone', $user->phone) }}" placeholder="Ej. +12345678">
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-sm-6 mb-2">
                                            <label for="emailLabel" class="form-label">Correo Electrónico</label>
                                            <input type="email" class="form-control" name="email" id="emailLabel"
                                                value="{{ old('email', $user->email) }}"
                                                placeholder="correo@ejemplo.com">
                                        </div>
                                        <div class="col-sm-6 mb-2">
                                            <label for="passwordLabel" class="form-label">Contraseña <span
                                                    class="text-muted fw-normal">(Opcional)</span></label>
                                            <input type="password" class="form-control" name="password"
                                                id="passwordLabel" placeholder="Deja en blanco para no cambiar">
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
                            <div id="addUserStepProfessional" class="card card-lg" style="display: none;">
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <div class="col-sm-12 mb-4">
                                            <label for="roleIdLabel" class="form-label">Rol en el Sistema</label>
                                            <select class="js-select form-select" name="role_id" id="roleIdLabel"
                                                data-hs-tom-select-options='{"searchInDropdown": false, "hideSearch": true}'>
                                                <option value="">Seleccione Rol</option>
                                                @foreach ($roles ?? [] as $role)
                                                    <option value="{{ $role->id }}"
                                                        {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                                        {{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-6 mb-4">
                                            <label for="unityExecutionIdLabel" class="form-label">Unidad Ejecutora</label>
                                            <select class="form-select" name="unity_execution_id"
                                                id="unityExecutionIdLabel">
                                                <option value="">Seleccione Unidad</option>
                                                @foreach ($unityExecutions ?? [] as $unity)
                                                    <option value="{{ $unity->id }}"
                                                        {{ old('unity_execution_id', $user->unity_execution_id) == $unity->id ? 'selected' : '' }}>
                                                        {{ $unity->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-6 mb-4">
                                            <label for="workDepartmentIdLabel" class="form-label">Departamento de
                                                Trabajo</label>
                                            <select class="form-select" name="work_department_id"
                                                id="workDepartmentIdLabel">
                                                <option value="">Seleccione Departamento</option>
                                                @foreach ($workDepartments ?? [] as $wd)
                                                    <option value="{{ $wd->id }}"
                                                        {{ old('work_department_id', $user->work_department_id) == $wd->id ? 'selected' : '' }}>
                                                        {{ $wd->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-sm-6 mb-2">
                                            <label for="specialtyLabel" class="form-label">Especialidad (Si es
                                                Médico)</label>
                                            <select class="form-select" name="specialty_id" id="specialtyLabel">
                                                <option value="">Seleccione Especialidad</option>
                                                @foreach ($specialties ?? [] as $specialty)
                                                    <option value="{{ $specialty->id }}"
                                                        {{ old('specialty_id', $user->specialty_id) == $specialty->id ? 'selected' : '' }}>
                                                        {{ $specialty->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-6 mb-2">
                                            <label for="scheduleIdLabel" class="form-label">Asignar Horario de
                                                Trabajo</label>
                                            <select class="js-select form-select" name="schedule_id" id="scheduleIdLabel"
                                                data-hs-tom-select-options='{"searchInDropdown": false, "hideSearch": true}'>
                                                <option value="">Seleccione Horario</option>
                                                @foreach ($schedules ?? [] as $schedule)
                                                    <option value="{{ $schedule->id }}"
                                                        {{ old('schedule_id', $user->schedule_id) == $schedule->id ? 'selected' : '' }}>
                                                        {{ $schedule->name }}</option>
                                                @endforeach
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
                            <div id="addUserStepLocation" class="card card-lg" style="display: none;">
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <div class="col-sm-4 mb-2">
                                            <label for="countryIdLabel" class="form-label">País</label>
                                            <select class="form-select" name="country_id" id="countryIdLabel">
                                                <option value="">Seleccione País</option>
                                                @foreach ($countries ?? [] as $country)
                                                    <option value="{{ $country->id }}"
                                                        {{ old('country_id', $user->country_id) == $country->id ? 'selected' : '' }}>
                                                        {{ $country->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-4 mb-2">
                                            <label for="departmentIdLabel" class="form-label">Departamento</label>
                                            <select class="form-select" name="department_id" id="departmentIdLabel">
                                                <option value="">Seleccione Departamento</option>
                                                @foreach ($departments ?? [] as $department)
                                                    <option value="{{ $department->id }}"
                                                        {{ old('department_id', $user->department_id) == $department->id ? 'selected' : '' }}>
                                                        {{ $department->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-4 mb-2">
                                            <label for="municipalityIdLabel" class="form-label">Municipio</label>
                                            <select class="form-select" name="municipality_id" id="municipalityIdLabel">
                                                <option value="">Seleccione Municipio</option>
                                                @foreach ($municipalities ?? [] as $municipality)
                                                    <option value="{{ $municipality->id }}"
                                                        {{ old('municipality_id', $user->municipality_id) == $municipality->id ? 'selected' : '' }}>
                                                        {{ $municipality->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-sm-12">
                                            <label for="addressLabel" class="form-label">Dirección Exacta</label>
                                            <input type="text" class="form-control" name="address" id="addressLabel"
                                                value="{{ old('address', $user->address) }}"
                                                placeholder="Ej. 1ra Avenida 2-33 Zona 1">
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
                            <div id="addUserStepConfirmation" class="card card-lg" style="display: none;">
                                <div class="card-body">
                                    <h3 class="card-title text-center mb-5">Por favor, confirma que la información es
                                        correcta antes de guardar.</h3>

                                    <h5 class="card-title">Información del Perfil</h5>
                                    <dl class="row mb-5">
                                        <dt class="col-sm-6 text-sm-end">Nombre Completo:</dt>
                                        <dd class="col-sm-6" id="confirm-fullName">-</dd>

                                        <dt class="col-sm-6 text-sm-end">CUI:</dt>
                                        <dd class="col-sm-6" id="confirm-cui">-</dd>

                                        <dt class="col-sm-6 text-sm-end">NIT:</dt>
                                        <dd class="col-sm-6" id="confirm-nit">-</dd>

                                        <dt class="col-sm-6 text-sm-end">Estado Civil:</dt>
                                        <dd class="col-sm-6" id="confirm-maritalStatus">-</dd>

                                        <dt class="col-sm-6 text-sm-end">Fecha de Nacimiento:</dt>
                                        <dd class="col-sm-6" id="confirm-birthDate">-</dd>

                                        <dt class="col-sm-6 text-sm-end">Género:</dt>
                                        <dd class="col-sm-6" id="confirm-gender">-</dd>

                                        <dt class="col-sm-6 text-sm-end">Teléfono:</dt>
                                        <dd class="col-sm-6" id="confirm-phone">-</dd>

                                        <dt class="col-sm-6 text-sm-end">Correo Electrónico:</dt>
                                        <dd class="col-sm-6" id="confirm-email">-</dd>
                                    </dl>
                                    <hr>

                                    <h5 class="card-title">Información Profesional</h5>
                                    <dl class="row mb-5">
                                        <dt class="col-sm-6 text-sm-end">Rol en el Sistema:</dt>
                                        <dd class="col-sm-6" id="confirm-roleId">-</dd>

                                        <dt class="col-sm-6 text-sm-end">Especialidad:</dt>
                                        <dd class="col-sm-6" id="confirm-specialty">-</dd>

                                        <dt class="col-sm-6 text-sm-end">Horario Asignado:</dt>
                                        <dd class="col-sm-6" id="confirm-scheduleId">-</dd>
                                    </dl>
                                    <hr>

                                    <h5 class="card-title">Ubicación</h5>
                                    <dl class="row">
                                        <dt class="col-sm-6 text-sm-end">País:</dt>
                                        <dd class="col-sm-6" id="confirm-countryId">-</dd>

                                        <dt class="col-sm-6 text-sm-end">Departamento:</dt>
                                        <dd class="col-sm-6" id="confirm-departmentId">-</dd>

                                        <dt class="col-sm-6 text-sm-end">Municipio:</dt>
                                        <dd class="col-sm-6" id="confirm-municipalityId">-</dd>

                                        <dt class="col-sm-6 text-sm-end">Dirección:</dt>
                                        <dd class="col-sm-6" id="confirm-address">-</dd>
                                    </dl>
                                </div>
                                <div class="card-footer d-sm-flex align-items-sm-center">
                                    <button type="button" class="btn btn-ghost-secondary mb-2 mb-sm-0"
                                        data-hs-step-form-prev-options='{ "targetSelector": "#addUserStepLocation" }'>
                                        <i class="bi-chevron-left"></i> Paso anterior
                                    </button>
                                    <div class="ms-auto">
                                        <button id="addUserFinishBtn" type="button" class="btn btn-primary">Actualizar
                                            usuario</button>
                                    </div>
                                </div>
                            </div>
                            @if (session('success'))
                                <div id="successMessageContent">
                                    <div class="text-center">
                                        <img class="img-fluid mb-3" src="{{ asset('svg/illustrations/oc-hi-five.svg') }}"
                                            alt="Image Description" data-hs-theme-appearance="default"
                                            style="max-width: 15rem;">
                                        <img class="img-fluid mb-3"
                                            src="{{ asset('svg/illustrations-light/oc-hi-five.svg') }}"
                                            alt="Image Description" data-hs-theme-appearance="dark"
                                            style="max-width: 15rem;">
                                        <div class="mb-4">
                                            <h2>¡Usuario Actualizado!</h2>
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
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/hs-step-form/dist/hs-step-form.min.js') }}"></script>
    <script src="{{ asset('vendor/hs-file-attach/dist/hs-file-attach.min.js') }}"></script>
    <script src="{{ asset('vendor/tom-select/dist/js/tom-select.complete.min.js') }}"></script>
    <script>
        (function() {
            window.onload = function() {
                // Función para actualizar datos en confirmación
                function updateConfirmation() {
                    let fullName = [
                        document.getElementById('firstNameLabel')?.value,
                        document.getElementById('secondNameLabel')?.value,
                        document.getElementById('thirdNameLabel')?.value,
                        document.getElementById('firstLastNameLabel')?.value,
                        document.getElementById('secondLastNameLabel')?.value,
                        document.getElementById('marriedLastNameLabel')?.value
                    ].filter(Boolean).join(' ');

                    document.getElementById('confirm-fullName').textContent = fullName || '-';

                    var fieldIds = [
                        'cui', 'nit', 'maritalStatus', 'birthDate', 'gender', 'phone', 'email',
                        'roleId', 'specialty', 'scheduleId', 'countryId', 'departmentId', 'municipalityId',
                        'address'
                    ];

                    fieldIds.forEach(function(field) {
                        let el = document.getElementById(field + 'Label');
                        let displayEl = document.getElementById('confirm-' + field);

                        if (el && displayEl) {
                            if (el.tagName === 'SELECT') {
                                displayEl.textContent = el.options[el.selectedIndex]?.text || '-';
                            } else {
                                displayEl.textContent = el.value || '-';
                            }
                        }
                    });
                }

                // INITIALIZATION OF STEP FORM
                new HSStepForm('.js-step-form', {
                    finish: () => {
                        const form = document.querySelector('.js-step-form');
                        form.submit();
                    },
                    onNextStep: function() {
                        updateConfirmation();
                        scrollToTop();
                    },
                    onPrevStep: function() {
                        scrollToTop();
                    }
                });

                if (typeof HSCore !== 'undefined' && HSCore.components.HSTomSelect) {
                    HSCore.components.HSTomSelect.init('.js-select');
                }

                // INITIALIZATION OF FILE ATTACH
                $('.js-file-attach').each(function() {
                    new HSFileAttach($(this)).init();
                });

                // PREVISUALIZACIÓN DEL AVATAR (Fallback)
                const avatarInput = document.getElementById('avatarUploader');
                const avatarImg = document.getElementById('avatarImg');

                if (avatarInput && avatarImg) {
                    avatarInput.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(event) {
                                avatarImg.src = event.target.result;
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                }

                function scrollToTop(el = '.js-step-form') {
                    var element = document.querySelector(el);
                    if (element) {
                        window.scrollTo({
                            top: (element.getBoundingClientRect().top + window.scrollY) - 30,
                            left: 0,
                            behavior: 'smooth'
                        });
                    }
                }

                // =====================================================
                // CASCADING DROPDOWNS: País → Departamento → Municipio
                // =====================================================
                const countrySelect = document.getElementById('countryIdLabel');
                const departmentSelect = document.getElementById('departmentIdLabel');
                const municipalitySelect = document.getElementById('municipalityIdLabel');

                function resetSelect(select, placeholder) {
                    select.innerHTML = `<option value="">${placeholder}</option>`;
                    select.disabled = true;
                    // select.value = ''; // No reset value here to keep initial values if needed
                }

                function populateSelect(select, items, placeholder, selectedId = null) {
                    select.innerHTML = `<option value="">${placeholder}</option>`;
                    items.forEach(item => {
                        const opt = document.createElement('option');
                        opt.value = item.id;
                        opt.textContent = item.name;
                        if (selectedId && item.id == selectedId) {
                            opt.selected = true;
                        }
                        select.appendChild(opt);
                    });
                    select.disabled = false;
                }

                if (countrySelect) {
                    countrySelect.addEventListener('change', function() {
                        const countryId = this.value;

                        // Reset downstream
                        resetSelect(departmentSelect, 'Seleccione primero un país');
                        resetSelect(municipalitySelect, 'Seleccione primero un departamento');

                        if (!countryId) return;

                        departmentSelect.innerHTML = '<option value="">Cargando...</option>';

                        fetch(`{{ route('patients.get-departments-by-country') }}?country_id=${countryId}`, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(r => r.json())
                            .then(data => {
                                if (data.length === 0) {
                                    resetSelect(departmentSelect, 'Sin departamentos disponibles');
                                } else {
                                    populateSelect(departmentSelect, data,
                                        'Seleccione un departamento');
                                }
                            })
                            .catch(() => resetSelect(departmentSelect, 'Error al cargar'));
                    });
                }

                if (departmentSelect) {
                    departmentSelect.addEventListener('change', function() {
                        const deptId = this.value;

                        // Reset downstream
                        resetSelect(municipalitySelect, 'Seleccione primero un departamento');

                        if (!deptId) return;

                        municipalitySelect.innerHTML = '<option value="">Cargando...</option>';

                        fetch(`{{ route('patients.get-municipalities-by-department') }}?department_id=${deptId}`, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(r => r.json())
                            .then(data => {
                                if (data.length === 0) {
                                    resetSelect(municipalitySelect, 'Sin municipios disponibles');
                                } else {
                                    populateSelect(municipalitySelect, data, 'Seleccione un municipio');
                                }
                            })
                            .catch(() => resetSelect(municipalitySelect, 'Error al cargar'));
                    });
                }

                // FILTRADO INICIAL (Para Editar)
                if (countrySelect && countrySelect.value) {
                    // Si ya hay un país seleccionado, filtramos departamentos pero manteniendo el actual seleccionado
                    const initialCountryId = countrySelect.value;
                    const initialDeptId = departmentSelect.value;
                    const initialMuniId = municipalitySelect.value;

                    if (initialCountryId) {
                         fetch(`{{ route('patients.get-departments-by-country') }}?country_id=${initialCountryId}`, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                        })
                        .then(r => r.json())
                        .then(data => {
                            if (data.length > 0) {
                                populateSelect(departmentSelect, data, 'Seleccione un departamento', initialDeptId);
                                
                                // Una vez cargados los departamentos, si hay uno seleccionado, cargamos municipios
                                if (initialDeptId) {
                                    fetch(`{{ route('patients.get-municipalities-by-department') }}?department_id=${initialDeptId}`, {
                                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                                    })
                                    .then(r => r.json())
                                    .then(muniData => {
                                        if (muniData.length > 0) {
                                            populateSelect(municipalitySelect, muniData, 'Seleccione un municipio', initialMuniId);
                                        }
                                    });
                                }
                            }
                        });
                    }
                }
            }
        })();
    </script>
@endpush
