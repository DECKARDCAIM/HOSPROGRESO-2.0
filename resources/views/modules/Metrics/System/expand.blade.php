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
                                <li class="breadcrumb-item"><a class="breadcrumb-link" href="{{ route('metrics.system.index') }}">Métricas</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $chartNames[$chartId] ?? 'Métricas del Sistema (Expandida)' }}</li>
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
            <!-- End Page Header -->

            <!-- Gráfica Expandida -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header card-header-content-between">
                            <h4 class="card-header-title">{{ $chartNames[$chartId] ?? 'Gráfica Expandida' }}</h4>
                            <div class="d-flex gap-2 align-items-center">
                                
                                <!-- Controles de Navegación -->
                                <div class="btn-group chart-nav-controls" id="navControls" style="display: none;">
                                    <button type="button" class="btn btn-white btn-sm" id="navLeftBtn" title="Mover Izquierda"><i class="bi-chevron-left"></i></button>
                                    <button type="button" class="btn btn-white btn-sm" id="navRightBtn" title="Mover Derecha"><i class="bi-chevron-right"></i></button>
                                </div>
                                
                                <!-- Controles de Zoom -->
                                <div class="btn-group chart-zoom-controls" id="zoomControls" style="display: none;">
                                    <button type="button" class="btn btn-white btn-sm" id="zoomInBtn" title="Acercar"><i class="bi-zoom-in"></i></button>
                                    <button type="button" class="btn btn-white btn-sm" id="zoomOutBtn" title="Alejar"><i class="bi-zoom-out"></i></button>
                                    <button type="button" class="btn btn-white btn-sm" id="resetZoomBtn" title="Restablecer"><i class="bi-arrow-counterclockwise"></i></button>
                                </div>

                                <div class="dropdown">
                                    <button type="button" class="btn btn-primary btn-sm dropdown-toggle"
                                        id="exportDropdownExpand" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi-download me-2"></i> Exportar
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-sm-end" aria-labelledby="exportDropdownExpand">
                                        <span class="dropdown-header">Opciones</span>
                                        <a class="dropdown-item print-chart-btn" href="javascript:;"
                                            data-chart-id="expandedChart">
                                            <i class="bi-printer me-2"></i> Imprimir
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <span class="dropdown-header">Descargar</span>
                                        <a class="dropdown-item export-pdf-btn" href="javascript:;"
                                            data-chart-id="expandedChart">
                                            <i class="bi-file-earmark-pdf me-2 text-danger"></i> PDF
                                        </a>
                                        <a class="dropdown-item export-svg-btn" href="javascript:;"
                                            data-chart-id="expandedChart">
                                            <i class="bi-file-earmark-image me-2 text-success"></i> SVG
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart-container-expanded"
                                style="position: relative; width: 100%; height: calc(100vh - 300px); overflow: hidden;">
                                <canvas id="expandedChart" style="display: block; cursor: crosshair !important;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

            // Datos de las gráficas
            const locationsData = @json($locationsData);
            const chartId = @json($chartId);

            let expandedChartInstance = null;
            let originalScaleLimits = {};

            // Crear gráfica según el ID
            const expandedCtx = document.getElementById('expandedChart');
            let chartConfig = {
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
                            pan: {
                                enabled: false
                            },
                            zoom: {
                                wheel: { enabled: false },
                                pinch: { enabled: false },
                                drag: { enabled: false }
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        intersect: false
                    }
                }
            };

            if (expandedCtx && chartConfig.type) {
                expandedChartInstance = new Chart(expandedCtx, chartConfig);

                window.charts = window.charts || {};
                window.charts['expandedChart'] = expandedChartInstance;

                // Guardar valores originales de las escalas para resetear
                if (expandedChartInstance && expandedChartInstance.scales) {
                    Object.keys(expandedChartInstance.scales).forEach(scaleId => {
                        const scale = expandedChartInstance.scales[scaleId];
                        if (scale) {
                            originalScaleLimits[scaleId] = {
                                min: scale.min !== undefined ? scale.min : null,
                                max: scale.max !== undefined ? scale.max : null
                            };
                        }
                    });
                }

                // Ajustar tamaño cuando se carga
                setTimeout(() => {
                    const container = document.querySelector('.chart-container-expanded');
                    if (container && expandedChartInstance.canvas) {
                        const containerRect = container.getBoundingClientRect();
                        expandedChartInstance.canvas.style.width = containerRect.width + 'px';
                        expandedChartInstance.canvas.style.height = containerRect.height + 'px';
                        expandedChartInstance.resize();

                        const canvas = expandedChartInstance.canvas;
                        let isMouseInside = false;
                        let isDragging = false;
                        let panStart = null;

                        // Limites originales para evitar zoom infinito hacia afuera
                        const origXMin = 0;
                        const origXMax = expandedChartInstance.data.labels.length;

                        // Detectar cuando el mouse entra/sale del canvas
                        canvas.addEventListener('mouseenter', function() {
                            isMouseInside = true;
                            canvas.style.cursor = 'crosshair';
                            canvas.style.setProperty('cursor', 'crosshair', 'important');
                        });

                        canvas.addEventListener('mouseleave', function() {
                            isMouseInside = false;
                            canvas.style.cursor = 'default';
                            canvas.style.removeProperty('cursor');
                            isDragging = false;
                            panStart = null;
                        });

                        // Asegurar que el cursor crosshair se mantenga al mover el mouse sobre el canvas
                        canvas.addEventListener('mousemove', function() {
                            if (isMouseInside && !isDragging) {
                                canvas.style.cursor = 'crosshair';
                                canvas.style.setProperty('cursor', 'crosshair', 'important');
                            }
                        });

                        // Zoom con scroll solo cuando el mouse está dentro del canvas
                        canvas.addEventListener('wheel', function(e) {
                            if (!isMouseInside) return;
                            e.preventDefault();
                            e.stopPropagation();

                            const rect = canvas.getBoundingClientRect();
                            const x = e.clientX - rect.left;
                            const y = e.clientY - rect.top;

                            const deltaY = e.deltaY;
                            const zoomFactor = deltaY > 0 ? 0.9 : 1.1;

                            if (expandedChartInstance && expandedChartInstance.scales) {
                                const scales = expandedChartInstance.scales;

                                if (scales.x && scales.y) {
                                    const xValue = scales.x.getValueForPixel(x);
                                    const yValue = scales.y.getValueForPixel(y);

                                    if (xValue !== null && yValue !== null) {
                                        let newXMin = xValue - (xValue - scales.x.min) * zoomFactor;
                                        let newXMax = xValue + (scales.x.max - xValue) * zoomFactor;
                                        let newYMin = yValue - (yValue - scales.y.min) * zoomFactor;
                                        let newYMax = yValue + (scales.y.max - yValue) * zoomFactor;

                                        // Aplicar límites para no alejar más del tamaño original (infinito)
                                        if (newXMin < origXMin) newXMin = origXMin;
                                        if (newXMax > origXMax) newXMax = origXMax;

                                        // Si llegamos a los límites originales, simplemente borrar las configuraciones custom (reset)
                                        if (newXMin <= origXMin && newXMax >= origXMax) {
                                            delete scales.x.min;
                                            delete scales.x.max;
                                        } else {
                                            scales.x.min = newXMin;
                                            scales.x.max = newXMax;
                                        }

                                        // Para el eje Y preferimos no poner limite o lo reseteamos a 0 usualmente
                                        if (newYMin < 0) newYMin = 0;
                                        scales.y.min = newYMin;
                                        scales.y.max = newYMax;

                                        // Para que sea suave, usamos una actualización con animación corta en vez de 'none'
                                        expandedChartInstance.update('active');
                                    }
                                }
                            }
                        }, {
                            passive: false
                        });

                        // Pan con click izquierdo y arrastrar (con transiciones suaves)
                        let panAnimationFrame = null;
                        let lastUpdateTime = 0;
                        const UPDATE_THROTTLE = 16; // ~60fps

                        canvas.addEventListener('mousedown', function(e) {
                            if (!isMouseInside || e.button !== 0) return;

                            isDragging = true;
                            panStart = {
                                x: e.clientX,
                                y: e.clientY
                            };
                            canvas.style.cursor = 'grabbing';
                            lastUpdateTime = performance.now();
                            e.preventDefault();
                        });

                        document.addEventListener('mousemove', function(e) {
                            if (!isDragging || !isMouseInside || !panStart) return;

                            const currentTime = performance.now();
                            if (currentTime - lastUpdateTime < UPDATE_THROTTLE) {
                                return;
                            }

                            const deltaX = e.clientX - panStart.x;
                            const deltaY = e.clientY - panStart.y;

                            if (panAnimationFrame) cancelAnimationFrame(panAnimationFrame);

                            panAnimationFrame = requestAnimationFrame(() => {
                                if (expandedChartInstance && expandedChartInstance.scales) {
                                    const scales = expandedChartInstance.scales;

                                    if (scales.x) {
                                        const xRange = scales.x.max - scales.x.min;
                                        const xStep = (deltaX / canvas.width) * xRange;
                                        if (scales.x.min !== undefined && scales.x.max !== undefined) {
                                            let newXMin = scales.x.min - xStep;
                                            let newXMax = scales.x.max - xStep;
                                            
                                            // Limites de pan
                                            if (newXMin < origXMin) {
                                                newXMax += (origXMin - newXMin);
                                                newXMin = origXMin;
                                            }
                                            if (newXMax > origXMax) {
                                                newXMin -= (newXMax - origXMax);
                                                newXMax = origXMax;
                                            }

                                            scales.x.min = newXMin;
                                            scales.x.max = newXMax;
                                        }
                                    }

                                    if (scales.y) {
                                        const yRange = scales.y.max - scales.y.min;
                                        const yStep = (deltaY / canvas.height) * yRange;
                                        if (scales.y.min !== undefined && scales.y.max !== undefined) {
                                            scales.y.min += yStep;
                                            scales.y.max += yStep;
                                        }
                                    }

                                    expandedChartInstance.update('active');
                                    lastUpdateTime = currentTime;
                                }
                            });

                            panStart = {
                                x: e.clientX,
                                y: e.clientY
                            };
                        });

                        document.addEventListener('mouseup', function() {
                            if (isDragging) {
                                if (panAnimationFrame) {
                                    cancelAnimationFrame(panAnimationFrame);
                                    panAnimationFrame = null;
                                }
                                isDragging = false;
                                panStart = null;
                                if (isMouseInside) {
                                    canvas.style.cursor = 'crosshair';
                                    canvas.style.setProperty('cursor', 'crosshair', 'important');
                                } else {
                                    canvas.style.cursor = 'default';
                                    canvas.style.removeProperty('cursor');
                                }
                            }
                        });

                        // Configuración para los botones
                        document.getElementById('zoomControls').style.display = 'flex';
                        document.getElementById('navControls').style.display = 'flex';

                        let visibleMin = 0;
                        let visibleMax = expandedChartInstance.data.labels.length;
                        const totalLabels = expandedChartInstance.data.labels.length;
                        let mousePosition = 0.5;

                        function applyZoom(animate = true) {
                            expandedChartInstance.options.scales.x.min = visibleMin;
                            expandedChartInstance.options.scales.x.max = visibleMax;
                            expandedChartInstance.update(animate ? 'active' : 'none');
                        }

                        canvas.addEventListener('mousemove', function(e) {
                            const rect = canvas.getBoundingClientRect();
                            mousePosition = (e.clientX - rect.left) / rect.width;
                        });

                        const zoomInBtn = document.getElementById('zoomInBtn');
                        const zoomOutBtn = document.getElementById('zoomOutBtn');
                        const resetZoomBtn = document.getElementById('resetZoomBtn');
                        const navLeftBtn = document.getElementById('navLeftBtn');
                        const navRightBtn = document.getElementById('navRightBtn');

                        if (zoomInBtn) {
                            zoomInBtn.onclick = function() {
                                try {
                                    const currentRange = visibleMax - visibleMin;
                                    let newRange = Math.max(Math.floor(currentRange * 0.5), 1);
                                    const focusPoint = visibleMin + (currentRange * mousePosition);
                                    if (newRange === 1) {
                                        visibleMin = Math.max(0, Math.min(Math.floor(focusPoint),
                                            totalLabels - 1));
                                        visibleMax = visibleMin + 1;
                                    } else {
                                        visibleMin = Math.max(0, Math.floor(focusPoint - newRange *
                                            mousePosition));
                                        visibleMax = Math.min(totalLabels, Math.ceil(visibleMin +
                                            newRange));
                                        if (visibleMax > totalLabels) {
                                            visibleMax = totalLabels;
                                            visibleMin = totalLabels - newRange;
                                        }
                                    }
                                    applyZoom(true);
                                } catch (e) {}
                            };
                        }

                        if (zoomOutBtn) {
                            zoomOutBtn.onclick = function() {
                                try {
                                    const currentRange = visibleMax - visibleMin;
                                    const newRange = Math.min(Math.ceil(currentRange * 2), totalLabels);
                                    const focusPoint = visibleMin + (currentRange * mousePosition);
                                    visibleMin = Math.max(0, Math.floor(focusPoint - newRange *
                                        mousePosition));
                                    visibleMax = Math.min(totalLabels, Math.ceil(visibleMin +
                                    newRange));
                                    if (visibleMin <= 0 && visibleMax >= totalLabels) {
                                        visibleMin = 0;
                                        visibleMax = totalLabels;
                                        delete expandedChartInstance.options.scales.x.min;
                                        delete expandedChartInstance.options.scales.x.max;
                                        expandedChartInstance.update('active');
                                    } else {
                                        applyZoom(true);
                                    }
                                } catch (e) {}
                            };
                        }

                        if (resetZoomBtn) {
                            resetZoomBtn.onclick = function() {
                                try {
                                    visibleMin = 0;
                                    visibleMax = totalLabels;
                                    delete expandedChartInstance.options.scales.x.min;
                                    delete expandedChartInstance.options.scales.x.max;
                                    delete expandedChartInstance.options.scales.y.min;
                                    delete expandedChartInstance.options.scales.y.max;
                                    expandedChartInstance.update('active');
                                } catch (e) {}
                            };
                        }

                        if (navLeftBtn) {
                            navLeftBtn.onclick = function() {
                                try {
                                    const currentRange = visibleMax - visibleMin;
                                    const step = Math.max(1, Math.floor(currentRange * 0.3));
                                    if (visibleMin > 0) {
                                        visibleMin = Math.max(0, visibleMin - step);
                                        visibleMax = visibleMin + currentRange;
                                        applyZoom(true);
                                    }
                                } catch (e) {}
                            };
                        }

                        if (navRightBtn) {
                            navRightBtn.onclick = function() {
                                try {
                                    const currentRange = visibleMax - visibleMin;
                                    const step = Math.max(1, Math.floor(currentRange * 0.3));
                                    if (visibleMax < totalLabels) {
                                        visibleMax = Math.min(totalLabels, visibleMax + step);
                                        visibleMin = visibleMax - currentRange;
                                        applyZoom(true);
                                    }
                                } catch (e) {}
                            };
                        }

                    }
                }, 300);
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
