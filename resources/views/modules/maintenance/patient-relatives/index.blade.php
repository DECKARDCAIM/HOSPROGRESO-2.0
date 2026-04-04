@extends('layouts.panel')
@section('title', 'Familiares de Pacientes')
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
                            <li class="breadcrumb-item active" aria-current="page">Familiares</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">Listado de familiares de pacientes</h1>
                </div>
                <div class="col-sm-auto">
                    <a class="btn btn-primary" href="{{ route('patient-relatives.create') }}">
                        <i class="bi-plus-circle-fill me-1"></i> Registrar familiar
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 mb-3 mb-lg-5">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2">Total de familiares registrados</h6>
                        <div class="row align-items-center gx-2">
                            <div class="col">
                                <span class="js-counter display-4 text-dark">{{ $totalCount ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header card-header-content-md-between">
                <div class="mb-2 mb-md-0">
                    <form action="{{ route('patient-relatives.index') }}" method="GET">
                        <div class="input-group input-group-merge input-group-flush">
                            <div class="input-group-prepend input-group-text bg-transparent border-0">
                                <i class="bi-search"></i>
                            </div>
                            <input name="search" type="text" class="form-control" placeholder="Buscar familiar (Nombre o CUI)" value="{{ request('search') }}">
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
                            <a id="bulk-delete" class="dropdown-item text-danger" href="javascript:;">
                                <i class="bi-trash me-2"></i> Eliminar seleccionados
                            </a>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button type="button" class="btn btn-white btn-sm dropdown-toggle w-100" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi-download me-2"></i> Exportar
                        </button>
                        <div class="dropdown-menu dropdown-menu-sm-end" aria-labelledby="exportDropdown">
                            <span class="dropdown-header">Opciones</span>
                            <a id="export-print" class="dropdown-item" href="javascript:;">
                                <i class="bi-printer me-2"></i> Imprimir
                            </a>
                            <div class="dropdown-divider"></div>
                            <span class="dropdown-header">Opciones de descarga</span>
                            <a id="export-excel" class="dropdown-item" href="javascript:;">
                                <i class="bi-file-earmark-excel me-2"></i> Excel
                            </a>
                            <a id="export-csv" class="dropdown-item" href="javascript:;">
                                <i class="bi-file-earmark-text me-2"></i> .CSV
                            </a>
                            <a id="export-pdf" class="dropdown-item" href="javascript:;">
                                <i class="bi-file-earmark-pdf me-2"></i> PDF
                            </a>
                        </div>
                    </div>
                    <span id="selectedCountWrapper" class="text-body small align-items-center gap-2 ms-2" style="display: none;">
                        <span><span class="fw-semibold" id="selectedCount">0</span> selec.</span>
                        <div class="form-check form-check-sm mb-0">
                            <input class="form-check-input" type="checkbox" id="selectAllFiltered" data-ids="{{ json_encode($allFilteredIds) }}">
                            <label class="form-check-label text-muted" style="font-size: 0.7rem;" for="selectAllFiltered">Todos</label>
                        </div>
                        <button type="button" id="clearSelection" class="btn btn-link btn-sm p-0 ms-1 text-muted" title="Limpiar selección">
                            <i class="bi-x-lg"></i>
                        </button>
                    </span>
                </div>
            </div>
            <div class="table-responsive datatable-custom position-relative">
                <table id="datatable" class="table table-lg table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                        <tr>
                            <th class="table-column-pe-0">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="datatableCheckAll">
                                    <label class="form-check-label" for="datatableCheckAll"></label>
                                </div>
                            </th>
                            <th>Nombre Completo</th>
                            <th>CUI</th>
                            <th>Paciente</th>
                            <th>Parentesco</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($patientRelatives as $item)
                        <tr>
                            <td class="table-column-pe-0">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="{{ $item->id }}" id="dataCheck{{ $item->id }}">
                                    <label class="form-check-label" for="dataCheck{{ $item->id }}"></label>
                                </div>
                            </td>
                            <td>
                                <span class="d-block h5 text-inherit mb-0">{{ $item->first_name }} {{ $item->first_last_name }}</span>
                                <small class="text-body">{{ $item->second_name }} {{ $item->second_last_name }}</small>
                            </td>
                            <td>
                                <span class="text-body">{{ $item->cui }}</span>
                            </td>
                            <td>
                                <a href="{{ route('patients.show', $item->patient_id) }}" class="text-body fw-semibold">
                                    {{ $item->patient->first_name }} {{ $item->patient->first_last_name }}
                                </a>
                            </td>
                            <td>
                                <span class="text-body">{{ $item->relationshipType->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('patient-relatives.edit', $item->id) }}" class="btn btn-white btn-sm" title="Editar">
                                        <i class="bi-pencil-fill"></i>
                                    </a>
                                    <form action="{{ route('patient-relatives.destroy', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-white btn-sm" title="Eliminar">
                                            <i class="bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No hay registros encontrados.</td>
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
                            <span class="text-secondary">{{ $patientRelatives->count() }}</span>
                            <span class="text-secondary mx-2">de</span>
                            <span>{{ $patientRelatives->total() }}</span>
                        </div>
                    </div>
                    <div class="col-sm-auto">
                        <div class="d-flex justify-content-center justify-content-sm-end">
                            {{ $patientRelatives->links('vendor.pagination.bootstrap-5') }}
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
    document.addEventListener('DOMContentLoaded', function () {
        const storageKey = 'selectedPatientRelatives';
        let selectedIds = JSON.parse(sessionStorage.getItem(storageKey) || '[]');
        
        const checkAll = document.getElementById('datatableCheckAll');
        const checkboxes = document.querySelectorAll('input[id^="dataCheck"]');
        const selectedCountSpan = document.getElementById('selectedCount');
        const countWrapper = document.getElementById('selectedCountWrapper');
        const actionsWrapper = document.getElementById('actionsDropdownWrapper');
        const btnClear = document.getElementById('clearSelection');
        
        function updateUI() {
            selectedCountSpan.textContent = selectedIds.length;
            if (selectedIds.length > 0) {
                countWrapper.style.display = 'flex';
                actionsWrapper.style.display = 'block';
            } else {
                countWrapper.style.display = 'none';
                actionsWrapper.style.display = 'none';
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
        if (bulkDeleteBtn) bulkDeleteBtn.addEventListener('click', () => sendBulkRequest('{{ route("patient-relatives.destroy-multiple") }}'));

        const exportExcelBtn = document.getElementById('export-excel');
        if (exportExcelBtn) exportExcelBtn.addEventListener('click', () => sendBulkRequest('{{ route("patient-relatives.export.excel") }}', 'excel'));

        const exportPdfBtn = document.getElementById('export-pdf');
        if (exportPdfBtn) exportPdfBtn.addEventListener('click', () => sendBulkRequest('{{ route("patient-relatives.export.pdf") }}', 'pdf'));

        const exportPrintBtn = document.getElementById('export-print');
        if (exportPrintBtn) exportPrintBtn.addEventListener('click', () => sendBulkRequest('{{ route("patient-relatives.print") }}', 'print'));
        
        updateUI();
    });
</script>
@endpush
