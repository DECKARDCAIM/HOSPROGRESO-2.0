@extends('layouts.panel')
@section('title', 'Listado de Pacientes')
@section('content')
    <main id="content" role="main" class="main">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-end">
                    <div class="col-sm mb-2 mb-sm-0">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-no-gutter">
                                <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Inicio</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Pacientes</li>
                            </ol>
                        </nav>
                        <h1 class="page-header-title">Listado de Pacientes</h1>
                    </div>
                    <div class="col-sm-auto">
                        <a class="btn btn-primary" href="{{ route('patients.create') }}">
                            <i class="bi-person-plus-fill me-1"></i> Agregar Paciente
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-lg-4 mb-3 mb-lg-5">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2">Pacientes en Total</h6>
                            <div class="row align-items-center gx-2">
                                <div class="col">
                                    <span class="js-counter display-4 text-dark">{{ $totalPatients ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4 mb-3 mb-lg-5">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2">Pacientes Hombres</h6>
                            <div class="row align-items-center gx-2">
                                <div class="col">
                                    <span class="js-counter display-4 text-dark">{{ $malePercentage ?? 0 }}</span>
                                    <span class="display-4 text-dark">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4 mb-3 mb-lg-5">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2">Pacientes Mujeres</h6>
                            <div class="row align-items-center gx-2">
                                <div class="col">
                                    <span class="js-counter display-4 text-dark">{{ $femalePercentage ?? 0 }}</span>
                                    <span class="display-4 text-dark">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header card-header-content-md-between">
                    <div class="mb-2 mb-md-0">
                        <form id="searchForm" method="GET" action="{{ route('patients.index') }}">
                            @if (request('per_page'))
                                <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                            @endif
                            @if (request('gender_id'))
                                <input type="hidden" name="gender_id" value="{{ request('gender_id') }}">
                            @endif
                            @if (request('civil_status_id'))
                                <input type="hidden" name="civil_status_id" value="{{ request('civil_status_id') }}">
                            @endif
                            @if (request('ethnicity_id'))
                                <input type="hidden" name="ethnicity_id" value="{{ request('ethnicity_id') }}">
                            @endif
                            @if (request('linguistic_community_id'))
                                <input type="hidden" name="linguistic_community_id"
                                    value="{{ request('linguistic_community_id') }}">
                            @endif
                            @if (request('department_id'))
                                <input type="hidden" name="department_id" value="{{ request('department_id') }}">
                            @endif
                            @if (request('municipality_id'))
                                <input type="hidden" name="municipality_id" value="{{ request('municipality_id') }}">
                            @endif
                            <div class="input-group input-group-merge input-group-flush">
                                <button type="submit" class="input-group-prepend input-group-text bg-transparent border-0">
                                    <i class="bi-search"></i>
                                </button>
                                <input id="datatableSearch" type="text" name="search" class="form-control" placeholder="Buscar pacientes" aria-label="Buscar pacientes" value="{{ request('search') }}">
                                @if (request('search'))
                                    <a class="input-group-append input-group-text text-muted" href="{{ route('patients.index', request()->except('search')) }}">
                                        <i class="bi-x-lg"></i>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                    <div class="d-grid d-sm-flex justify-content-md-end align-items-sm-center gap-2">
                        <div id="datatableCounterInfo" style="display: none;">
                            <div class="d-flex align-items-center">
                                <span class="fs-5 me-3">
                                    <span id="datatableCounter">0</span> Seleccionados
                                </span>
                                <a class="btn btn-outline-danger btn-sm" href="javascript:;" id="deleteSelectedBtn">
                                    <i class="bi-trash"></i> Eliminar
                                </a>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button type="button" class="btn btn-white btn-sm dropdown-toggle w-100" id="usersExportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi-download me-2"></i> Exportar
                            </button>
                            <div class="dropdown-menu dropdown-menu-sm-end" aria-labelledby="usersExportDropdown">
                                <span class="dropdown-header">Opciones</span>
                                <a id="export-copy" class="dropdown-item" href="javascript:;">
                                    <img class="avatar avatar-xss avatar-4x3 me-2" src="{{ asset('dist/svg/illustrations/copy-icon.svg') }}" alt="Copiar"> Copiar
                                </a>
                                <a id="export-print" class="dropdown-item" href="javascript:;">
                                    <img class="avatar avatar-xss avatar-4x3 me-2" src="{{ asset('dist/svg/illustrations/print-icon.svg') }}" alt="Imprimir"> Imprimir
                                </a>
                                <div class="dropdown-divider"></div>
                                <span class="dropdown-header">Opciones de descarga</span>
                                <a id="export-excel" class="dropdown-item" href="javascript:;">
                                    <img class="avatar avatar-xss avatar-4x3 me-2" src="{{ asset('dist/svg/brands/excel-icon.svg') }}" alt="Excel"> Excel
                                </a>
                                <a id="export-csv" class="dropdown-item" href="javascript:;">
                                    <img class="avatar avatar-xss avatar-4x3 me-2" src="{{ asset('dist/svg/components/placeholder-csv-format.svg') }}" alt="CSV"> .CSV
                                </a>
                                <a id="export-pdf" class="dropdown-item" href="javascript:;">
                                    <img class="avatar avatar-xss avatar-4x3 me-2" src="{{ asset('dist/svg/brands/pdf-icon.svg') }}" alt="PDF"> PDF
                                </a>
                            </div>
                        </div>
                        <div class="dropdown">
                            @php
                                $activeFilters = 0;
                                if (request('status')) {
                                    $activeFilters++;
                                }
                                if (request('gender_id')) {
                                    $activeFilters++;
                                }
                                if (request('civil_status_id')) {
                                    $activeFilters++;
                                }
                                if (request('ethnicity_id')) {
                                    $activeFilters++;
                                }
                                if (request('linguistic_community_id')) {
                                    $activeFilters++;
                                }
                                if (request('country_id')) {
                                    $activeFilters++;
                                }
                                if (request('department_id')) {
                                    $activeFilters++;
                                }
                                if (request('municipality_id')) {
                                    $activeFilters++;
                                }
                                if (request('birth_date_from') || request('birth_date_to')) {
                                    $activeFilters++;
                                }
                            @endphp
                            <button type="button" class="btn btn-white btn-sm w-100" id="usersFilterDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false" title="Filtrar">
                                <i class="bi-filter" style="font-style: normal;"> Filtrar </i>
                                @if ($activeFilters > 0)
                                    <span class="badge bg-soft-dark text-dark rounded-circle ms-1">{{ $activeFilters }}</span>
                                @endif
                            </button>
                            <div class="dropdown-menu dropdown-menu-sm-end dropdown-card card-dropdown-filter-centered" aria-labelledby="usersFilterDropdown" style="min-width: 25rem;">
                                <div class="card">
                                    <div class="card-header card-header-content-between">
                                        <h5 class="card-header-title">Filtrar pacientes</h5>
                                    </div>
                                    <div class="card-body">
                                        <form id="filterForm" method="GET" action="{{ route('patients.index') }}">
                                            @if (request('search'))
                                                <input type="hidden" name="search" value="{{ request('search') }}">
                                            @endif
                                            <div class="row">
                                                <div class="col-12 mb-3">
                                                    <label class="form-label">Estado</label>
                                                    <select name="status" id="filter_status" class="js-select form-select form-select-sm">
                                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activos</option>
                                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactivos</option>
                                                    </select>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <label class="form-label">Fecha de Nacimiento</label>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <label class="form-label">Desde</label>
                                                            <div class="input-group input-group-sm">
                                                                <input type="date" name="birth_date_from" id="filter_birth_date_from" class="form-control form-control-sm" value="{{ request('birth_date_from') }}" placeholder="Desde">
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <label class="form-label">Hasta</label>
                                                            <div class="input-group input-group-sm">
                                                                <input type="date" name="birth_date_to" id="filter_birth_date_to" class="form-control form-control-sm" value="{{ request('birth_date_to') }}" placeholder="Hasta">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <label class="form-label">País</label>
                                                    <select name="country_id" id="filter_country_id" class="js-select form-select form-select-sm">
                                                        <option value="">Todos</option>
                                                        @foreach ($countries as $country)
                                                            <option value="{{ $country->id }}" {{ request('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <label class="form-label">Departamento</label>
                                                    <select name="department_id" id="filter_department_id" class="js-select form-select form-select-sm">
                                                        <option value="">Todos</option>
                                                        @if (request('country_id'))
                                                            @foreach ($departments->where('country_id', request('country_id')) as $department)
                                                                <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <label class="form-label">Municipio</label>
                                                    <select name="municipality_id" id="filter_municipality_id" class="js-select form-select form-select-sm">
                                                        <option value="">Todos</option>
                                                        @if (request('department_id'))
                                                            @foreach ($municipalities->where('department_id', request('department_id')) as $municipality)
                                                                <option value="{{ $municipality->id }}" {{ request('municipality_id') == $municipality->id ? 'selected' : '' }}>{{ $municipality->name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <label class="form-label">Género</label>
                                                    <select name="gender_id" id="filter_gender_id" class="js-select form-select form-select-sm">
                                                        <option value="">Todos</option>
                                                        @foreach ($genders as $gender)
                                                            <option value="{{ $gender->id }}" {{ request('gender_id') == $gender->id ? 'selected' : '' }}>{{ $gender->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <label class="form-label">Estado Civil</label>
                                                    <select name="civil_status_id" id="filter_civil_status_id" class="js-select form-select form-select-sm">
                                                        <option value="">Todos</option>
                                                        @foreach ($civilStatuses as $civilStatus)
                                                            <option value="{{ $civilStatus->id }}" {{ request('civil_status_id') == $civilStatus->id ? 'selected' : '' }}>{{ $civilStatus->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <label class="form-label">Etnia</label>
                                                    <select name="ethnicity_id" id="filter_ethnicity_id" class="js-select form-select form-select-sm">
                                                        <option value="">Todas</option>
                                                        @foreach ($ethnicities as $ethnicity)
                                                            <option value="{{ $ethnicity->id }}" {{ request('ethnicity_id') == $ethnicity->id ? 'selected' : '' }}>{{ $ethnicity->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <label class="form-label">Comunidad Lingüística</label>
                                                    <select name="linguistic_community_id" id="filter_linguistic_community_id" class="js-select form-select form-select-sm">
                                                        <option value="">Todas</option>
                                                        @foreach ($linguisticCommunities as $linguisticCommunity)
                                                            <option value="{{ $linguisticCommunity->id }}" {{ request('linguistic_community_id') == $linguisticCommunity->id ? 'selected' : '' }}>{{ $linguisticCommunity->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="d-grid gap-2">
                                                <button type="submit" class="btn btn-primary btn-sm">Aplicar Filtros</button>
                                                <a href="{{ route('patients.index') }}" class="btn btn-white btn-sm">Limpiar Filtros</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive datatable-custom position-relative">
                    <table id="datatable" class="table table-lg table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                        <thead class="thead-light">
                            <tr>
                                <th class="table-column-pe-0 no-sort">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="datatableCheckAll">
                                        <label class="form-check-label" for="datatableCheckAll"></label>
                                    </div>
                                </th>
                                <th>Nombre</th>
                                <th>Expediente</th>
                                <th>Nacimiento</th>
                                <th>Edad</th>
                                <th>Ubicación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($patients as $patient)
                                <tr>
                                    <td class="table-column-pe-0">
                                        <div class="form-check">
                                            <input class="form-check-input patient-checkbox" type="checkbox" name="patient_ids[]" value="{{ $patient->id }}" id="patientCheck{{ $patient->id }}">
                                            <label class="form-check-label" for="patientCheck{{ $patient->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="table-column-ps-0">
                                        <a class="d-flex align-items-start" href="{{ route('patients.show', $patient) }}">
                                            <div class="avatar avatar-soft-primary avatar-circle flex-shrink-0">
                                                <span class="avatar-initials">{{ strtoupper(substr($patient->first_name ?? ($patient->mother->first_name ?? 'P'), 0, 1)) }}</span>
                                            </div>
                                            <div class="ms-3 flex-grow-1">
                                                <span class="d-block h5 text-inherit mb-0">{{ $patient->only_names ?: 'Sin nombre' }}</span>
                                                <span class="d-block h5 text-inherit mb-0">{{ $patient->only_last_names ?: '' }}</span>
                                                <span class="d-block fs-6 text-muted">{{ $patient->cui ?: 'Sin CUI' }}</span>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        @if ($patient->clinicalRecord)
                                            <span class="text-body fw-semibold">{{ $patient->clinicalRecord->record_number }}</span>
                                        @else
                                            <span class="text-muted fst-italic">Sin expediente</span>
                                        @endif
                                    </td>
                                    <td>{{ $patient->birth_date ? $patient->birth_date->format('d/m/Y') : '-' }}</td>
                                    <td>{{ $patient->age ? $patient->age . ' años' : '-' }}</td>
                                    <td>
                                        <span class="d-block text-inherit mb-0">{{ ($patient->municipality && $patient->municipality->department && $patient->municipality->department->country) ? $patient->municipality->department->country->name : '-' }}</span>
                                        <span class="d-block fs-6 text-body">{{ $patient->department ? $patient->department->name : '-' }}</span>
                                        <span class="d-block fs-6 text-muted">{{ $patient->municipality ? $patient->municipality->name : '-' }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a class="btn btn-white btn-sm" href="{{ route('patients.show', $patient) }}" title="Ver">
                                                <i class="bi-eye-fill"></i>
                                            </a>
                                            @if ($patient->trashed())
                                                <form action="{{ route('patients.restore', $patient->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-white btn-sm" title="Reactivar">
                                                        <i class="bi-arrow-clockwise"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <a class="btn btn-white btn-sm" href="{{ route('patients.edit', $patient) }}" title="Editar">
                                                    <i class="bi-pencil-fill"></i>
                                                </a>
                                                <form action="{{ route('patients.destroy', $patient) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de eliminar este paciente?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-white btn-sm" title="Eliminar">
                                                        <i class="bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <img class="mb-3" src="{{ asset('dist/svg/illustrations/oc-error.svg') }}" alt="Image Description" style="width: 10rem;" data-hs-theme-appearance="default">
                                        <p class="text-muted mb-0">No hay pacientes registrados</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                        <div class="col-sm mb-2 mb-sm-0">
                            <div class="d-flex justify-content-center justify-content-sm-start align-items-center">
                                <span class="me-2">Mostrando:</span>
                                <div class="tom-select-custom">
                                    <select id="datatableEntries" class="js-select form-select form-select-borderless w-auto" autocomplete="off" data-hs-tom-select-options='{ "searchInDropdown": false, "hideSearch": true }' onchange="window.location.href = '{{ route('patients.index', request()->except(['per_page', 'page'])) }}' + (window.location.search.includes('?') ? '&' : '?') + 'per_page=' + this.value">
                                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10
                                        </option>
                                        <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25
                                        </option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50
                                        </option>
                                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100
                                        </option>
                                    </select>
                                </div>
                                <span class="text-secondary me-2">de</span>
                                <span id="datatableWithPaginationInfoTotalQty">{{ $patients->total() }}</span>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex justify-content-center justify-content-sm-end">
                                {{ $patients->appends(request()->query())->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#filter_country_id').on('change', function() {
                var countryId = $(this).val();
                $.ajax({
                    url: '{{ route('patients.get-departments-by-country') }}',
                    type: 'GET',
                    data: {
                        country_id: countryId
                    },
                }).done(function(response) {
                    $('#filter_department_id').empty();
                    $('#filter_department_id').append('<option value="">Todos</option>');
                    $.each(response, function(index, department) {
                        $('#filter_department_id').append('<option value="' + department
                            .id + '">' + department.name + '</option>');
                    });
                });
            });
            $('#filter_department_id').on('change', function() {
                var departmentId = $(this).val();
                $.ajax({
                    url: '{{ route('patients.get-municipalities-by-department') }}',
                    type: 'GET',
                    data: {
                        department_id: departmentId
                    },
                }).done(function(response) {
                    $('#filter_municipality_id').empty();
                    $('#filter_municipality_id').append('<option value="">Todos</option>');
                    $.each(response, function(index, municipality) {
                        $('#filter_municipality_id').append('<option value="' + municipality
                            .id + '">' + municipality.name + '</option>');
                    });
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#export-copy').on('click', function() {
                var selectedPatients = $('.patient-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();
                if (selectedPatients.length > 0) {
                    var patients = selectedPatients.join(',');
                    navigator.clipboard.writeText(patients);
                }
            });
        });
        $('#export-print').on('click', function() {
            var selectedPatients = $('.patient-checkbox:checked').map(function() {
                return $(this).val();
            }).get();
            if (selectedPatients.length > 0) {
                var patients = selectedPatients.join(',');
                window.print(patients);
            }
        });
        $('#export-excel').on('click', function() {
            window.location.href = '{{ route('patients.export.excel') }}';
        });
        $('#export-csv').on('click', function() {
            window.location.href = '{{ route('patients.export.csv') }}';
        });
        $('#export-pdf').on('click', function() {
            window.location.href = '{{ route('patients.export.pdf') }}';
        });
    </script>
@endpush