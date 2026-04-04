@extends('layouts.panel')
@section('title', 'Crear Municipio')
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
        <div class="row justify-content-lg-center">
            <div class="col-lg-9">
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

            <form action="{{ route('municipalities.store') }}" method="POST" id="municipalityForm" class="needs-validation" novalidate>
                    @csrf
                    <div class="card card-lg mb-3 mb-lg-5">
                        <div class="card-header border-bottom">
                            <h4 class="card-header-title">Detalles del municipio</h4>
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
                                <label for="departmentSelect" class="form-label">Departamento</label>
                                <div class="tom-select-custom">
                                    <select class="js-select form-select @error('department_id') is-invalid @enderror" name="department_id" id="departmentSelect" required disabled data-hs-tom-select-options='{"placeholder": "Seleccione primero un país..."}'>
                                        <option value="" selected disabled>Selecciona primero un país...</option>
                                    </select>
                                </div>
                                @error('department_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="nameLabel" class="form-label">Nombre del municipio</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="nameLabel" placeholder="Ej. Villa Nueva" value="{{ old('name') }}" required minlength="5" autocomplete="off">
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end align-items-center gap-3">
                            <a href="{{ route('municipalities.index') }}" class="btn btn-white">Cancelar</a>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        HSCore.components.HSTomSelect.init('.js-select')

        const countrySelect = document.getElementById('countrySelect');
        const departmentSelect = document.getElementById('departmentSelect');
        const form = document.getElementById('municipalityForm');

        function resetSelect(select, placeholder) {
            select.innerHTML = `<option value="">${placeholder}</option>`;
            select.disabled = true;
            select.value = '';

            const ts = select.tomselect;
            if (ts) {
                ts.clearOptions();
                ts.clear();
                ts.sync();
                ts.disable();
            }
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

            const ts = select.tomselect;
            if (ts) {
                ts.clearOptions();
                ts.clear();
                ts.sync();

                if (selectedId && items.some(item => item.id == selectedId)) {
                    ts.setValue(selectedId.toString());
                }

                ts.enable();
            }
        }

        countrySelect.addEventListener('change', function() {
            const countryId = this.value;
            
            if (!countryId) {
                resetSelect(departmentSelect, 'Selecciona un país...');
                return;
            }

            const tsDept = departmentSelect.tomselect;
            if (tsDept) {
                tsDept.clearOptions();
                tsDept.clear();
                departmentSelect.innerHTML = '<option value="">Cargando departamentos...</option>';
                tsDept.sync();
            }

            fetch(`{{ route('patients.get-departments-by-country') }}?country_id=${countryId}`)
                .then(response => response.json())
                .then(data => {
                    const oldDeptId = "{{ old('department_id') }}";
                    const isInitialLoad = (countryId == "{{ old('country_id') }}");
                    const targetDeptId = isInitialLoad ? oldDeptId : null;

                    populateSelect(departmentSelect, data, 'Selecciona un departamento...', targetDeptId);
                })
                .catch(error => {
                    console.error('Error fetching departments:', error);
                    resetSelect(departmentSelect, 'Error al cargar');
                    
                });
        });

        if (countrySelect.value) {
            countrySelect.dispatchEvent(new Event('change'));
        }
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