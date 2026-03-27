@extends('layouts.panel')
@section('title', 'Comunicados')

@section('styles')
    <link rel="stylesheet" href="{{ asset('vendor/fullcalendar/main.min.css') }}">
    <style>
        .fc-daygrid-day {
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .fc-daygrid-day:hover {
            background-color: rgba(55, 125, 255, 0.05) !important;
        }

        /* Ajuste visual para que los textos negros en fondos amarillos se lean mejor */
        .event-warning {
            color: #333 !important;
        }
    </style>
@endsection

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
                                <li class="breadcrumb-item active" aria-current="page">Comunicados</li>
                            </ol>
                        </nav>
                        <h1 class="page-header-title">Gestión de Comunicados</h1>
                    </div>
                    <div class="col-sm-auto">
                        <a class="btn btn-primary" href="{{ route('releases.create') }}">
                            <i class="bi-plus-circle me-1"></i> Crear Comunicado
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header border-bottom">
                            <h5 class="card-header-title"><i class="bi-calendar3 me-2 text-primary"></i> Calendario</h5>
                        </div>
                        <div class="card-body">
                            <div id="js-fullcalendar" class="js-fullcalendar fullcalendar-custom"></div>

                            @if (request('date'))
                                <div class="mt-4 text-center">
                                    <a href="{{ route('releases.index') }}" class="btn btn-soft-secondary btn-sm">
                                        <i class="bi-x-circle me-1"></i> Quitar filtro y ver todos los registros
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                            <h5 class="card-header-title mb-0">
                                @if (request('date'))
                                    Mostrando: <span
                                        class="text-primary">{{ \Carbon\Carbon::parse(request('date'))->format('d/m/Y') }}</span>
                                @else
                                    Listado General
                                @endif
                            </h5>
                            <div class="col-auto">
                                <div class="input-group input-group-merge input-group-sm">
                                    <span class="input-group-prepend input-group-text"><i class="bi-search"></i></span>
                                    <input type="text" class="form-control" placeholder="Buscar en listado..."
                                        id="filterSearch">
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive datatable-custom">
                            <table
                                class="table table-lg table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>DETALLES</th>
                                        <th>CATEGORÍA</th>
                                        <th>PROGRAMACIÓN</th>
                                        <th class="text-end">ACCIONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($releases as $release)
                                        <tr>
                                            <td>
                                                <span class="d-block h5 text-inherit mb-0">{{ $release->title }}</span>
                                                <small
                                                    class="text-muted">{{ Str::limit(strip_tags($release->content), 40) }}</small>
                                            </td>
                                            <td>
                                                @if ($release->type == 'actualizacion')
                                                    <span class="badge bg-soft-info text-info">🔄 Actualización</span>
                                                @else
                                                    <span class="badge bg-soft-primary text-primary">📢 Comunicado</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="fs-6">{{ $release->published_at ? $release->published_at->format('d M, Y H:i') : 'Publicado' }}</span>
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group">
                                                    <a href="{{ route('releases.edit', $release->id) }}"
                                                        class="btn btn-white btn-sm" title="Editar">
                                                        <i class="bi-pencil"></i>
                                                    </a>
                                                    <a href="{{ route('releases.show', $release->id) }}"
                                                        class="btn btn-white btn-sm" title="Ver">
                                                        <i class="bi-eye"></i>
                                                    </a>
                                                    <form action="{{ route('releases.destroy', $release->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('¿Eliminar permanente?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-white btn-sm text-danger"
                                                            title="Borrar">
                                                            <i class="bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5">
                                                <p class="mb-0 text-muted">No se encontraron registros para mostrar en esta
                                                    fecha.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer border-0">
                            {{ $releases->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('vendor/fullcalendar/main.min.js') }}"></script>
    <script src="{{ asset('vendor/fullcalendar/locales-all.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarData = @json($calendarData);
            var eventos = [];

            for (var date in calendarData) {
                let dataInfo = calendarData[date];

                // Si el controlador manda solo un número (código anterior)
                if (typeof dataInfo === 'number') {
                    eventos.push({
                        title: dataInfo + ' reg.',
                        start: date,
                        allDay: true,
                        backgroundColor: '#377dff',
                        borderColor: '#377dff'
                    });
                }
                // Si el controlador manda el detalle de publicados y programados
                else {
                    if (dataInfo.publicados > 0) {
                        eventos.push({
                            title: dataInfo.publicados + ' Publicados',
                            start: date,
                            allDay: true,
                            backgroundColor: '#00c9a7', // Verde
                            borderColor: '#00c9a7',
                            textColor: '#fff'
                        });
                    }
                    if (dataInfo.programados > 0) {
                        eventos.push({
                            title: dataInfo.programados + ' Programados',
                            start: date,
                            allDay: true,
                            backgroundColor: '#ffc107', // Amarillo
                            borderColor: '#ffc107',
                            className: 'event-warning' // Clase para forzar texto oscuro
                        });
                    }
                }
            }

            HSCore.components.HSFullCalendar.init('#js-fullcalendar', {
                locale: 'es',
                events: eventos,
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: ''
                },
                dayHeaderFormat: {
                    weekday: 'short'
                },
                aspectRatio: 2.2, // Hace el calendario más ancho y menos alto
                height: 'auto',
                contentHeight: 'auto',
                dateClick: function(info) {
                    // Al hacer clic, redirige enviando la fecha y bajando hacia la tabla
                    window.location.href = "{{ route('releases.index') }}?date=" + info.dateStr;
                }
            });
        });
    </script>
@endpush
