<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mi Perfil</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body {
    font-family: system-ui;
    background: #e0f7ff;
    padding: 2rem;
}

.card {
    max-width: 450px;
    margin: auto;
    background: white;
    border-radius: 14px;
    padding: 1.5rem;
    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
    border: 1px solid rgba(0,188,212,0.25);
}

input {
    width: 100%;
    padding: 0.6rem;
    margin-top: 4px;
    margin-bottom: 10px;
    border-radius: 8px;
    border: 1px solid #ddd;
}

.btn {
    background: #00bcd4;
    color: white;
    padding: 0.6rem 1.1rem;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    margin-top: 10px;
    display: inline-block;
}

.btn:hover {
    background: #0097a7;
}

.btn-danger {
    background: #ff5252;
}

.btn-danger:hover {
    background: #e53935;
}
</style>
</head>

<body>

<div class="card">
    <h2 style="text-align:center;">Mi Perfil</h2>

    <p><strong>Nombre:</strong> {{ $usuario->nombres }} {{ $usuario->apellido_pat }} {{ $usuario->apellido_mat }}</p>

    <hr style="margin: 1rem 0;">

    <h3>Cambiar contraseña</h3>

    @if(session('error'))
        <div style="color: red; margin-bottom: 10px;">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div style="color: green; margin-bottom: 10px;">{{ session('success') }}</div>
    @endif

    <form action="{{ route('perfil.password') }}" method="POST">
        @csrf

        <label>Contraseña actual</label>
        <input type="password" name="password_actual" required>

        <label>Nueva contraseña</label>
        <input type="password" name="password_nueva" required>
        <p style="font-size: 0.75rem; color:#555; margin-top:-4px;">
    La contraseña debe tener al menos 8 caracteres, incluir una mayúscula,
    una minúscula, un número y un símbolo.
</p>


        <label>Confirmar nueva contraseña</label>
        <input type="password" name="password_nueva_confirmation" required>

        <button class="btn" type="submit">Actualizar contraseña</button>
    </form>

    <hr>

    <a href="{{ route('logout') }}" class="btn btn-danger mt-3">Cerrar sesión</a>

    <div style="margin-top: 1.2rem; text-align:center;">
        <a href="{{ route('dash.registrador') }}" style="color:#0097a7;">⬅ Volver al panel</a>
    </div>
</div>

</body>
</html>
