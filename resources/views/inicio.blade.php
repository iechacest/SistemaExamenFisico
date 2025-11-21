<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SISTEMA DE EXAMEN FÍSICO</title>

    <style>
        :root {
            --celeste: #00bcd4;
            --celeste-suave: #e0f7ff;
            --negro: #111111;
            --gris-claro: #f5f5f5;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--celeste-suave), #ffffff);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: #ffffff;
            width: 100%;
            max-width: 420px;
            padding: 2.5rem 2.2rem;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border: 1px solid rgba(0, 188, 212, 0.2);
        }

        .logo-box {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .logo-box img {
            width: 90px;
            height: 90px;
            object-fit: contain;
            margin-bottom: 0.5rem;
        }

        .logo-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--negro);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .logo-subtitle {
            font-size: 0.9rem;
            color: #555;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--negro);
            margin-bottom: 0.3rem;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.65rem 0.8rem;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 0.95rem;
            outline: none;
            transition: border 0.2s, box-shadow 0.2s;
        }

        input:focus {
            border-color: var(--celeste);
            box-shadow: 0 0 0 2px rgba(0, 188, 212, 0.2);
        }

        .btn {
            width: 100%;
            padding: 0.75rem;
            border-radius: 999px;
            border: none;
            background: var(--celeste);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.98rem;
            cursor: pointer;
            margin-top: 0.5rem;
            transition: transform 0.1s ease, box-shadow 0.1s ease, background 0.2s;
        }

        .btn:hover {
            background: #0097a7;
            box-shadow: 0 6px 15px rgba(0, 188, 212, 0.4);
            transform: translateY(-1px);
        }

        .btn:active {
            transform: translateY(1px);
            box-shadow: none;
        }

        .extra {
            margin-top: 0.8rem;
            text-align: center;
            font-size: 0.85rem;
            color: #555;
        }

        .extra a {
            color: var(--celeste);
            text-decoration: none;
            font-weight: 600;
        }

        .extra a:hover {
            text-decoration: underline;
        }

        .error-msg {
            background: #ffebee;
            color: #b71c1c;
            border-radius: 8px;
            padding: 0.6rem 0.8rem;
            font-size: 0.85rem;
            margin-bottom: 0.8rem;
            border: 1px solid #ffcdd2;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="logo-box">
            {{-- Aquí va tu imagen --}}
            <img src="{{ asset('img/escudo2.png') }}" alt="Sistema de Examen Físico">
            <div class="logo-title">Sistema de Examen Físico</div>
            <div class="logo-subtitle">Test de Cooper</div>
        </div>

        {{-- Mensaje de error de sesión (opcional) --}}
        @if(session('error'))
            <div class="error-msg">
                {{ session('error') }}
            </div>
        @endif

        {{-- Formulario de inicio de sesión --}}
        <form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="form-group">
        <label for="usuario">Usuario</label>
        <input type="text" id="usuario" name="usuario" required value="{{ old('usuario') }}">
    </div>

    <div class="form-group">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" required>
    </div>

    <button type="submit" class="btn">
        Iniciar sesión
    </button>
</form>

    </div>

</body>
</html>
