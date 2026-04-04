@extends('layouts.panel')
@section('title', 'Roles')
@section('content')
<main id="content" role="main" class="main">
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Inicio</a></li>
                            <li class="breadcrumb-item"><a class="breadcrumb-link" href="javascript:;">Mantenimiento</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Roles</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">Listado de roles</h1>
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
                        <h6 class="card-subtitle mb-2">Total registrados</h6>
                        <div class="row align-items-center gx-2">
                            <div class="col">
                                <span class="js-counter display-4 text-dark">{{ $totalCount ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4 mb-3 mb-lg-5">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2">Activos</h6>
                        <div class="row align-items-center gx-2">
                            <div class="col">
                                <span class="js-counter display-4 text-dark">{{ $activeCount ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4 mb-3 mb-lg-5">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2">Inactivos</h6>
                        <div class="row align-items-center gx-2">
                            <div class="col">
                                <span class="js-counter display-4 text-dark">{{ $inactiveCount ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header card-header-content-md-between">
                <div class="mb-2 mb-md-0">
                    <form action="{{ route('roles.index') }}" method="GET">
                        <div class="input-group input-group-merge input-group-flush">
                            <div class="input-group-prepend input-group-text bg-transparent border-0">
                                <i class="bi-search"></i>
                            </div>
                            <input name="search" type="text" class="form-control" placeholder="Buscar rol" value="{{ request('search') }}">
                        </div>
                    </form>
                </div>
                <div class="d-grid d-sm-flex justify-content-md-end align-items-sm-center gap-2">
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
                    <div class="dropdown">
                        <button type="button" class="btn btn-white btn-sm w-100" id="filterDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                            <i class="bi-filter"></i> Filtrar
                        </button>
                        <div class="dropdown-menu dropdown-menu-sm-end dropdown-card card-dropdown-filter-centered" aria-labelledby="filterDropdown" style="min-width: 22rem;">
                            <div class="card">
                                <div class="card-header card-header-content-between">
                                    <h5 class="card-header-title">Filtrar</h5>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('roles.index') }}" method="GET">
                                        <div class="row">
                                            <div class="col-sm mb-4">
                                                <small class="text-cap text-body">Estado</small>
                                                <select name="status" class="js-select form-select form-select-sm">
                                                    <option value="active" {{ request('status', 'active')=='active' ? 'selected' : '' }}>Activo</option>
                                                    <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>Inactivo</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary">Aplicar</button>
                                            <a class="btn btn-white" href="{{ route('roles.index') }}">Limpiar</a>
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
                            <th>Nombre</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($roles as $item)
                        <tr>
                            <td>
                                <span class="d-block h5 text-inherit mb-0">{{ $item->name }}</span>
                            </td>
                            <td>
                                @if ($item->is_active)
                                <span class="legend-indicator bg-success"></span>Activo
                                @else
                                <span class="legend-indicator bg-danger"></span>Inactivo
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center">No hay registros encontrados.</td>
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
                            <span class="text-secondary">{{ $roles->count() }}</span>
                            <span class="text-secondary mx-2">de</span>
                            <span>{{ $roles->total() }}</span>
                        </div>
                    </div>
                    <div class="col-sm-auto">
                        <div class="d-flex justify-content-center justify-content-sm-end">
                            {{ $roles->links('vendor.pagination.bootstrap-5') }}
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
        function sendExportRequest(url) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = url;
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            document.body.appendChild(form);
            form.submit();
        }

        const exportExcelBtn = document.getElementById('export-excel');
        if (exportExcelBtn) exportExcelBtn.addEventListener('click', () => sendExportRequest('{{ route("roles.export.excel") }}'));

        const exportPdfBtn = document.getElementById('export-pdf');
        if (exportPdfBtn) exportPdfBtn.addEventListener('click', () => sendExportRequest('{{ route("roles.export.pdf") }}'));

        const exportPrintBtn = document.getElementById('export-print');
        if (exportPrintBtn) exportPrintBtn.addEventListener('click', () => sendExportRequest('{{ route("roles.print") }}'));
    });
</script>
@endpush
