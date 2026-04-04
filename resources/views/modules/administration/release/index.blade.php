@extends('layouts.panel')
@section('title', 'Comunicados')
@section('content')
    <main id="content" role="main" class="main">
        <div class="content container-fluid py-4">
            <div class="page-header">
                <div class="row align-items-end">
                    <div class="col-sm mb-2 mb-sm-0">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-no-gutter">
                                <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('home') }}">Inicio</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Comunicados</li>
                            </ol>
                        </nav>
                        <h1 class="page-header-title">Listado de comunicados</h1>
                    </div>
                    <div class="col-sm-auto">
                        <a class="btn btn-primary" href="{{ route('releases.create') }}">
                            <i class="bi-person-plus-fill me-1"></i> Agregar comunicado
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
                <div class="col-sm-6 col-md-3 mb-3 mb-lg-5">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2">Total</h6>
                            <div class="row align-items-center gx-2">
                                <div class="col">
                                    <span class="js-counter display-4 text-dark">{{ $allTotal ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 mb-3 mb-lg-5">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2">Publicados</h6>
                            <div class="row align-items-center gx-2">
                                <div class="col">
                                    <span class="js-counter display-4 text-dark">{{ $allPublished ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 mb-3 mb-lg-5">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2">Borradores</h6>
                            <div class="row align-items-center gx-2">
                                <div class="col">
                                    <span class="js-counter display-4 text-dark">{{ $allDrafts ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 mb-3 mb-lg-5">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-subtitle mb-2">Este mes</h6>
                            <div class="row align-items-center gx-2">
                                <div class="col">
                                    <span class="js-counter display-4 text-dark">{{ $allThisMonth ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 mb-4 mb-lg-0">
                    <div class="card">
                        <div class="card-header card-header-content-md-between">
                            <div class="mb-2 mb-md-0">
                                <form action="{{ route('releases.index') }}" method="GET">
                                    @if (request('per_page'))
                                        <input type="hidden" name="per_page" value="{{ request('per_page') }}">
                                    @endif
                                    <div class="input-group input-group-merge input-group-flush">
                                        <button type="submit" class="input-group-prepend input-group-text bg-transparent border-0">
                                            <i class="bi-search"></i>
                                        </button>
                                        <input name="search" type="text" class="form-control" placeholder="Buscar comunicados" value="{{ request('search') }}">
                                        @if (request('search'))
                                            <a class="input-group-append input-group-text text-muted"
                                                href="{{ route('releases.index', request()->except('search')) }}">
                                                <i class="bi-x-lg"></i>
                                            </a>
                                        @endif
                                    </div>
                                </form>
                            </div>
                            <div class="d-grid d-sm-flex justify-content-md-end align-items-sm-center gap-2">
                                <div class="dropdown">
                                    <button type="button" class="btn btn-white btn-sm w-100" id="releasesFilterDropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                        <i class="bi-filter" style="font-style: normal;"> Filtrar </i>
                                        @if ($activeFilters > 0)
                                            <span class="badge bg-soft-dark text-dark rounded-circle ms-1">{{ $activeFilters }}</span>
                                        @endif
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-sm-end dropdown-card card-dropdown-filter-centered" style="min-width: 25rem;">
                                        <div class="card">
                                            <div class="card-header card-header-content-between">
                                                <h5 class="card-header-title">Filtrar comunicados</h5>
                                            </div>
                                            <div class="card-body">
                                                <form action="{{ route('releases.index') }}" method="GET">
                                                    @if (request('search'))
                                                        <input type="hidden" name="search" value="{{ request('search') }}">
                                                    @endif
                                                    <div class="row">
                                                        <div class="col-12 mb-3">
                                                            <label class="form-label">Estado Registro</label>
                                                            <select name="record_status" class="form-select form-select-sm">
                                                                <option value="active" {{ request('record_status', 'active') == 'active' ? 'selected' : '' }}>Activo</option>
                                                                <option value="inactive" {{ request('record_status') == 'inactive' ? 'selected' : '' }}>Eliminado (Inactivo)</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-12 mb-3">
                                                            <label class="form-label">Publicación</label>
                                                            <select name="status" class="form-select form-select-sm">
                                                                <option value="" {{ !request()->has('status') ? 'selected' : '' }}>Todos</option>
                                                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Publicados</option>
                                                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Borradores</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-12 mb-3">
                                                            <label class="form-label">Tipo</label>
                                                            <select name="type" class="form-select form-select-sm">
                                                                <option value="">Todos</option>
                                                                <option value="release" {{ request('type') == 'release' ? 'selected' : '' }}>Comunicado</option>
                                                                <option value="update" {{ request('type') == 'update' ? 'selected' : '' }}>Actualización</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-12 mb-3">
                                                            <label class="form-label">Fecha de Publicación</label>
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                                                                </div>
                                                                <div class="col-6">
                                                                    <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="d-grid gap-2">
                                                        <button type="submit" class="btn btn-primary btn-sm">Aplicar Filtros</button>
                                                        <a href="{{ route('releases.index') }}" class="btn btn-white btn-sm">Limpiar Filtros</a>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive datatable-custom position-relative">
                            <table class="table table-lg table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Título</th>
                                        <th>Tipo</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($releases as $release)
                                        @php
                                            $pubDate = $release->published_at ?? $release->created_at;
                                            $isDraft = $release->status === 'draft';
                                            $isActual = $release->type === 'update';
                                        @endphp
                                        <tr>
                                            <td>
                                                <span class="d-block text-inherit mb-0" title="{{ $release->title }}">
                                                    {{ Str::limit($release->title, 22) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($isActual)
                                                    <span class="text-body">Actualización</span>
                                                @else
                                                    <span class="text-body">Comunicado</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($release->trashed())
                                                    <span class="legend-indicator bg-danger"></span>Eliminado
                                                @elseif ($isDraft)
                                                    <span class="legend-indicator bg-warning"></span>Borrador
                                                @else
                                                    <span class="legend-indicator bg-success"></span>Publicado
                                                @endif
                                            </td>
                                            <td>
                                                <span class="d-block mb-0">{{ $pubDate->format('d/m/Y') }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex gap-1 justify-content-start">
                                                    <a class="btn btn-white btn-sm" style="padding: 0.35rem 0.5rem;" href="{{ route('releases.show', $release->id) }}" title="Ver"><i class="bi-eye-fill" style="font-size: 0.85rem;"></i></a>
                                                    @if (!$release->trashed())
                                                    <a class="btn btn-white btn-sm" style="padding: 0.35rem 0.5rem;" href="{{ route('releases.edit', $release->id) }}" title="Editar"><i class="bi-pencil-fill" style="font-size: 0.85rem;"></i></a>
                                                    <form action="{{ route('releases.destroy', $release->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-white btn-sm" style="padding: 0.35rem 0.5rem;" title="Eliminar"><i class="bi-trash" style="font-size: 0.85rem;"></i></button>
                                                    </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                <div class="p-4">
                                                    <i class="bi-mailbox fs-1 text-muted mb-3 d-block"></i>
                                                    <h5>No hay comunicados todavía</h5>
                                                    <p class="text-muted">Crea el primero usando el botón "Nuevo comunicado"</p>
                                                </div>
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
                                            <select id="datatableEntries" class="js-select form-select form-select-borderless w-auto" autocomplete="off" data-hs-tom-select-options='{"searchInDropdown": false, "hideSearch": true}' onchange="window.location.href = '{{ route('releases.index', request()->except(['per_page', 'page'])) }}' + ( '{{ route('releases.index', request()->except(['per_page', 'page'])) }}'.includes('?') ? '&' : '?' ) + 'per_page=' + this.value">
                                                <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5</option>
                                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                            </select>
                                        </div>
                                        <span class="text-secondary me-2">de</span>
                                        <span id="datatableWithPaginationInfoTotalQty">{{ $releases->total() }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-auto">
                                    <div class="d-flex justify-content-center justify-content-sm-end">
                                        {{ $releases->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header border-bottom">
                            <h5 class="card-header-title">
                                <i class="bi-calendar3 text-primary me-2"></i> Calendario
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <button type="button" class="btn btn-white btn-sm px-2 py-1" id="calPrev">
                                    <i class="bi-chevron-left"></i>
                                </button>
                                <span class="fw-bold text-dark text-capitalize" id="calMonthLabel"
                                    style="font-size: 0.95rem;"></span>
                                <button type="button" class="btn btn-white btn-sm px-2 py-1" id="calNext">
                                    <i class="bi-chevron-right"></i>
                                </button>
                            </div>
                            <div id="miniCalendarGrid"></div>
                            <hr class="my-3">
                            <div id="calEventsList" class="list-group list-group-flush list-group-no-gutters">
                                <div class="text-center text-muted small py-3">Cargando eventos...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@push('scripts')
    @php
        $calendarEvents = \App\Models\Release::select('id', 'title', 'status', 'published_at', 'created_at')
            ->orderBy('published_at', 'asc')
            ->get()
            ->map(function ($r) {
                $date = $r->published_at ?: $r->created_at;
                return [
                    'id' => $r->id,
                    'title' => $r->title,
                    'status' => $r->status,
                    'date' => $date ? \Carbon\Carbon::parse($date)->format('Y-m-d') : null,
                    'url' => route('releases.show', $r->id),
                ];
            });
    @endphp
    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
            const releaseEvents = @json($calendarEvents);
            const today = new Date();
            let calYear = today.getFullYear();
            let calMonth = today.getMonth();

            const MONTHS_ES = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto',
                'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
            ];
            const DAYS_ES = ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'];

            function buildEventMap() {
                const map = {};
                releaseEvents.forEach(ev => {
                    if (ev.date) {
                        if (!map[ev.date]) map[ev.date] = [];
                        map[ev.date].push(ev);
                    }
                });
                return map;
            }

            function renderCalendar(year, month) {
                const eventMap = buildEventMap();
                const gridContainer = document.getElementById('miniCalendarGrid');

                document.getElementById('calMonthLabel').textContent = `${MONTHS_ES[month]} ${year}`;

                const firstDay = new Date(year, month, 1).getDay();
                const daysInMonth = new Date(year, month + 1, 0).getDate();
                const daysInPrevMonth = new Date(year, month, 0).getDate();

                const todayStr =
                    `${today.getFullYear()}-${String(today.getMonth()+1).padStart(2,'0')}-${String(today.getDate()).padStart(2,'0')}`;

                let html = '<div class="d-flex text-center mb-2">';
                DAYS_ES.forEach(d => {
                    html +=
                        `<div style="width: 14.28%;"><small class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem;">${d}</small></div>`;
                });
                html += '</div><div class="d-flex flex-wrap text-center g-1">';
                for (let i = firstDay - 1; i >= 0; i--) {
                    html += `<div style="width: 14.28%; padding: 2px;">
                                <div class="p-1 text-muted" style="opacity: 0.4; font-size: 0.85rem;">${daysInPrevMonth - i}</div>
                             </div>`;
                }
                for (let d = 1; d <= daysInMonth; d++) {
                    const dateStr = `${year}-${String(month+1).padStart(2,'0')}-${String(d).padStart(2,'0')}`;
                    const isToday = dateStr === todayStr;
                    const events = eventMap[dateStr] || [];
                    const hasDraft = events.some(e => e.status === 'draft');
                    const hasPub = events.some(e => e.status === 'published');

                    let bgClass = '';
                    let textClass = 'text-dark';
                    let fwClass = 'fw-normal';
                    let dot = '';
                    let cursor = '';

                    if (isToday) {
                        bgClass = 'bg-primary';
                        textClass = 'text-white';
                        fwClass = 'fw-bold';
                        
                        if (hasPub) {
                            dot = '<div class="bg-white rounded-circle mx-auto mt-1" style="width: 4px; height: 4px;"></div>';
                            cursor = 'cursor: pointer;';
                        } else if (hasDraft) {
                            dot = '<div class="bg-light rounded-circle mx-auto mt-1" style="width: 4px; height: 4px; opacity: 0.8;"></div>';
                            cursor = 'cursor: pointer;';
                        }
                    } else if (hasPub) {
                        bgClass = 'bg-soft-primary';
                        textClass = 'text-primary';
                        fwClass = 'fw-bold';
                        cursor = 'cursor: pointer;';
                        dot =
                            '<div class="bg-primary rounded-circle mx-auto mt-1" style="width: 4px; height: 4px;"></div>';
                    } else if (hasDraft && !hasPub) {
                        bgClass = 'bg-soft-warning';
                        textClass = 'text-warning';
                        fwClass = 'fw-bold';
                        cursor = 'cursor: pointer;';
                        dot =
                            '<div class="bg-warning rounded-circle mx-auto mt-1" style="width: 4px; height: 4px;"></div>';
                    } else {
                        textClass = 'text-muted';
                    }

                    const onClickAttr = events.length ? `onclick="calDayClick('${dateStr}')"` : '';

                    html += `<div style="width: 14.28%; padding: 2px;">
                                <div class="rounded p-1 ${bgClass} ${textClass} ${fwClass}" ${onClickAttr} style="font-size: 0.85rem; transition: 0.2s; ${cursor}">
                                    ${d}
                                    ${dot}
                                </div>
                             </div>`;
                }
                const totalCells = firstDay + daysInMonth;
                const remaining = totalCells % 7 === 0 ? 0 : 7 - (totalCells % 7);
                for (let i = 1; i <= remaining; i++) {
                    html += `<div style="width: 14.28%; padding: 2px;">
                                <div class="p-1 text-muted" style="opacity: 0.4; font-size: 0.85rem;">${i}</div>
                             </div>`;
                }

                html += '</div>';
                gridContainer.innerHTML = html;

                document.getElementById('calPrev').onclick = () => {
                    calMonth--;
                    if (calMonth < 0) {
                        calMonth = 11;
                        calYear--;
                    }
                    renderCalendar(calYear, calMonth);
                    renderUpcoming(null);
                };

                document.getElementById('calNext').onclick = () => {
                    calMonth++;
                    if (calMonth > 11) {
                        calMonth = 0;
                        calYear++;
                    }
                    renderCalendar(calYear, calMonth);
                    renderUpcoming(null);
                };
            }

            function renderUpcoming(filterDate) {
                const list = document.getElementById('calEventsList');
                let events;

                if (!filterDate) {
                    filterDate = `${today.getFullYear()}-${String(today.getMonth()+1).padStart(2,'0')}-${String(today.getDate()).padStart(2,'0')}`;
                }

                events = releaseEvents.filter(e => e.date === filterDate);

                if (!events.length) {
                    const isToday = filterDate === `${today.getFullYear()}-${String(today.getMonth()+1).padStart(2,'0')}-${String(today.getDate()).padStart(2,'0')}`;
                    list.innerHTML =
                        `<div class="text-center text-muted small py-3">${isToday ? 'Sin comunicados hoy.' : 'Sin comunicados ese día.'}</div>`;
                    return;
                }

                list.innerHTML = events.map(ev => `
                    <a href="${ev.url}" class="list-group-item list-group-item-action d-flex align-items-center border-0 px-0 py-2">
                        <span class="badge ${ev.status === 'draft' ? 'bg-warning' : 'bg-primary'} rounded-circle p-1 me-2" style="width: 8px; height: 8px;">
                            <span class="visually-hidden">Estado</span>
                        </span>
                        <div class="flex-grow-1 text-truncate">
                            <span class="d-block text-dark fw-semi-bold" style="font-size: 0.85rem;">${ev.title}</span>
                        </div>
                        <div class="flex-shrink-0 ms-2 text-muted" style="font-size: 0.75rem;">
                            ${ev.date.slice(8)}
                        </div>
                    </a>
                `).join('');
            }

            window.calDayClick = function(dateStr) {
                renderUpcoming(dateStr);
            };
            renderCalendar(calYear, calMonth);
            renderUpcoming(null);
        });
    </script>
@endpush