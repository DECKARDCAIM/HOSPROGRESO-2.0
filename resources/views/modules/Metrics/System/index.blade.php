@extends('layouts.panel')
@section('title', 'Métricas del Sistema')
@section('content')
    <main id="content" role="main" class="main">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-end">
                    <div class="col-sm mb-2 mb-sm-0">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-no-gutter">
                                <li class="breadcrumb-item"><a class="breadcrumb-link" href="/">Inicio</a></li>
                                <li class="breadcrumb-item"><span>Métricas</span></li>
                                <li class="breadcrumb-item active" aria-current="page">Sistema</li>
                            </ol>
                        </nav>
                        <h1 class="page-header-title">Métricas del Sistema</h1>
                    </div>
                </div>
            </div>
            <div class="row mb-3 mb-lg-5">
                <div class="col-12">
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

                    <div class="card">
                        <div class="card-header card-header-content-between">
                            <h4 class="card-header-title">Gráfica de Ubicaciones</h4>
                            <div class="d-flex gap-2 align-items-center">
                                <div class="dropdown">
                                    <button type="button" class="btn btn-white btn-sm dropdown-toggle" id="exportDropdown1" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi-download me-2"></i> Exportar
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-sm-end" aria-labelledby="exportDropdown1">
                                        <span class="dropdown-header">Opciones</span>
                                        <a class="dropdown-item print-chart-btn" href="javascript:;" data-chart-id="chart1">
                                            <img class="avatar avatar-xss avatar-4x3 me-2" src="{{ asset('dist/svg/illustrations/print-icon.svg') }}" alt="Imprimir"> Imprimir
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <span class="dropdown-header">Opciones de descarga</span>
                                        <a class="dropdown-item export-pdf-btn" href="javascript:;" data-chart-id="chart1">
                                            <img class="avatar avatar-xss avatar-4x3 me-2" src="{{ asset('dist/svg/brands/pdf-icon.svg') }}" alt="PDF"> PDF
                                        </a>
                                        <a class="dropdown-item export-svg-btn" href="javascript:;" data-chart-id="chart1">
                                            <img class="avatar avatar-xss avatar-4x3 me-2" src="{{ asset('dist/svg/components/placeholder-csv-format.svg') }}" alt="SVG"> SVG
                                        </a>
                                    </div>
                                </div>
                                <a href="{{ route('metrics.system.expand', 'chart1') }}" class="btn btn-white btn-sm">
                                    <i class="bi-arrows-fullscreen me-1"></i> Expandir
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chartjs-custom" style="height: 20rem;">
                                <canvas id="chart1" class="js-chart" data-hs-chartjs-options='{
                                    "type": "bar",
                                    "options": {
                                        "scales": {
                                            "y": {
                                                "grid": {
                                                    "color": "#e7eaf3",
                                                    "drawBorder": false,
                                                    "zeroLineColor": "#e7eaf3"
                                                },
                                                "ticks": {
                                                    "beginAtZero": true,
                                                    "color": "#97a4af",
                                                    "font": {
                                                        "family": "Inter, sans-serif"
                                                    },
                                                    "padding": 10
                                                }
                                            },
                                            "x": {
                                                "grid": {
                                                    "display": false,
                                                    "drawBorder": false
                                                },
                                                "ticks": {
                                                    "color": "#97a4af",
                                                    "font": {
                                                        "size": 12,
                                                        "family": "Inter, sans-serif"
                                                    },
                                                    "padding": 5
                                                },
                                                "categoryPercentage": 0.5
                                            }
                                        },
                                        "cornerRadius": 2,
                                        "plugins": {
                                            "tooltip": {
                                                "hasIndicator": true,
                                                "mode": "index",
                                                "intersect": false
                                            }
                                        },
                                        "hover": {
                                            "mode": "nearest",
                                            "intersect": true
                                        }
                                    }
                                }'></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Chart === 'undefined') {
                console.error("Chart.js no cargó. Verifica la ruta local.");
                return;
            }

            try {
                if (typeof zoomPlugin !== 'undefined') {
                    Chart.register(zoomPlugin);
                } else if (window.zoomPlugin) {
                    Chart.register(window.zoomPlugin);
                }
            } catch (e) {}

            const locationsData = @json($locationsData);

            const chartLabels = locationsData.map(item => item.type);
            const activeData = locationsData.map(item => item.active);
            const inactiveData = locationsData.map(item => item.inactive);

            HSCore.components.HSChartJS.init(document.querySelector('#chart1'), {
                data: {
                    labels: chartLabels,
                    datasets: [
                        {
                            label: 'Activos',
                            data: activeData,
                            backgroundColor: '#377dff',
                            hoverBackgroundColor: '#377dff',
                            borderColor: '#377dff',
                            maxBarThickness: 15
                        },
                        {
                            label: 'Desactivados',
                            data: inactiveData,
                            backgroundColor: '#ed4c78',
                            hoverBackgroundColor: '#ed4c78',
                            borderColor: '#ed4c78',
                            maxBarThickness: 15
                        }
                    ]
                }
            });

            window.charts = window.charts || {};
            window.charts.chart1 = HSCore.components.HSChartJS.getItem('chart1');

            function submitChartExport(url, chartId) {
                const chart = window.charts[chartId];
                if (!chart) return;

                const canvas = chart.canvas;
                const imgData = canvas.toDataURL('image/png');

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;
                form.target = '_blank';

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);

                const imageInput = document.createElement('input');
                imageInput.type = 'hidden';
                imageInput.name = 'chart_image';
                imageInput.value = imgData;
                form.appendChild(imageInput);

                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'chart_id';
                idInput.value = chartId;
                form.appendChild(idInput);

                document.body.appendChild(form);
                form.submit();
                document.body.removeChild(form);
            }

            document.querySelectorAll('.print-chart-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    submitChartExport('{{ route('metrics.system.export.print') }}', this.getAttribute('data-chart-id'));
                });
            });

            document.querySelectorAll('.export-pdf-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    submitChartExport('{{ route('metrics.system.export.pdf') }}', this.getAttribute('data-chart-id'));
                });
            });

            document.querySelectorAll('.export-svg-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const chartId = this.getAttribute('data-chart-id');
                    const chart = window.charts[chartId];

                    if (!chart) return;

                    const canvas = chart.canvas;
                    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                    svg.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
                    svg.setAttribute('width', canvas.width);
                    svg.setAttribute('height', canvas.height);

                    const image = document.createElementNS('http://www.w3.org/2000/svg', 'image');
                    image.setAttribute('href', canvas.toDataURL('image/png'));
                    image.setAttribute('width', canvas.width);
                    image.setAttribute('height', canvas.height);
                    svg.appendChild(image);

                    const svgBlob = new Blob([new XMLSerializer().serializeToString(svg)], {
                        type: 'image/svg+xml;charset=utf-8'
                    });
                    const svgUrl = URL.createObjectURL(svgBlob);
                    const link = document.createElement('a');
                    link.download = 'grafica-' + chartId + '.svg';
                    link.href = svgUrl;
                    link.click();
                    URL.revokeObjectURL(svgUrl);
                });
            });
        });
    </script>
@endsection