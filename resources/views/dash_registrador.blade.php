<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel Registrador - Sistema de Examen Físico</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
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
<section class="filters-card">
    <div class="filters-title">Filtros de búsqueda (aplican a atendidos y no atendidos)</div>
    <form id="filtersForm" method="GET" action="{{ route('dash.registrador') }}" onsubmit="applyFilters(event)">
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
    <main id="postulantes-container">
    {{-- Postulantes NO atendidos --}}
    <section class="card">
        <div class="card-header">
            <div class="card-title">Postulantes no atendidos</div>
            <div class="card-subtitle">
                Total: <span id="noAtendidosCount">{{ $postulantesNoAtendidos->count() }}</span>
            </div>
        </div>

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
            <tbody id="noAtendidosTable">
                {{-- Aquí se llenará dinámicamente con los postulantes filtrados --}}
                @foreach($postulantesNoAtendidos as $index => $p)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $p->ci }}</td>
                        <td>{{ $p->apellido_paterno }} {{ $p->apellido_materno }}</td>
                        <td>{{ $p->nombres }}</td>
                        <td><span class="badge-inst">
    @php
        $instituto = match($p->instituto) {
            1 => 'COLMILAV',
            2 => 'POLMILAE',
            3 => 'EMMFAB',
            default => 'N/D', // En caso de que el instituto no coincida con ninguno de los anteriores
        };
    @endphp
    {{ $instituto }}
</span></td>

                        <td>
                            <button class="btn btn-primary" onclick="abrirMenuEvaluacion({{ $p->id_postulante }})">Llenar evaluación</button>
                            <button class="btn btn-success" onclick="finalizarEvaluacion({{ $p->id_postulante }})">Finalizar</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>

    {{-- Postulantes ATENDIDOS --}}
    <section class="card">
        <div class="card-header">
            <div class="card-title">Postulantes atendidos</div>
            <div class="card-subtitle">
                Total: <span id="atendidosCount">{{ $postulantesAtendidos->count() }}</span>
            </div>
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
            <tbody id="atendidosTable">
                {{-- Aquí se llenará dinámicamente con los postulantes atendidos filtrados --}}
                @foreach($postulantesAtendidos as $index => $p)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $p->ci }}</td>
                        <td>{{ $p->apellido_paterno }} {{ $p->apellido_materno }}</td>
                        <td>{{ $p->nombres }}</td>
                        <td><span class="badge-inst">
    @php
        $instituto = match($p->instituto) {
            1 => 'COLMILAV',
            2 => 'POLMILAE',
            3 => 'EMMFAB',
            default => 'N/D', // En caso de que el instituto no coincida con ninguno de los anteriores
        };
    @endphp
    {{ $instituto }}
</span></td>

                        <td>
                            @if($p->pruebas()->latest()->first())
                                <a href="{{ route('pdf.ver', basename($p->pruebas()->latest()->first()->ruta_pdf)) }}" class="btn btn-secondary">Ver PDF</a>
                                <a href="{{ route('pdf.descargar', basename($p->pruebas()->latest()->first()->ruta_pdf)) }}" class="btn btn-primary">Descargar</a>
                            @else
                                <span style="font-size: 0.75rem; color: #777;">Sin PDF</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</main>


</div>
<div id="modalVelocidad"
     style="display:none; position:fixed; inset:0; background:#0008;
            justify-content:center; align-items:center; z-index:99999;">
    <div style="background:white; padding:1.5rem; border-radius:10px; width:330px;">
        <h3 style="margin-bottom:1rem;">Registrar Velocidad</h3>

        <p><b>Valor actual:</b> <span id="velocidad_actual">—</span></p>
        <p><b>Nota actual:</b> <span id="nota_velocidad_actual">—</span></p>

        <form id="formVelocidad" method="POST" action="">
            @csrf
            <label style="font-size:0.85rem;">Tiempo</label>
            <input type="text" name="velocidad"
                style="width:100%; margin-top:0.3rem; padding:0.4rem;
                       border:1px solid #ccc; border-radius:7px;" required>

            <button type="submit" class="btn btn-primary"
                    style="margin-top:1rem; width:100%;">
                Guardar
            </button>
        </form>

        <button onclick="cerrarVelocidad()" class="btn btn-secondary"
                style="margin-top:0.7rem; width:100%;">
            Cancelar
        </button>
    </div>

</div>

<div id="modalResistencia"
     style="display:none; position:fixed; inset:0; background:#0008;
            justify-content:center; align-items:center; z-index:99999;">


    <div style="background:white; padding:1.5rem; border-radius:10px; width:350px;">
        <h3 style="margin-bottom:1rem;">Registrar Resistencia</h3>

        <p><b>Valor actual:</b> <span id="res_valor">—</span></p>
        <p><b>Nota actual:</b> <span id="res_nota">—</span></p>


        <form id="formResistencia" method="POST" action="">
            @csrf

            <label style="font-size:0.85rem;">Tiempo</label>
            <input type="text" name="prueba_resis"
                   style="width:100%; margin-top:0.3rem; padding:0.4rem;
                          border:1px solid #ccc; border-radius:7px;" required>

            <button type="submit"
                    class="btn btn-primary"
                    style="margin-top:1rem; width:100%;">
                Guardar
            </button>
        </form>

        <button onclick="cerrarResistencia()"
                class="btn btn-secondary"
                style="margin-top:0.7rem; width:100%;">
            Cancelar
        </button>
    </div>
</div>


{{-- ===== MODAL BARRA ===== --}}
<div id="modalBarra"
     style="display:none; position:fixed; inset:0; background:#0008;
            justify-content:center; align-items:center; z-index:99999;">
    <div style="background:white; padding:1.5rem; border-radius:10px; width:350px;">
        <h3 style="margin-bottom:1rem;">Registrar Barra (sin tiempo)</h3>
        <p><b>Valor actual:</b> <span id="barra_valor">—</span></p>
        <p><b>Nota actual:</b> <span id="barra_nota">—</span></p>
        <form id="formBarra" method="POST" action="">
            @csrf
            <label style="font-size:0.85rem;">N° Repeticiones</label>
            <input type="number" name="barra"
                   style="width:100%; margin-top:0.3rem; padding:0.4rem;
                          border:1px solid #ccc; border-radius:7px;" required>
            <button type="submit"
                    class="btn btn-primary"
                    style="margin-top:1rem; width:100%;">
                Guardar
            </button>
        </form>

        <button onclick="cerrarBarra()"
                class="btn btn-secondary"
                style="margin-top:0.7rem; width:100%;">
            Cancelar
        </button>
    </div>
</div>

{{-- ===== MODAL ABDOMINALES ===== --}}
<div id="modalAbdominal"
     style="display:none; position:fixed; inset:0; background:#0008;
            justify-content:center; align-items:center; z-index:99999;">


    <div style="background:white; padding:1.5rem; border-radius:10px; width:350px;">
        <h3 style="margin-bottom:1rem;">Registrar Abdominales</h3>
        <p><b>Valor actual:</b> <span id="abd_valor">—</span></p>
        <p><b>Nota actual:</b> <span id="abd_nota">—</span></p>
        <form id="formAbdominal" method="POST" action="">
            @csrf

            <label style="font-size:0.85rem;">N° Repeticiones</label>
            <input type="number" name="cap_abdominal"
                   style="width:100%; margin-top:0.3rem; padding:0.4rem;
                          border:1px solid #ccc; border-radius:7px;" required>

            <button type="submit"
                    class="btn btn-primary"
                    style="margin-top:1rem; width:100%;">
                Guardar
            </button>
        </form>

        <button onclick="cerrarAbdominal()"
                class="btn btn-secondary"
                style="margin-top:0.7rem; width:100%;">
            Cancelar
        </button>
    </div>
</div>


{{-- ===== MODAL NATACIÓN ===== --}}
<div id="modalNatacion"
     style="display:none; position:fixed; inset:0; background:#0008;
            justify-content:center; align-items:center; z-index:99999;">


    <div style="background:white; padding:1.5rem; border-radius:10px; width:350px;">
        <h3 style="margin-bottom:1rem;">Registrar Natación</h3>
        <p><b>Valor actual:</b> <span id="nat_valor">—</span></p>
        <p><b>Nota actual:</b> <span id="nat_nota">—</span></p>
        <form id="formNatacion" method="POST" action="">
            @csrf

            <label style="font-size:0.85rem;">Distancia (metros)</label>
            <input type="text" name="natacion"
                   style="width:100%; margin-top:0.3rem; padding:0.4rem;
                          border:1px solid #ccc; border-radius:7px;" required>

            <button type="submit"
                    class="btn btn-primary"
                    style="margin-top:1rem; width:100%;">
                Guardar
            </button>
        </form>

        <button onclick="cerrarNatacion()"
                class="btn btn-secondary"
                style="margin-top:0.7rem; width:100%;">
            Cancelar
        </button>
    </div>
</div>
{{-- ===== MODAL FLEXIONES ===== --}}
<div id="modalFlexiones"
     style="display:none; position:fixed; inset:0; background:#0008;
            justify-content:center; align-items:center; z-index:99999;">

    
    <div style="background:white; padding:1.5rem; border-radius:10px; width:360px;">
        <h3 style="margin-bottom:1rem;">Registrar Flexiones</h3>

        <p><b>Valor actual:</b> <span id="flex_valor">—</span></p>
        <p><b>Nota actual:</b> <span id="flex_nota">—</span></p>

        <form id="formFlexiones" method="POST" action="">
            @csrf

            <label style="font-size:0.85rem;">N° Repeticiones</label>
            <input type="number" name="flexiones"
                   style="width:100%; margin-top:0.3rem; padding:0.4rem;
                          border:1px solid #ccc; border-radius:7px;" required>

            <button type="submit" class="btn btn-primary" 
                    style="margin-top:1rem; width:100%;">Guardar</button>
        </form>

        <button onclick="cerrarFlexiones()"
                class="btn btn-secondary"
                style="margin-top:0.7rem; width:100%;">Cancelar</button>
    </div>
</div>
{{-- ===== MODAL MENÚ DE EVALUACIÓN ===== --}}
<!-- Modal Menu de Evaluación -->
<div id="modalMenuEval" 
     style="display:none; position:fixed; inset:0; background:#0008;
            justify-content:center; align-items:center; z-index:9000;">
    <div style="background:white; padding:1.5rem; border-radius:12px; width:360px;">
        <h3 style="margin-bottom:1rem;">Evaluación Física</h3>

        <!-- Aquí se coloca el nombre del postulante que puede ser dinámico -->
        <p><b>Postulante:</b> <span id="menu_nombre"></span></p>

        <div id="menu_botones"></div>
        <button onclick="cerrarMenuEval()"
                class="btn btn-secondary"
                style="margin-top:0.5rem; width:100%;">Cerrar</button>
    </div>
</div>
<!-- Modal Finalizar Evaluación -->
<div id="modalFinalizar" 
     style="display:none; position:fixed; inset:0; background:#0008; 
            justify-content:center; align-items:center; z-index:99999;">
    <div style="background:white; padding:1.5rem; border-radius:10px; width:350px;">
        <h3 style="margin-bottom:1rem;">Finalizar Evaluación</h3>

        <p><b>ID Postulante:</b> <span id="finalizar_id_postulante">—</span></p>
        <p><b>ID Prueba:</b> <span id="finalizar_id_prueba">—</span></p>
        
        <!-- Mostrar Promedio -->
        <p><b>Promedio:</b> <span id="finalizar_promedio">—</span></p>

        <!-- Mostrar Conclusión -->
        <p><b>Conclusión:</b> <span id="finalizar_conclusion">—</span></p>

        <!-- Nuevo Campo de Observación -->
        <p><b>Observación:</b></p>
        <textarea id="finalizar_observacion" style="width:100%; height:80px;"></textarea>

        <!-- Botón Guardar -->
        <button onclick="guardarEvaluacion()" class="btn btn-primary" 
                style="margin-top:1rem; width:100%;">Guardar</button>

        <button onclick="cerrarFinalizar()" class="btn btn-secondary" 
                style="margin-top:0.7rem; width:100%;">Cerrar</button>
    </div>
</div>


</body>
</html>

<script>
    function guardarEvaluacion() {
    const id_postulante = document.getElementById("finalizar_id_postulante").innerText;
    const nota_total = document.getElementById("finalizar_promedio").innerText;  // Cambiar 'nota_final' por 'nota_total'
    const conclusion = document.getElementById("finalizar_conclusion").innerText;
    const observacion = document.getElementById("finalizar_observacion").value;

    // Validar que todos los datos estén disponibles
    if (!id_postulante || !nota_total || !conclusion) {
        alert("Por favor complete todos los campos.");
        return;
    }

    // Obtener el token CSRF desde la metaetiqueta
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Actualizar la prueba en la base de datos
    fetch(`/actualizar-prueba/${id_postulante}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken // Incluir el token CSRF
        },
        body: JSON.stringify({
            nota_total: nota_total,  // Cambiar 'nota_final' por 'nota_total'
            conclusion: conclusion,
            observacion: observacion,
        }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            alert("Error: " + data.error);
        } else {
            alert("Prueba actualizada correctamente");

            // Llamar a la función para generar el PDF después de actualizar la prueba
            generarPDF(id_postulante);
            cerrarFinalizar();  // Cerrar el modal después de guardar
        }
    })
    .catch(error => {
        console.error("Error al guardar los datos:", error);
        alert("Hubo un error al guardar los datos.");
    });
}

function generarPDF(id_postulante) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Llamar a la ruta para generar el PDF
    fetch(`/generar-pdf/${id_postulante}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': csrfToken // Incluir el token CSRF
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            alert(data.message);  // Mostrar mensaje de éxito
        } else {
            alert("Error al generar el PDF");
        }
    })
    .catch(error => {
        console.error("Error al generar el PDF:", error);
        alert("Hubo un error al generar el PDF.");
    });
}


// Función para abrir el modal de Finalizar y obtener el id_prueba
function finalizarEvaluacion(id_postulante) {
    fetch("/obtener-id-prueba/" + id_postulante)
        .then(r => r.json())
        .then(data => {
            // Mostrar el modal
            document.getElementById("modalFinalizar").style.display = "flex";
            document.getElementById("finalizar_id_postulante").innerText = data.id_postulante;
            document.getElementById("finalizar_id_prueba").innerText = data.id_prueba ?? "No asignado";

            // Mostrar el promedio
            document.getElementById("finalizar_promedio").innerText = data.promedio !== null ? data.promedio.toFixed(2) : "—";

            // Mostrar la conclusión
            document.getElementById("finalizar_conclusion").innerText = data.conclusion ?? "—";
        })
        .catch(error => {
            console.error("Error al obtener los datos:", error);
        });
}


// Función para cerrar el modal de Finalizar
function cerrarFinalizar() {
    document.getElementById("modalFinalizar").style.display = "none";
}


function abrirMenuEvaluacion(id) {
    fetch("/api/evaluacion/" + id)
        .then(r => r.json())
        .then(data => {
            let html = "";

            const pruebas = [
                { campo: "velocidad",         label: "Velocidad",     modal: "abrirVelocidad" },
                { campo: "prueba_resis",      label: "Resistencia",   modal: "abrirResistencia" },
                { campo: "barra",             label: "Barras",        modal: "abrirBarra" },
                { campo: "natacion",          label: "Natación",      modal: "abrirNatacion" },
                { campo: "cap_abdominal",     label: "Abdominales",   modal: "abrirAbdominal" },
                { campo: "flexiones",         label: "Flexiones",     modal: "abrirFlexiones" },
            ];

            let completas = 0;

            pruebas.forEach(p => {
                let lleno = data[p.campo] !== null && data[p.campo] !== "";

                if (lleno) completas++;

                html += `
                    <button class="btn ${lleno ? 'btn-success' : 'btn-primary'}"
                            style="width:100%; margin-bottom:6px;"
                            onclick="${p.modal}(${id})">
                        ${p.label} ${lleno ? '✔' : ''}
                    </button>
                `;
            });

            document.getElementById("menu_botones").innerHTML = html;

            document.getElementById("modalMenuEval").style.display = "flex";  // Mostrar el modal
        });
}
</script>

<script>
function abrirVelocidad(id){

    fetch("/api/evaluacion/" + id)
        .then(r => r.json())
        .then(data => {

            document.getElementById("velocidad_actual").innerText =
                data.velocidad ?? "—";

            document.getElementById("nota_velocidad_actual").innerText =
                data.nota_velocidad ?? "—";

            document.querySelector("#formVelocidad input[name='velocidad']")
                .value = data.velocidad ?? "";
        });
    // Mostrar modal
    document.getElementById("modalVelocidad").style.display="flex";
    document.getElementById("formVelocidad").action="/postulantes/"+id+"/velocidad";
}


function cerrarVelocidad() {
    document.getElementById("modalVelocidad").style.display = "none";
}
</script>
<script>

// ===== RESISTENCIA =====
function abrirResistencia(id){

    fetch("/api/evaluacion/" + id)
        .then(r => r.json())
        .then(data => {

            document.getElementById("res_valor").innerText =
                data.prueba_resis ?? "—";

            document.getElementById("res_nota").innerText =
                data.nota_prueba ?? "—";

            document.querySelector("#formResistencia input[name='prueba_resis']")
                .value = data.prueba_resis ?? "";
        });

    document.getElementById("modalResistencia").style.display="flex";
    document.getElementById("formResistencia").action="/postulantes/"+id+"/resistencia";
}

function cerrarResistencia(){
    document.getElementById("modalResistencia").style.display="none";
}


function cerrarResistencia(){
    document.getElementById("modalResistencia").style.display="none";
}



// ===== BARRA =====
function abrirBarra(id){

    fetch("/api/evaluacion/" + id)
        .then(r => r.json())
        .then(data => {

            document.getElementById("barra_valor").innerText =
                data.barra ?? "—";

            document.getElementById("barra_nota").innerText =
                data.nota_barra ?? "—";

            document.querySelector("#formBarra input[name='barra']")
                .value = data.barra ?? "";
        });

    document.getElementById("modalBarra").style.display="flex";
    document.getElementById("formBarra").action="/postulantes/"+id+"/barra";
}

function cerrarBarra(){
    document.getElementById("modalBarra").style.display="none";
}

function abrirAbdominal(id){

    fetch("/api/evaluacion/" + id)
        .then(r => r.json())
        .then(data => {

            document.getElementById("abd_valor").innerText =
                data.cap_abdominal ?? "—";

            document.getElementById("abd_nota").innerText =
                data.nota_cap ?? "—";

            document.querySelector("#formAbdominal input[name='cap_abdominal']")
                .value = data.cap_abdominal ?? "";
        });

    document.getElementById("modalAbdominal").style.display="flex";
    document.getElementById("formAbdominal").action="/postulantes/"+id+"/abdominal";
}

function cerrarAbdominal(){
    document.getElementById("modalAbdominal").style.display="none";
}

function cerrarMenuEval(){
    document.getElementById("modalMenuEval").style.display="none";
}
function abrirFlexiones(id){ fetch("/api/evaluacion/" + id) .then(r => r.json()) .then(data => { document.getElementById("flex_valor").innerText = data.flexiones ?? "—"; document.getElementById("flex_nota").innerText = data.nota_flexiones ?? "—"; document.querySelector("#formFlexiones input[name='flexiones']") .value = data.flexiones ?? ""; }); document.getElementById("modalFlexiones").style.display="flex"; document.getElementById("formFlexiones").action="/postulantes/"+id+"/flexiones"; }

function cerrarFlexiones(){
    document.getElementById("modalFlexiones").style.display = "none";
}



// ===== NATACION =====
function abrirNatacion(id){

    fetch("/api/evaluacion/" + id)
        .then(r => r.json())
        .then(data => {

            document.getElementById("nat_valor").innerText =
                data.natacion ?? "—";

            document.getElementById("nat_nota").innerText =
                data.nota_natacion ?? "—";

            document.querySelector("#formNatacion input[name='natacion']")
                .value = data.natacion ?? "";
        });

    document.getElementById("modalNatacion").style.display="flex";
    document.getElementById("formNatacion").action="/postulantes/"+id+"/natacion";
}

function cerrarNatacion(){
    document.getElementById("modalNatacion").style.display="none";
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Función que aplica los filtros
    function applyFilters(event) {
        event.preventDefault();  // Evitar el envío normal del formulario

        const form = document.getElementById('filtersForm');
        const formData = new FormData(form);

        // Convertir los datos del formulario en parámetros de consulta
        const queryString = new URLSearchParams(formData).toString();

        // Usar Fetch API para enviar los filtros al backend sin recargar la página
        fetch(`/filtrar-postulantes?${queryString}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())  // Recibimos los postulantes filtrados en formato JSON
        .then(data => {
            // Actualizamos las tablas de postulantes NO atendidos
            const postulantesNoAtendidosTable = document.getElementById('noAtendidosTable');
            const postulantesAtendidosTable = document.getElementById('atendidosTable');

            // Limpiar el contenido de las tablas
            postulantesNoAtendidosTable.innerHTML = '';
            postulantesAtendidosTable.innerHTML = '';

            // Si no hay postulantes, mostramos un mensaje
            if (data.postulantesNoAtendidos.length === 0) {
                postulantesNoAtendidosTable.innerHTML = '<tr><td colspan="6">No se encontraron postulantes pendientes con los filtros aplicados.</td></tr>';
            } else {
                data.postulantesNoAtendidos.forEach((p, index) => {
                    const inst = p.instituto == 1 ? 'COLMILAV' : p.instituto == 2 ? 'POLMILAE' : p.instituto == 3 ? 'EMMFAB' : 'N/D';
                    postulantesNoAtendidosTable.innerHTML += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${p.ci}</td>
                            <td>${p.apellido_paterno} ${p.apellido_materno}</td>
                            <td>${p.nombres}</td>
                            <td><span class="badge-inst">${inst}</span></td>
                            <td>
                                <button class="btn btn-primary" onclick="abrirMenuEvaluacion(${p.id_postulante})">Llenar evaluación</button>
                                <button class="btn btn-success" onclick="finalizarEvaluacion(${p.id_postulante})">Finalizar</button>
                            </td>
                        </tr>
                    `;
                });
            }

            // Si no hay postulantes atendidos, mostramos un mensaje
            if (data.postulantesAtendidos.length === 0) {
                postulantesAtendidosTable.innerHTML = '<tr><td colspan="6">No se encontraron postulantes atendidos con los filtros aplicados.</td></tr>';
            } else {
                data.postulantesAtendidos.forEach((p, index) => {
                    const inst = p.instituto == 1 ? 'COLMILAV' : p.instituto == 2 ? 'POLMILAE' : p.instituto == 3 ? 'EMMFAB' : 'N/D';
                    const ultima = p.pruebas.length > 0 ? p.pruebas[0] : null;
                    postulantesAtendidosTable.innerHTML += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${p.ci}</td>
                            <td>${p.apellido_paterno} ${p.apellido_materno}</td>
                            <td>${p.nombres}</td>
                            <td><span class="badge-inst">${inst}</span></td>
                            <td>
                                ${ultima && ultima.ruta_pdf ? ` 
                                    <a href="/pdf/ver/${basename(ultima.ruta_pdf)}" class="btn btn-secondary">Ver PDF</a>
                                    <a href="/pdf/descargar/${basename(ultima.ruta_pdf)}" class="btn btn-primary">Descargar</a>
                                    <a href="/postulante/editar/${ultima.id_prueba}" class="btn btn-secondary">Editar</a>
                                ` : '<span style="font-size: 0.75rem; color: #777;">Sin PDF</span>'}
                            </td>
                        </tr>
                    `;
                });
            }
        })
        .catch(error => {
            console.error('Error al aplicar filtros:', error);
        });
    }

    // Adjuntar el evento de filtro
    const filterForm = document.getElementById('filtersForm');
    filterForm.addEventListener('submit', applyFilters);
});

</script>
