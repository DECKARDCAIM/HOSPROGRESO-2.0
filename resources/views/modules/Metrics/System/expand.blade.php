@extends('layouts.panel')

@section('content')
    <main id="content" role="main" class="main">
        <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-end">
                    <div class="col-sm mb-2 mb-sm-0">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-no-gutter">
                                <li class="breadcrumb-item"><a class="breadcrumb-link" href="/">Inicio</a></li>
                                <li class="breadcrumb-item"><a class="breadcrumb-link"
                                        href="{{ route('metrics.system.index') }}">Métricas</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ $chartNames[$chartId] ?? 'Métricas del Sistema (Expandida)' }}</li>
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
                    <div class="card">
                        <div class="card-header d-flex align-items-center justify-content-between">

                            <div style="flex: 1;">
                                <h4 class="card-header-title mb-0">{{ $chartNames[$chartId] ?? 'Gráfica Expandida' }}</h4>
                            </div>

                            <div class="d-flex justify-content-center" style="flex: 1;">
                                <div class="btn-group chart-nav-controls" id="navControls">
                                    <button type="button" class="btn btn-white btn-sm" id="navLeftBtn"
                                        title="Mover Izquierda"><i class="bi-chevron-left"></i></button>
                                    <button type="button" class="btn btn-white btn-sm" id="navRightBtn"
                                        title="Mover Derecha"><i class="bi-chevron-right"></i></button>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end align-items-center" style="flex: 1;">
                                <div class="dropdown">
                                    <button type="button" class="btn btn-white btn-sm dropdown-toggle"
                                        id="exportDropdownExpand" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi-download me-2"></i> Exportar
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-sm-end" aria-labelledby="exportDropdownExpand">
                                        <span class="dropdown-header">Opciones</span>
                                        <a class="dropdown-item" href="javascript:;" id="printChartBtn">
                                            <img class="avatar avatar-xss avatar-4x3 me-2"
                                                src="{{ asset('svg/illustrations/print-icon.svg') }}" alt="Imprimir"> Imprimir
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <span class="dropdown-header">Opciones de descarga</span>
                                        <a class="dropdown-item" href="javascript:;" id="exportPdfBtn">
                                            <img class="avatar avatar-xss avatar-4x3 me-2"
                                                src="{{ asset('svg/brands/pdf-icon.svg') }}" alt="PDF"> PDF
                                        </a>
                                        <a class="dropdown-item" href="javascript:;" id="exportSvgBtn">
                                            <img class="avatar avatar-xss avatar-4x3 me-2"
                                                src="{{ asset('svg/components/placeholder-csv-format.svg') }}" alt="SVG"> SVG
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-body" style="position: relative;">

                            <div class="position-absolute shadow-sm" style="right: 35px; top: 35px; z-index: 10;">
                                <div class="btn-group-vertical chart-zoom-controls" id="zoomControls">
                                    <button type="button" class="btn btn-white btn-sm" id="zoomInBtn" title="Acercar">
                                        <i class="bi-zoom-in"></i>
                                    </button>
                                    <button type="button" class="btn btn-white btn-sm" id="resetZoomBtn"
                                        title="Restablecer">
                                        <i class="bi-arrow-counterclockwise"></i>
                                    </button>
                                    <button type="button" class="btn btn-white btn-sm" id="zoomOutBtn" title="Alejar">
                                        <i class="bi-zoom-out"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="chart-container-expanded"
                                style="position: relative; width: 100%; height: calc(100vh - 300px); overflow: hidden;">
                                <canvas id="expandedChart" style="display: block; cursor: grab;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@2.0.1/dist/chartjs-plugin-zoom.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Chart === 'undefined') return;

            // Registrar Plugin de Zoom
            if (typeof zoomPlugin !== 'undefined') {
                Chart.register(zoomPlugin);
            } else if (window.zoomPlugin) {
                Chart.register(window.zoomPlugin);
            }

            // Datos desde el backend
            const locationsData = @json($locationsData);
            const currentChartId = @json($chartId);
            const canvas = document.getElementById('expandedChart');

            if (!canvas) return;

            // Configuración e inicialización de la gráfica
            const expandedChartInstance = new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: locationsData.map(item => item.type),
                    datasets: [{
                            label: 'Activos',
                            data: locationsData.map(item => item.active),
                            backgroundColor: 'rgba(55, 125, 255, 0.8)',
                            borderColor: 'rgba(55, 125, 255, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Desactivados',
                            data: locationsData.map(item => item.inactive),
                            backgroundColor: 'rgba(237, 76, 120, 0.8)',
                            borderColor: 'rgba(237, 76, 120, 1)',
                            borderWidth: 1
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
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: true
                        },
                        zoom: {
                            limits: {
                                x: {
                                    min: 'original',
                                    max: 'original'
                                }
                            },
                            pan: {
                                enabled: true,
                                mode: 'x',
                                modifierKey: null
                            },
                            zoom: {
                                wheel: {
                                    enabled: true
                                },
                                pinch: {
                                    enabled: true
                                },
                                mode: 'x'
                            }
                        }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false
                    }
                }
            });

            // Cambiar el cursor al arrastrar
            canvas.addEventListener('mousedown', () => canvas.style.cursor = 'grabbing');
            window.addEventListener('mouseup', () => canvas.style.cursor = 'grab');

            // --- CONTROLES DE INTERFAZ (Botones) ---
            document.getElementById('zoomInBtn')?.addEventListener('click', () => expandedChartInstance.zoom(1.2));
            document.getElementById('zoomOutBtn')?.addEventListener('click', () => expandedChartInstance.zoom(0.8));
            document.getElementById('resetZoomBtn')?.addEventListener('click', () => expandedChartInstance
                .resetZoom());
            document.getElementById('navLeftBtn')?.addEventListener('click', () => expandedChartInstance.pan({
                x: 150
            }));
            document.getElementById('navRightBtn')?.addEventListener('click', () => expandedChartInstance.pan({
                x: -150
            }));

            // --- EXPORTACIONES ---

            // Función auxiliar para obtener la imagen
            const getChartImage = () => canvas.toDataURL('image/png', 1.0);

            // 1. Imprimir
            document.getElementById('printChartBtn')?.addEventListener('click', () => {
                const printWindow = window.open('', '_blank');
                printWindow.document.write(`
                    <html>
                      <head><title>Imprimir Gráfica</title></head>
                      <body style="margin:0;padding:20px;text-align:center;background-color:#ffffff;">
                        <img src="${getChartImage()}" style="max-width:100%;height:auto;" onload="window.print(); window.close();" />
                      </body>
                    </html>
                `);
                printWindow.document.close();
            });

            // 2. Exportar PDF
            document.getElementById('exportPdfBtn')?.addEventListener('click', () => {
                const {
                    jsPDF
                } = window.jspdf;
                const pdf = new jsPDF('landscape', 'mm', 'a4');
                const imgWidth = 297;
                const imgHeight = (canvas.height * imgWidth) / canvas.width;

                pdf.addImage(getChartImage(), 'PNG', 0, 0, imgWidth, imgHeight);
                pdf.save(`grafica-${currentChartId}.pdf`);
            });

            // 3. Exportar SVG 
            document.getElementById('exportSvgBtn')?.addEventListener('click', () => {
                const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                svg.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
                svg.setAttribute('width', canvas.width);
                svg.setAttribute('height', canvas.height);

                const image = document.createElementNS('http://www.w3.org/2000/svg', 'image');
                image.setAttribute('href', getChartImage());
                image.setAttribute('width', canvas.width);
                image.setAttribute('height', canvas.height);
                svg.appendChild(image);

                const svgBlob = new Blob([new XMLSerializer().serializeToString(svg)], {
                    type: 'image/svg+xml;charset=utf-8'
                });
                const svgUrl = URL.createObjectURL(svgBlob);

                const link = document.createElement('a');
                link.download = `grafica-${currentChartId}.svg`;
                link.href = svgUrl;
                link.click();

                URL.revokeObjectURL(svgUrl);
            });
        });
    </script>
@endsection
