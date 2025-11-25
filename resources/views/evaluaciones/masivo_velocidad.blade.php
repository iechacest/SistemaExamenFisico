<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Llenado de Velocidad</title>

    <style>
        body { font-family: Arial; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px;}
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #e0f7ff; }
        input { width: 100%; padding: 4px; }
        .btn { padding: 8px 18px; background: #00bcd4; border: none; border-radius: 6px; color: white; cursor: pointer; }
    </style>
</head>
<body>

<h2>Llenado masivo: Velocidad</h2>

<form method="POST" action="{{ route('evaluacion.velocidad.guardar') }}">
    @csrf
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Postulante</th>
                <th>CI</th>
                <th>Tiempo (ej: 11''50)</th>
            </tr>
        </thead>

        <tbody>
            @foreach($postulantes as $i => $p)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $p->apellido_paterno }} {{ $p->apellido_materno }} {{ $p->nombres }}</td>
                <td>{{ $p->ci }}</td>
                <td>
                    <input type="text" 
                           name="velocidades[{{ $p->id_postulante }}]" 
                           placeholder="11''50">
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <button type="submit" class="btn">Guardar velocidad</button>
</form>

</body>
</html>
