@extends('layouts.panel')
@section('title', 'Crear Municipio')

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
                            <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('municipalities.index') }}">Municipios</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Crear</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">Crear municipio</h1>
                </div>
                <div class="col-auto">
                    <a href="{{ route('municipalities.index') }}" class="btn btn-primary">
                        <i class="bi-arrow-left"></i> Regresar
                    </a>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row justify-content-lg-center">
            <div class="col-lg-9">
                <!-- Card -->
                <form action="{{ route('municipalities.store') }}" method="POST" id="municipalityForm" class="needs-validation" novalidate>
                    @csrf
                    
                    <div class="card card-lg mb-3 mb-lg-5">
                        <!-- Header -->
                        <div class="card-header border-bottom">
                            <h4 class="card-header-title">Detalles del municipio</h4>
                        </div>
                        <!-- End Header -->

                        <!-- Body -->
                        <div class="card-body">
                            <!-- Form Group -->
                            <div class="mb-4">
                                <label for="countrySelect" class="form-label">País</label>
                                <select class="form-select @error('country_id') is-invalid @enderror" 
                                        name="country_id" id="countrySelect" required>
                                    <option value="" selected disabled>Selecciona un país...</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- End Form Group -->

                            <!-- Form Group -->
                            <div class="mb-4">
                                <label for="departmentSelect" class="form-label">Departamento</label>
                                <select class="form-select @error('department_id') is-invalid @enderror" 
                                        name="department_id" id="departmentSelect" required>
                                    <option value="" selected disabled>Selecciona un departamento...</option>
                                    {{-- Se cargará vía AJAX --}}
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <!-- End Form Group -->

                            <!-- Form Group -->
                            <div class="mb-4">
                                <label for="nameLabel" class="form-label">Nombre del municipio</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       name="name" id="nameLabel" placeholder="Ej. Villa Nueva" 
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
                            <a href="{{ route('municipalities.index') }}" class="btn btn-white">Cancelar</a>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const countrySelect = document.getElementById('countrySelect');
        const departmentSelect = document.getElementById('departmentSelect');
        const form = document.getElementById('municipalityForm');

        // Logic for dependent selects
        countrySelect.addEventListener('change', function() {
            const countryId = this.value;
            
            // Clear department select
            departmentSelect.innerHTML = '<option value="" selected disabled>Cargando departamentos...</option>';
            
            if (!countryId) {
                departmentSelect.innerHTML = '<option value="" selected disabled>Selecciona un departamento...</option>';
                return;
            }

            // Fetch departments via AJAX
            fetch(`{{ route('patients.get-departments-by-country') }}?country_id=${countryId}`)
                .then(response => response.json())
                .then(data => {
                    departmentSelect.innerHTML = '<option value="" selected disabled>Selecciona un departamento...</option>';
                    data.forEach(dept => {
                        const option = document.createElement('option');
                        option.value = dept.id;
                        option.textContent = dept.name;
                        // Keep old value if exists (for validation errors)
                        if (dept.id == "{{ old('department_id') }}") {
                            option.selected = true;
                        }
                        departmentSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching departments:', error);
                    departmentSelect.innerHTML = '<option value="" selected disabled>Error al cargar</option>';
                    window.showToast('Error', 'No se pudieron cargar los departamentos.', 'error');
                });
        });

        // Trigger change if country is already selected (on validation error)
        if (countrySelect.value) {
            countrySelect.dispatchEvent(new Event('change'));
        }

        // Form validation
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
