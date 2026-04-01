@extends('layouts.panel')
@section('title', 'Editar Municipio')

@section('styles')
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
                                <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Inicio</a>
                                </li>
                                <li class="breadcrumb-item"><a class="breadcrumb-link"
                                        href="{{ route('home') }}">Mantenimiento</a></li>
                                <li class="breadcrumb-item"><a class="breadcrumb-link"
                                        href="{{ route('municipalities.index') }}">Municipios</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Editar</li>
                            </ol>
                        </nav>
                        <h1 class="page-header-title">Editar municipio</h1>
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
                    <form action="{{ route('municipalities.update', $municipality->id) }}" method="POST"
                        id="municipalityForm" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')

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
                                    <div class="tom-select-custom">
                                        <select class="js-select form-select @error('country_id') is-invalid @enderror"
                                            name="country_id" id="countrySelect" required
                                            data-hs-tom-select-options='{
                                                "placeholder": "Seleccione un país..."
                                            }'>
                                            <option value="" disabled>Selecciona un país...</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}"
                                                    {{ old('country_id', $municipality->department->country_id) == $country->id ? 'selected' : '' }}>
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
                                    <label for="departmentSelect" class="form-label">Departamento</label>
                                    <div class="tom-select-custom">
                                        <select class="js-select form-select @error('department_id') is-invalid @enderror"
                                            name="department_id" id="departmentSelect" required
                                            data-hs-tom-select-options='{
                                                "placeholder": "Seleccione un departamento..."
                                            }'>
                                            <option value="" disabled>Selecciona un departamento...</option>
                                            {{-- Se cargará vía AJAX o se mantendrá el actual --}}
                                        </select>
                                    </div>
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
                                        value="{{ old('name', $municipality->name) }}" required minlength="5"
                                        autocomplete="off">
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
        document.addEventListener('DOMContentLoaded', function() {
            // INITIALIZATION OF TOM SELECT
            HSCore.components.HSTomSelect.init('.js-select')

            const countrySelect = document.getElementById('countrySelect');
            const departmentSelect = document.getElementById('departmentSelect');
            const form = document.getElementById('municipalityForm');

            const initialDepartmentId = "{{ old('department_id', $municipality->department_id) }}";

            function resetSelect(select, placeholder) {
                select.innerHTML = `<option value="">${placeholder}</option>`;
                select.disabled = true;
                select.value = '';

                const ts = select.tomselect;
                if (ts) {
                    ts.clearOptions();
                    ts.clear(); // Limpia la selección actual
                    ts.sync();
                    ts.disable();
                }
            }

            function populateSelect(select, items, placeholder, selectedId = null) {
                // Reconstruimos el HTML del select original
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
                    // Sincronizamos TomSelect con el nuevo HTML que acabamos de crear
                    ts.clearOptions();
                    ts.clear();
                    ts.sync();

                    // Si había un ID seleccionado y existe en las opciones, lo marcamos
                    if (selectedId && items.some(item => item.id == selectedId)) {
                        ts.setValue(selectedId.toString());
                    }

                    ts.enable();
                }
            }

            // Logic for dependent selects
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
                    // En lugar de añadir opciones manuales, actualizamos el HTML y sincronizamos
                    departmentSelect.innerHTML = '<option value="">Cargando departamentos...</option>';
                    tsDept.sync();
                }

                // Fetch departments via AJAX
                fetch(`{{ route('patients.get-departments-by-country') }}?country_id=${countryId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Si el usuario acaba de cambiar de país (no es la carga inicial), no forzamos el initialDepartmentId
                        const isInitialLoad = (countryId ==
                            "{{ old('country_id', $municipality->department->country_id) }}");
                        const targetDeptId = isInitialLoad ? initialDepartmentId : null;

                        populateSelect(departmentSelect, data, 'Selecciona un departamento...',
                            targetDeptId);
                    })
                    .catch(error => {
                        console.error('Error fetching departments:', error);
                        resetSelect(departmentSelect, 'Error al cargar');
                        window.showToast('Error', 'No se pudieron cargar los departamentos.', 'error');
                    });
            });

            // Initial load
            if (countrySelect.value) {
                // Esto dispara el evento change y carga los departamentos al iniciar la página
                countrySelect.dispatchEvent(new Event('change'));
            }

            // Form validation
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();

                    const preloader = document.getElementById('loading-spinner');
                    if (preloader) {
                        preloader.style.setProperty('display', 'none', 'important');
                        preloader.style.opacity = '0';
                    }

                    window.showToast('Atención', 'Por favor, completa los campos requeridos correctamente.',
                        'warning');
                }
                form.classList.add('was-validated');
            }, false);
        });
    </script>
@endpush
