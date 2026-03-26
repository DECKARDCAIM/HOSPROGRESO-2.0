@extends('layouts.panel')

@section('content')
    <main id="content" role="main" class="main">
        <!-- Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
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
            <!-- End Page Header -->

            <!-- Gráfica -->
            <div class="row mb-3 mb-lg-5">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header card-header-content-between">
                            <h4 class="card-header-title">Gráfica de Ubicaciones</h4>
                            <div class="d-flex gap-2 align-items-center">
                                <div class="dropdown">
                                    <button type="button" class="btn btn-white btn-sm dropdown-toggle" id="exportDropdown1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi-download me-2"></i> Exportar
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-sm-end" aria-labelledby="exportDropdown1">
                                        <span class="dropdown-header">Opciones</span>
                                        <a class="dropdown-item print-chart-btn" href="javascript:;" data-chart-id="chart1">
                                            <img class="avatar avatar-xss avatar-4x3 me-2"
                                                src="{{ asset('svg/illustrations/print-icon.svg') }}" alt="Imprimir"> Imprimir
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <span class="dropdown-header">Opciones de descarga</span>
                                        <a class="dropdown-item export-pdf-btn" href="javascript:;" data-chart-id="chart1">
                                            <img class="avatar avatar-xss avatar-4x3 me-2"
                                                src="{{ asset('svg/brands/pdf-icon.svg') }}" alt="PDF"> PDF
                                        </a>
                                        <a class="dropdown-item export-svg-btn" href="javascript:;" data-chart-id="chart1">
                                            <img class="avatar avatar-xss avatar-4x3 me-2"
                                                src="{{ asset('svg/components/placeholder-csv-format.svg') }}" alt="SVG"> SVG
                                        </a>
                                    </div>
                                </div>
                                <a href="{{ route('metrics.system.expand', 'chart1') }}" class="btn btn-white btn-sm">
                                    <i class="bi-arrows-fullscreen me-1"></i> Expandir
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container" style="position: relative; height: 400px;">
                                <canvas id="chart1"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Gráfica -->

        </div>
        <!-- End Content -->
    </main>

    <!-- Scripts adicionales -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@2.0.1/dist/chartjs-plugin-zoom.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Chart === 'undefined') {
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

            // Gráfica de barras
            const ctx1 = document.getElementById('chart1');
            if (ctx1) {
                const chart1 = new Chart(ctx1, {
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
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {
                                display: true
                            }
                        }
                    }
                });

                // Guardar referencia
                window.charts = window.charts || {};
                window.charts.chart1 = chart1;
            }

            // Manejar impresión
            document.querySelectorAll('.print-chart-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const chartId = this.getAttribute('data-chart-id');
                    const chart = window.charts[chartId];

                    if (!chart) return;

                    const canvas = chart.canvas;
                    const url = canvas.toDataURL('image/png');
                    const printWindow = window.open('', '_blank');
                    printWindow.document.write(`
                    <html>
                      <head><title>Imprimir Gráfica</title></head>
                      <body style="margin:0;padding:20px;text-align:center;background-color:#ffffff;">
                        <img src="${url}" style="max-width:100%;height:auto;" onload="window.print(); window.close();" />
                      </body>
                    </html>
                `);
                    printWindow.document.close();
                });
            });

            // Manejar exportación PDF
            document.querySelectorAll('.export-pdf-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const chartId = this.getAttribute('data-chart-id');
                    const chart = window.charts[chartId];

                    if (!chart) return;

                    const canvas = chart.canvas;
                    const imgData = canvas.toDataURL('image/png');
                    const {
                        jsPDF
                    } = window.jspdf;
                    const pdf = new jsPDF('landscape', 'mm', 'a4');
                    const imgWidth = 297;
                    const imgHeight = (canvas.height * imgWidth) / canvas.width;
                    pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
                    pdf.save('grafica-' + chartId + '.pdf');
                });
            });

            // Manejar exportación SVG
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
