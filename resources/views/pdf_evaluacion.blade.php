<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ficha Individual del Postulante</title>

    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .titulo {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        th, td {
            border: 1px solid #000;
            padding: 4px 6px;
        }

        .th-small { width: 15%; font-weight: bold; }
        .text-center { text-align: center; }
        .huella { border: 1px solid #000; height: 70px; }

        .box {
            border: 1px solid #000;
            padding: 8px;
            margin-bottom: 10px;
        }

        .firmas {
    width: 100%;
    margin-top: 40px;
    text-align: center;
}

.firma-block {
    width: 45%;
    display: inline-block;
    vertical-align: top;
    text-align: center;
}

.linea-firma {
    margin-top: 45px;
    border-top: 1px solid #000;
    width: 80%;
    margin-left: auto;
    margin-right: auto;
    margin-bottom: 5px;
}

    </style>
</head>
<body>

@php
use App\Models\Usuario;
    $inst = match($postulante->instituto) {
        1 => 'COLMILAV',
        2 => 'POLMILAE',
        3 => 'EMMFAB',
        default => 'N/D',
    };

    $sexo = $postulante->sexo === 0 ? 'MASCULINO' : 'FEMENINO';

    $edad = null;
    if (!empty($postulante->fecha_nac)) {
        $edad = \Carbon\Carbon::parse($postulante->fecha_nac)->age;
    }

    $evaluadorId = session('id_usu');
    $evaluador = Usuario::find($evaluadorId);

@endphp

<div class="titulo">
    FICHA INDIVIDUAL PERSONAL {{ $sexo }}
</div>

<table>
    <tr>
        <td class="th-small">C.I.</td>
        <td>{{ $postulante->ci }}</td>

        <td class="th-small"></td>
        <td></td>

        <td class="th-small">FECHA</td>
        <td>{{ $prueba->created_at->format('Y-m-d') }}</td>
    </tr>

    <tr>
        <td colspan="6"><strong>Nombres y Apellidos:</strong>
            {{ $postulante->apellido_paterno }}
            {{ $postulante->apellido_materno }}
            {{ $postulante->nombres }}
        </td>
    </tr>

    <tr>
        <td><strong>Edad</strong></td>
        <td>{{ $edad }}</td>

        <td><strong>Instituto</strong></td>
        <td>{{ $inst }}</td>

        <td colspan="2" class="text-center"><strong>Huella Digital</strong></td>
    </tr>

    <tr>
        <td colspan="4"></td>
        <td colspan="2" class="huella"></td>
    </tr>
</table>

<div class="subtitulo" style="text-align:center; font-weight:bold; margin-bottom:6px;">
    PRUEBAS FÍSICAS
</div>

<table>
    <thead>
        <tr>
            <th>PRUEBA</th>
            <th>DIAGNÓSTICO</th>
            <th>NOTA %</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>VELOCIDAD <br><small>(100 mts)</small></td>
            <td>{{ $evaluacion->velocidad }}</td>
            <td class="text-center">{{ $evaluacion->nota_velocidad }}</td>
        </tr>

        <tr>
            <td>PRUEBA DE RESISTENCIA <br><small>(2400 mts)</small></td>
            <td>{{ $evaluacion->prueba_resis }}</td>
            <td class="text-center">{{ $evaluacion->nota_prueba }}</td>
        </tr>

        <tr>
            <td>FLEXIONES EN BARRA <br><small>(sin tiempo)</small></td>
            <td>{{ $evaluacion->barra }}</td>
            <td class="text-center">{{ $evaluacion->nota_barra }}</td>
        </tr>

        <tr>
            <td>NATACIÓN <br><smal>(sin tiempo)</smal></td>
            <td>{{ $evaluacion->natacion }}</td>
            <td class="text-center">{{ $evaluacion->nota_natacion }}</td>
        </tr>

        <tr>
            <td>CAPACIDAD ABDOMINAL <br><small>120 segundos)</small></td>
            <td>{{ $evaluacion->cap_abdominal }}</td>
            <td class="text-center">{{ $evaluacion->nota_cap }}</td>
        </tr>

        <tr>
            <td>FLEXIONES EN SUELO <br><small>(sin tiempo)</small></td>
            <td>{{ $evaluacion->flexiones }}</td>
            <td class="text-center">{{ $evaluacion->nota_flexiones }}</td>
        </tr>
    </tbody>
</table>

<table>
    <tr>
        <td><strong>Nota Total</strong></td>
        <td>{{ number_format($prueba->nota_total, 2) }}</td>

        <td><strong>Conclusión</strong></td>
        <td>{{ $prueba->conclusion }}</td>
    </tr>
</table>

<div class="box">
    <strong>Observaciones:</strong><br>
    {{ $prueba->observacion ?? 'Sin observaciones' }}
</div>

{{-- ========================= --}}
{{--      FIRMAS FINALES       --}}
{{-- ========================= --}}
<table style="width: 100%; margin-top: 40px; text-align: center; border: none;">
    <tr>
        <td style="width: 50%; border: none;">

            <div style="border-top: 1px solid #000; width: 80%; margin: 0 auto; margin-bottom: 5px; margin-top: 40px;"></div>

            {{ $evaluador->nombres ?? '' }}
            {{ $evaluador->apellido_pat ?? '' }}
            {{ $evaluador->apellido_mat ?? '' }}<br>
            <strong>EVALUADOR</strong>

        </td>

        <td style="width: 50%; border: none;">

            <div style="border-top: 1px solid #000; width: 80%; margin: 0 auto; margin-bottom: 5px; margin-top: 40px;"></div>

            {{ $postulante->apellido_paterno }}
            {{ $postulante->apellido_materno }}
            {{ $postulante->nombres }}<br>
            <strong>POSTULANTE</strong>

        </td>
    </tr>
</table>



</body>
</html>
