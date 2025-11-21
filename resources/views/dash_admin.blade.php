<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Administrador - Sistema de Examen Físico</title>
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
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--celeste-suave), #ffffff);
            color: var(--negro);
        }

        .layout {
            max-width: 1180px;
            margin: 0 auto;
            padding: 1.5rem 1rem 2rem;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .brand {
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
        }

        .brand-title {
            font-size: 1.4rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .brand-subtitle {
            font-size: 0.9rem;
            color: var(--gris-texto);
        }

        .user-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 0.9rem 1.2rem;
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
            border: 1px solid rgba(0,188,212,0.25);
            min-width: 260px;
            text-align: right;
        }

        .user-label {
            font-size: 0.8rem;
            color: var(--gris-texto);
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .user-name {
            font-size: 1rem;
            font-weight: 700;
        }

        .btn {
            border: none;
            border-radius: 999px;
            padding: 0.35rem 0.8rem;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: var(--celeste);
            color: white;
        }
        .btn-danger {
            background: #ff5252;
            color: white;
        }
        .btn-secondary {
            background: #607d8b;
            color: white;
        }
        .btn-warning {
            background: #ffc107;
            color: black;
        }

        .card {
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
            border: 1px solid rgba(0,188,212,0.18);
            padding: 1rem 1.1rem 1.1rem;
            margin-top: 1rem;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            margin-bottom: 0.6rem;
        }

        .card-title {
            font-size: 1rem;
            font-weight: 700;
        }

        .card-subtitle {
            font-size: 0.8rem;
            color: var(--gris-texto);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0.4rem;
            font-size: 0.85rem;
        }

        thead {
            background: var(--celeste-suave);
        }

        th, td {
            padding: 0.4rem 0.35rem;
            text-align: left;
        }

        th {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--gris-texto);
        }

        tbody tr:nth-child(even) {
            background: #fafafa;
        }

        tbody tr:hover {
            background: #e0f7ff;
        }

        .badge-inst {
            font-size: 0.75rem;
            padding: 0.1rem 0.5rem;
            border-radius: 999px;
            background: #eeeeee;
        }

        .msg-success, .msg-error {
            margin-top: 0.6rem;
            padding: 0.5rem 0.7rem;
            border-radius: 8px;
            font-size: 0.8rem;
        }

        .msg-success {
            background: #e0f7e9;
            color: #2a6d3a;
            border: 1px solid #9adfb2;
        }

        .msg-error {
            background: #ffebee;
            color: #b71c1c;
            border: 1px solid #ffcdd2;
        }
        .filters-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 1rem 1.2rem;
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
            border: 1px solid rgba(0,188,212,0.18);
            margin-bottom: 1.5rem;
        }

        .filters-title {
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 0.7rem;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 0.7rem;
        }

        @media (max-width: 900px) {
            .filters-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 600px) {
            header {
                flex-direction: column;
                align-items: flex-start;
            }
            .filters-grid {
                grid-template-columns: 1fr;
            }
        }

        .filters-grid label {
            font-size: 0.8rem;
            font-weight: 600;
            display: block;
            margin-bottom: 0.2rem;
        }

        .filters-grid input,
        .filters-grid select {
            width: 100%;
            padding: 0.45rem 0.6rem;
            border-radius: 8px;
            border: 1px solid #cccccc;
            font-size: 0.9rem;
            outline: none;
            transition: border 0.15s, box-shadow 0.15s;
        }

        .filters-grid input:focus,
        .filters-grid select:focus {
            border-color: var(--celeste);
            box-shadow: 0 0 0 2px rgba(0, 188, 212, 0.25);
        }

        .filters-actions {
            margin-top: 0.7rem;
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }

    </style>
</head>
<body>

<div class="layout">

    {{-- HEADER --}}
    <header>
        <div class="brand">
            <div class="brand-title">Sistema de Examen Físico</div>
            <div class="brand-subtitle">Panel del Administrador</div>
        </div>

        <div>
    <div class="user-card">
        <div class="user-label">Administrador</div>
        <div class="user-name">
            {{ $admin->nombres }} {{ $admin->apellido_pat }} {{ $admin->apellido_mat }}
        </div>
    </div>

    <!-- === MENÚ ADMIN === -->
    <div style="margin-top:0.6rem; display:flex; flex-direction:column; gap:0.4rem;">

        <a href="{{ route('admin.usuarios') }}" class="btn btn-primary">
            Administrar Usuarios
        </a>

        <a href="{{ route('perfil.ver') }}" class="btn btn-secondary">
            Mi Perfil
        </a>

    </div>

    <a href="{{ route('logout') }}" class="btn btn-danger" style="margin-top:0.6rem;">
        Cerrar sesión
    </a>
</div>

    </header>

    {{-- MENSAJES --}}
    @if(session('success'))
        <div class="msg-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="msg-error">{{ session('error') }}</div>
    @endif

    <section class="filters-card">
        <div class="filters-title">Filtros de búsqueda (aplican a atendidos y no atendidos)</div>
        <form method="GET" action="{{ route('dash.admin') }}">
            <div class="filters-grid">
                <div>
                    <label for="ci">CI</label>
                    <input type="text" id="ci" name="ci" value="{{ request('ci') }}">
                </div>
                <div>
                    <label for="apellido_paterno">Apellido paterno</label>
                    <input type="text" id="apellido_paterno" name="apellido_paterno" value="{{ request('apellido_paterno') }}">
                </div>
                <div>
                    <label for="apellido_materno">Apellido materno</label>
                    <input type="text" id="apellido_materno" name="apellido_materno" value="{{ request('apellido_materno') }}">
                </div>
                <div>
                    <label for="instituto">Instituto</label>
                    <select id="instituto" name="instituto">
                        <option value="">Todos</option>
                        <option value="1" {{ request('instituto') == '1' ? 'selected' : '' }}>COLMILAV</option>
                        <option value="2" {{ request('instituto') == '2' ? 'selected' : '' }}>POLMILAE</option>
                        <option value="3" {{ request('instituto') == '3' ? 'selected' : '' }}>EMMFAB</option>
                    </select>
                </div>
            </div>

            <div class="filters-actions">
                <button type="submit" class="btn btn-primary">Aplicar filtros</button>
                <a href="{{ route('dash.admin') }}" class="btn btn-secondary">Limpiar</a>
            </div>
        </form>
    </section>

    {{-- TABLA DE POSTULANTES ATENDIDOS --}}
    <section class="card">
        <div class="card-header">
            <div class="card-title">Postulantes Atendidos</div>
            <div class="card-subtitle">Total: {{ $postulantesAtendidos->count() }}</div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>CI</th>
                    <th>Apellidos</th>
                    <th>Nombres</th>
                    <th>Instituto</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
            @foreach($postulantesAtendidos as $index => $p)
                @php
                    $inst = match($p->instituto) {
                        1 => 'COLMILAV',
                        2 => 'POLMILAE',
                        3 => 'EMMFAB',
                        default => 'N/D',
                    };

                    $prueba = $p->pruebas->first();
                @endphp

                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $p->ci }}</td>
                    <td>{{ $p->apellido_paterno }} {{ $p->apellido_materno }}</td>
                    <td>{{ $p->nombres }}</td>
                    <td><span class="badge-inst">{{ $inst }}</span></td>

                    <td>
                        @if($prueba && $prueba->ruta_pdf)
                            <a class="btn btn-secondary"
                               href="{{ route('pdf.ver', basename($prueba->ruta_pdf)) }}">
                                Ver
                            </a>

                            <a class="btn btn-primary"
                               href="{{ route('pdf.descargar', basename($prueba->ruta_pdf)) }}">
                                Descargar
                            </a>

                            <a class="btn btn-warning"
                               href="{{ route('admin.evaluacion.editar', $prueba->id_prueba) }}">
                                Editar
                            </a>

                            <form action="{{ route('admin.evaluacion.eliminar', $prueba->id_prueba) }}"
                                  method="POST"
                                  style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger"
                                        onclick="return confirm('¿Eliminar evaluación completa?')">
                                    Eliminar
                                </button>
                            </form>
                        @else
                            <span style="color:#777;">Sin PDF</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </section>

</div>

</body>
</html>
