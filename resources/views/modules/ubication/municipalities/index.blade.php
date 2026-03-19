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
                                <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Inicio</a></li>
                                <li class="breadcrumb-item"><span>Mantenimiento</span></li>
                                <li class="breadcrumb-item"><span>Gestionar Ubicaciones</span></li>
                                <li class="breadcrumb-item active" aria-current="page">Municipios</li>
                            </ol>
                        </nav>
                        <h1 class="page-header-title">Listado de municipios</h1>
                    </div>
                    <div class="col-sm-auto">
                        <a class="btn btn-primary" href="{{ route('municipalities.create') }}">
                            <i class="bi-plus-circle-fill me-1"></i> Agregar municipio
                        </a>
                    </div>
                </div>
            </div>

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
                        <form action="{{ route('municipalities.index') }}" method="GET">
                            <!-- Conservar otros filtros -->
                            @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                            @if(request('country_id')) <input type="hidden" name="country_id" value="{{ request('country_id') }}"> @endif
                            @if(request('department_id')) <input type="hidden" name="department_id" value="{{ request('department_id') }}"> @endif
                            @if(request('per_page')) <input type="hidden" name="per_page" value="{{ request('per_page') }}"> @endif
                            
                            <div class="input-group input-group-merge input-group-flush">
                                <button type="submit" class="input-group-prepend input-group-text bg-transparent border-0">
                                    <i class="bi-search"></i>
                                </button>
                                <input name="search" type="text" class="form-control" placeholder="Buscar"
                                    aria-label="Buscar municipios" value="{{ request('search') }}">
                                @if(request('search'))
                                    <a class="input-group-append input-group-text text-muted" href="{{ route('municipalities.index', request()->except('search')) }}">
                                        <i class="bi-x-lg"></i>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    <div class="d-grid d-sm-flex justify-content-md-end align-items-sm-center gap-2">

                        <div class="dropdown">
                            <button type="button" class="btn btn-white btn-sm dropdown-toggle w-100"
                                id="municipalitiesExportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi-download me-2"></i> Export
                            </button>
                            <div class="dropdown-menu dropdown-menu-sm-end" aria-labelledby="municipalitiesExportDropdown">
                                <span class="dropdown-header">Options</span>
                                <a id="export-copy" class="dropdown-item" href="javascript:;">
                                    <img class="avatar avatar-xss avatar-4x3 me-2" src="{{ asset('svg/illustrations/copy-icon.svg') }}" alt="Copy"> Copy
                                </a>
                                <a id="export-print" class="dropdown-item" href="javascript:;">
                                    <img class="avatar avatar-xss avatar-4x3 me-2" src="{{ asset('svg/illustrations/print-icon.svg') }}" alt="Print"> Print
                                </a>
                                <div class="dropdown-divider"></div>
                                <span class="dropdown-header">Download options</span>
                                <a id="export-excel" class="dropdown-item" href="javascript:;">
                                    <img class="avatar avatar-xss avatar-4x3 me-2" src="{{ asset('svg/brands/excel-icon.svg') }}" alt="Excel"> Excel
                                </a>
                                <a id="export-csv" class="dropdown-item" href="javascript:;">
                                    <img class="avatar avatar-xss avatar-4x3 me-2" src="{{ asset('svg/components/placeholder-csv-format.svg') }}" alt="CSV"> .CSV
                                </a>
                                <a id="export-pdf" class="dropdown-item" href="javascript:;">
                                    <img class="avatar avatar-xss avatar-4x3 me-2" src="{{ asset('svg/brands/pdf-icon.svg') }}" alt="PDF"> PDF
                                </a>
                            </div>
                        </div>

                        <div class="dropdown">
                            <button type="button" class="btn btn-white btn-sm w-100" id="municipalitiesFilterDropdown"
                                data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                <i class="bi-filter me-1"></i> Filtrar
                            </button>
                            <div class="dropdown-menu dropdown-menu-sm-end dropdown-card card-dropdown-filter-centered"
                                aria-labelledby="municipalitiesFilterDropdown" style="min-width: 22rem;">
                                <div class="card">
                                    <div class="card-header card-header-content-between">
                                        <h5 class="card-header-title">Filtrar municipios</h5>
                                        <button type="button" class="btn btn-ghost-secondary btn-icon btn-sm ms-2">
                                            <i class="bi-x-lg"></i>
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <form action="{{ route('municipalities.index') }}" method="GET">
                                            @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                                            @if(request('per_page')) <input type="hidden" name="per_page" value="{{ request('per_page') }}"> @endif
                                            
                                            <div class="row">
                                                <div class="col-sm mb-4">
                                                    <small class="text-cap text-body">País</small>
                                                    <div class="tom-select-custom">
                                                        <select name="country_id" class="js-select form-select form-select-sm"
                                                            data-hs-tom-select-options='{
                                                                "placeholder": "Cualquier país",
                                                                "searchInDropdown": false,
                                                                "hideSearch": true,
                                                                "dropdownWidth": "10rem"
                                                            }' onchange="this.form.submit()">
                                                            <option value="">Cualquier país</option>
                                                            @foreach ($countries as $country)
                                                                <option value="{{ $country->id }}" {{ request('country_id') == $country->id ? 'selected' : '' }}>
                                                                    {{ $country->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm mb-4">
                                                    <small class="text-cap text-body">Departamento</small>
                                                    <div class="tom-select-custom">
                                                        <select name="department_id" class="js-select form-select form-select-sm"
                                                            data-hs-tom-select-options='{
                                                                "placeholder": "Cualquier depto.",
                                                                "searchInDropdown": false,
                                                                "hideSearch": true,
                                                                "dropdownWidth": "10rem"
                                                            }'>
                                                            <option value="">Cualquier departamento</option>
                                                            @foreach ($departments as $dept)
                                                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                                                    {{ $dept->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm mb-4">
                                                    <small class="text-cap text-body">Estado</small>
                                                    <div class="tom-select-custom">
                                                        <select name="status" class="js-select form-select form-select-sm"
                                                            data-hs-tom-select-options='{
                                                                "placeholder": "Cualquier estado",
                                                                "searchInDropdown": false,
                                                                "hideSearch": true,
                                                                "dropdownWidth": "10rem"
                                                            }'>
                                                            <option value="">Cualquier estado</option>
                                                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activo</option>
                                                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-grid gap-2">
                                                <button type="submit" class="btn btn-primary">Aplicar</button>
                                                <a class="btn btn-white" href="{{ route('municipalities.index') }}">Limpiar</a>
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
                           "columnDefs": [{"targets": [0, 6], "orderable": false}],
                           "order": [],
                           "info": {"totalQty": "#datatableWithPaginationInfoTotalQty"},
                           "search": "#datatableSearch",
                           "entries": "#datatableEntries",
                           "pageLength": {{ request('per_page', 25) }},
                           "isResponsive": false,
                           "isShowPaging": false,
                           "pagination": "datatablePagination"
                         }'>
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
                                <th>Descripción</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($municipalities as $municipality)
                                <tr>
                                    <td class="table-column-pe-0">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="{{ $municipality->id }}"
                                                id="municipalitiesDataCheck{{ $municipality->id }}">
                                            <label class="form-check-label" for="municipalitiesDataCheck{{ $municipality->id }}"></label>
                                        </div>
                                    </td>
                                    <td class="table-column-ps-0">
                                        <span class="d-block h5 text-inherit mb-0">{{ $municipality->name }}</span>
                                    </td>
                                    <td>{{ $municipality->department->name ?? '—' }}</td>
                                    <td>{{ $municipality->department->country->name ?? '—' }}</td>
                                    <td>
                                        <span class="d-block fs-5">{{ $municipality->description ?? '—' }}</span>
                                    </td>
                                    <td>
                                        @if ($municipality->is_active)
                                            <span class="legend-indicator bg-success"></span>Activo
                                        @else
                                            <span class="legend-indicator bg-danger"></span>Inactivo
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('municipalities.edit', $municipality->id) }}" class="btn btn-white btn-sm">
                                                <i class="bi-pencil-fill me-1"></i> Editar
                                            </a>

                                            @if ($municipality->is_active)
                                                <form action="{{ route('municipalities.destroy', $municipality->id) }}" method="POST"
                                                    onsubmit="return confirm('¿Desactivar el municipio {{ addslashes($municipality->name) }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-white btn-sm" title="Desactivar">
                                                        <i class="bi-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('municipalities.restore', $municipality->id) }}" method="POST"
                                                    onsubmit="return confirm('¿Reactivar el municipio {{ addslashes($municipality->name) }}?')">
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
                                    <td colspan="7" class="text-center">No hay municipios registrados.</td>
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
                                    <select id="datatableEntries" class="js-select form-select form-select-borderless w-auto"
                                        autocomplete="off"
                                        data-hs-tom-select-options='{"searchInDropdown": false, "hideSearch": true}'
                                        onchange="window.location.href = '{{ route('municipalities.index', request()->except(['per_page', 'page'])) }}' + (window.location.search.includes('?') ? '&' : '?') + 'per_page=' + this.value">
                                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ request('per_page', 25) == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
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
    <script src="{{ asset('vendor/hs-counter/dist/hs-counter.min.js') }}"></script>
    <script src="{{ asset('vendor/appear/dist/appear.min.js') }}"></script>
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
