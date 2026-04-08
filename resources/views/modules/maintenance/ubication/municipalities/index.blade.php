@extends('layouts.panel')
@section('title', 'Municipios')
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
                            <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Mantenimiento</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Municipios</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">Listado de municipios</h1>
                </div>
                <div class="col-sm-auto">
                    <a class="btn btn-primary" href="{{ route('maintenance.municipalities.create') }}">
                        <i class="bi-plus-circle-fill me-1"></i> Agregar municipio
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
            <div class="col-sm-6 col-lg-4 mb-3 mb-lg-5">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2">Total municipios registrados</h6>
                        <div class="row align-items-center gx-2">
                            <div class="col">
                                <span class="js-counter display-4 text-dark">{{ $totalMunicipalities ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4 mb-3 mb-lg-5">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2">Municipios activos</h6>
                        <div class="row align-items-center gx-2">
                            <div class="col">
                                <span class="js-counter display-4 text-dark">{{ $activeMunicipalities ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4 mb-3 mb-lg-5">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2">Municipios inactivos</h6>
                        <div class="row align-items-center gx-2">
                            <div class="col">
                                <span class="js-counter display-4 text-dark">{{ $inactiveMunicipalities ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header card-header-content-md-between">
                <div class="mb-2 mb-md-0">
                    <form action="{{ route('maintenance.municipalities.index') }}" method="GET">
                        @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        @if(request('country_id')) <input type="hidden" name="country_id" value="{{ request('country_id') }}"> @endif
                        @if(request('department_id')) <input type="hidden" name="department_id" value="{{ request('department_id') }}"> @endif
                        @if(request('per_page')) <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                        @endif
                        <div class="input-group input-group-merge input-group-flush">
                            <button type="submit" class="input-group-prepend input-group-text bg-transparent border-0">
                                <i class="bi-search"></i>
                            </button>
                            <input name="search" type="text" class="form-control" placeholder="Buscar" aria-label="Buscar municipios" value="{{ request('search') }}">
                            @if(request('search'))
                            <a class="input-group-append input-group-text text-muted" href="{{ route('maintenance.municipalities.index', request()->except('search')) }}">
                                <i class="bi-x-lg"></i>
                            </a>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="d-grid d-sm-flex justify-content-md-end align-items-sm-center gap-2">
                            <div class="dropdown" id="actionsDropdownWrapper" style="display: none;">
                                <button type="button" class="btn btn-white btn-sm dropdown-toggle" id="actionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi-gear me-2"></i> Acciones
                                </button>
                                <div class="dropdown-menu dropdown-menu-sm-end" aria-labelledby="actionsDropdown">
                                    <span class="dropdown-header">Acciones masivas</span>
                                    @if (request('status') === 'inactive')
                                        <a id="bulk-restore" class="dropdown-item" href="javascript:;">
                                            <i class="bi-arrow-clockwise me-2"></i> Reactivar
                                        </a>
                                    @else
                                        <a id="bulk-delete" class="dropdown-item text-danger" href="javascript:;">
                                            <i class="bi-trash me-2"></i> Eliminar
                                        </a>
                                    @endif
                                </div>
                            </div>
                    <div class="dropdown">
                        <button type="button" class="btn btn-white btn-sm dropdown-toggle w-100" id="municipalitiesExportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi-download me-2"></i> Exportar
                        </button>
                        <div class="dropdown-menu dropdown-menu-sm-end" aria-labelledby="municipalitiesExportDropdown">
                            <span class="dropdown-header">Opciones</span>
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
                    @php
                        $activeFilters = count(array_filter(request()->only(['country_id', 'department_id', 'status'])));
                    @endphp
                    <div class="dropdown">
                        <button type="button" class="btn btn-white btn-sm w-100" id="municipalitiesFilterDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                            <i class="bi-filter" style="font-style: normal;"> Filtrar </i>
                            @if($activeFilters > 0)
                                <span class="badge bg-soft-dark text-dark rounded-circle ms-1">{{ $activeFilters }}</span>
                            @endif
                        </button>
                        <div class="dropdown-menu dropdown-menu-sm-end dropdown-card card-dropdown-filter-centered" aria-labelledby="municipalitiesFilterDropdown" style="min-width: 22rem;">
                            <div class="card">
                                <div class="card-header card-header-content-between">
                                    <h5 class="card-header-title">Filtrar municipios</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('maintenance.municipalities.index') }}" method="GET">
                                        @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                                        @if(request('per_page')) <input type="hidden" name="per_page" value="{{ request('per_page') }}"> @endif
                                        <div class="row">
                                            <div class="col-12 mb-4">
                                                <small class="text-cap text-body">País</small>
                                                <div class="tom-select-custom">
                                                    <select name="country_id" class="js-select form-select form-select-sm" data-hs-tom-select-options='{"placeholder": "Todos", "searchInDropdown": false, "hideSearch": true, "dropdownWidth": "10rem"}'>
                                                        <option value="">Todos</option>
                                                        @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}" {{ request('country_id')==$country->id ? 'selected' : '' }}>
                                                            {{ $country->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 mb-4">
                                                <small class="text-cap text-body">Departamento</small>
                                                <div class="tom-select-custom">
                                                    <select name="department_id" class="js-select form-select form-select-sm" data-hs-tom-select-options='{"placeholder": "Todos", "searchInDropdown": false, "hideSearch": true, "dropdownWidth": "10rem"}'>
                                                        <option value="">Todos</option>
                                                        @foreach ($departments as $dept)
                                                        <option value="{{ $dept->id }}" {{ request('department_id')==$dept->id ? 'selected' : '' }}>
                                                            {{ $dept->name }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12 mb-4">
                                                <small class="text-cap text-body">Estado</small>
                                                <div class="tom-select-custom">
                                                    <select name="status" class="js-select form-select form-select-sm" data-hs-tom-select-options='{"searchInDropdown": false, "hideSearch": true, "dropdownWidth": "10rem"}'>
                                                        <option value="active" {{ request('status', 'active')=='active' ? 'selected' : '' }}>Activo</option>
                                                        <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>Inactivo</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary">Aplicar</button>
                                            <a class="btn btn-white" href="{{ route('maintenance.municipalities.index') }}">Limpiar</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <span id="selectedCountWrapper" class="text-body small align-items-center gap-2 ms-2" style="display: flex; display: none !important; border-left: 1px solid #e7eaf3; padding-left: .5rem;">
                        <span><span class="fw-semibold" id="selectedCount">0</span> selec.</span>
                        <div class="form-check form-check-sm mb-0" title="Seleccionar todos los registros ({{ count($allFilteredIds) }})">
                            <input class="form-check-input" type="checkbox" id="selectAllFiltered" data-ids="{{ json_encode($allFilteredIds) }}">
                            <label class="form-check-label text-muted" style="font-size: 0.7rem; margin-top: 1px;" for="selectAllFiltered">Todos</label>
                        </div>
                        <button type="button" id="clearSelection" class="btn btn-link btn-sm p-0 ms-1 text-muted" style="line-height: 1; font-size: 0.75rem; opacity: 0.7; transition: opacity 0.2s;" onmouseover="this.style.opacity='1'" onmouseout="this.style.opacity='0.7'" title="Limpiar selección">
                            <i class="bi-x-lg"></i>
                        </button>
                    </span>
                </div>
            </div>
            <div class="table-responsive datatable-custom position-relative">
                <table id="datatable" class="table table-lg table-borderless table-thead-bordered table-nowrap table-align-middle card-table" data-hs-datatables-options='{
                           "columnDefs": [{"targets": [0, 5], "orderable": false}],
                           "order": [],
                           "info": {"totalQty": "#datatableWithPaginationInfoTotalQty"},
                           "search": "#datatableSearch",
                           "entries": "#datatableEntries",
                           "pageLength": {{ request('per_page', 25) }}, "isResponsive" : false, "isShowPaging" :
                    false, "pagination" : "datatablePagination" }'>
                    <thead class="thead-light">
                        <tr>
                            <th class="table-column-pe-0">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="datatableCheckAll">
                                    <label class="form-check-label" for="datatableCheckAll"></label>
                                </div>
                            </th>
                            <th class="table-column-ps-0">Municipio</th>
                            <th>Departamento</th>
                            <th>País</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($municipalities as $municipality)
                        <tr>
                            <td class="table-column-pe-0">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $municipality->id }}" id="municipalitiesDataCheck{{ $municipality->id }}">
                                    <label class="form-check-label" for="municipalitiesDataCheck{{ $municipality->id }}"></label>
                                </div>
                            </td>
                            <td class="table-column-ps-0">
                                <span class="d-block h5 text-inherit mb-0">{{ $municipality->name }}</span>
                            </td>
                            <td>{{ $municipality->department->name ?? '—' }}</td>
                            <td>{{ $municipality->department->country->name ?? '—' }}</td>
                            <td>
                                @if ($municipality->is_active)
                                <span class="legend-indicator bg-success"></span>Activo
                                @else
                                <span class="legend-indicator bg-danger"></span>Inactivo
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('maintenance.municipalities.edit', $municipality->id) }}" class="btn btn-white btn-sm">
                                        <i class="bi-pencil-fill me-1"></i> Editar
                                    </a>

                                    @if ($municipality->is_active)
                                    <form action="{{ route('maintenance.municipalities.destroy', $municipality->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-white btn-sm" title="Desactivar">
                                            <i class="bi-trash"></i>
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ route('maintenance.municipalities.restore', $municipality->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-white btn-sm" title="Reactivar">
                                            <i class="bi-arrow-counterclockwise"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No hay municipios registrados.</td>
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
                                <select id="datatableEntries" class="js-select form-select form-select-borderless w-auto" autocomplete="off" data-hs-tom-select-options='{"searchInDropdown": false, "hideSearch": true}' onchange="window.location.href = '{{ route('maintenance.municipalities.index', request()->except(['per_page', 'page'])) }}' + ( '{{ route('maintenance.municipalities.index', request()->except(['per_page', 'page'])) }}'.includes('?') ? '&' : '?' ) + 'per_page=' + this.value">
                                    <option value="10" {{ request('per_page')==10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('per_page', 25)==25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('per_page')==50 ? 'selected' : '' }}>50</option>
                                    <option value="100" {{ request('per_page')==100 ? 'selected' : '' }}>100</option>
                                </select>
                            </div>
                            <span class="text-secondary me-2">de</span>
                            <span id="datatableWithPaginationInfoTotalQty">{{ $municipalities->total() }}</span>
                        </div>
                    </div>
                    <div class="col-sm-auto">
                        <div class="d-flex justify-content-center justify-content-sm-end">
                            {{ $municipalities->links('vendor.pagination.bootstrap-5') }}
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
    document.addEventListener('DOMContentLoaded', function () {
        const storageKey = 'selectedMunicipalities';
        let selectedIds = JSON.parse(sessionStorage.getItem(storageKey) || '[]');
        
        const checkAll = document.getElementById('datatableCheckAll');
        const checkboxes = document.querySelectorAll('input[id^="municipalitiesDataCheck"]');
        const selectedCountSpan = document.getElementById('selectedCount');
        const countWrapper = document.getElementById('selectedCountWrapper');
        const actionsWrapper = document.getElementById('actionsDropdownWrapper');
        const btnClear = document.getElementById('clearSelection');
        
        function updateUI() {
            selectedCountSpan.textContent = selectedIds.length;
            if (selectedIds.length > 0) {
                countWrapper.style.setProperty('display', 'flex', 'important');
                actionsWrapper.style.display = 'block';
            } else {
                countWrapper.style.setProperty('display', 'none', 'important');
                actionsWrapper.style.display = 'none';
            }
            
            if (checkboxes.length > 0) {
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                if (checkAll) checkAll.checked = allChecked;
            }
            sessionStorage.setItem(storageKey, JSON.stringify(selectedIds));
        }

        checkboxes.forEach(cb => {
            if (selectedIds.includes(cb.value)) cb.checked = true;
            cb.addEventListener('change', function() {
                if (this.checked) {
                    if (!selectedIds.includes(this.value)) selectedIds.push(this.value);
                } else {
                    selectedIds = selectedIds.filter(id => id !== this.value);
                }
                updateUI();
            });
        });
        
        if (checkAll) {
            checkAll.addEventListener('change', function() {
                const isChecked = this.checked;
                checkboxes.forEach(cb => {
                    cb.checked = isChecked;
                    if (isChecked && !selectedIds.includes(cb.value)) {
                        selectedIds.push(cb.value);
                    } else if (!isChecked) {
                        selectedIds = selectedIds.filter(id => id !== cb.value);
                    }
                });
                updateUI();
            });
        }
        
        if (btnClear) {
            btnClear.addEventListener('click', function() {
                selectedIds = [];
                checkboxes.forEach(cb => cb.checked = false);
                if(checkAll) checkAll.checked = false;
                updateUI();
            });
        }
        
        const btnSelectAllFiltered = document.getElementById('selectAllFiltered');
        if (btnSelectAllFiltered) {
            btnSelectAllFiltered.addEventListener('change', function() {
                if (this.checked) {
                    const allIds = JSON.parse(this.dataset.ids || '[]');
                    selectedIds = allIds.map(id => String(id));
                    checkboxes.forEach(cb => cb.checked = true);
                    if(checkAll) checkAll.checked = true;
                } else {
                    selectedIds = [];
                    checkboxes.forEach(cb => cb.checked = false);
                    if(checkAll) checkAll.checked = false;
                }
                updateUI();
            });
        }
        
        updateUI();

        function sendBulkRequest(url, formatStr = null) {
            if (selectedIds.length === 0) return;
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            const idsInput = document.createElement('input');
            idsInput.type = 'hidden';
            idsInput.name = 'ids';
            idsInput.value = JSON.stringify(selectedIds);
            form.appendChild(idsInput);
            
            if (formatStr) {
                const typeInput = document.createElement('input');
                typeInput.type = 'hidden';
                typeInput.name = 'format';
                typeInput.value = formatStr;
                form.appendChild(typeInput);
            }

            document.body.appendChild(form);
            form.submit();
        }

        const bulkDeleteBtn = document.getElementById('bulk-delete');
        if (bulkDeleteBtn) {
            bulkDeleteBtn.addEventListener('click', function() {
                sendBulkRequest('{{ route("maintenance.municipalities.destroy-multiple") }}');
                sessionStorage.removeItem(storageKey);
            });
        }

        const bulkRestoreBtn = document.getElementById('bulk-restore');
        if (bulkRestoreBtn) {
            bulkRestoreBtn.addEventListener('click', function() {
                sendBulkRequest('{{ route("maintenance.municipalities.restore-multiple") }}');
                sessionStorage.removeItem(storageKey);
            });
        }

        const exportExcelBtn = document.getElementById('export-excel');
        if (exportExcelBtn) exportExcelBtn.addEventListener('click', () => sendBulkRequest('{{ route("maintenance.municipalities.export.excel") }}', 'excel'));
        
        const exportCsvBtn = document.getElementById('export-csv');
        if (exportCsvBtn) exportCsvBtn.addEventListener('click', () => sendBulkRequest('{{ route("maintenance.municipalities.export.csv") }}', 'csv'));
        
        const exportPdfBtn = document.getElementById('export-pdf');
        if (exportPdfBtn) exportPdfBtn.addEventListener('click', () => sendBulkRequest('{{ route("maintenance.municipalities.export.pdf") }}', 'pdf'));
        
        const exportPrintBtn = document.getElementById('export-print');
        if (exportPrintBtn) exportPrintBtn.addEventListener('click', () => sendBulkRequest('{{ route("maintenance.municipalities.print") }}', 'print'));
    });
</script>
@endpush

