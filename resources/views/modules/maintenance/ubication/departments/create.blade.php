@extends('layouts.panel')
@section('title', 'Crear Departamento')
@section('content')
<main id="content" role="main" class="main">
    <div class="content container-fluid">

        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Inicio</a></li>
                            <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Mantenimiento</a></li>
                            <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('maintenance.departments.index') }}">Departamentos</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Crear</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">Crear departamento</h1>
                </div>
                <div class="col-auto">
                    <a href="{{ route('maintenance.departments.index') }}" class="btn btn-primary">
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



        <div class="row justify-content-lg-center">
            <div class="col-lg-9">

            <form action="{{ route('maintenance.departments.store') }}" method="POST" id="departmentForm" class="needs-validation" novalidate>
                    @csrf
                    <div class="card card-lg mb-3 mb-lg-5">
                        <div class="card-header border-bottom">
                            <h4 class="card-header-title">Detalles del departamento</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label for="countrySelect" class="form-label">País</label>
                                <div class="tom-select-custom">
                                    <select class="js-select form-select @error('country_id') is-invalid @enderror" name="country_id" id="countrySelect" required data-hs-tom-select-options='{"placeholder": "Seleccione un país..."}'>
                                        <option value="" selected disabled>Selecciona un país...</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                                {{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('country_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="nameLabel" class="form-label">Nombre del departamento</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="nameLabel" placeholder="Ej. Guatemala" value="{{ old('name') }}" required minlength="5" autocomplete="off">
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end align-items-center gap-3">
                            <a href="{{ route('maintenance.departments.index') }}" class="btn btn-white">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
@push('scripts')
<script type="module">
    document.addEventListener('DOMContentLoaded', function () {
        HSCore.components.HSTomSelect.init('.js-select')

        const form = document.querySelector('#departmentForm');

        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();

                const preloader = document.getElementById('loading-spinner');
                if (preloader) {
                    preloader.style.setProperty('display', 'none', 'important');
                    preloader.style.opacity = '0';
                }

            }
            form.classList.add('was-validated');
        }, false);

    });
</script>
@endpush
