@extends('layouts.panel')
@section('title', 'Crear Departamento')

@section('styles')
    <link rel="stylesheet" href="{{ asset('vendor/tom-select/dist/css/tom-select.bootstrap5.css') }}">
@endsection

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
                            <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Mantenimiento</a></li>
                            <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('departments.index') }}">Departamentos</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Crear</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">Crear departamento</h1>
                </div>
                <div class="col-auto">
                    <a href="{{ route('departments.index') }}" class="btn btn-primary">
                        <i class="bi-arrow-left"></i> Regresar
                    </a>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row justify-content-lg-center">
            <div class="col-lg-9">
                <!-- Card -->
                <form action="{{ route('departments.store') }}" method="POST" id="departmentForm" class="needs-validation" novalidate>
                    @csrf
                    
                    <div class="card card-lg mb-3 mb-lg-5">
                        <!-- Header -->
                        <div class="card-header border-bottom">
                            <h4 class="card-header-title">Detalles del departamento</h4>
                        </div>
                        <!-- End Header -->

                        <!-- Body -->
                        <div class="card-body">
                            <!-- Form Group -->
                            <div class="mb-4">
                                <label for="countrySelect" class="form-label">País</label>
                                <div class="tom-select-custom">
                                    <select class="js-select form-select @error('country_id') is-invalid @enderror" 
                                            name="country_id" id="countrySelect" required
                                            data-hs-tom-select-options='{
                                                "placeholder": "Seleccione un país..."
                                            }'>
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
                            <!-- End Form Group -->

                            <!-- Form Group -->
                            <div class="mb-4">
                                <label for="nameLabel" class="form-label">Nombre del departamento</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" id="nameLabel" placeholder="Ej. Guatemala" 
                                       value="{{ old('name') }}" required minlength="5" autocomplete="off">
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- End Form Group -->
                        </div>
                        <!-- End Body -->

                        <!-- Footer -->
                        <div class="card-footer d-flex justify-content-end align-items-center gap-3">
                            <a href="{{ route('departments.index') }}" class="btn btn-white">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                        </div>
                        <!-- End Footer -->
                    </div>
                </form>
                <!-- End Card -->
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script src="{{ asset('vendor/tom-select/dist/js/tom-select.complete.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // INITIALIZATION OF TOM SELECT
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
                
                window.showToast('Atención', 'Por favor, completa los campos requeridos correctamente.', 'warning');
            }
            form.classList.add('was-validated');
        }, false);


    });
</script>
@endpush

