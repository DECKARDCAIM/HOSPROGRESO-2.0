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
        <div class="row">
            <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
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

            <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
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

            <div class="col-sm-6 col-lg-3 mb-3 mb-lg-5">
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
                        @if(request('per_page')) <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                        @endif
                        <div class="input-group input-group-merge input-group-flush">
                            <button type="submit" class="input-group-prepend input-group-text bg-transparent border-0">
                                <i class="bi-search"></i>
                            </button>
                            <input name="search" type="text" class="form-control" placeholder="Buscar"
                                aria-label="Buscar usuarios" value="{{ request('search') }}">
                            @if(request('search'))
                            <a class="input-group-append input-group-text text-muted"
                                href="{{ route('users.index', request()->except('search')) }}">
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
                                <span id="datatableCounter">0</span>
                                Selected
                            </span>
                            <a class="btn btn-outline-danger btn-sm" href="javascript:;">
                                <i class="bi-trash"></i> Delete
                            </a>
                        </div>
                    </div>

                    <div class="dropdown">
                        <button type="button" class="btn btn-white btn-sm dropdown-toggle w-100"
                            id="usersExportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi-download me-2"></i> Exportar
                        </button>

                        <div class="dropdown-menu dropdown-menu-sm-end" aria-labelledby="usersExportDropdown">
                            <span class="dropdown-header">Opciones</span>
                            <a id="export-copy" class="dropdown-item" href="javascript:;">
                                <img class="avatar avatar-xss avatar-4x3 me-2"
                                    src="{{ asset('svg/illustrations/copy-icon.svg') }}" alt="Copiar">
                                Copiar
                            </a>
                            <a id="export-print" class="dropdown-item" href="javascript:;">
                                <img class="avatar avatar-xss avatar-4x3 me-2"
                                    src="{{ asset('svg/illustrations/print-icon.svg') }}" alt="Imprimir">
                                Imprimir
                            </a>
                            <div class="dropdown-divider"></div>
                            <span class="dropdown-header">Opciones de descarga</span>
                            <a id="export-excel" class="dropdown-item" href="javascript:;">
                                <img class="avatar avatar-xss avatar-4x3 me-2"
                                    src="{{ asset('svg/brands/excel-icon.svg') }}" alt="Excel">
                                Excel
                            </a>
                            <a id="export-csv" class="dropdown-item" href="javascript:;">
                                <img class="avatar avatar-xss avatar-4x3 me-2"
                                    src="{{ asset('svg/components/placeholder-csv-format.svg') }}"
                                    alt="CSV">
                                .CSV
                            </a>
                            <a id="export-pdf" class="dropdown-item" href="javascript:;">
                                <img class="avatar avatar-xss avatar-4x3 me-2"
                                    src="{{ asset('svg/brands/pdf-icon.svg') }}" alt="PDF">
                                PDF
                            </a>
                        </div>
                    </div>

                    @php
                        $activeFilters = count(array_filter(request()->only(['status'])));
                    @endphp
                    <div class="dropdown">
                        <button type="button" class="btn btn-white btn-sm w-100" id="usersFilterDropdown"
                            data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                            <i class="bi-filter" style="font-style: normal;"> Filtrar </i>
                            @if($activeFilters > 0)
                                <span class="badge bg-soft-dark text-dark rounded-circle ms-1">{{ $activeFilters }}</span>
                            @endif
                        </button>

                        <div class="dropdown-menu dropdown-menu-sm-end dropdown-card card-dropdown-filter-centered"
                            aria-labelledby="usersFilterDropdown" style="min-width: 22rem;">
                            <div class="card">
                                <div class="card-header card-header-content-between">
                                    <h5 class="card-header-title">Filtrar usuarios</h5>
                                </div>

                                <div class="card-body">
                                    <form>
                                        <div class="mb-4">
                                            <small class="text-cap text-body">Rol</small>

                                            <div class="row">
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value=""
                                                            id="usersFilterCheckAll" checked>
                                                        <label class="form-check-label" for="usersFilterCheckAll">
                                                            Todos
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" value=""
                                                            id="usersFilterCheckEmployee">
                                                        <label class="form-check-label" for="usersFilterCheckEmployee">
                                                            Empleado
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm mb-4">
                                                <small class="text-cap text-body">Posición</small>

                                                <div class="tom-select-custom">
                                                    <select
                                                        class="js-select js-datatable-filter form-select form-select-sm"
                                                        data-target-column-index="2" data-hs-tom-select-options='{
                                      "placeholder": "Cualquiera",
                                      "searchInDropdown": false,
                                      "hideSearch": true,
                                      "dropdownWidth": "10rem"
                                    }'>
                                                        <option value="">Cualquiera</option>
                                                        <option value="Accountant">Accountant</option>
                                                        <option value="Co-founder">Co-founder</option>
                                                        <option value="Designer">Designer</option>
                                                        <option value="Developer">Developer</option>
                                                        <option value="Director">Director</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm mb-4">
                                                <small class="text-cap text-body">Status</small>

                                                <div class="tom-select-custom">
                                                    <select
                                                        class="js-select js-datatable-filter form-select form-select-sm"
                                                        data-target-column-index="4" data-hs-tom-select-options='{
                                      "placeholder": "Cualquiera status",
                                      "searchInDropdown": false,
                                      "hideSearch": true,
                                      "dropdownWidth": "10rem"
                                    }'>
                                                        <option value="">Cualquiera status</option>
                                                        <option value="Completed"
                                                            data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-success"></span>Completed</span>'>
                                                            Completed</option>
                                                        <option value="In progress"
                                                            data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-warning"></span>In progress</span>'>
                                                            In progress</option>
                                                        <option value="To do"
                                                            data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-danger"></span>To do</span>'>
                                                            To do</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-grid">
                                            <a class="btn btn-primary" href="javascript:;">Apply</a>
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
                                    <input class="form-check-input" type="checkbox" value="" id="datatableCheckAll">
                                    <label class="form-check-label" for="datatableCheckAll"></label>
                                </div>
                            </th>
                            <th class="table-column-ps-0">Nombre</th>
                            <th>Rol / Departamento</th>
                            <th>Especialidad</th>
                            <th>Estado</th>
                            <th>Unidad Ejecutora</th>
                            <th>No. Colegiado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td class="table-column-pe-0">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $user->id }}"
                                        id="usersDataCheck{{ $user->id }}">
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
                                        @if ($user->avatar_url || $user->avatar)
                                        <img class="avatar-img"
                                            src="{{ $user->avatar_url ?? asset('storage/img/profiles/' . $user->avatar) }}"
                                            alt="Avatar">
                                        @else
                                        <span class="avatar-initials">{{ $iniciales }}</span>
                                        @endif
                                    </div>
                                    <div class="ms-3">
                                        <span class="d-block h5 text-inherit mb-0">{{ trim(($user->first_name ?? '') . '
                                            ' . ($user->first_last_name ?? '')) ?: $user->email ?? 'Usuario' }}</span>
                                        <span class="d-block fs-5 text-body">{{ $user->email }}</span>
                                    </div>
                                </a>
                            </td>
                            <td>
                                <span class="d-block h5 mb-0">{{ $user->role->name ?? 'Sin Rol' }}</span>
                                <span class="d-block fs-5">{{ $user->workDepartment->name ?? 'Sin departamento'
                                    }}</span>
                            </td>
                            <td>
                                {{ $user->specialty->name ?? 'No especificada' }}
                            </td>
                            <td>
                                @if ($user->is_active)
                                <span class="legend-indicator bg-success"></span>Activo
                                @else
                                <span class="legend-indicator bg-danger"></span>Inactivo
                                @endif
                            </td>
                            <td>
                                {{ $user->unityExecution->name ?? 'Sin Unidad' }}
                            </td>
                            <td>{{ $user->collegiate_number ?? 'N/A' }}</td>
                            <td>
                                <button type="button" class="btn btn-white btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editUserModal{{ $user->id }}">
                                    <i class="bi-pencil-fill me-1"></i> Edit
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay usuarios registrados.</td>
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
                                <select id="datatableEntries"
                                    class="js-select form-select form-select-borderless w-auto" autocomplete="off"
                                    data-hs-tom-select-options='{
                                            "searchInDropdown": false,
                                            "hideSearch": true
                                        }'
                                    onchange="window.location.href = '{{ route('users.index', request()->except(['per_page', 'page'])) }}' + (window.location.search.includes('?') ? '&' : '?') + 'per_page=' + this.value">
                                    <option value="10" {{ request('per_page')==10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('per_page', 25)==25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page')==50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page')==100 ? 'selected' : '' }}>100</option>
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
<script src="{{ asset('vendor/hs-toggle-password/dist/js/hs-toggle-password.js') }}"></script>
<script src="{{ asset('vendor/hs-file-attach/dist/hs-file-attach.min.js') }}"></script>
<script src="{{ asset('vendor/hs-nav-scroller/dist/hs-nav-scroller.min.js') }}"></script>
<script src="{{ asset('vendor/hs-step-form/dist/hs-step-form.min.js') }}"></script>
<script src="{{ asset('vendor/hs-counter/dist/hs-counter.min.js') }}"></script>
<script src="{{ asset('vendor/appear/dist/appear.min.js') }}"></script>
<script src="{{ asset('vendor/imask/dist/imask.min.js') }}"></script>
<script src="{{ asset('vendor/tom-select/dist/js/tom-select.complete.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables.net.extensions/select/select.min.js') }}"></script>
<script src="{{ asset('vendor/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('vendor/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
<script src="{{ asset('vendor/jszip/dist/jszip.min.js') }}"></script>
<script src="{{ asset('vendor/pdfmake/build/pdfmake.min.js') }}"></script>
<script src="{{ asset('vendor/pdfmake/build/vfs_fonts.js') }}"></script>
<script src="{{ asset('vendor/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('vendor/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('vendor/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
@endpush