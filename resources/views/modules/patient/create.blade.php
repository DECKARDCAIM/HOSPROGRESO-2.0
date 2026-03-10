@extends('layouts.panel')
@section('title', 'Crear Paciente')

@section('content')
    <main id="content" role="main" class="main">
        <div class="content container-fluid">

            <form id="addPatientForm" action="{{ route('patients.store') }}" method="POST" class="js-step-form py-md-5"
                data-hs-step-form-options='{"progressSelector": "#addUserStepFormProgress","stepsSelector": "#addUserStepFormContent","endSelector": "#addUserFinishBtn","isValidate": false}'>
                @csrf
                <div class="row justify-content-lg-center">
                    <div class="col-lg-8">

                        <ul id="addUserStepFormProgress"
                            class="js-step-progress step step-sm step-icon-sm step step-inline step-item-between mb-3 mb-md-5">
                            <li class="step-item">
                                <a class="step-content-wrapper" href="javascript:;"
                                    data-hs-step-form-next-options='{"targetSelector": "#addUserStepProfile"}'>
                                    <span class="step-icon step-icon-soft-dark">1</span>
                                    <div class="step-content">
                                        <span class="step-title">Datos Generales</span>
                                    </div>
                                </a>
                            </li>

                            <li class="step-item">
                                <a class="step-content-wrapper" href="javascript:;"
                                    data-hs-step-form-next-options='{"targetSelector": "#addUserStepBillingAddress"}'>
                                    <span class="step-icon step-icon-soft-dark">2</span>
                                    <div class="step-content">
                                        <span class="step-title">Ubicación</span>
                                    </div>
                                </a>
                            </li>

                            <li class="step-item">
                                <a class="step-content-wrapper" href="javascript:;"
                                    data-hs-step-form-next-options='{"targetSelector": "#addUserStepFamily"}'>
                                    <span class="step-icon step-icon-soft-dark">3</span>
                                    <div class="step-content">
                                        <span class="step-title">Datos de Familiares</span>
                                    </div>
                                </a>
                            </li>

                            <li class="step-item">
                                <a class="step-content-wrapper" href="javascript:;"
                                    data-hs-step-form-next-options='{"targetSelector": "#addUserStepConfirmation"}'>
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
                                        <label class="col-sm-3 col-form-label form-label">Expediente Clínico <i
                                                class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="El número de expediente clínico será generado automáticamente al guardar"></i></label>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-sm-vertical">
                                                <input type="text" class="form-control bg-light"
                                                    value="Generado automáticamente al guardar (EXP-AÑO-MES-CORRELATIVO)"
                                                    readonly disabled>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-sm-12">
                                            <hr>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label for="firstNameLabel" class="col-sm-3 col-form-label form-label">Nombres <i
                                                class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="Rellene los nombres del paciente"></i></label>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-sm-vertical">
                                                <input type="text" class="form-control" name="first_name"
                                                    id="firstNameLabel" placeholder="Primer nombre"
                                                    aria-label="Primer nombre" required>
                                                <input type="text" class="form-control" name="second_name"
                                                    id="secondNameLabel" placeholder="Segundo nombre"
                                                    aria-label="Segundo nombre">
                                                <input type="text" class="form-control" name="third_name"
                                                    id="thirdNameLabel" placeholder="Tercer nombre"
                                                    aria-label="Tercer nombre">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label for="firstLastNameLabel" class="col-sm-3 col-form-label form-label">Apellidos
                                            <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="Rellene los apellidos del paciente"></i></label>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-sm-vertical">
                                                <input type="text" class="form-control" name="first_last_name"
                                                    id="firstLastNameLabel" placeholder="Primer apellido"
                                                    aria-label="Primer apellido" required>
                                                <input type="text" class="form-control" name="second_last_name"
                                                    id="secondLastNameLabel" placeholder="Segundo apellido"
                                                    aria-label="Segundo apellido">
                                                <input type="text" class="form-control" name="married_last_name"
                                                    id="marriedLastNameLabel" placeholder="Apellido de casada"
                                                    aria-label="Apellido de casada">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-12">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="dpiLabel" class="col-sm-3 col-form-label form-label">DPI <i
                                                        class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="Rellene el DPI del paciente"></i></label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="js-input-mask form-control"
                                                        name="dpi" id="dpiLabel" placeholder="0000 00000 0000"
                                                        aria-label="DPI"
                                                        data-hs-mask-options='{"mask": "0000 00000 0000"}'>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="birthDateLabel"
                                                    class="col-sm-6 col-form-label form-label">Fecha
                                                    de
                                                    nacimiento</label>
                                                <div class="col-sm-9">
                                                    <input type="date" class="form-control" name="birth_date"
                                                        id="birthDateLabel" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-sm-12">
                                            <hr>
                                        </div>
                                    </div>


                                    <div class="row mb-12">
                                        <div class="row mb-3">

                                            <div class="col-md-6">
                                                <label for="emailLabel" class="col-sm-9 col-form-label form-label">Correo
                                                    electrónico <span
                                                        class="form-label-secondary">(Opcional)</span></label>
                                                <div class="col-sm-9">
                                                    <input type="email" class="form-control" name="email"
                                                        id="emailLabel" placeholder="ejemplo@correo.com"
                                                        aria-label="ejemplo@correo.com">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="phoneLabel"
                                                    class="col-sm-9 col-form-label form-label">Teléfono
                                                    <span class="form-label-secondary">(Opcional)</span></label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="js-input-mask form-control"
                                                        name="phone" id="phoneLabel" placeholder="00000000"
                                                        aria-label="00000000" data-hs-mask-options='{"mask": "00000000"}'>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-sm-12">
                                            <hr>
                                        </div>
                                    </div>

                                    <div class="row mb-12">

                                        <div class="row mb-3">

                                            <div class="col-md-6">
                                                <label for="genderLabel" class="form-label">
                                                    Género
                                                </label>
                                                <select class="form-select form-select-sm" name="gender_id"
                                                    id="genderLabel">
                                                    <option value="">Seleccione</option>
                                                    @foreach ($genders as $gender)
                                                        <option value="{{ $gender->id }}">{{ $gender->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="civilStatusLabel" class="form-label">
                                                    Estado civil
                                                </label>
                                                <select class="form-select form-select-sm" name="civil_status_id"
                                                    id="civilStatusLabel">
                                                    <option value="">Seleccione</option>
                                                    @foreach ($civilStatuses as $status)
                                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>

                                        <div class="row mb-3">

                                            <div class="col-md-6">
                                                <label for="ethnicityLabel" class="form-label">
                                                    Etnia
                                                </label>
                                                <select class="form-select form-select-sm" name="ethnicity_id"
                                                    id="ethnicityLabel">
                                                    <option value="">Seleccione</option>
                                                    @foreach ($ethnicities as $ethnicity)
                                                        <option value="{{ $ethnicity->id }}">{{ $ethnicity->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="linguisticCommunityLabel" class="form-label">
                                                    Comunidad lingüística
                                                </label>
                                                <select class="form-select form-select-sm" name="linguistic_community_id"
                                                    id="linguisticCommunityLabel">
                                                    <option value="">Seleccione</option>
                                                    @foreach ($linguisticCommunities as $community)
                                                        <option value="{{ $community->id }}">{{ $community->name }}
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

                                        <div class="row mb-3">

                                            <div class="col-md-6">
                                                <label for="educationLabel" class="form-label">
                                                    Escolaridad <span class="form-label-secondary">(Opcional)</span>
                                                </label>
                                                <select class="form-select form-select-sm" name="education"
                                                    id="educationLabel">
                                                    <option value="">Seleccione</option>
                                                    <option value="ninguna">Ninguna</option>
                                                    <option value="primaria">Primaria</option>
                                                    <option value="basico">Básico</option>
                                                    <option value="diversificado">Diversificado</option>
                                                    <option value="universitario">Universitario</option>
                                                    <option value="postgrado">Postgrado</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="occupationLabel" class="form-label">
                                                    Ocupación <span class="form-label-secondary">(Opcional)</span>
                                                </label>
                                                <input type="text" class="form-control form-control-sm"
                                                    name="occupation" id="occupationLabel"
                                                    placeholder="Ej: Agricultor, Estudiante, Ama de casa...">
                                            </div>

                                        </div>

                                    </div>

                                    <div class="card-footer d-flex justify-content-end align-items-center">
                                        <button type="button" class="btn btn-primary"
                                            data-hs-step-form-next-options='{ "targetSelector": "#addUserStepBillingAddress" }'>
                                            Siguiente <i class="bi-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>


                            <!-- Step 2 -->
                            <div id="addUserStepBillingAddress" class="card card-lg" style="display: none;">
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <label for="countryLabel" class="col-sm-3 col-form-label form-label">País</label>
                                        <div class="col-sm-9">
                                            <select class="form-select" name="country_id" id="countryLabel">
                                                <option value="">Seleccione un país</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->id }}">{{ $country->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label for="departmentLabel"
                                            class="col-sm-3 col-form-label form-label">Departamento</label>
                                        <div class="col-sm-9">
                                            <select class="form-select" name="department_id" id="departmentLabel"
                                                disabled>
                                                <option value="">Seleccione primero un país</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label for="municipalityLabel"
                                            class="col-sm-3 col-form-label form-label">Municipio</label>
                                        <div class="col-sm-9">
                                            <select class="form-select" name="municipality_id" id="municipalityLabel"
                                                disabled>
                                                <option value="">Seleccione primero un departamento</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label for="placeLabel" class="col-sm-3 col-form-label form-label">
                                            Dirección</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="place" id="placeLabel"
                                                placeholder="Dirección exacta" aria-label="Dirección exacta">
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer d-flex align-items-center">
                                    <button type="button" class="btn btn-ghost-secondary"
                                        data-hs-step-form-prev-options='{
                       "targetSelector": "#addUserStepProfile"
                     }'>
                                        <i class="bi-chevron-left"></i> Anterior
                                    </button>

                                    <div class="ms-auto">
                                        <button type="button" class="btn btn-primary"
                                            data-hs-step-form-next-options='{
                              "targetSelector": "#addUserStepFamily"
                            }'>
                                            Siguiente <i class="bi-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div id="addUserStepFamily" class="card card-lg" style="display: none;">
                                <div class="card-body">
                                    <h4 class="card-header-title mb-1">Familiares</h4>
                                    <p class="text-muted small mb-4">Busca un familiar por nombre o DPI. Si no
                                        existe,
                                        puedes crearlo desde aquí.</p>

                                    {{-- SEARCH BAR --}}
                                    <div class="position-relative mb-4">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi-search"></i></span>
                                            <input type="text" id="relativeSearchInput" class="form-control"
                                                placeholder="Buscar por nombre o DPI..." autocomplete="off">
                                            <button type="button" class="btn btn-outline-primary" id="btnNewRelative">
                                                <i class="bi-person-plus me-1"></i> Crear nuevo
                                            </button>
                                        </div>
                                        {{-- SEARCH RESULTS DROPDOWN --}}
                                        <div id="relativeSearchResults"
                                            class="list-group position-absolute w-100 shadow-sm z-index-3"
                                            style="display:none; top:100%; z-index:999; max-height:220px; overflow-y:auto; background-color: var(--bs-card-bg); border: 1px solid var(--bs-border-color);">
                                        </div>
                                    </div>

                                    {{-- INLINE CREATE FORM (hidden by default) --}}
                                    <div id="relativeCreateForm" class="card card-bordered border-primary mb-4"
                                        style="display:none;">
                                        <div
                                            class="card-header bg-soft-primary d-flex justify-content-between align-items-center py-2">
                                            <span class="fw-semibold text-primary"><i
                                                    class="bi-person-plus me-2"></i>Crear nuevo familiar</span>
                                            <button type="button" class="btn btn-xs btn-ghost-secondary"
                                                id="btnCancelCreate"><i class="bi-x-lg"></i></button>
                                        </div>
                                        <div class="card-body p-3">
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <label class="form-label form-label-sm">Primer Nombre *</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="newRelFirstName" placeholder="Primer nombre">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label form-label-sm">Segundo Nombre</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="newRelSecondName" placeholder="Segundo nombre">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label form-label-sm">Primer Apellido
                                                        *</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="newRelFirstLastName" placeholder="Primer apellido">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label form-label-sm">Segundo
                                                        Apellido</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="newRelSecondLastName" placeholder="Segundo apellido">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label form-label-sm">Apellido de
                                                        casada</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="newRelMarriedLastName" placeholder="Apellido de casada">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label form-label-sm">DPI</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm js-input-mask" id="newRelDpi"
                                                        placeholder="0000 00000 0000"
                                                        data-hs-mask-options='{"mask": "0000 00000 0000"}'>
                                                </div>
                                                <div class="col-md-6 d-flex align-items-end">
                                                    <button type="button" class="btn btn-primary btn-sm w-100"
                                                        id="btnSaveNewRelative">
                                                        <i class="bi-check2 me-1"></i> Guardar y seleccionar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- SELECTED RELATIVES LIST --}}
                                    <div id="selectedRelativesContainer">
                                        <div class="text-center py-4" id="noRelativesData">
                                            <i class="bi-people display-4 text-muted"></i>
                                            <p class="text-muted mt-2 mb-0 small">Aún no has agregado familiares.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer d-flex align-items-center">
                                    <button type="button" class="btn btn-ghost-secondary"
                                        data-hs-step-form-prev-options='{
                       "targetSelector": "#addUserStepBillingAddress"
                     }'>
                                        <i class="bi-chevron-left"></i> Anterior
                                    </button>
                                    <div class="ms-auto">
                                        <button type="button" class="btn btn-primary"
                                            data-hs-step-form-next-options='{
                              "targetSelector": "#addUserStepConfirmation"
                            }'>
                                            Siguiente <i class="bi-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Step 4 -->
                            <div id="addUserStepConfirmation" class="card card-lg" style="display:none;">

                                <!-- Avatar y nombre -->
                                <div class="card-header d-flex align-items-center gap-3 py-4 border-bottom">
                                    <div id="confirmAvatarCircle" class="avatar avatar-xl avatar-circle flex-shrink-0"
                                        style="background: linear-gradient(135deg,#0d6efd,#6610f2);">
                                        <span id="confirmAvatarInitials" class="avatar-initials text-white fs-3">?</span>
                                    </div>
                                    <div>
                                        <h5 id="confirmFullName" class="mb-0 fw-bold">—</h5>
                                        <small id="confirmExpCard" class="text-muted">El número de expediente se generará
                                            al guardar</small>
                                    </div>
                                </div>

                                <div class="card-body">

                                    <!-- Datos personales -->
                                    <h6 class="text-uppercase text-muted mb-3">Datos personales</h6>

                                    <dl class="row small mb-4">

                                        <dt class="col-sm-4 text-sm-end text-muted">Primer nombre</dt>
                                        <dd class="col-sm-8"><span id="confirmFirstName">—</span></dd>

                                        <dt class="col-sm-4 text-sm-end text-muted">Segundo nombre</dt>
                                        <dd class="col-sm-8"><span id="confirmSecondName">—</span></dd>

                                        <dt class="col-sm-4 text-sm-end text-muted">Tercer nombre</dt>
                                        <dd class="col-sm-8"><span id="confirmThirdName">—</span></dd>

                                        <dt class="col-sm-4 text-sm-end text-muted">Primer apellido</dt>
                                        <dd class="col-sm-8"><span id="confirmFirstLastName">—</span></dd>

                                        <dt class="col-sm-4 text-sm-end text-muted">Segundo apellido</dt>
                                        <dd class="col-sm-8"><span id="confirmSecondLastName">—</span></dd>

                                        <dt class="col-sm-4 text-sm-end text-muted">Apellido de casada</dt>
                                        <dd class="col-sm-8"><span id="confirmMarriedLastName">—</span></dd>

                                    </dl>

                                    <!-- Contacto -->
                                    <h6 class="text-uppercase text-muted mb-3">Información de contacto</h6>

                                    <dl class="row small mb-4">

                                        <dt class="col-sm-4 text-sm-end text-muted">Correo electrónico</dt>
                                        <dd class="col-sm-8"><span id="confirmEmail">—</span></dd>

                                        <dt class="col-sm-4 text-sm-end text-muted">Teléfono</dt>
                                        <dd class="col-sm-8"><span id="confirmPhone">—</span></dd>

                                        <dt class="col-sm-4 text-sm-end text-muted">DPI</dt>
                                        <dd class="col-sm-8"><span id="confirmDpi">—</span></dd>

                                    </dl>

                                    <!-- Información personal -->
                                    <h6 class="text-uppercase text-muted mb-3">Información personal</h6>

                                    <dl class="row small mb-4">

                                        <dt class="col-sm-4 text-sm-end text-muted">Fecha de nacimiento</dt>
                                        <dd class="col-sm-8"><span id="confirmBirthDate">—</span></dd>

                                        <dt class="col-sm-4 text-sm-end text-muted">Género</dt>
                                        <dd class="col-sm-8"><span id="confirmGender">—</span></dd>

                                        <dt class="col-sm-4 text-sm-end text-muted">Estado civil</dt>
                                        <dd class="col-sm-8"><span id="confirmCivilStatus">—</span></dd>

                                        <dt class="col-sm-4 text-sm-end text-muted">Escolaridad</dt>
                                        <dd class="col-sm-8"><span id="confirmEducation">—</span></dd>

                                        <dt class="col-sm-4 text-sm-end text-muted">Ocupación</dt>
                                        <dd class="col-sm-8"><span id="confirmOccupation">—</span></dd>

                                    </dl>

                                    <!-- Ubicación -->
                                    <h6 class="text-uppercase text-muted mb-3">Ubicación</h6>

                                    <dl class="row small">

                                        <dt class="col-sm-4 text-sm-end text-muted">País</dt>
                                        <dd class="col-sm-8"><span id="confirmCountry">—</span></dd>

                                        <dt class="col-sm-4 text-sm-end text-muted">Departamento</dt>
                                        <dd class="col-sm-8"><span id="confirmDepartment">—</span></dd>

                                        <dt class="col-sm-4 text-sm-end text-muted">Municipio</dt>
                                        <dd class="col-sm-8"><span id="confirmMunicipality">—</span></dd>

                                        <dt class="col-sm-4 text-sm-end text-muted">Dirección</dt>
                                        <dd class="col-sm-8"><span id="confirmPlace">—</span></dd>

                                    </dl>

                                </div>

                                <div class="card-footer d-sm-flex align-items-sm-center">

                                    <button type="button" class="btn btn-ghost-secondary mb-2 mb-sm-0"
                                        data-hs-step-form-prev-options='{
                "targetSelector": "#addUserStepFamily"
            }'>
                                        <i class="bi-chevron-left"></i> Anterior
                                    </button>

                                    <div class="ms-auto">
                                        <button id="addUserFinishBtn" type="button" class="btn btn-primary"
                                            onclick="document.getElementById('addPatientForm').submit();">
                                            <i class="bi-check-lg me-1"></i> Guardar paciente
                                        </button>
                                    </div>

                                </div>

                            </div>

                            <div id="successMessageContent" style="display:none;">
                                <div class="text-center">
                                    <img class="img-fluid mb-3" src="{{ asset('svg/illustrations/oc-hi-five.svg') }}"
                                        alt="Image Description" data-hs-theme-appearance="default"
                                        style="max-width: 15rem;">
                                    <img class="img-fluid mb-3"
                                        src="{{ asset('svg/illustrations-light/oc-hi-five.svg') }}"
                                        alt="Image Description" data-hs-theme-appearance="dark"
                                        style="max-width: 15rem;">

                                    <div class="mb-4">
                                        <h2>¡Exitoso!</h2>
                                        <p>El paciente ha sido creado exitosamente y guardado en la base de datos.</p>
                                    </div>

                                    <div class="d-flex justify-content-center">
                                        <a class="btn btn-white me-3" href="{{ route('patients.index') }}">
                                            <i class="bi-chevron-left ms-1"></i> Volver a pacientes
                                        </a>
                                        <a class="btn btn-primary" href="{{ route('patients.create') }}">
                                            <i class="bi-person-plus-fill me-1"></i> Agregar nuevo paciente
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/hs-file-attach/dist/hs-file-attach.min.js') }}"></script>
    <script src="{{ asset('vendor/hs-step-form/dist/hs-step-form.min.js') }}"></script>
    <script src="{{ asset('vendor/hs-add-field/dist/hs-add-field.min.js') }}"></script>
    <script src="{{ asset('vendor/imask/dist/imask.min.js') }}"></script>
    <script src="{{ asset('vendor/tom-select/dist/js/tom-select.complete.min.js') }}"></script>

    <!-- JS Plugins Init. -->
    <script>
        (function() {
            window.onload = function() {


                // INITIALIZATION OF NAVBAR VERTICAL ASIDE
                // =======================================================
                new HSSideNav('.js-navbar-vertical-aside').init()


                // INITIALIZATION OF FORM SEARCH
                // =======================================================
                new HSFormSearch('.js-form-search')


                // INITIALIZATION OF BOOTSTRAP DROPDOWN
                // =======================================================
                HSBsDropdown.init()


                // INITIALIZATION OF FILE ATTACH
                // =======================================================
                new HSFileAttach('.js-file-attach')


                // INITIALIZATION OF STEP FORM
                // =======================================================
                new HSStepForm('.js-step-form', {
                    finish: () => {
                        document.getElementById("addUserStepFormProgress").style.display = 'none'
                        document.getElementById("addUserStepProfile").style.display = 'none'
                        document.getElementById("addUserStepBillingAddress").style.display = 'none'
                        document.getElementById("addUserStepConfirmation").style.display = 'none'
                        document.getElementById("successMessageContent").style.display = 'block'
                        scrollToTop('#header');
                        const formContainer = document.getElementById('formContainer')
                    },
                    onNextStep: function() {
                        scrollToTop();
                        updateConfirmationStep();
                    },
                    onPrevStep: function() {
                        scrollToTop()
                    }
                })

                function scrollToTop(el = '.js-step-form') {
                    el = document.querySelector(el)
                    window.scrollTo({
                        top: (el.getBoundingClientRect().top + window.scrollY) - 30,
                        left: 0,
                        behavior: 'smooth'
                    })
                }

                // Logic for auto-filling the confirmation step
                function updateConfirmationStep() {
                    const getVal = (id) => document.getElementById(id) ? document.getElementById(id).value.trim() :
                        '';
                    const getText = (id) => {
                        const el = document.getElementById(id);
                        return el && el.options && el.selectedIndex >= 0 && el.value !== "" ? el.options[el
                            .selectedIndex].text : '';
                    };
                    const updateSpan = (id, val) => {
                        const span = document.getElementById(id);
                        if (span) {
                            if (val) {
                                span.textContent = val;
                                span.classList.remove('text-danger');
                                span.classList.add('text-dark', 'fw-semibold');
                            } else {
                                span.innerHTML = '<i>Sin especificar</i>';
                                span.classList.remove('text-dark', 'fw-semibold');
                                span.classList.add('text-danger');
                            }
                        }
                    };

                    // -- Update initials avatar --
                    const fn = getVal('firstNameLabel');
                    const fln = getVal('firstLastNameLabel');
                    const initials = [(fn[0] || ''), (fln[0] || '')].join('').toUpperCase() || '?';
                    const fullName = [fn, getVal('secondNameLabel'), fln, getVal('secondLastNameLabel')].filter(
                        Boolean).join(' ');
                    const initialsEl = document.getElementById('confirmAvatarInitials');
                    const fullNameEl = document.getElementById('confirmFullName');
                    if (initialsEl) initialsEl.textContent = initials;
                    if (fullNameEl) fullNameEl.textContent = fullName || '—';

                    updateSpan('confirmFirstName', fn);
                    updateSpan('confirmSecondName', getVal('secondNameLabel'));
                    updateSpan('confirmThirdName', getVal('thirdNameLabel'));
                    updateSpan('confirmFirstLastName', fln);
                    updateSpan('confirmSecondLastName', getVal('secondLastNameLabel'));
                    updateSpan('confirmMarriedLastName', getVal('marriedLastNameLabel'));
                    updateSpan('confirmEmail', getVal('emailLabel'));
                    updateSpan('confirmPhone', getVal('phoneLabel'));
                    updateSpan('confirmDpi', getVal('dpiLabel'));
                    updateSpan('confirmBirthDate', getVal('birthDateLabel'));

                    updateSpan('confirmGender', getText('genderLabel'));
                    updateSpan('confirmCivilStatus', getText('civilStatusLabel'));
                    updateSpan('confirmEducation', getText('educationLabel'));
                    updateSpan('confirmOccupation', getVal('occupationLabel'));
                    updateSpan('confirmCountry', getText('countryLabel'));
                    updateSpan('confirmDepartment', getText('departmentLabel'));
                    updateSpan('confirmMunicipality', getText('municipalityLabel'));
                    updateSpan('confirmPlace', getVal('placeLabel'));
                }


                // INITIALIZATION OF ADD FIELD
                // =======================================================
                new HSAddField('.js-add-field', {
                    addedField: field => {
                        if (window.HSCore && window.HSCore.components && window.HSCore.components
                            .HSMask) {
                            HSCore.components.HSMask.init(field.querySelector('.js-input-mask'))
                        }
                    }
                })

                // INITIALIZATION OF INPUT MASK
                // =======================================================
                HSCore.components.HSMask.init('.js-input-mask')


                // =====================================================
                // RELATIVES SEARCH + SELECT + INLINE CREATE LOGIC
                // =====================================================
                let selectedRelatives = [];
                let searchTimer = null;

                const searchInput = document.getElementById('relativeSearchInput');
                const searchResults = document.getElementById('relativeSearchResults');
                const createForm = document.getElementById('relativeCreateForm');
                const selectedContainer = document.getElementById('selectedRelativesContainer');

                // -- Debounced search --
                if (searchInput) {
                    searchInput.addEventListener('input', function() {
                        clearTimeout(searchTimer);
                        const term = this.value.trim();
                        searchResults.style.display = 'none';
                        searchResults.innerHTML = '';
                        if (term.length < 2) return;

                        searchTimer = setTimeout(() => {
                            fetch(`{{ route('patients.relatives.search') }}?term=${encodeURIComponent(term)}`, {
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(r => r.json())
                                .then(data => {
                                    searchResults.innerHTML = '';
                                    if (data.length === 0) {
                                        searchResults.innerHTML =
                                            `<div class="list-group-item text-muted small py-2"><i class="bi-info-circle me-1"></i>No se encontraron resultados. Prueba «Crear nuevo».</div>`;
                                    } else {
                                        data.forEach(rel => {
                                            const item = document.createElement(
                                                'button');
                                            item.type = 'button';
                                            item.className =
                                                'list-group-item list-group-item-action py-2';
                                            item.innerHTML =
                                                `<strong>${rel.name}</strong>${rel.married_last_name ? ` (de ${rel.married_last_name})` : ''}${rel.dpi ? ` <span class="text-muted small">· DPI: ${rel.dpi}</span>` : ''}`;
                                            item.addEventListener('click', () => {
                                                addSelectedRelative(rel);
                                                searchInput.value = '';
                                                searchResults.style.display =
                                                    'none';
                                            });
                                            searchResults.appendChild(item);
                                        });
                                    }
                                    searchResults.style.display = 'block';
                                });
                        }, 350);
                    });

                    // Hide results when clicking outside
                    document.addEventListener('click', function(e) {
                        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                            searchResults.style.display = 'none';
                        }
                    });
                }

                // -- Toggle create form --
                document.getElementById('btnNewRelative')?.addEventListener('click', function() {
                    createForm.style.display = createForm.style.display === 'none' ? 'block' : 'none';
                    searchResults.style.display = 'none';
                });
                document.getElementById('btnCancelCreate')?.addEventListener('click', function() {
                    createForm.style.display = 'none';
                    clearCreateForm();
                });

                // -- Save new relative inline --
                document.getElementById('btnSaveNewRelative')?.addEventListener('click', function() {
                    const firstName = document.getElementById('newRelFirstName').value.trim();
                    const firstLastName = document.getElementById('newRelFirstLastName').value.trim();
                    if (!firstName || !firstLastName) {
                        alert('Primer nombre y primer apellido son requeridos.');
                        return;
                    }
                    const relData = {
                        id: null,
                        first_name: firstName,
                        second_name: document.getElementById('newRelSecondName').value.trim(),
                        first_last_name: firstLastName,
                        second_last_name: document.getElementById('newRelSecondLastName').value.trim(),
                        married_last_name: document.getElementById('newRelMarriedLastName').value
                            .trim(),
                        dpi: document.getElementById('newRelDpi').value.trim(),
                        name: [firstName, document.getElementById('newRelSecondName').value.trim(),
                            firstLastName, document.getElementById('newRelSecondLastName').value
                            .trim()
                        ].filter(Boolean).join(' ')
                    };
                    addSelectedRelative(relData);
                    createForm.style.display = 'none';
                    clearCreateForm();
                });

                function clearCreateForm() {
                    ['newRelFirstName', 'newRelSecondName', 'newRelFirstLastName', 'newRelSecondLastName',
                        'newRelMarriedLastName',
                        'newRelDpi'
                    ]
                    .forEach(id => {
                        const el = document.getElementById(id);
                        if (el) el.value = '';
                    });
                }

                // -- Add a selected relative card to the list --
                function addSelectedRelative(rel) {
                    const idx = selectedRelatives.length;
                    selectedRelatives.push(rel);
                    document.getElementById('noRelativesData').style.display = 'none';

                    const card = document.createElement('div');
                    card.className = 'card card-bordered mb-3 selected-relative-card';
                    card.id = `selectedRel_${idx}`;
                    card.innerHTML = `
                <div class="card-body d-flex align-items-center gap-3 py-3 px-3">
                    <div class="avatar avatar-sm avatar-soft-primary avatar-circle flex-shrink-0">
                        <span class="avatar-initials">${rel.first_name ? rel.first_name[0].toUpperCase() : '?'}</span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-semibold">${rel.name}</div>
                        ${rel.dpi ? `<div class="text-muted small">DPI: ${rel.dpi}</div>` : ''}
                    </div>
                    <div style="min-width:180px;">
                        <select class="form-select form-select-sm" name="relatives[${idx}][relationship]" required>
                            <option value="">Relación *</option>
                            <option value="madre">Madre</option>
                            <option value="padre">Padre</option>
                            <option value="tutor">Tutor</option>
                            <option value="tutor legal">Tutor Legal</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-xs btn-soft-danger flex-shrink-0" onclick="removeSelectedRelative(${idx})">
                        <i class="bi-trash"></i>
                    </button>
                    {{-- Hidden data fields --}}
                    <input type="hidden" name="relatives[${idx}][first_name]"       value="${rel.first_name || ''}">
                    <input type="hidden" name="relatives[${idx}][second_name]"      value="${rel.second_name || ''}">
                    <input type="hidden" name="relatives[${idx}][first_last_name]"  value="${rel.first_last_name || ''}">
                    <input type="hidden" name="relatives[${idx}][second_last_name]" value="${rel.second_last_name || ''}">
                    <input type="hidden" name="relatives[${idx}][married_last_name]" value="${rel.married_last_name || ''}">
                    <input type="hidden" name="relatives[${idx}][dpi]"              value="${rel.dpi || ''}">
                </div>
            `;
                    selectedContainer.appendChild(card);
                }

                window.removeSelectedRelative = function(idx) {
                    const el = document.getElementById(`selectedRel_${idx}`);
                    if (el) el.remove();
                    if (document.querySelectorAll('.selected-relative-card').length === 0) {
                        document.getElementById('noRelativesData').style.display = 'block';
                    }
                };
                // =====================================================
                // CASCADING DROPDOWNS: País → Departamento → Municipio
                // =====================================================
                const countrySelect = document.getElementById('countryLabel');
                const departmentSelect = document.getElementById('departmentLabel');
                const municipalitySelect = document.getElementById('municipalityLabel');

                function resetSelect(select, placeholder) {
                    select.innerHTML = `<option value="">${placeholder}</option>`;
                    select.disabled = true;
                    select.value = '';
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

            }
        })()
    </script>

    <!-- Style Switcher JS -->

    <script>
        (function() {
            // STYLE SWITCHER
            // =======================================================
            const $dropdownBtn = document.getElementById('selectThemeDropdown') // Dropdowon trigger
            const $variants = document.querySelectorAll(
                `[aria-labelledby="selectThemeDropdown"] [data-icon]`) // All items of the dropdown

            // Function to set active style in the dorpdown menu and set icon for dropdown trigger
            const setActiveStyle = function() {
                $variants.forEach($item => {
                    if ($item.getAttribute('data-value') === HSThemeAppearance.getOriginalAppearance()) {
                        $dropdownBtn.innerHTML = `<i class="${$item.getAttribute('data-icon')}" />`
                        return $item.classList.add('active')
                    }

                    $item.classList.remove('active')
                })
            }

            // Add a click event to all items of the dropdown to set the style
            $variants.forEach(function($item) {
                $item.addEventListener('click', function() {
                    HSThemeAppearance.setAppearance($item.getAttribute('data-value'))
                })
            })

            // Call the setActiveStyle on load page
            setActiveStyle()

            // Add event listener on change style to call the setActiveStyle function
            window.addEventListener('on-hs-appearance-change', function() {
                setActiveStyle()
            })
        })()
    </script>
@endpush
