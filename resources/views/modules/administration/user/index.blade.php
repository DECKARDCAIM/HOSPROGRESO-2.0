@extends('layouts.panel')
@section('title', 'Usuarios')
@section('content')
    <main id="content" role="main" class="main">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-end">
                    <div class="col-sm mb-2 mb-sm-0">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-no-gutter">
                                <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Inicio</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Usuarios</li>
                            </ol>
                        </nav>
                        <h1 class="page-header-title">Listado de usuarios</h1>
                    </div>
                    <div class="col-sm-auto">
                        <a class="btn btn-primary" href="{{ route('users.create') }}">
                            <i class="bi-person-plus-fill me-1"></i> Agregar usuario
                        </a>
                    </div>
                </div>
            </div>
            @if (session('success'))
                <div class="alert alert-success text-white mb-4" role="alert">
                    <strong>¡Éxito!</strong> {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger text-white mb-4" role="alert">
                    <strong>¡Ups!</strong> {{ session('error') }}
                </div>
            @endif
            @if (session('notification'))
                <div class="alert alert-{{ session('notification')['type'] == 'success' ? 'success' : (session('notification')['type'] == 'error' ? 'danger' : 'info') }} text-white mb-4" role="alert">
                    <strong>¡Atención!</strong> {{ session('notification')['message'] }}
                </div>
            @endif

            <div class="row">
                <div class="col-sm-6 col-md-4 mb-3 mb-lg-5">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2">Total usuarios registrados</h6>
                            <div class="row align-items-center gx-2">
                                <div class="col">
                                    <span class="js-counter display-4 text-dark">{{ $totalUsers ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 mb-3 mb-lg-5">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2">Usuarios activos</h6>
                            <div class="row align-items-center gx-2">
                                <div class="col">
                                    <span class="js-counter display-4 text-dark">{{ $activeUsers ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 mb-3 mb-lg-5">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2">Usuarios inactivos</h6>
                            <div class="row align-items-center gx-2">
                                <div class="col">
                                    <span class="js-counter display-4 text-dark">{{ $inactiveUsers ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header card-header-content-md-between">
                    <div class="mb-2 mb-md-0">
                        <form action="{{ route('users.index') }}" method="GET">
                            @if (request('per_page'))
                                <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                            @endif
                            <div class="input-group input-group-merge input-group-flush">
                                <button type="submit" class="input-group-prepend input-group-text bg-transparent border-0">
                                    <i class="bi-search"></i>
                                </button>
                                <input name="search" type="text" class="form-control" placeholder="Buscar"
                                    aria-label="Buscar usuarios" value="{{ request('search') }}">
                                @if (request('search'))
                                    <a class="input-group-append input-group-text text-muted" href="{{ route('users.index', request()->except('search')) }}">
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
                                    <span id="datatableCounter">0</span>Selected
                                </span>
                                <a class="btn btn-outline-danger btn-sm" href="javascript:;">
                                    <i class="bi-trash"></i> Delete
                                </a>
                            </div>
                        </div>
                        @php
                            $activeFilters = 0;
                            if (request()->filled('role_id')) {
                                $activeFilters++;
                            }
                            if (request()->filled('work_department_id')) {
                                $activeFilters++;
                            }
                            if (request()->filled('specialty_id')) {
                                $activeFilters++;
                            }
                            if (request()->filled('status') && request('status') !== 'active') {
                                $activeFilters++;
                            }
                            if (request()->filled('gender_id')) {
                                $activeFilters++;
                            }
                            if (request()->filled('country_id')) {
                                $activeFilters++;
                            }
                            if (request()->filled('department_id')) {
                                $activeFilters++;
                            }
                            if (request()->filled('municipality_id')) {
                                $activeFilters++;
                            }
                            if (request()->filled('birth_date_from') || request()->filled('birth_date_to')) {
                                $activeFilters++;
                            }
                        @endphp
                        <div class="dropdown">
                            <button type="button" class="btn btn-white btn-sm w-100" id="usersFilterDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                <i class="bi-filter" style="font-style: normal;"> Filtrar </i>
                                @if ($activeFilters > 0)
                                    <span class="badge bg-soft-dark text-dark rounded-circle ms-1">{{ $activeFilters }}</span>
                                @endif
                            </button>
                            <div class="dropdown-menu dropdown-menu-sm-end dropdown-card card-dropdown-filter-centered" aria-labelledby="usersFilterDropdown" style="min-width: 25rem;">
                                <div class="card">
                                    <div class="card-header card-header-content-between">
                                        <h5 class="card-header-title">Filtrar usuarios</h5>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('users.index') }}" method="GET">
                                            @if (request('search'))
                                                <input type="hidden" name="search" value="{{ request('search') }}">
                                            @endif
                                            @if (request('per_page'))
                                                <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                                            @endif
                                            <div class="row">
                                                <div class="col-12 mb-3">
                                                    <label class="form-label">Estado</label>
                                                    <select name="status" class="js-select form-select form-select-sm">
                                                        <option value="active" {{ request('status') == 'active' || !request()->has('status') ? 'selected' : '' }}>Activos</option>
                                                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactivos</option>
                                                    </select>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <label class="form-label">Rol</label>
                                                    <select name="role_id" class="js-select form-select form-select-sm">
                                                        <option value="">Todos</option>
                                                        @foreach ($roles as $role)
                                                            <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <label class="form-label">Departamento de Trabajo</label>
                                                    <select name="work_department_id" class="js-select form-select form-select-sm">
                                                        <option value="">Todos</option>
                                                        @foreach ($workDepartments as $wd)
                                                            <option value="{{ $wd->id }}" {{ request('work_department_id') == $wd->id ? 'selected' : '' }}>{{ $wd->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <label class="form-label">Especialidad</label>
                                                    <select name="specialty_id" class="js-select form-select form-select-sm">
                                                        <option value="">Todas</option>
                                                        @foreach ($specialties as $specialty)
                                                            <option value="{{ $specialty->id }}" {{ request('specialty_id') == $specialty->id ? 'selected' : '' }}>{{ $specialty->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <label class="form-label">Género</label>
                                                    <select name="gender_id" class="js-select form-select form-select-sm">
                                                        <option value="">Todos</option>
                                                        @foreach ($genders ?? [] as $gender)
                                                            <option value="{{ $gender->id }}" {{ request('gender_id') == $gender->id ? 'selected' : '' }}>{{ $gender->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <label class="form-label">Ubicación</label>
                                                    <div class="mb-2">
                                                        <select name="country_id" id="filter_country_id" class="js-select form-select form-select-sm">
                                                            <option value="">País (Todos)</option>
                                                            @foreach ($countries as $country)
                                                                <option value="{{ $country->id }}" {{ request('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-2">
                                                        <select name="department_id" id="filter_department_id" class="js-select form-select form-select-sm">
                                                            <option value="">Departamento (Todos)</option>
                                                            @if (request('country_id'))
                                                                @foreach ($departments->where('country_id', request('country_id')) as $dept)
                                                                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <select name="municipality_id" id="filter_municipality_id" class="js-select form-select form-select-sm">
                                                            <option value="">Municipio (Todos)</option>
                                                            @if (request('department_id'))
                                                                @foreach ($municipalities->where('department_id', request('department_id')) as $mun)
                                                                    <option value="{{ $mun->id }}" {{ request('municipality_id') == $mun->id ? 'selected' : '' }}>{{ $mun->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-3">
                                                    <label class="form-label">Fecha de Nacimiento</label>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <input type="date" name="birth_date_from" class="form-control form-control-sm" value="{{ request('birth_date_from') }}" placeholder="Desde">
                                                        </div>
                                                        <div class="col-6">
                                                            <input type="date" name="birth_date_to" class="form-control form-control-sm" value="{{ request('birth_date_to') }}" placeholder="Hasta">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-grid gap-2">
                                                <button type="submit" class="btn btn-primary btn-sm">Aplicar Filtros</button>
                                                <a href="{{ route('users.index') }}" class="btn btn-white btn-sm">Limpiar Filtros</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive datatable-custom position-relative">
                    <table id="datatable"
                        class="table table-lg table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                        data-hs-datatables-options='{
                   "columnDefs": [{
                      "targets": [0, 7],
                      "orderable": false
                    }],
                   "order": [],
                   "info": {
                     "totalQty": "#datatableWithPaginationInfoTotalQty"
                   },
                   "search": "#datatableSearch",
                   "entries": "#datatableEntries",
                   "pageLength": {{ request(' per_page', 25) }}, "isResponsive" : false, "isShowPaging" :
                    false, "pagination" : "datatablePagination" }'>
                        <thead class="thead-light">
                            <tr>
                                <th class="table-column-pe-0">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value=""
                                            id="datatableCheckAll">
                                        <label class="form-check-label" for="datatableCheckAll"></label>
                                    </div>
                                </th>
                                <th class="table-column-ps-0">Nombre</th>
                                <th>Rol / Departamento</th>
                                <th>Especialidad</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td class="table-column-pe-0">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="{{ $user->id }}" id="usersDataCheck{{ $user->id }}">
                                            <label class="form-check-label" for="usersDataCheck{{ $user->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="table-column-ps-0">
                                        <a class="d-flex align-items-center" href="#">
                                            <div class="avatar avatar-soft-primary avatar-circle">
                                                @php
                                                    $primerNombre = $user->first_name ?? '';
                                                    $primerApellido = $user->first_last_name ?? '';
                                                    $iniciales = '';
                                                    if (!empty($primerNombre)) {
                                                        $iniciales .= strtoupper(substr($primerNombre, 0, 1));
                                                    }
                                                    if (!empty($primerApellido)) {
                                                        $iniciales .= strtoupper(substr($primerApellido, 0, 1));
                                                    }
                                                    if (empty($iniciales)) {
                                                        $iniciales = 'U';
                                                    }
                                                @endphp
                                                @if ($user->avatar_url)
                                                    <img class="avatar-img" id="dropdown-avatar-img" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%; aspect-ratio: 1/1;" src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Avatar">
                                                @else
                                                    <span class="avatar-initials">{{ $iniciales }}</span>
                                                @endif
                                            </div>
                                            <div class="ms-3">
                                                <span class="d-block h5 text-inherit mb-0">{{ trim(($user->first_name ?? '') . ' ' . ($user->first_last_name ?? ''),) ?: $user->email ?? 'Usuario' }}</span>
                                                <span class="d-block fs-5 text-body">{{ $user->email }}</span>
                                            </div>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="d-block h5 mb-0">{{ $user->role->name ?? 'Sin Rol' }}</span>
                                        <span class="d-block fs-5">{{ $user->staff->workDepartment->name ?? 'Sin departamento' }}</span>
                                    </td>
                                    <td>
                                        <span class="d-block h5 mb-0">{{ $user->staff->specialty->name ?? 'No especificada' }}</span>
                                        <span class="d-block fs-5">{{ $user->staff->collegiate_number ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        @if ($user->is_active)
                                            <span class="legend-indicator bg-success"></span>Activo
                                        @else
                                            <span class="legend-indicator bg-danger"></span>Inactivo
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a class="btn btn-white btn-sm" href="{{ route('users.show', $user->id) }}" title="Ver">
                                                <i class="bi-eye-fill"></i>
                                            </a>
                                            <a class="btn btn-white btn-sm" href="{{ route('users.edit', $user->id) }}" title="Editar">
                                                <i class="bi-pencil-fill"></i>
                                            </a>
                                            @if ($user->is_active)
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Está seguro de desactivar este usuario?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-white btn-sm" title="Eliminar">
                                                        <i class="bi-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('users.restore', $user->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-white btn-sm" title="Reactivar">
                                                        <i class="bi-arrow-clockwise"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No hay usuarios registrados.</td>
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
                                    <select id="datatableEntries" class="js-select form-select form-select-borderless w-auto" autocomplete="off" data-hs-tom-select-options='{"searchInDropdown": false, "hideSearch": true}' onchange="window.location.href = '{{ route('users.index', request()->except(['per_page', 'page'])) }}' + (window.location.search.includes('?') ? '&' : '?') + 'per_page=' + this.value">
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
                                <span id="datatableWithPaginationInfoTotalQty">{{ $users->total() }}</span>
                            </div>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex justify-content-center justify-content-sm-end">
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@push('scripts')
    <script type="module">
        $(document).ready(function() {
            $('#filter_country_id').on('change', function() {
                var countryId = $(this).val();
                if (countryId) {
                    $.ajax({
                        url: '{{ route('patients.get-departments-by-country') }}',
                        type: 'GET',
                        data: {
                            country_id: countryId
                        },
                    }).done(function(response) {
                        $('#filter_department_id').empty().append(
                            '<option value="">Departamento (Todos)</option>');
                        $('#filter_municipality_id').empty().append(
                            '<option value="">Municipio (Todos)</option>');
                        $.each(response, function(index, department) {
                            $('#filter_department_id').append('<option value="' + department
                                .id + '">' + department.name + '</option>');
                        });
                    });
                } else {
                    $('#filter_department_id').empty().append(
                        '<option value="">Departamento (Todos)</option>');
                    $('#filter_municipality_id').empty().append(
                        '<option value="">Municipio (Todos)</option>');
                }
            });

            $('#filter_department_id').on('change', function() {
                var departmentId = $(this).val();
                if (departmentId) {
                    $.ajax({
                        url: '{{ route('patients.get-municipalities-by-department') }}',
                        type: 'GET',
                        data: {
                            department_id: departmentId
                        },
                    }).done(function(response) {
                        $('#filter_municipality_id').empty().append(
                            '<option value="">Municipio (Todos)</option>');
                        $.each(response, function(index, municipality) {
                            $('#filter_municipality_id').append('<option value="' +
                                municipality.id + '">' + municipality.name + '</option>'
                            );
                        });
                    });
                } else {
                    $('#filter_municipality_id').empty().append(
                        '<option value="">Municipio (Todos)</option>');
                }
            });
        });
    </script>
@endpush