<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Imprimir Municipios</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Listado de Municipios</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Departamento</th>
                <th>País</th>
                <th>Estado</th>
                <th>Fecha de Creación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($municipalities as $municipality)
            <tr>
                <td>{{ $municipality->id }}</td>
                <td>{{ $municipality->name }}</td>
                <td>{{ $municipality->department->name ?? '—' }}</td>
                <td>{{ $municipality->department->country->name ?? '—' }}</td>
                <td>{{ $municipality->is_active ? 'Activo' : 'Inactivo' }}</td>
                <td>{{ $municipality->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        window.onload = function() {
            if (typeof window.print === 'function') {
                window.print();
            }
        };
    </script>
</body>
</html>
