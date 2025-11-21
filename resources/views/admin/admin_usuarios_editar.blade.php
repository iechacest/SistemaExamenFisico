<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario - Administrador</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        :root {
            --celeste: #00bcd4;
            --negro: #111111;
            --gris-texto: #555555;
        }

        body {
            background: #f5f7fb;
            font-family: system-ui, sans-serif;
        }

        .layout {
            max-width: 1100px;
            margin: 0 auto;
            padding: 1.5rem 1rem 2rem;
        }

        .titulo {
            text-align: center;
            font-size: 1.3rem;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .card {
            background: white;
            padding: 1rem;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.08);
            margin-bottom: 1rem;
        }

        label {
            display: block;
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
            font-weight: 600;
            color: var(--gris-texto);
        }

        input, select {
            width: 100%;
            border-radius: 8px;
            border: 1px solid #aaa;
            padding: 0.45rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .acciones {
            display: flex;
            justify-content: flex-end;
            gap: 0.6rem;
        }

        .btn {
            border: none;
            padding: 0.45rem 1.1rem;
            font-weight: 600;
            border-radius: 999px;
            cursor: pointer;
        }

        .btn-primary { background: var(--celeste); color: white; }
        .btn-secondary { background: #eeeeee; }

        .msg-error {
            background: #ffebee;
            padding: 0.6rem;
            border-radius: 8px;
            border: 1px solid #ffcdd2;
            color: #b71c1c;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

<div class="layout">

    <div class="titulo">Editar Usuario</div>

    <div class="card">

        @if($errors->any())
            <div class="msg-error">
                <ul>
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.usuarios.actualizar', $usuario->id_usu) }}" method="POST">
            @csrf

            <label>Nombres:</label>
            <input type="text" name="nombres" value="{{ $usuario->nombres }}" required>

            <label>Apellido Paterno:</label>
            <input type="text" name="apellido_pat" value="{{ $usuario->apellido_pat }}" required>

            <label>Apellido Materno:</label>
            <input type="text" name="apellido_mat" value="{{ $usuario->apellido_mat }}" required>

            <label>Cargo:</label>
            <select name="cargo">
                <option value="0" {{ $usuario->cargo == 0 ? 'selected' : '' }}>Administrador</option>
                <option value="1" {{ $usuario->cargo == 1 ? 'selected' : '' }}>Registrador</option>
            </select>

            <label>Nueva contraseña (opcional):</label>
            <input type="password" name="password" placeholder="Dejar vacío si no deseas cambiar">

            <div class="acciones">
                <a href="{{ route('admin.usuarios') }}" class="btn btn-secondary">Volver</a>
                <button class="btn btn-primary">Guardar cambios</button>
            </div>

        </form>

    </div>

</div>

</body>
</html>
