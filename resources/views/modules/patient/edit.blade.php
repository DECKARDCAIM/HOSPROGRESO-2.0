@extends('layouts.panel')
@section('title', 'Editar Paciente')
@section('content')
    <main id="content" role="main" class="main">
        <div class="content container-fluid">
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

            <form id="editPatientForm" action="{{ route('patients.update', $patient) }}" method="POST" class="js-step-form py-md-5" autocomplete="off" data-hs-step-form-options='{"progressSelector": "#addUserStepFormProgress","stepsSelector": "#addUserStepFormContent","endSelector": "#addUserFinishBtn","isValidate": false}'>
                @csrf
                @method('PUT')
                <div class="row justify-content-lg-center">
                    <div class="col-lg-8">
                        <ul id="addUserStepFormProgress" class="js-step-progress step step-sm step-icon-sm step step-inline step-item-between mb-3 mb-md-5">
                            <li class="step-item">
                                <a class="step-content-wrapper" href="javascript:;" data-hs-step-form-next-options='{"targetSelector": "#addUserStepProfile"}'>
                                    <span class="step-icon step-icon-soft-dark">1</span>
                                    <div class="step-content">
                                        <span class="step-title">Datos Generales</span>
                                    </div>
                                </a>
                            </li>
                            <li class="step-item">
                                <a class="step-content-wrapper" href="javascript:;" data-hs-step-form-next-options='{"targetSelector": "#addUserStepBillingAddress"}'>
                                    <span class="step-icon step-icon-soft-dark">2</span>
                                    <div class="step-content">
                                        <span class="step-title">Ubicación</span>
                                    </div>
                                </a>
                            </li>
                            <li class="step-item">
                                <a class="step-content-wrapper" href="javascript:;" data-hs-step-form-next-options='{"targetSelector": "#addUserStepFamily"}'>
                                    <span class="step-icon step-icon-soft-dark">3</span>
                                    <div class="step-content">
                                        <span class="step-title">Datos de Familiares</span>
                                    </div>
                                </a>
                            </li>
                            <li class="step-item">
                                <a class="step-content-wrapper" href="javascript:;" data-hs-step-form-next-options='{"targetSelector": "#addUserStepConfirmation"}'>
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
                                        <label class="col-sm-3 col-form-label form-label">Expediente Clínico</label>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-sm-vertical">
                                                <input type="text" class="form-control bg-light" value="{{ $patient->clinicalRecord->record_number ?? 'Sin expediente asignado' }}" readonly disabled autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-sm-12">
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="firstNameLabel" class="col-sm-3 col-form-label form-label">Nombres <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Rellene los nombres del paciente"></i></label>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-sm-vertical">
                                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" id="firstNameLabel" placeholder="Primer nombre" aria-label="Primer nombre" value="{{ old('first_name', $patient->first_name) }}" autocomplete="off" required>
                                                <input type="text" class="form-control @error('second_name') is-invalid @enderror" name="second_name" id="secondNameLabel" placeholder="Segundo nombre" aria-label="Segundo nombre" value="{{ old('second_name', $patient->second_name) }}" autocomplete="off">
                                                <input type="text" class="form-control @error('third_name') is-invalid @enderror" name="third_name" id="thirdNameLabel" placeholder="Tercer nombre" aria-label="Tercer nombre" value="{{ old('third_name', $patient->third_name) }}" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="firstLastNameLabel" class="col-sm-3 col-form-label form-label">Apellidos <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Rellene los apellidos del paciente"></i></label>
                                        <div class="col-sm-9">
                                            <div class="input-group input-group-sm-vertical">
                                                <input type="text" class="form-control @error('first_last_name') is-invalid @enderror" name="first_last_name" id="firstLastNameLabel" placeholder="Primer apellido" aria-label="Primer apellido" value="{{ old('first_last_name', $patient->first_last_name) }}" autocomplete="off" required>
                                                <input type="text" class="form-control @error('second_last_name') is-invalid @enderror" name="second_last_name" id="secondLastNameLabel" placeholder="Segundo apellido" aria-label="Segundo apellido" value="{{ old('second_last_name', $patient->second_last_name) }}" autocomplete="off">
                                                <input type="text" class="form-control @error('married_last_name') is-invalid @enderror" name="married_last_name" id="marriedLastNameLabel" placeholder="Apellido de casada" aria-label="Apellido de casada" value="{{ old('married_last_name', $patient->married_last_name) }}" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-12">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="cuiLabel" class="col-sm-3 col-form-label form-label">CUI <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Rellene el CUI del paciente"></i></label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="js-input-mask form-control @error('cui') is-invalid @enderror" name="cui" id="cuiLabel" placeholder="0000000000000" aria-label="CUI" value="{{ old('cui', $patient->cui) }}" autocomplete="off" data-hs-mask-options='{"mask": "0000000000000"}'>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="birthDateLabel" class="col-sm-6 col-form-label form-label">Fecha de nacimiento</label>
                                                <div class="col-sm-9">
                                                    <input type="date" class="form-control @error('birth_date') is-invalid @enderror" name="birth_date" id="birthDateLabel" value="{{ old('birth_date', $patient->birth_date ? $patient->birth_date->format('Y-m-d') : '') }}" autocomplete="off" required>
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
                                                <label for="emailLabel" class="col-sm-9 col-form-label form-label">Correo electrónico <span class="form-label-secondary">(Opcional)</span></label>
                                                <div class="col-sm-9">
                                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="emailLabel" placeholder="ejemplo@correo.com" aria-label="ejemplo@correo.com" value="{{ old('email', $patient->email) }}" autocomplete="off">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="phoneLabel" class="col-sm-9 col-form-label form-label">Teléfono <span class="form-label-secondary">(Opcional)</span></label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="js-input-mask form-control @error('phone') is-invalid @enderror" name="phone" id="phoneLabel" placeholder="00000000" aria-label="00000000" value="{{ old('phone', $patient->phone) }}" autocomplete="off" data-hs-mask-options='{"mask": "00000000"}'>
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
                                                <label for="genderLabel" class="form-label">Género</label>
                                                <div class="tom-select-custom">
                                                    <select class="js-select form-select form-select-sm @error('gender_id') is-invalid @enderror" name="gender_id" id="genderLabel" data-hs-tom-select-options='{"placeholder": "Seleccione género..."}' required>
                                                        <option value="">Seleccione</option>
                                                        @foreach ($genders as $gender)
                                                            <option value="{{ $gender->id }}" {{ old('gender_id', $patient->gender_id) == $gender->id ? 'selected' : '' }}>{{ $gender->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="civilStatusLabel" class="form-label">Estado civil</label>
                                                <div class="tom-select-custom">
                                                    <select class="js-select form-select form-select-sm @error('civil_status_id') is-invalid @enderror" name="civil_status_id" id="civilStatusLabel" data-hs-tom-select-options='{"placeholder": "Seleccione estado civil..."}' required>
                                                        <option value="">Seleccione</option>
                                                        @foreach ($civilStatuses as $status)
                                                            <option value="{{ $status->id }}" {{ old('civil_status_id', $patient->civil_status_id) == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="ethnicityLabel" class="form-label">Etnia</label>
                                                <div class="tom-select-custom">
                                                    <select class="js-select form-select form-select-sm @error('ethnicity_id') is-invalid @enderror" name="ethnicity_id" id="ethnicityLabel" data-hs-tom-select-options='{"placeholder": "Seleccione etnia..."}'>
                                                        <option value="">Seleccione</option>
                                                        @foreach ($ethnicities as $ethnicity)
                                                            <option value="{{ $ethnicity->id }}" {{ old('ethnicity_id', $patient->ethnicity_id) == $ethnicity->id ? 'selected' : '' }}>{{ $ethnicity->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="linguisticCommunityLabel" class="form-label">Comunidad lingüística</label>
                                                <div class="tom-select-custom">
                                                    <select class="js-select form-select form-select-sm @error('linguistic_community_id') is-invalid @enderror" name="linguistic_community_id" id="linguisticCommunityLabel" data-hs-tom-select-options='{"placeholder": "Seleccione comunidad..."}'>
                                                        <option value="">Seleccione</option>
                                                        @foreach ($linguisticCommunities as $community)
                                                            <option value="{{ $community->id }}" {{ old('linguistic_community_id', $patient->linguistic_community_id) == $community->id ? 'selected' : '' }}>{{ $community->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
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
                                                <label for="educationLabel" class="form-label">Escolaridad <span class="form-label-secondary">(Opcional)</span></label>
                                                <div class="tom-select-custom">
                                                    <select class="js-select form-select form-select-sm @error('education') is-invalid @enderror" name="education" id="educationLabel" data-hs-tom-select-options='{"placeholder": "Seleccione escolaridad..."}'>
                                                        <option value="">Seleccione</option>
                                                        <option value="ninguna" {{ old('education', $patient->education) == 'ninguna' ? 'selected' : '' }}>Ninguna</option>
                                                        <option value="primaria" {{ old('education', $patient->education) == 'primaria' ? 'selected' : '' }}>Primaria</option>
                                                        <option value="basico" {{ old('education', $patient->education) == 'basico' ? 'selected' : '' }}>Básico</option>
                                                        <option value="diversificado" {{ old('education', $patient->education) == 'diversificado' ? 'selected' : '' }}>Diversificado</option>
                                                        <option value="universitario" {{ old('education', $patient->education) == 'universitario' ? 'selected' : '' }}>Universitario</option>
                                                        <option value="postgrado" {{ old('education', $patient->education) == 'postgrado' ? 'selected' : '' }}>Postgrado</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="occupationLabel" class="form-label">Ocupación <span class="form-label-secondary">(Opcional)</span></label>
                                                <input type="text" class="form-control form-control-sm @error('occupation') is-invalid @enderror" name="occupation" id="occupationLabel" placeholder="Ej: Agricultor, Estudiante, Ama de casa..." value="{{ old('occupation', $patient->occupation) }}" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-sm-12">
                                            <hr>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-3 col-form-label form-label">Alergias <span class="form-label-secondary">(Opcional)</span></label>
                                        <div class="col-sm-9">
                                            <div class="tom-select-custom">
                                                <select class="js-select form-select @error('allergies') is-invalid @enderror" name="allergies[]" id="allergiesLabel" multiple data-hs-tom-select-options='{"placeholder": "Seleccione alergias..."}'>
                                                    @foreach ($allergies as $allergy)
                                                        <option value="{{ $allergy->id }}" {{ in_array($allergy->id, $selectedAllergyIds) ? 'selected' : '' }}>{{ $allergy->name }}</option>
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
                                        <label class="col-sm-3 col-form-label form-label">Discapacidades <span class="form-label-secondary">(Opcional)</span></label>
                                        <div class="col-sm-9">
                                            <div class="tom-select-custom">
                                                <select class="js-select form-select @error('disabilities') is-invalid @enderror" name="disabilities[]" id="disabilitiesLabel" multiple data-hs-tom-select-options='{"placeholder": "Seleccione discapacidades..."}'>
                                                    @foreach ($disabilities as $disability)
                                                        <option value="{{ $disability->id }}" {{ in_array($disability->id, $selectedDisabilityIds) ? 'selected' : '' }}>{{ $disability->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex justify-content-end align-items-center">
                                        <button type="button" class="btn btn-primary" data-hs-step-form-next-options='{ "targetSelector": "#addUserStepBillingAddress" }'>Siguiente <i class="bi-chevron-right"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div id="addUserStepBillingAddress" class="card card-lg" style="display: none;">
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <label for="countryLabel" class="col-sm-3 col-form-label form-label">País</label>
                                        <div class="col-sm-9">
                                            <div class="tom-select-custom">
                                                <select class="js-select form-select @error('country_id') is-invalid @enderror" name="country_id" id="countryLabel" data-hs-tom-select-options='{"placeholder": "Seleccione un país..."}'>
                                                    <option value="">Seleccione un país</option>
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}" {{ old('country_id', ($patient->municipality && $patient->municipality->department) ? $patient->municipality->department->country_id : null) == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="departmentLabel" class="col-sm-3 col-form-label form-label">Departamento</label>
                                        <div class="col-sm-9">
                                            <div class="tom-select-custom">
                                                <select class="js-select form-select @error('department_id') is-invalid @enderror" name="department_id" id="departmentLabel" {{ $departments->isEmpty() ? 'disabled' : '' }} data-hs-tom-select-options='{"placeholder": "Seleccione un departamento..."}'>
                                                    <option value="">Seleccione primero un país</option>
                                                    @foreach ($departments as $dept)
                                                        <option value="{{ $dept->id }}" {{ old('department_id', $patient->municipality ? $patient->municipality->department_id : null) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="municipalityLabel" class="col-sm-3 col-form-label form-label">Municipio</label>
                                        <div class="col-sm-9">
                                            <div class="tom-select-custom">
                                                <select class="js-select form-select @error('municipality_id') is-invalid @enderror" name="municipality_id" id="municipalityLabel" {{ $municipalities->isEmpty() ? 'disabled' : '' }} data-hs-tom-select-options='{"placeholder": "Seleccione un municipio..."}'>
                                                    <option value="">Seleccione primero un departamento</option>
                                                    @foreach ($municipalities as $muni)
                                                        <option value="{{ $muni->id }}" {{ old('municipality_id', $patient->municipality_id) == $muni->id ? 'selected' : '' }}>{{ $muni->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="placeLabel" class="col-sm-3 col-form-label form-label">Dirección</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control @error('place') is-invalid @enderror" name="place" id="placeLabel" placeholder="Dirección exacta" aria-label="Dirección exacta" value="{{ old('place', $patient->place) }}" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center">
                                    <button type="button" class="btn btn-ghost-secondary" data-hs-step-form-prev-options='{"targetSelector": "#addUserStepProfile"}'>
                                        <i class="bi-chevron-left"></i> Anterior
                                    </button>
                                    <div class="ms-auto">
                                        <button type="button" class="btn btn-primary" data-hs-step-form-next-options='{"targetSelector": "#addUserStepFamily"}'>
                                            Siguiente <i class="bi-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div id="addUserStepFamily" class="card card-lg" style="display: none;">
                                <div class="card-body">
                                    <h4 class="card-header-title mb-1">Familiares</h4>
                                    <p class="text-muted small mb-4">Busca un familiar por nombre o CUI. Si no existe, puedes crearlo desde aquí.</p>
                                    <div class="position-relative mb-4">
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bi-search"></i></span>
                                            <input type="text" id="relativeSearchInput" class="form-control" placeholder="Buscar por nombre o CUI..." autocomplete="off">
                                            <button type="button" class="btn btn-outline-primary" id="btnNewRelative">
                                                <i class="bi-person-plus me-1"></i> Crear nuevo
                                            </button>
                                        </div>
                                        <div id="relativeSearchResults" class="list-group position-absolute w-100 shadow-sm z-index-3" style="display:none; top:100%; z-index:999; max-height:220px; overflow-y:auto; background-color: var(--bs-card-bg); border: 1px solid var(--bs-border-color);"></div>
                                    </div>
                                    <div id="relativeCreateForm" class="card card-bordered border-primary mb-4" style="display:none;">
                                        <div class="card-header bg-soft-primary d-flex justify-content-between align-items-center py-2">
                                            <span class="fw-semibold text-primary"><i class="bi-person-plus me-2"></i>Crear nuevo familiar</span>
                                            <button type="button" class="btn btn-xs btn-ghost-secondary" id="btnCancelCreate"><i class="bi-x-lg"></i></button>
                                        </div>
                                        <div class="card-body p-3">
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <label class="form-label form-label-sm">Primer Nombre *</label>
                                                    <input type="text" class="form-control form-control-sm" id="newRelFirstName" placeholder="Primer nombre" autocomplete="off">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label form-label-sm">Segundo Nombre</label>
                                                    <input type="text" class="form-control form-control-sm" id="newRelSecondName" placeholder="Segundo nombre" autocomplete="off">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label form-label-sm">Primer Apellido *</label>
                                                    <input type="text" class="form-control form-control-sm" id="newRelFirstLastName" placeholder="Primer apellido" autocomplete="off">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label form-label-sm">Segundo Apellido</label>
                                                    <input type="text" class="form-control form-control-sm" id="newRelSecondLastName" placeholder="Segundo apellido" autocomplete="off">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label form-label-sm">Apellido de casada</label>
                                                    <input type="text" class="form-control form-control-sm" id="newRelMarriedLastName" placeholder="Apellido de casada" autocomplete="off">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label form-label-sm">CUI</label>
                                                    <input type="text" class="form-control form-control-sm js-input-mask" id="newRelCui" placeholder="0000000000000" data-hs-mask-options='{"mask": "0000000000000"}' autocomplete="off">
                                                </div>
                                                <div class="col-md-6 d-flex align-items-end">
                                                    <button type="button" class="btn btn-primary btn-sm w-100" id="btnSaveNewRelative">
                                                        <i class="bi-check2 me-1"></i> Guardar y seleccionar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="selectedRelativesContainer">
                                        <div class="text-center py-4" id="noRelativesData">
                                            <i class="bi-people display-4 text-muted"></i>
                                            <p class="text-muted mt-2 mb-0 small">Aún no has agregado familiares.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex align-items-center">
                                    <button type="button" class="btn btn-ghost-secondary" data-hs-step-form-prev-options='{"targetSelector": "#addUserStepBillingAddress"}'>
                                        <i class="bi-chevron-left"></i> Anterior
                                    </button>
                                    <div class="ms-auto">
                                        <button type="button" class="btn btn-primary" data-hs-step-form-next-options='{"targetSelector": "#addUserStepConfirmation"}'>
                                            Siguiente <i class="bi-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div id="addUserStepConfirmation" class="card card-lg" style="display:none;">
                                <div class="profile-cover">
                                    <div class="profile-cover-img-wrapper">
                                        <img class="profile-cover-img" src="{{ asset('dist/img/1920x400/img1.jpg') }}" alt="Portada">
                                    </div>
                                </div>
                                <label class="avatar avatar-xxl avatar-circle avatar-border-lg profile-cover-avatar">
                                    <img id="confirmAvatarImg" class="avatar-img" src="{{ asset('dist/img/160x160/img1.jpg') }}" alt="Avatar">
                                </label>
                                <div class="card-body">
                                    <dl class="row">
                                        <dt class="col-sm-6 text-sm-end">Expediente Clínico:</dt>
                                        <dd class="col-sm-6" id="confirm-expediente">
                                            <strong>{{ $patient->clinicalRecord->record_number ?? 'Sin expediente' }}</strong>
                                        </dd>
                                        <dt class="col-sm-6 text-sm-end">
                                            <hr class="my-2 w-100">
                                        </dt>
                                        <dd class="col-sm-6">
                                            <hr class="my-2 w-100">
                                        </dd>
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
                                        <dt class="col-sm-6 text-sm-end">
                                            <hr class="my-2 w-100">
                                        </dt>
                                        <dd class="col-sm-6">
                                            <hr class="my-2 w-100">
                                        </dd>
                                        <dt class="col-sm-6 text-sm-end">CUI:</dt>
                                        <dd class="col-sm-6" id="confirm-cui">—</dd>
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
                                        <dt class="col-sm-6 text-sm-end">
                                            <hr class="my-2 w-100">
                                        </dt>
                                        <dd class="col-sm-6">
                                            <hr class="my-2 w-100">
                                        </dd>
                                        <dt class="col-sm-6 text-sm-end">Correo electrónico:</dt>
                                        <dd class="col-sm-6" id="confirm-email">—</dd>
                                        <dt class="col-sm-6 text-sm-end">Teléfono:</dt>
                                        <dd class="col-sm-6" id="confirm-phone">—</dd>
                                        <dt class="col-sm-6 text-sm-end">
                                            <hr class="my-2 w-100">
                                        </dt>
                                        <dd class="col-sm-6">
                                            <hr class="my-2 w-100">
                                        </dd>
                                        <dt class="col-sm-6 text-sm-end">País:</dt>
                                        <dd class="col-sm-6" id="confirm-country">—</dd>
                                        <dt class="col-sm-6 text-sm-end">Departamento:</dt>
                                        <dd class="col-sm-6" id="confirm-department">—</dd>
                                        <dt class="col-sm-6 text-sm-end">Municipio:</dt>
                                        <dd class="col-sm-6" id="confirm-municipality">—</dd>
                                        <dt class="col-sm-6 text-sm-end">Dirección:</dt>
                                        <dd class="col-sm-6" id="confirm-place">—</dd>
                                        <dt class="col-sm-6 text-sm-end">
                                            <hr class="my-2 w-100">
                                        </dt>
                                        <dd class="col-sm-6">
                                            <hr class="my-2 w-100">
                                        </dd>
                                        <dt class="col-sm-6 text-sm-end">Alergias:</dt>
                                        <dd class="col-sm-6" id="confirm-allergies">—</dd>
                                        <dt class="col-sm-6 text-sm-end">
                                            <hr class="my-2 w-100">
                                        </dt>
                                        <dd class="col-sm-6">
                                            <hr class="my-2 w-100">
                                        </dd>
                                        <dt class="col-sm-6 text-sm-end">Discapacidades:</dt>
                                        <dd class="col-sm-6" id="confirm-disabilities">—</dd>
                                    </dl>
                                </div>
                                <div class="card-footer d-sm-flex align-items-sm-center">
                                    <button type="button" class="btn btn-ghost-secondary mb-2 mb-sm-0" data-hs-step-form-prev-options='{ "targetSelector": "#addUserStepFamily" }'>
                                        <i class="bi-chevron-left"></i> Paso anterior
                                    </button>
                                    <div class="ms-auto">
                                        <button id="addUserFinishBtn" type="button" class="btn btn-primary" onclick="document.getElementById('editPatientForm').submit();">
                                            <i class="bi-pencil-fill me-1"></i> Guardar cambios
                                        </button>
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
    <script>
        (function() {
            window.addEventListener('load', function() {
                new HSFileAttach('.js-file-attach')
                HSCore.components.HSTomSelect.init('.js-select')
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

                function updateConfirmationStep() {
                    const getVal = (id) => document.getElementById(id) ? document.getElementById(id).value.trim() :
                        '';
                    const getText = (id) => {
                        const el = document.getElementById(id);
                        if (!el) return '';
                        if (el.tomselect) {
                            const items = el.tomselect.getValue();
                            if (Array.isArray(items)) {
                                return items.map(val => {
                                    const opt = el.tomselect.options[val];
                                    return opt ? opt.text : val;
                                }).join(', ');
                            }
                            const selected = el.tomselect.getItem(items);
                            return selected ? selected.textContent.trim() : '';
                        }
                        return el.options && el.selectedIndex >= 0 && el.value !== "" ? el.options[el
                            .selectedIndex].text : '';
                    };
                    const setEl = (id, val) => {
                        const el = document.getElementById(id);
                        if (!el) return;
                        if (val && val !== "") {
                            el.textContent = val;
                            el.classList.remove('text-muted', 'fst-italic');
                        } else {
                            el.innerHTML = '<em class="text-muted small">Sin especificar</em>';
                        }
                    };

                    setEl('confirm-firstName', getVal('firstNameLabel'));
                    setEl('confirm-secondName', getVal('secondNameLabel'));
                    setEl('confirm-thirdName', getVal('thirdNameLabel'));
                    setEl('confirm-firstLastName', getVal('firstLastNameLabel'));
                    setEl('confirm-secondLastName', getVal('secondLastNameLabel'));
                    setEl('confirm-marriedLastName', getVal('marriedLastNameLabel'));
                    setEl('confirm-cui', getVal('cuiLabel'));
                    setEl('confirm-birthDate', getVal('birthDateLabel'));
                    setEl('confirm-gender', getText('genderLabel'));
                    setEl('confirm-civilStatus', getText('civilStatusLabel'));
                    setEl('confirm-ethnicity', getText('ethnicityLabel'));
                    setEl('confirm-linguisticCommunity', getText('linguisticCommunityLabel'));
                    setEl('confirm-education', getText('educationLabel'));
                    setEl('confirm-occupation', getVal('occupationLabel'));
                    setEl('confirm-email', getVal('emailLabel'));
                    setEl('confirm-phone', getVal('phoneLabel'));
                    setEl('confirm-country', getText('countryLabel'));
                    setEl('confirm-department', getText('departmentLabel'));
                    setEl('confirm-municipality', getText('municipalityLabel'));
                    setEl('confirm-place', getVal('placeLabel'));
                    setEl('confirm-allergies', getText('allergiesLabel'));
                    setEl('confirm-disabilities', getText('disabilitiesLabel'));
                }
                updateConfirmationStep();
                HSCore.components.HSMask.init('.js-input-mask')
                let selectedRelatives = @json($existingRelatives);
                let relativesSeed = [...selectedRelatives];
                selectedRelatives = [];
                let searchTimer = null;

                const searchInput = document.getElementById('relativeSearchInput');
                const searchResults = document.getElementById('relativeSearchResults');
                const createForm = document.getElementById('relativeCreateForm');
                const selectedContainer = document.getElementById('selectedRelativesContainer');
                relativesSeed.forEach(rel => addSelectedRelative(rel));
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
                                                `<strong>${rel.name}</strong>${rel.married_last_name ? ` (de ${rel.married_last_name})` : ''}${rel.cui ? ` <span class="text-muted small">· CUI: ${rel.cui}</span>` : ''}`;
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
                    document.addEventListener('click', function(e) {
                        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                            searchResults.style.display = 'none';
                        }
                    });
                }
                document.getElementById('btnNewRelative')?.addEventListener('click', function() {
                    createForm.style.display = createForm.style.display === 'none' ? 'block' : 'none';
                    searchResults.style.display = 'none';
                });
                document.getElementById('btnCancelCreate')?.addEventListener('click', function() {
                    createForm.style.display = 'none';
                    clearCreateForm();
                });
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
                        cui: document.getElementById('newRelCui').value.trim(),
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
                        'newRelMarriedLastName', 'newRelCui'
                    ].forEach(id => {
                        const el = document.getElementById(id);
                        if (el) el.value = '';
                    });
                }
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
                        ${rel.cui ? `<div class="text-muted small">CUI: ${rel.cui}</div>` : ''}
                    </div>
                    <div style="min-width:180px;">
                        <select class="form-select form-select-sm @error('relatives[${idx}][relationship_type_id]') is-invalid @enderror" name="relatives[${idx}][relationship_type_id]" required>
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
                    <input type="hidden" name="relatives[${idx}][cui]"              value="${rel.cui || ''}">
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
                const countrySelect = document.getElementById('countryLabel');
                const departmentSelect = document.getElementById('departmentLabel');
                const municipalitySelect = document.getElementById('municipalityLabel');

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
                        resetSelect(municipalitySelect, 'Seleccione primero un departamento');

                        if (!deptId) return;

                        municipalitySelect.innerHTML = '<option value="">Cargando...</option>';
                        syncTS(municipalitySelect);

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
            });
        })()
    </script>
@endpush