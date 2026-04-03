@extends('layouts.panel')
@section('title', $chartNames[$chartId])
@section('content')
    <main id="content" role="main" class="main">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-end">
                    <div class="col-sm mb-2 mb-sm-0">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-no-gutter">
                                <li class="breadcrumb-item"><a class="breadcrumb-link" href="/">Inicio</a></li>
                                <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('metrics.system.index') }}">Métricas</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $chartNames[$chartId] ?? 'Sistema (Expandida)' }}</li>
                            </ol>
                        </nav>
                        <h1 class="page-header-title">{{ $chartNames[$chartId] ?? 'Gráfica Expandida' }}</h1>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('metrics.system.index') }}" class="btn btn-primary">
                            <i class="bi-arrow-left"></i> Regresar
                        </a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card h-100">
                        <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <h4 class="card-header-title mb-0">{{ $chartNames[$chartId] ?? 'Vista Detallada' }}</h4>
                        </div>
                        <div class="card-body p-4">
                            <div class="chartjs-custom" style="height: calc(100vh - 280px); min-height: 450px;">
                                <canvas id="expandedChart" class="js-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Chart === 'undefined') {
                console.error("Chart.js no cargó. Verifica la ruta local.");
                return;
            }

            const locationsData = @json($locationsData);
            const canvasEl = document.getElementById('expandedChart');

            if (!canvasEl) return;

            const chartLabels = locationsData.map(item => item.type);
            const activeData = locationsData.map(item => item.active);
            const inactiveData = locationsData.map(item => item.inactive);
            HSCore.components.HSChartJS.init(canvasEl, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [
                        {
                            label: 'Activos',
                            data: activeData,
                            backgroundColor: '#377dff',
                            hoverBackgroundColor: '#377dff',
                            borderColor: '#377dff',
                            maxBarThickness: 25 
                        },
                        {
                            label: 'Desactivados',
                            data: inactiveData,
                            backgroundColor: '#ed4c78',
                            hoverBackgroundColor: '#ed4c78',
                            borderColor: '#ed4c78',
                            maxBarThickness: 25
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 150,
                        easing: 'easeOutQuad'
                    },
                    scales: {
                        y: {
                            grid: { color: "#e7eaf3", drawBorder: false, zeroLineColor: "#e7eaf3" },
                            ticks: { beginAtZero: true, color: "#97a4af", font: { family: "Inter, sans-serif" }, padding: 10 }
                        },
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { color: "#97a4af", font: { size: 12, family: "Inter, sans-serif" }, padding: 5 }
                        }
                    },
                    plugins: {
                        legend: { display: true },
                        tooltip: { mode: 'index', intersect: false, hasIndicator: true }
                    },
                    hover: { mode: 'nearest', intersect: true }
                }
            });
        });
    </script>
@endsection