<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Usuario - Administrador</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        :root {
            --celeste: #00bcd4;
            --celeste-suave: #e0f7ff;
            --negro: #111111;
            --gris-texto: #555555;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: system-ui, sans-serif;
        }

        body {
            background: #f5f7fb;
            min-height: 100vh;
            color: var(--negro);
        }

        .layout {
            max-width: 1100px;
            margin: 0 auto;
            padding: 1.5rem 1rem 2rem;
        }

        .titulo {
            font-size: 1.3rem;
            font-weight: 800;
            text-transform: uppercase;
            text-align: center;
            margin-bottom: 1rem;
        }

        .card {
            background: white;
            padding: 1rem 1.2rem;
            border-radius: 12px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.08);
            border: 1px solid rgba(0,0,0,0.08);
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
            padding: 0.45rem 0.6rem;
            margin-bottom: 1rem;
            font-size: 0.9rem;
            outline: none;
        }

        input:focus, select:focus {
            border-color: var(--celeste);
            box-shadow: 0 0 0 2px rgba(0,188,212,0.25);
        }

        .acciones {
            display: flex;
            justify-content: flex-end;
            gap: 0.6rem;
        }

        .btn {
            border: none;
            border-radius: 999px;
            padding: 0.45rem 1.1rem;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.08s;
        }

        .btn-primary {
            background: var(--celeste);
            color: white;
        }

        .btn-secondary {
            background: #eeeeee;
            color: var(--negro);
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .msg-error {
            background: #ffebee;
            border: 1px solid #ffcdd2;
            padding: 0.6rem;
            border-radius: 8px;
            color: #b71c1c;
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }

    </style>
</head>
<body>

<div class="layout">

    <div class="titulo">Crear Nuevo Usuario</div>

    <div class="card">

        @if($errors->any())
            <div class="msg-error">
                <strong>Corrige los siguientes errores:</strong>
                <ul>
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.usuarios.guardar') }}" method="POST">
            @csrf

            <label>Nombres:</label>
            <input type="text" name="nombres" required>

            <label>Apellido Paterno:</label>
            <input type="text" name="apellido_pat" required>

            <label>Apellido Materno:</label>
            <input type="text" name="apellido_mat" required>

            <div class="campo">
    <label>Nombre de usuario</label>
    <input type="text" name="usuario" required>
</div>

            <label>Contraseña:</label>
            <input type="password" name="password" required
                   placeholder="Mínimo 8 caracteres, mayúscula, minúscula, número y símbolo">

            <label>Cargo:</label>
            <select name="cargo" required>
                <option value="0">Administrador</option>
                <option value="1">Registrador</option>
            </select>

            <div class="acciones">
                <a href="{{ route('admin.usuarios') }}" class="btn btn-secondary">Volver</a>
                <button class="btn btn-primary">Crear usuario</button>
            </div>

        </form>

    </div>

</div>

</body>
</html>
