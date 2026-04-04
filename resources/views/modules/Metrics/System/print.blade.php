<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $chartTitle }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #377dff;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 14px;
        }
        .chart-container {
            text-align: center;
            margin-top: 20px;
        }
        .chart-image {
            max-width: 100%;
            height: auto;
            border: 1px solid #ddd;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $chartTitle }}</h1>
        <p>Generado el: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="chart-container">
        @if($chartImage)
            <img src="{{ $chartImage }}" class="chart-image" alt="Gráfica de Métricas">
        @else
            <p>No se pudo cargar la imagen de la gráfica.</p>
        @endif
    </div>

    <div class="footer">
        <p>Sistema HOSPROGRESO - Módulo de Métricas</p>
    </div>

    <script type="module">
        // Si no es PDF (es decir, es la vista de impresión en navegador)
        window.onload = function() {
            if (!window.location.search.includes('pdf=1')) {
                // Pequeño delay para asegurar que la imagen cargó si el navegador es lento
                setTimeout(function() {
                    window.print();
                }, 500);
            }
        };
    </script>
</body>
</html>
