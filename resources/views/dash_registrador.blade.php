<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Registrador - Sistema de Examen Físico</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        :root {
            --celeste: #00bcd4;
            --celeste-suave: #e0f7ff;
            --negro: #111111;
            --gris-claro: #f5f5f5;
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
            color: var(--negro);
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
        }

        .user-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--gris-texto);
            margin-bottom: 0.2rem;
        }

        .user-name {
            font-size: 1rem;
            font-weight: 700;
        }

        .user-extra {
            font-size: 0.85rem;
            color: var(--gris-texto);
            margin-top: 0.2rem;
        }

        .tag {
            display: inline-block;
            font-size: 0.75rem;
            padding: 0.1rem 0.6rem;
            border-radius: 999px;
            background: var(--celeste-suave);
            color: var(--negro);
            margin-top: 0.25rem;
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

        .btn {
            border: none;
            border-radius: 999px;
            padding: 0.45rem 1rem;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.15s, box-shadow 0.15s, transform 0.08s;
        }

        .btn-primary {
            background: var(--celeste);
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(0,188,212,0.4);
        }

        .btn-primary:hover {
            background: #0097a7;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #eeeeee;
            color: var(--negro);
        }

        .btn-secondary:hover {
            background: #e0e0e0;
        }

        main {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1.2rem;
        }

        @media (max-width: 900px) {
            main {
                grid-template-columns: 1fr;
            }
        }

        .card {
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
            border: 1px solid rgba(0,188,212,0.18);
            padding: 1rem 1.1rem 1.1rem;
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

        .empty-msg {
            font-size: 0.84rem;
            color: var(--gris-texto);
            margin-top: 0.5rem;
        }

        .badge-inst {
            font-size: 0.75rem;
            padding: 0.1rem 0.5rem;
            border-radius: 999px;
            background: #eeeeee;
        }

        .msg-error {
            margin-top: 0.6rem;
            padding: 0.5rem 0.7rem;
            background: #ffebee;
            color: #b71c1c;
            border-radius: 8px;
            border: 1px solid #ffcdd2;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

<div class="layout">

    {{-- Encabezado con info del usuario --}}
    <header>
        <div class="brand">
            <div class="brand-title">Sistema de Examen Físico</div>
            <div class="brand-subtitle">Panel del registrador</div>
        </div>

        @php
            // Mapeo de instituto (numérico -> texto)
            $nombreInstituto = match($registrador->instituto ?? null) {
                1 => 'COLMILAV',
                2 => 'POLMILAE',
                3 => 'EMMFAB',
                default => 'Sin instituto',
            };
        @endphp

        <div style="text-align: right;">
    <div class="user-card" style="margin-bottom: 0.4rem;">
        <div class="user-label">Registrador</div>
        <div class="user-name">
            {{ $registrador->nombres }}
            {{ $registrador->apellido_pat }}
            {{ $registrador->apellido_mat }}
        </div>
    </div>

    <a href="{{ route('perfil.ver') }}" 
       class="btn btn-secondary" 
       style="font-size: 0.8rem; padding: 0.35rem 0.8rem;">
        Mi perfil
    </a>

    <a href="{{ route('logout') }}" 
       class="btn btn-primary" 
       style="font-size: 0.8rem; padding: 0.35rem 0.8rem; margin-left: 0.3rem; background:#ff5252; box-shadow:none;">
        Cerrar sesión
    </a>
</div>

    </header>

    {{-- Mensaje de error global (por ejemplo, de middleware) --}}
    @if(session('error'))
        <div class="msg-error">
            {{ session('error') }}
        </div>
    @endif

    {{-- Filtros (afectan ambas tablas) --}}
    <section class="filters-card">
        <div class="filters-title">Filtros de búsqueda (aplican a atendidos y no atendidos)</div>
        <form method="GET" action="{{ route('dash.registrador') }}">
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
                <a href="{{ route('dash.registrador') }}" class="btn btn-secondary">Limpiar</a>
            </div>
        </form>
    </section>

    {{-- Dos columnas: No atendidos / Atendidos --}}
    <main>
        {{-- Postulantes NO atendidos --}}
<section class="card">
    <div class="card-header">
        <div class="card-title">Postulantes no atendidos</div>
        <div class="card-subtitle">
            Total: {{ $postulantesNoAtendidos->count() }}
        </div>
    </div>

    @if($postulantesNoAtendidos->isEmpty())
        <p class="empty-msg">No hay postulantes pendientes con los filtros actuales.</p>
    @else
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>CI</th>
                <th>Apellidos</th>
                <th>Nombres</th>
                <th>Instituto</th>
                <th>Acciones</th> {{-- 👈 NUEVA COLUMNA --}}
            </tr>
            </thead>
            <tbody>
            @foreach($postulantesNoAtendidos as $index => $p)
                @php
                    $inst = match($p->instituto) {
                        1 => 'COLMILAV',
                        2 => 'POLMILAE',
                        3 => 'EMMFAB',
                        default => 'N/D',
                    };
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $p->ci }}</td>
                    <td>{{ $p->apellido_paterno }} {{ $p->apellido_materno }}</td>
                    <td>{{ $p->nombres }}</td>
                    <td><span class="badge-inst">{{ $inst }}</span></td>
                    <td>
                        <a href="{{ route('postulante.form', $p->id_postulante) }}" class="btn btn-primary" style="padding: 0.25rem 0.8rem; font-size: 0.8rem;">
                            Llenar
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</section>


        {{-- Postulantes ATENDIDOS --}}
        <section class="card">
            <div class="card-header">
                <div class="card-title">Postulantes atendidos</div>
                <div class="card-subtitle">
                    Total: {{ $postulantesAtendidos->count() }}
                </div>
            </div>

            @if($postulantesAtendidos->isEmpty())
                <p class="empty-msg">No hay postulantes atendidos con los filtros actuales.</p>
            @else
                <table>
                    <thead>
<tr>
    <th>#</th>
    <th>CI</th>
    <th>Apellidos</th>
    <th>Nombres</th>
    <th>Instituto</th>
    <th>Acciones</th> {{-- NUEVA COLUMNA --}}
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

        // Obtener su última prueba (que tiene ruta_pdf)
        $ultima = $p->pruebas()->latest()->first();
    @endphp
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $p->ci }}</td>
        <td>{{ $p->apellido_paterno }} {{ $p->apellido_materno }}</td>
        <td>{{ $p->nombres }}</td>
        <td><span class="badge-inst">{{ $inst }}</span></td>

        <td>
            @if($ultima && $ultima->ruta_pdf)
                <a href="{{ route('pdf.ver', basename($ultima->ruta_pdf)) }}"
                   class="btn btn-secondary" 
                   style="padding: 0.25rem 0.8rem; font-size: 0.78rem;">
                    Ver PDF
                </a>

                <a href="{{ route('pdf.descargar', basename($ultima->ruta_pdf)) }}"
                   class="btn btn-primary"
                   style="padding: 0.25rem 0.8rem; font-size: 0.78rem;">
                    Descargar
                </a>
            @else
                <span style="font-size: 0.75rem; color: #777;">Sin PDF</span>
            @endif
        </td>
    </tr>
@endforeach
</tbody>

                </table>
            @endif
        </section>
    </main>

</div>

</body>
</html>
