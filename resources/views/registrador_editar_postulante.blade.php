<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar ficha del postulante</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
        :root {
            --celeste: #00bcd4;
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
            background: #f5f7fb;
            color: var(--negro);
        }

        .layout {
            max-width: 1100px;
            margin: 0 auto;
            padding: 1.5rem 1rem 2rem;
        }

        .titulo-ficha {
            text-align: center;
            font-size: 1.2rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 0.7rem;
        }

        .card {
            background: #ffffff;
            border-radius: 10px;
            padding: 0.8rem 1rem;
            box-shadow: 0 4px 14px rgba(0,0,0,0.06);
            border: 1px solid rgba(0,0,0,0.08);
            margin-bottom: 1rem;
        }

        table.ficha {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }

        .ficha th,
        .ficha td {
            border: 1px solid #000;
            padding: 0.25rem 0.35rem;
        }

        .ficha .encabezado-label {
            font-weight: 700;
            font-size: 0.78rem;
            text-transform: uppercase;
        }

        .ficha input[type="text"],
        .ficha input[type="date"],
        .ficha input[type="number"],
        .ficha textarea {
            width: 100%;
            border: none;
            padding: 0.1rem 0.2rem;
            font-size: 0.8rem;
            outline: none;
        }

        .ficha textarea {
            resize: vertical;
            min-height: 40px;
        }

        .ficha .bloque-huella {
            height: 70px;
        }

        .subtitulo-bloque {
            text-align: center;
            font-weight: 700;
            font-size: 0.85rem;
            margin-bottom: 0.4rem;
        }

        .tabla-pruebas {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8rem;
        }

        .tabla-pruebas th,
        .tabla-pruebas td {
            border: 1px solid #000;
            padding: 0.25rem 0.3rem;
        }

        .tabla-pruebas th {
            background: #e2f6ff;
            text-align: center;
        }

        .tabla-pruebas .nombre-prueba {
            font-weight: 600;
            width: 22%;
        }

        .tabla-pruebas input[type="text"],
        .tabla-pruebas input[type="number"] {
            width: 100%;
            border: none;
            padding: 0.1rem 0.2rem;
            font-size: 0.78rem;
            outline: none;
        }

        .fila-extra {
            margin-top: 0.6rem;
            display: grid;
            grid-template-columns: 1fr 1.7fr 1fr;
            gap: 0.6rem;
            font-size: 0.85rem;
        }

        .fila-extra label {
            font-weight: 500;
            font-size: 0.8rem;
        }

        .fila-extra textarea,
        .fila-extra input {
            width: 100%;
            border-radius: 6px;
            border: 1px solid #aaa;
            padding: 0.25rem 0.35rem;
            font-size: 0.8rem;
            outline: none;
        }

        .fila-extra textarea {
            min-height: 80px;
            resize: vertical;
        }

        .promedio {
            margin-top: 0.7rem;
            font-size: 0.85rem;
            display: flex;
            justify-content: flex-end;
            gap: 0.4rem;
            align-items: center;
        }

        .promedio input {
            width: 90px;
            border-radius: 6px;
            border: 1px solid #aaa;
            padding: 0.2rem 0.3rem;
            font-size: 0.85rem;
            text-align: right;
        }

        .acciones {
            margin-top: 1rem;
            display: flex;
            justify-content: flex-end;
            gap: 0.6rem;
        }

        .btn {
            border: none;
            border-radius: 999px;
            padding: 0.45rem 1.3rem;
            font-size: 0.9rem;
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

        .msg-error {
            background: #ffebee;
            color: #b71c1c;
            border-radius: 6px;
            border: 1px solid #ffcdd2;
            padding: 0.5rem 0.7rem;
            font-size: 0.8rem;
            margin-bottom: 0.6rem;
        }
    </style>
</head>

<body>

<div class="layout">

    @php
        $inst = match($postulante->instituto) {
            1 => 'COLMILAV',
            2 => 'POLMILAE',
            3 => 'EMMFAB',
            default => 'N/D',
        };

        $tituloSexo = $postulante->sexo === 0 ? 'MASCULINO' : 'FEMENINO';

        $edad = $postulante->fecha_nac ? \Carbon\Carbon::parse($postulante->fecha_nac)->age : null;
    @endphp

    <div class="titulo-ficha">
        EDITAR FICHA INDIVIDUAL PERSONAL ({{ $tituloSexo }})
    </div>

    <div class="card">

        @if(session('success'))
            <div class="msg-success">{{ session('success') }}</div>
        @endif

<form method="POST" action="{{ route('postulante.actualizar', $prueba->id_prueba) }}">
            @csrf

            <!-- === IDENTIFICACIÓN === -->
            <table class="ficha">
                <tr>
                    <td class="encabezado-label">C.I.</td>
                    <td><input type="text" value="{{ $postulante->ci }}" readonly></td>

                    <td class="encabezado-label">EXPEDIDO</td>
                    <td><input type="text" value="{{ $postulante->procedencia }}"></td>

                    <td class="encabezado-label">FECHA</td>
                    <td><input type="date" name="fecha" value="{{ $prueba->fecha }}"></td>
                </tr>

                <tr>
                    <td class="encabezado-label">NOMBRES Y APELLIDOS:</td>
                    <td colspan="5">
                        {{ $postulante->apellido_paterno }}
                        {{ $postulante->apellido_materno }}
                        {{ $postulante->nombres }}
                    </td>
                </tr>

                <tr>
                    <td class="encabezado-label">EDAD</td>
                    <td><input type="text" value="{{ $edad }}" readonly></td>

                    <td class="encabezado-label">INSTITUTO</td>
                    <td>{{ $inst }}</td>

                    <td colspan="2" class="encabezado-label" style="text-align:center;">HUELLA DIGITAL</td>
                </tr>

                <tr>
                    <td colspan="4"></td>
                    <td colspan="2" class="bloque-huella"></td>
                </tr>
            </table>
        </div>

        <!-- === PRUEBAS FÍSICAS === -->
        <div class="card">
            <div class="subtitulo-bloque">PRUEBAS FÍSICAS</div>

            <table class="tabla-pruebas">
                <thead>
                    <tr>
                        <th style="width: 24%;">PRUEBA</th>
                        <th style="width: 20%;">OBSERVACIÓN<br>(tiempo / dist. / N° rep)</th>
                        <th style="width: 16%;">Nota %</th>
                    </tr>
                </thead>
                <tbody>

                <tr>
                    <td class="nombre-prueba">
        VELOCIDAD<br><small>(100 mts)</small>
    </td>
                        <td><input type="text" id="velocidad" name="velocidad" value="{{ $evaluacion->velocidad }}"></td>

                    <td>
        <input
            type="number"
            id="nota_velocidad"
            name="nota_velocidad"
            min="0"
            max="100"
            placeholder="%"
            readonly>
        <small id="velocidad_pts_text">Nota: 0 pts</small>
    </td>
                </tr>

                <tr>
                    <td class="nombre-prueba">
        PRUEBA DE RESISTENCIA<br><small>(2400 mts)</small>
    </td>
                    <td><input type="text" id="prueba_resis" name="prueba_resis" value="{{ $evaluacion->prueba_resis }}"></td>
                    <td>
        <input type="number"
               id="nota_prueba"
               name="nota_prueba"
               min="0"
               max="100"
               placeholder="%"
               readonly>
        <small id="resis_pts_text">Nota: 0 pts</small>
    </td>
                </tr>

                <tr>
                    <td class="nombre-prueba">
        FLEXIONES EN BARRA<br><small>(sin tiempo)</small>
    </td>
                    <td><input type="number" id="barra" name="barra" value="{{ $evaluacion->barra }}"></td>
                    <td>
        <input type="number"
               id="nota_barra"
               name="nota_barra"
               min="0"
               max="100"
               placeholder="%"
               readonly>
        <small id="barra_pts_text">Nota: 0 pts</small>
    </td>
                </tr>

                <tr>
                    <td class="nombre-prueba">
        NATACIÓN ESTILO LIBRE<br><small>(en segundos)</small>
    </td>
                    <td><input type="number" id="natacion" name="natacion" value="{{ $evaluacion->natacion }}"></td>
                    <td>
        <input type="number"
               id="nota_natacion"
               name="nota_natacion"
               min="0"
               max="100"
               placeholder="%"
               readonly>
        <small id="natacion_pts_text">Nota: 0 pts</small>
    </td>
                </tr>

                <tr>
<td class="nombre-prueba">
        CAPACIDAD ABDOMINAL<br><small>(repeticiones)</small>
    </td>                    
    <td><input type="number" id="cap_abdominal" name="cap_abdominal" value="{{ $evaluacion->cap_abdominal }}"></td>
    <td>
        <input type="number"
               id="nota_cap"
               name="nota_cap"
               min="0"
               max="100"
               placeholder="%"
               readonly>
        <small id="cap_pts_text">Nota: 0 pts</small>
    </td>
                </tr>

                <tr>
                    <td class="nombre-prueba">
        FLEXIONES EN SUELO<br><small>(sin tiempo)</small>
    </td>
                    <td><input type="number" id="flexiones" name="flexiones" value="{{ $evaluacion->flexiones }}"></td>
                    <td>
        <input type="number"
               id="nota_flexiones"
               name="nota_flexiones"
               min="0"
               max="100"
               placeholder="%"
               readonly>
        <small id="flexiones_pts_text">Nota: 0 pts</small>
    </td>
                </tr>

                </tbody>
            </table>

            <div class="fila-extra">
                <div>
                    <label for="nota_total">Nota Total al 100%</label>
                    <input type="number" id="nota_total" value="{{ $prueba->nota_total }}" readonly>
                </div>

                <div>
                    <label for="observacion">Observaciones</label>
                    <textarea name="observacion">{{ $prueba->observacion }}</textarea>
                </div>

                <div>
                    <label for="promedio">Conclusión</label>
                    <input type="text" id="promedio" value="{{ $prueba->conclusion }}" readonly>
                </div>
            </div>

            <div class="acciones">
                <a href="{{ route('dash.registrador') }}" class="btn btn-secondary">Volver</a>
                <button class="btn btn-primary">Guardar cambios y regenerar PDF</button>
            </div>

        </div>
        </form>

</div>

</body>
</html>
<script>
    // 0 = varón, 1 = mujer (lo traemos desde PHP)
    const sexoPostulante = {{ (int) $postulante->sexo }};

    function normalizarTiempo(valor) {
        valor = valor.trim();
        if (!valor) return null;

        // "11''50" -> "11.50"
        let normalizado = valor
            .replace(/''/g, '.')     // dos comillas simples
            .replace(/['´`]/g, '.')  // cualquier comilla suelta rara
            .replace(/,/g, '.')      // coma -> punto
            .replace(/\s+/g, '');    // quitar espacios

        if (isNaN(normalizado)) {
            return null;
        }
        return parseFloat(normalizado);
    }

    function calcularPuntajeVelocidad(sexo, tiempoCrudo) {
        const tiempo = normalizarTiempo(tiempoCrudo);

        if (tiempo === null) {
            return 0;
        }

        // redondear a 2 decimales
        const t = Math.round(tiempo * 100) / 100;

        if (sexo === 0) {
            // VARONES
            if (t <= 11.50) return 100;
            if (t <= 12.00) return 90;
            if (t <= 12.50) return 80;
            if (t <= 13.00) return 70;
            if (t <= 13.50) return 60;
            if (t <= 14.00) return 50;
            if (t <= 14.50) return 40;
            if (t <= 15.00) return 30;
            if (t <= 15.50) return 20;
            if (t <= 16.00) return 10;
            return 0; // 16''01 en adelante
        } else {
            // MUJERES
            if (t <= 14.00) return 100;
            if (t <= 14.50) return 90;
            if (t <= 15.00) return 80;
            if (t <= 15.50) return 70;
            if (t <= 16.00) return 60;
            if (t <= 16.50) return 50;
            if (t <= 17.00) return 40;
            if (t <= 17.50) return 30;
            if (t <= 18.00) return 20;
            if (t <= 18.50) return 10;
            return 0; // 18''51 en adelante
        }
    }

    function calcularPuntajeResistencia(sexo, tiempoCrudo) {
    const tiempo = normalizarTiempo(tiempoCrudo);

    if (tiempo === null) {
        return 0;
    }

    const t = Math.round(tiempo * 100) / 100;

    if (sexo === 0) {
        // VARONES
        if (t <= 11.30) return 100;
        if (t <= 11.40) return 90;
        if (t <= 11.50) return 80;
        if (t <= 12.00) return 70;
        if (t <= 12.10) return 60;
        if (t <= 12.20) return 50;
        if (t <= 12.30) return 40;
        if (t <= 12.40) return 30;
        if (t <= 12.50) return 20;
        if (t <= 13.00) return 10;
        return 0;
    } else {
        // MUJERES
        if (t <= 12.00) return 100;
        if (t <= 12.10) return 90;
        if (t <= 12.20) return 80;
        if (t <= 12.30) return 70;
        if (t <= 12.40) return 60;
        if (t <= 12.50) return 50;
        if (t <= 13.00) return 40;
        if (t <= 13.10) return 30;
        if (t <= 13.20) return 20;
        if (t <= 13.30) return 10;
        return 0;
    }
}

// Calculador de barra
function calcularPuntajeBarra(sexo, repeticiones) {
    if (sexo === 0) {
        // VARONES
        if (repeticiones >= 12) return 100;
        if (repeticiones === 11) return 90;
        if (repeticiones === 10) return 80;
        if (repeticiones === 9) return 70;
        if (repeticiones === 8) return 60;
        if (repeticiones === 7) return 50;
        if (repeticiones === 6) return 40;
        if (repeticiones === 5) return 30;
        if (repeticiones === 4) return 20;
        if (repeticiones === 3) return 10;
        return 0;  // Menos de 3 repeticiones
    } else {
        // MUJERES
        if (repeticiones >= 8) return 100;
        if (repeticiones === 7) return 90;
        if (repeticiones === 6) return 80;
        if (repeticiones === 5) return 70;
        if (repeticiones === 4) return 60;
        if (repeticiones === 3) return 50;
        if (repeticiones === 2) return 30;
        if (repeticiones === 1) return 10;
        return 0;  // Menos de 1 repetición
    }
}

// Función para calcular el puntaje de natación
function calcularPuntajeNatacion(sexo, tiempo) {
    if (sexo === 0) {
        // VARONES
        if (tiempo >= 100) return 100;
        if (tiempo >= 90) return 90;
        if (tiempo >= 80) return 80;
        if (tiempo >= 70) return 70;
        if (tiempo >= 60) return 60;
        if (tiempo >= 50) return 50;
        if (tiempo >= 40) return 40;
        if (tiempo >= 30) return 30;
        if (tiempo >= 20) return 20;
        if (tiempo >= 10) return 10;
        return 0;
    } else {
        // MUJERES
        if (tiempo >= 50) return 100;
        if (tiempo >= 45) return 90;
        if (tiempo >= 40) return 80;
        if (tiempo >= 35) return 70;
        if (tiempo >= 30) return 60;
        if (tiempo >= 25) return 50;
        if (tiempo >= 20) return 40;
        if (tiempo >= 15) return 30;
        if (tiempo >= 10) return 20;
        if (tiempo >= 5) return 10;
        return 0;
    }
}

// Función para calcular el puntaje de abdominales
function calcularPuntajeAbdominales(sexo, repeticiones) {
    if (sexo === 0) {
        // VARONES
        if (repeticiones >= 80) return 100;
        if (repeticiones >= 70) return 90;
        if (repeticiones >= 60) return 80;
        if (repeticiones >= 50) return 70;
        if (repeticiones >= 40) return 60;
        if (repeticiones >= 30) return 50;
        if (repeticiones >= 25) return 40;
        if (repeticiones >= 20) return 30;
        if (repeticiones >= 15) return 20;
        if (repeticiones >= 10) return 10;
        return 0;
    } else {
        // MUJERES
        if (repeticiones >= 70) return 100;
        if (repeticiones >= 60) return 90;
        if (repeticiones >= 50) return 80;
        if (repeticiones >= 40) return 70;
        if (repeticiones >= 30) return 60;
        if (repeticiones >= 20) return 50;
        if (repeticiones >= 15) return 40;
        if (repeticiones >= 10) return 30;
        if (repeticiones >= 5) return 10;
        return 0;
    }
}

// Función para calcular el puntaje de flexiones en suelo
function calcularPuntajeFlexiones(sexo, repeticiones) {
    if (sexo === 0) {
        // VARONES
        if (repeticiones >= 70) return 100;
        if (repeticiones >= 65) return 90;
        if (repeticiones >= 60) return 80;
        if (repeticiones >= 55) return 70;
        if (repeticiones >= 50) return 60;
        if (repeticiones >= 45) return 50;
        if (repeticiones >= 40) return 40;
        if (repeticiones >= 30) return 30;
        if (repeticiones >= 20) return 20;
        if (repeticiones >= 10) return 10;
        return 0;  // Menos de 10 repeticiones
    } else {
        // MUJERES
        if (repeticiones >= 50) return 100;
        if (repeticiones >= 43) return 90;
        if (repeticiones >= 35) return 80;
        if (repeticiones >= 28) return 70;
        if (repeticiones >= 20) return 60;
        if (repeticiones >= 13) return 50;
        if (repeticiones >= 11) return 40;
        if (repeticiones >= 9) return 30;
        if (repeticiones >= 7) return 20;
        if (repeticiones >= 5) return 10;
        return 0;  // Menos de 5 repeticiones
    }
}

// Llamar esta función cuando cambien los valores
const flexionesInput = document.getElementById('flexiones');
const notaFlexionesInput = document.getElementById('nota_flexiones');
const flexionesPtsText = document.getElementById('flexiones_pts_text');

function actualizarNotaFlexiones() {
    const repeticiones = parseInt(flexionesInput.value, 10) || 0;
    const pts = calcularPuntajeFlexiones(sexoPostulante, repeticiones);

    notaFlexionesInput.value = pts;
    if (flexionesPtsText) {
        flexionesPtsText.textContent = 'Nota: ' + pts + ' pts';
    }
}

// Escucha el cambio en el input de flexiones
if (flexionesInput) {
    flexionesInput.addEventListener('input', actualizarNotaFlexiones);
    flexionesInput.addEventListener('blur', actualizarNotaFlexiones);
    actualizarNotaFlexiones();  // Llama al principio por si viene prellenado
}


// Llamar esta función cuando cambien los valores
const capAbdominalInput = document.getElementById('cap_abdominal');
const notaCapInput = document.getElementById('nota_cap');
const capPtsText = document.getElementById('cap_pts_text');

function actualizarNotaAbdominales() {
    const repeticiones = parseInt(capAbdominalInput.value, 10) || 0;
    const pts = calcularPuntajeAbdominales(sexoPostulante, repeticiones);

    notaCapInput.value = pts;
    if (capPtsText) {
        capPtsText.textContent = 'Nota: ' + pts + ' pts';
    }
}

// Escucha el cambio en el input de abdominales
if (capAbdominalInput) {
    capAbdominalInput.addEventListener('input', actualizarNotaAbdominales);
    capAbdominalInput.addEventListener('blur', actualizarNotaAbdominales);
    actualizarNotaAbdominales();  // Llama al principio por si viene prellenado
}


// Función para actualizar la nota en tiempo real
const natacionInput = document.getElementById('natacion');
const notaNatacionInput = document.getElementById('nota_natacion');
const natacionPtsText = document.getElementById('natacion_pts_text');

function actualizarNotaNatacion() {
    const tiempo = parseFloat(natacionInput.value) || 0; // Asegura que sea un número
    const pts = calcularPuntajeNatacion(sexoPostulante, tiempo);

    notaNatacionInput.value = pts;
    if (natacionPtsText) {
        natacionPtsText.textContent = 'Nota: ' + pts + ' pts';
    }
}

// Escucha el cambio en el input de natación
if (natacionInput) {
    natacionInput.addEventListener('input', actualizarNotaNatacion);
    natacionInput.addEventListener('blur', actualizarNotaNatacion);  // Cuando pierda el foco
    actualizarNotaNatacion();  // Llama al principio por si viene prellenado
}


// Llamar esta función cuando cambien los valores
const barraInput = document.getElementById('barra');
const notaBarraInput = document.getElementById('nota_barra');
const barraPtsText = document.getElementById('barra_pts_text');

function actualizarNotaBarra() {
    const repeticiones = parseInt(barraInput.value, 10) || 0;
    const pts = calcularPuntajeBarra(sexoPostulante, repeticiones);

    notaBarraInput.value = pts;
    if (barraPtsText) {
        barraPtsText.textContent = 'Nota: ' + pts + ' pts';
    }
}

if (barraInput) {
    barraInput.addEventListener('input', actualizarNotaBarra);
    barraInput.addEventListener('blur', actualizarNotaBarra);
    // por si viene prellenado
    actualizarNotaBarra();
}



    const velocidadInput = document.getElementById('velocidad');
    const notaVelocidadInput = document.getElementById('nota_velocidad');
    const velocidadPtsText = document.getElementById('velocidad_pts_text');

    function actualizarNotaVelocidad() {
        const tiempo = velocidadInput.value;
        const pts = calcularPuntajeVelocidad(sexoPostulante, tiempo);

        notaVelocidadInput.value = pts;
        if (velocidadPtsText) {
            velocidadPtsText.textContent = 'Nota: ' + pts + ' pts';
        }
    }

    if (velocidadInput) {
        velocidadInput.addEventListener('input', actualizarNotaVelocidad);
        velocidadInput.addEventListener('blur', actualizarNotaVelocidad);
        // por si viene prellenado
        actualizarNotaVelocidad();
    }

    const resisInput = document.getElementById('prueba_resis');
const notaResisInput = document.getElementById('nota_prueba');
const resisPtsText = document.getElementById('resis_pts_text');

function actualizarNotaResistencia() {
    const tiempo = resisInput.value;
    const pts = calcularPuntajeResistencia(sexoPostulante, tiempo);

    notaResisInput.value = pts;
    if (resisPtsText) {
        resisPtsText.textContent = 'Nota: ' + pts + ' pts';
    }
}

if (resisInput) {
    resisInput.addEventListener('input', actualizarNotaResistencia);
    resisInput.addEventListener('blur', actualizarNotaResistencia);
    actualizarNotaResistencia();
}

// Función para calcular el promedio y determinar si está aprobado o reprobado
function calcularPromedio() {
    // Obtenemos las notas de cada prueba
    const notas = [
        parseFloat(document.getElementById('nota_velocidad').value) || 0,
        parseFloat(document.getElementById('nota_prueba').value) || 0,
        parseFloat(document.getElementById('nota_barra').value) || 0,
        parseFloat(document.getElementById('nota_natacion').value) || 0,
        parseFloat(document.getElementById('nota_cap').value) || 0,
        parseFloat(document.getElementById('nota_flexiones').value) || 0
    ];

    // Si alguna nota es menor a 51 → ya está reprobado
    const existeNotaBaja = notas.some(nota => nota < 51);

    // Calculamos el promedio
    const sumaNotas = notas.reduce((acc, nota) => acc + nota, 0);
    const promedio = sumaNotas / notas.length;

    // Asignamos el promedio al campo de "nota_total"
    document.getElementById('nota_total').value = promedio.toFixed(2);

    // Conclusión final
    let conclusion;

    if (existeNotaBaja) {
        conclusion = 'REPROBADO';
    } else {
        // Si no hay notas menores a 51, evaluamos el promedio
        conclusion = promedio >= 51 ? 'APROBADO' : 'REPROBADO';
    }

    document.getElementById('promedio').value = conclusion;
}

// Escucha el cambio en las notas de las pruebas
const inputsNotas = [
    document.getElementById('nota_velocidad'),
    document.getElementById('nota_prueba'),
    document.getElementById('nota_barra'),
    document.getElementById('nota_natacion'),
    document.getElementById('nota_cap'),
    document.getElementById('nota_flexiones')
];

inputsNotas.forEach(input => {
    input.addEventListener('input', calcularPromedio);
    input.addEventListener('blur', calcularPromedio);  // Cuando pierda el foco
});

// Llamar al principio por si ya hay valores prellenados
calcularPromedio();

</script>