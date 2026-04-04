@extends('layouts.panel')
@section('title', 'Registrar Familiar')
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
                            <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('patient-relatives.index') }}">Familiares</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Registrar</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">Registrar familiar</h1>
                </div>
                <div class="col-auto">
                    <a href="{{ route('patient-relatives.index') }}" class="btn btn-primary">
                        <i class="bi-arrow-left"></i> Regresar
                    </a>
                </div>
            </div>
        </div>
        <div class="row justify-content-lg-center">
            <div class="col-lg-10">
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

            <form action="{{ route('patient-relatives.store') }}" method="POST" id="mainForm" class="needs-validation" novalidate>
                    @csrf
                    <div class="card card-lg mb-3 mb-lg-5">
                        <div class="card-header">
                            <h4 class="card-header-title">Información del Familiar</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6 mb-4">
                                    <label for="patientLabel" class="form-label">Paciente vincular</label>
                                    <div class="tom-select-custom">
                                        <select class="js-select form-select @error('patient_id') is-invalid @enderror" name="patient_id" id="patientLabel" required data-hs-tom-select-options='{"placeholder": "Seleccionar paciente...", "searchInDropdown": true}'>
                                            <option value="">Seleccionar...</option>
                                            @foreach($patients as $patient)
                                                <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>{{ $patient->first_name }} {{ $patient->first_last_name }} ({{ $patient->cui }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('patient_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm-6 mb-4">
                                    <label for="relationshipLabel" class="form-label">Tipo de Parentesco</label>
                                    <div class="tom-select-custom">
                                        <select class="js-select form-select @error('relationship_type_id') is-invalid @enderror" name="relationship_type_id" id="relationshipLabel" required data-hs-tom-select-options='{"placeholder": "Seleccionar parentesco...", "hideSearch": true}'>
                                            <option value="">Seleccionar...</option>
                                            @foreach($relationshipTypes as $type)
                                                <option value="{{ $type->id }}" {{ old('relationship_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('relationship_type_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <hr class="my-4">
                            <div class="row">
                                <div class="col-sm-4 mb-4">
                                    <label for="firstNameLabel" class="form-label">Primer Nombre</label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" id="firstNameLabel" placeholder="Ej. Juan" value="{{ old('first_name') }}" required>
                                </div>
                                <div class="col-sm-4 mb-4">
                                    <label for="secondNameLabel" class="form-label">Segundo Nombre</label>
                                    <input type="text" class="form-control @error('second_name') is-invalid @enderror" name="second_name" id="secondNameLabel" placeholder="Ej. Antonio" value="{{ old('second_name') }}">
                                </div>
                                <div class="col-sm-4 mb-4">
                                    <label for="thirdNameLabel" class="form-label">Tercer Nombre</label>
                                    <input type="text" class="form-control @error('third_name') is-invalid @enderror" name="third_name" id="thirdNameLabel" placeholder="Otros nombres" value="{{ old('third_name') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4 mb-4">
                                    <label for="firstLastLabel" class="form-label">Primer Apellido</label>
                                    <input type="text" class="form-control @error('first_last_name') is-invalid @enderror" name="first_last_name" id="firstLastLabel" placeholder="Ej. Pérez" value="{{ old('first_last_name') }}" required>
                                </div>
                                <div class="col-sm-4 mb-4">
                                    <label for="secondLastLabel" class="form-label">Segundo Apellido</label>
                                    <input type="text" class="form-control @error('second_last_name') is-invalid @enderror" name="second_last_name" id="secondLastLabel" placeholder="Ej. García" value="{{ old('second_last_name') }}">
                                </div>
                                <div class="col-sm-4 mb-4">
                                    <label for="marriedLastLabel" class="form-label">Apellido de Casada</label>
                                    <input type="text" class="form-control @error('married_last_name') is-invalid @enderror" name="married_last_name" id="marriedLastLabel" placeholder="Si aplica" value="{{ old('married_last_name') }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 mb-4">
                                    <label for="cuiLabel" class="form-label">CUI (DPI)</label>
                                    <input type="text" class="form-control @error('cui') is-invalid @enderror" name="cui" id="cuiLabel" placeholder="13 dígitos" value="{{ old('cui') }}" required maxlength="13" minlength="13">
                                    @error('cui')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end align-items-center gap-3">
                            <a href="{{ route('patient-relatives.index') }}" class="btn btn-white">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Registrar Familiar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('#mainForm');
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                
            }
            form.classList.add('was-validated');
        }, false);
    });
</script>
@endpush
