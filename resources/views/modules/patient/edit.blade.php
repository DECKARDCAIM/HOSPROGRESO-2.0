@extends('layouts.panel')
@section('title', 'Editar Paciente')

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
                            <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('patients.index') }}">Pacientes</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Editar</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">Editar paciente</h1>
                </div>

                <div class="col-auto">
                    <a href="{{ route('patients.index') }}" class="btn btn-primary">
                        <i class="bi-arrow-left"></i> Regresar
                    </a>
                </div>

            </div>
        </div>
        <!-- End Page Header -->
            <form id="editPatientForm" action="{{ route('patients.update', $patient) }}" method="POST" class="js-step-form py-md-5"
                autocomplete="off"
                data-hs-step-form-options='{"progressSelector": "#addUserStepFormProgress","stepsSelector": "#addUserStepFormContent","endSelector": "#addUserFinishBtn","isValidate": false}'>
                @csrf
                @method('PUT')
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

                                    {{-- Expediente (solo lectura) --}}
                                    <div class="row mb-4">
                                        <label class="col-sm-3 col-form-label form-label">Expediente Clínico</label>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-sm-vertical">
                                                <input type="text" class="form-control bg-light"
                                                    value="{{ $patient->clinicalRecord->record_number ?? 'Sin expediente asignado' }}"
                                                    readonly disabled autocomplete="off">
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
                                                    aria-label="Primer nombre"
                                                    value="{{ old('first_name', $patient->first_name) }}"
                                                    autocomplete="off" required>
                                                <input type="text" class="form-control" name="second_name"
                                                    id="secondNameLabel" placeholder="Segundo nombre"
                                                    aria-label="Segundo nombre"
                                                    value="{{ old('second_name', $patient->second_name) }}"
                                                    autocomplete="off">
                                                <input type="text" class="form-control" name="third_name"
                                                    id="thirdNameLabel" placeholder="Tercer nombre"
                                                    aria-label="Tercer nombre"
                                                    value="{{ old('third_name', $patient->third_name) }}"
                                                    autocomplete="off">
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
                                                    aria-label="Primer apellido"
                                                    value="{{ old('first_last_name', $patient->first_last_name) }}"
                                                    autocomplete="off" required>
                                                <input type="text" class="form-control" name="second_last_name"
                                                    id="secondLastNameLabel" placeholder="Segundo apellido"
                                                    aria-label="Segundo apellido"
                                                    value="{{ old('second_last_name', $patient->second_last_name) }}"
                                                    autocomplete="off">
                                                <input type="text" class="form-control" name="married_last_name"
                                                    id="marriedLastNameLabel" placeholder="Apellido de casada"
                                                    aria-label="Apellido de casada"
                                                    value="{{ old('married_last_name', $patient->married_last_name) }}"
                                                    autocomplete="off">
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
                                                        value="{{ old('dpi', $patient->dpi) }}"
                                                        autocomplete="off"
                                                        data-hs-mask-options='{"mask": "0000 00000 0000"}'>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="birthDateLabel"
                                                    class="col-sm-6 col-form-label form-label">Fecha de nacimiento</label>
                                                <div class="col-sm-9">
                                                    <input type="date" class="form-control" name="birth_date"
                                                        id="birthDateLabel"
                                                        value="{{ old('birth_date', $patient->birth_date ? $patient->birth_date->format('Y-m-d') : '') }}"
                                                        autocomplete="off" required>
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
                                                        aria-label="ejemplo@correo.com"
                                                        value="{{ old('email', $patient->email) }}"
                                                        autocomplete="off">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="phoneLabel"
                                                    class="col-sm-9 col-form-label form-label">Teléfono
                                                    <span class="form-label-secondary">(Opcional)</span></label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="js-input-mask form-control"
                                                        name="phone" id="phoneLabel" placeholder="00000000"
                                                        aria-label="00000000"
                                                        value="{{ old('phone', $patient->phone) }}"
                                                        autocomplete="off"
                                                        data-hs-mask-options='{"mask": "00000000"}'>
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
                                                        <option value="{{ $gender->id }}"
                                                            {{ old('gender_id', $patient->gender_id) == $gender->id ? 'selected' : '' }}>{{ $gender->name }}</option>
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
                                                        <option value="{{ $status->id }}"
                                                            {{ old('civil_status_id', $patient->civil_status_id) == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
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
                                                        <option value="{{ $ethnicity->id }}"
                                                            {{ old('ethnicity_id', $patient->ethnicity_id) == $ethnicity->id ? 'selected' : '' }}>{{ $ethnicity->name }}
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
                                                        <option value="{{ $community->id }}"
                                                            {{ old('linguistic_community_id', $patient->linguistic_community_id) == $community->id ? 'selected' : '' }}>{{ $community->name }}
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
                                                    <option value="ninguna" {{ old('education', $patient->education) == 'ninguna' ? 'selected' : '' }}>Ninguna</option>
                                                    <option value="primaria" {{ old('education', $patient->education) == 'primaria' ? 'selected' : '' }}>Primaria</option>
                                                    <option value="basico" {{ old('education', $patient->education) == 'basico' ? 'selected' : '' }}>Básico</option>
                                                    <option value="diversificado" {{ old('education', $patient->education) == 'diversificado' ? 'selected' : '' }}>Diversificado</option>
                                                    <option value="universitario" {{ old('education', $patient->education) == 'universitario' ? 'selected' : '' }}>Universitario</option>
                                                    <option value="postgrado" {{ old('education', $patient->education) == 'postgrado' ? 'selected' : '' }}>Postgrado</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="occupationLabel" class="form-label">
                                                    Ocupación <span class="form-label-secondary">(Opcional)</span>
                                                </label>
                                                <input type="text" class="form-control form-control-sm"
                                                    name="occupation" id="occupationLabel"
                                                    placeholder="Ej: Agricultor, Estudiante, Ama de casa..."
                                                    value="{{ old('occupation', $patient->occupation) }}"
                                                    autocomplete="off">
                                            </div>

                                        </div>

                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-sm-12">
                                            <hr>
                                        </div>
                                    </div>

                                    {{-- Alergias --}}
                                    <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label form-label">
                                            Alergias <span class="form-label-secondary">(Opcional)</span>
                                        </label>
                                        <div class="col-sm-9">
                                            @if($allergies->isEmpty())
                                                <p class="text-muted small">No hay alergias registradas en el catálogo.</p>
                                            @else
                                                <div class="row g-2" id="allergiesCheckboxGroup">
                                                    @foreach($allergies as $allergy)
                                                        <div class="col-md-4 col-6">
                                                            <div class="form-check">
                                                                <input class="form-check-input allergy-check" type="checkbox"
                                                                    name="allergies[]" value="{{ $allergy->id }}"
                                                                    id="allergy_{{ $allergy->id }}"
                                                                    data-label="{{ $allergy->name }}"
                                                                    {{ in_array($allergy->id, $selectedAllergyIds) ? 'checked' : '' }}>
                                                                <label class="form-check-label small" for="allergy_{{ $allergy->id }}">
                                                                    {{ $allergy->name }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-sm-12">
                                            <hr>
                                        </div>
                                    </div>

                                    {{-- Discapacidades --}}
                                    <div class="row mb-4">
                                        <label class="col-sm-3 col-form-label form-label">
                                            Discapacidades <span class="form-label-secondary">(Opcional)</span>
                                        </label>
                                        <div class="col-sm-9">
                                            @if($disabilities->isEmpty())
                                                <p class="text-muted small">No hay discapacidades registradas en el catálogo.</p>
                                            @else
                                                <div class="row g-2" id="disabilitiesCheckboxGroup">
                                                    @foreach($disabilities as $disability)
                                                        <div class="col-md-4 col-6">
                                                            <div class="form-check">
                                                                <input class="form-check-input disability-check" type="checkbox"
                                                                    name="disabilities[]" value="{{ $disability->id }}"
                                                                    id="disability_{{ $disability->id }}"
                                                                    data-label="{{ $disability->name }}"
                                                                    {{ in_array($disability->id, $selectedDisabilityIds) ? 'checked' : '' }}>
                                                                <label class="form-check-label small" for="disability_{{ $disability->id }}">
                                                                    {{ $disability->name }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
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
                                                    <option value="{{ $country->id }}"
                                                        {{ old('country_id', $patient->country_id) == $country->id ? 'selected' : '' }}>{{ $country->name }}
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
                                                {{ $departments->isEmpty() ? 'disabled' : '' }}>
                                                <option value="">Seleccione primero un país</option>
                                                @foreach ($departments as $dept)
                                                    <option value="{{ $dept->id }}"
                                                        {{ old('department_id', $patient->department_id) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label for="municipalityLabel"
                                            class="col-sm-3 col-form-label form-label">Municipio</label>
                                        <div class="col-sm-9">
                                            <select class="form-select" name="municipality_id" id="municipalityLabel"
                                                {{ $municipalities->isEmpty() ? 'disabled' : '' }}>
                                                <option value="">Seleccione primero un departamento</option>
                                                @foreach ($municipalities as $muni)
                                                    <option value="{{ $muni->id }}"
                                                        {{ old('municipality_id', $patient->municipality_id) == $muni->id ? 'selected' : '' }}>{{ $muni->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label for="placeLabel" class="col-sm-3 col-form-label form-label">
                                            Dirección</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="place" id="placeLabel"
                                                placeholder="Dirección exacta" aria-label="Dirección exacta"
                                                value="{{ old('place', $patient->place) }}"
                                                autocomplete="off">
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
                                                    class="bi-person-plus me-2"></i>Crear
                                                nuevo familiar</span>
                                            <button type="button" class="btn btn-xs btn-ghost-secondary"
                                                id="btnCancelCreate"><i class="bi-x-lg"></i></button>
                                        </div>
                                        <div class="card-body p-3">
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <label class="form-label form-label-sm">Primer Nombre *</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="newRelFirstName" placeholder="Primer nombre" autocomplete="off">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label form-label-sm">Segundo Nombre</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="newRelSecondName" placeholder="Segundo nombre" autocomplete="off">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label form-label-sm">Primer Apellido
                                                        *</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="newRelFirstLastName" placeholder="Primer apellido" autocomplete="off">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label form-label-sm">Segundo
                                                        Apellido</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="newRelSecondLastName" placeholder="Segundo apellido" autocomplete="off">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label form-label-sm">Apellido de
                                                        casada</label>
                                                    <input type="text" class="form-control form-control-sm"
                                                        id="newRelMarriedLastName" placeholder="Apellido de casada" autocomplete="off">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label form-label-sm">DPI</label>
                                                    <input type="text"
                                                        class="form-control form-control-sm js-input-mask" id="newRelDpi"
                                                        placeholder="0000 00000 0000"
                                                        data-hs-mask-options='{"mask": "0000 00000 0000"}' autocomplete="off">
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

                            {{-- ========================================================== --}}
                            {{-- PASO 4: CONFIRMACIÓN --}}
                            {{-- ========================================================== --}}
                            <div id="addUserStepConfirmation" class="card card-lg" style="display:none;">

                                <!-- Profile Cover -->
                                <div class="profile-cover">
                                    <div class="profile-cover-img-wrapper">
                                        <img class="profile-cover-img" src="{{ asset('img/1920x400/img1.jpg') }}" alt="Portada">
                                    </div>
                                </div>
                                <!-- End Profile Cover -->

                                <!-- Avatar -->
                                <label class="avatar avatar-xxl avatar-circle avatar-border-lg profile-cover-avatar">
                                    <img id="confirmAvatarImg" class="avatar-img" src="{{ asset('img/160x160/img1.jpg') }}" alt="Avatar">
                                </label>
                                <!-- End Avatar -->

                                <!-- Body -->
                                <div class="card-body">
                                    <dl class="row">

                                        <dt class="col-sm-6 text-sm-end">Expediente Clínico:</dt>
                                        <dd class="col-sm-6" id="confirm-expediente">
                                            <strong>{{ $patient->clinicalRecord->record_number ?? 'Sin expediente' }}</strong>
                                        </dd>

                                        <dt class="col-sm-6 text-sm-end"><hr class="my-2 w-100"></dt>
                                        <dd class="col-sm-6"><hr class="my-2 w-100"></dd>

                                        <dt class="col-sm-6 text-sm-end">Primer nombre:</dt>
                                        <dd class="col-sm-6" id="confirm-firstName">—</dd>

                                        <dt class="col-sm-6 text-sm-end">Segundo nombre:</dt>
                                        <dd class="col-sm-6" id="confirm-secondName">—</dd>

                                        <dt class="col-sm-6 text-sm-end">Tercer nombre:</dt>
                                        <dd class="col-sm-6" id="confirm-thirdName">—</dd>

                                        <dt class="col-sm-6 text-sm-end">Primer apellido:</dt>
                                        <dd class="col-sm-6" id="confirm-firstLastName">—</dd>

                                        <dt class="col-sm-6 text-sm-end">Segundo apellido:</dt>
                                        <dd class="col-sm-6" id="confirm-secondLastName">—</dd>

                                        <dt class="col-sm-6 text-sm-end">Apellido de casada:</dt>
                                        <dd class="col-sm-6" id="confirm-marriedLastName">—</dd>

                                        <dt class="col-sm-6 text-sm-end"><hr class="my-2 w-100"></dt>
                                        <dd class="col-sm-6"><hr class="my-2 w-100"></dd>

                                        <dt class="col-sm-6 text-sm-end">DPI:</dt>
                                        <dd class="col-sm-6" id="confirm-dpi">—</dd>

                                        <dt class="col-sm-6 text-sm-end">Fecha de nacimiento:</dt>
                                        <dd class="col-sm-6" id="confirm-birthDate">—</dd>

                                        <dt class="col-sm-6 text-sm-end">Género:</dt>
                                        <dd class="col-sm-6" id="confirm-gender">—</dd>

                                        <dt class="col-sm-6 text-sm-end">Estado civil:</dt>
                                        <dd class="col-sm-6" id="confirm-civilStatus">—</dd>

                                        <dt class="col-sm-6 text-sm-end">Etnia:</dt>
                                        <dd class="col-sm-6" id="confirm-ethnicity">—</dd>

                                        <dt class="col-sm-6 text-sm-end">Comunidad lingüística:</dt>
                                        <dd class="col-sm-6" id="confirm-linguisticCommunity">—</dd>

                                        <dt class="col-sm-6 text-sm-end">Escolaridad:</dt>
                                        <dd class="col-sm-6" id="confirm-education">—</dd>

                                        <dt class="col-sm-6 text-sm-end">Ocupación:</dt>
                                        <dd class="col-sm-6" id="confirm-occupation">—</dd>

                                        <dt class="col-sm-6 text-sm-end"><hr class="my-2 w-100"></dt>
                                        <dd class="col-sm-6"><hr class="my-2 w-100"></dd>

                                        <dt class="col-sm-6 text-sm-end">Correo electrónico:</dt>
                                        <dd class="col-sm-6" id="confirm-email">—</dd>

                                        <dt class="col-sm-6 text-sm-end">Teléfono:</dt>
                                        <dd class="col-sm-6" id="confirm-phone">—</dd>

                                        <dt class="col-sm-6 text-sm-end"><hr class="my-2 w-100"></dt>
                                        <dd class="col-sm-6"><hr class="my-2 w-100"></dd>

                                        <dt class="col-sm-6 text-sm-end">País:</dt>
                                        <dd class="col-sm-6" id="confirm-country">—</dd>

                                        <dt class="col-sm-6 text-sm-end">Departamento:</dt>
                                        <dd class="col-sm-6" id="confirm-department">—</dd>

                                        <dt class="col-sm-6 text-sm-end">Municipio:</dt>
                                        <dd class="col-sm-6" id="confirm-municipality">—</dd>

                                        <dt class="col-sm-6 text-sm-end">Dirección:</dt>
                                        <dd class="col-sm-6" id="confirm-place">—</dd>

                                        <dt class="col-sm-6 text-sm-end"><hr class="my-2 w-100"></dt>
                                        <dd class="col-sm-6"><hr class="my-2 w-100"></dd>

                                        <dt class="col-sm-6 text-sm-end">Alergias:</dt>
                                        <dd class="col-sm-6" id="confirm-allergies">—</dd>

                                        <dt class="col-sm-6 text-sm-end"><hr class="my-2 w-100"></dt>
                                        <dd class="col-sm-6"><hr class="my-2 w-100"></dd>

                                        <dt class="col-sm-6 text-sm-end">Discapacidades:</dt>
                                        <dd class="col-sm-6" id="confirm-disabilities">—</dd>

                                    </dl>
                                </div>
                                <!-- End Body -->

                                <!-- Footer -->
                                <div class="card-footer d-sm-flex align-items-sm-center">
                                    <button type="button" class="btn btn-ghost-secondary mb-2 mb-sm-0"
                                        data-hs-step-form-prev-options='{ "targetSelector": "#addUserStepFamily" }'>
                                        <i class="bi-chevron-left"></i> Paso anterior
                                    </button>
                                    <div class="ms-auto">
                                        <button id="addUserFinishBtn" type="button" class="btn btn-primary"
                                            onclick="document.getElementById('editPatientForm').submit();">
                                            <i class="bi-pencil-fill me-1"></i> Guardar cambios
                                        </button>
                                    </div>
                                </div>
                                <!-- End Footer -->
                            </div>
                            {{-- End Paso 4 --}}


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
                new HSSideNav('.js-navbar-vertical-aside').init()

                // INITIALIZATION OF FORM SEARCH
                new HSFormSearch('.js-form-search')

                // INITIALIZATION OF BOOTSTRAP DROPDOWN
                HSBsDropdown.init()

                // INITIALIZATION OF FILE ATTACH
                new HSFileAttach('.js-file-attach')

                // INITIALIZATION OF STEP FORM
                new HSStepForm('.js-step-form', {
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
                    const getVal = (id) => document.getElementById(id) ? document.getElementById(id).value.trim() : '';
                    const getText = (id) => {
                        const el = document.getElementById(id);
                        return el && el.options && el.selectedIndex >= 0 && el.value !== "" ? el.options[el.selectedIndex].text : '';
                    };
                    const setEl = (id, val) => {
                        const el = document.getElementById(id);
                        if (!el) return;
                        if (val) {
                            el.textContent = val;
                            el.classList.remove('text-muted', 'fst-italic');
                        } else {
                            el.innerHTML = '<em class="text-muted">Sin especificar</em>';
                        }
                    };

                    setEl('confirm-firstName',           getVal('firstNameLabel'));
                    setEl('confirm-secondName',          getVal('secondNameLabel'));
                    setEl('confirm-thirdName',           getVal('thirdNameLabel'));
                    setEl('confirm-firstLastName',       getVal('firstLastNameLabel'));
                    setEl('confirm-secondLastName',      getVal('secondLastNameLabel'));
                    setEl('confirm-marriedLastName',     getVal('marriedLastNameLabel'));
                    setEl('confirm-dpi',                 getVal('dpiLabel'));
                    setEl('confirm-birthDate',           getVal('birthDateLabel'));
                    setEl('confirm-gender',              getText('genderLabel'));
                    setEl('confirm-civilStatus',         getText('civilStatusLabel'));
                    setEl('confirm-ethnicity',           getText('ethnicityLabel'));
                    setEl('confirm-linguisticCommunity', getText('linguisticCommunityLabel'));
                    setEl('confirm-education',           getText('educationLabel'));
                    setEl('confirm-occupation',          getVal('occupationLabel'));
                    setEl('confirm-email',               getVal('emailLabel'));
                    setEl('confirm-phone',               getVal('phoneLabel'));
                    setEl('confirm-country',             getText('countryLabel'));
                    setEl('confirm-department',          getText('departmentLabel'));
                    setEl('confirm-municipality',        getText('municipalityLabel'));
                    setEl('confirm-place',               getVal('placeLabel'));

                    // Alergias & discapacidades
                    const getCheckedLabels = (selector) => {
                        const labels = [];
                        document.querySelectorAll(selector + ':checked').forEach(cb => labels.push(cb.dataset.label));
                        return labels.join(', ');
                    };
                    setEl('confirm-allergies',   getCheckedLabels('.allergy-check'));
                    setEl('confirm-disabilities', getCheckedLabels('.disability-check'));
                }

                // Run immediately so pre-filled values show on confirm step
                updateConfirmationStep();

                // INITIALIZATION OF INPUT MASK
                HSCore.components.HSMask.init('.js-input-mask')

                // =====================================================
                // RELATIVES SEARCH + SELECT + INLINE CREATE LOGIC
                // =====================================================
                // Pre-existing relatives from DB (populated by PHP)
                let selectedRelatives = @json($existingRelatives);
                let relativesSeed = [...selectedRelatives];
                selectedRelatives = [];
                let searchTimer = null;

                const searchInput = document.getElementById('relativeSearchInput');
                const searchResults = document.getElementById('relativeSearchResults');
                const createForm = document.getElementById('relativeCreateForm');
                const selectedContainer = document.getElementById('selectedRelativesContainer');

                // Pre-load existing relatives on page ready
                relativesSeed.forEach(rel => addSelectedRelative(rel));

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
                                            const item = document.createElement('button');
                                            item.type = 'button';
                                            item.className = 'list-group-item list-group-item-action py-2';
                                            item.innerHTML =
                                                `<strong>${rel.name}</strong>${rel.married_last_name ? ` (de ${rel.married_last_name})` : ''}${rel.dpi ? ` <span class="text-muted small">· DPI: ${rel.dpi}</span>` : ''}`;
                                            item.addEventListener('click', () => {
                                                addSelectedRelative(rel);
                                                searchInput.value = '';
                                                searchResults.style.display = 'none';
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
                        married_last_name: document.getElementById('newRelMarriedLastName').value.trim(),
                        dpi: document.getElementById('newRelDpi').value.trim(),
                        name: [firstName, document.getElementById('newRelSecondName').value.trim(),
                            firstLastName, document.getElementById('newRelSecondLastName').value.trim()
                        ].filter(Boolean).join(' ')
                    };
                    addSelectedRelative(relData);
                    createForm.style.display = 'none';
                    clearCreateForm();
                });

                function clearCreateForm() {
                    ['newRelFirstName', 'newRelSecondName', 'newRelFirstLastName', 'newRelSecondLastName',
                        'newRelMarriedLastName', 'newRelDpi'
                    ].forEach(id => {
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
                        <select class="form-select form-select-sm" name="relatives[${idx}][relationship_type_id]" required>
                            <option value="">Relación *</option>
                            @foreach ($relationshipTypes as $type)
                                <option value="{{ $type->id }}" ${rel.relationship_type_id == {{ $type->id }} ? 'selected' : ''}>{{ ucfirst($type->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" class="btn btn-xs btn-soft-danger flex-shrink-0" onclick="removeSelectedRelative(${idx})">
                        <i class="bi-trash"></i>
                    </button>
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
                            })
                            .then(r => r.json())
                            .then(data => {
                                if (data.length === 0) {
                                    resetSelect(departmentSelect, 'Sin departamentos disponibles');
                                } else {
                                    populateSelect(departmentSelect, data, 'Seleccione un departamento');
                                }
                            })
                            .catch(() => resetSelect(departmentSelect, 'Error al cargar'));
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

    <script>
        (function() {
            // STYLE SWITCHER
            const $dropdownBtn = document.getElementById('selectThemeDropdown')
            const $variants = document.querySelectorAll(`[aria-labelledby="selectThemeDropdown"] [data-icon]`)
            const setActiveStyle = function() {
                $variants.forEach($item => {
                    if ($item.getAttribute('data-value') === HSThemeAppearance.getOriginalAppearance()) {
                        $dropdownBtn.innerHTML = `<i class="${$item.getAttribute('data-icon')}" />`
                        return $item.classList.add('active')
                    }
                    $item.classList.remove('active')
                })
            }
            $variants.forEach(function($item) {
                $item.addEventListener('click', function() {
                    HSThemeAppearance.setAppearance($item.getAttribute('data-value'))
                })
            })
            setActiveStyle()
            window.addEventListener('on-hs-appearance-change', function() {
                setActiveStyle()
            })
        })()
    </script>
@endpush
