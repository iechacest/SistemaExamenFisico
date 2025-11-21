<?php

namespace App\Http\Controllers;
use App\Models\Usuario;
use App\Models\Postulante;
use App\Models\Evaluacion;
use App\Models\Prueba;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
class RegistradorController extends Controller
{
    public function index(Request $request)
    {
        // Verificamos que haya sesión
        $registradorId = session('id_usu');

        if (!$registradorId) {
            return redirect('/inicio')->with('error', 'Debes iniciar sesión.');
        }

        // Usuario que está logueado
        $registrador = Usuario::findOrFail($registradorId);

        // Consulta base: postulantes de este registrador
        $queryBase = Postulante::query();

        // === Filtros ===
        if ($ci = $request->input('ci')) {
            $queryBase->where('ci', 'like', "%{$ci}%");
        }

        if ($apPat = $request->input('apellido_paterno')) {
            $queryBase->where('apellido_paterno', 'like', "%{$apPat}%");
        }

        if ($apMat = $request->input('apellido_materno')) {
            $queryBase->where('apellido_materno', 'like', "%{$apMat}%");
        }

        if ($inst = $request->input('instituto')) {
            $queryBase->where('instituto', $inst);
        }

        // Orden alfabético base
        $queryBase->orderBy('apellido_paterno')
                  ->orderBy('apellido_materno')
                  ->orderBy('nombres');

        // ✅ ATENDIDOS: tienen al menos una prueba
        $postulantesAtendidos = (clone $queryBase)
            ->whereHas('pruebas')
            ->get();

        // ✅ NO ATENDIDOS: no tienen ninguna prueba
        $postulantesNoAtendidos = (clone $queryBase)
            ->whereDoesntHave('pruebas')
            ->get();

        return view('dash_registrador', compact(
            'registrador',
            'postulantesAtendidos',
            'postulantesNoAtendidos'
        ));
    }

    // ==========================
    //  Mostrar formulario.blade
    // ==========================
    public function formulario($id_postulante)
    {
        $registradorId = session('id_usu');

        if (!$registradorId) {
            return redirect('/inicio')->with('error', 'Debes iniciar sesión.');
        }

        $registrador = Usuario::findOrFail($registradorId);

        // Sólo puede ver postulantes que él registró
        $postulante = Postulante::findOrFail($id_postulante);


        return view('formulario', compact('registrador', 'postulante'));
    }

    // ======================================================
    //  Helpers de cálculo (velocidad) — se usan desde POST
    // ======================================================

    /**
     * Normaliza un tiempo tipo "11''50", "11,50", "11.50" a float (segundos con centésimas).
     * Ej: "11''50" -> 11.50
     */
    public function normalizarTiempo(string $valor): ?float
{
    $valor = trim($valor);
    if ($valor === '') {
        return null;
    }

    // Reemplazar comillas dobles por punto (11''50 -> 11.50)
    $normalizado = str_replace(["''", "'", "´", "`"], '.', $valor);
    // Reemplazar coma por punto (11,50 -> 11.50)
    $normalizado = str_replace(',', '.', $normalizado);
    // Quitar espacios
    $normalizado = preg_replace('/\s+/', '', $normalizado);

    // Validamos si es un número
    if (!is_numeric($normalizado)) {
        return null;
    }

    // Devolver el valor como un número decimal
    return (float) $normalizado;
}


    /**
     * Calcula el puntaje de velocidad según sexo y tiempo.
     * $sexo: 0 = varón, 1 = mujer.
     * $tiempoCrudo: string que viene del formulario (ej. "11''50").
     */
    public function calcularPuntajeVelocidad(int $sexo, string $tiempoCrudo): int
    {
        $tiempo = $this->normalizarTiempo($tiempoCrudo);

        // Si no se pudo interpretar, 0 puntos
        if ($tiempo === null) {
            return 0;
        }

        // Redondeo a 2 decimales (centésimas)
        $t = round($tiempo, 2);

        if ($sexo === 0) {
            // VARONES
            if ($t <= 11.50) return 100;
            if ($t <= 12.00) return 90;
            if ($t <= 12.50) return 80;
            if ($t <= 13.00) return 70;
            if ($t <= 13.50) return 60;
            if ($t <= 14.00) return 50;
            if ($t <= 14.50) return 40;
            if ($t <= 15.00) return 30;
            if ($t <= 15.50) return 20;
            if ($t <= 16.00) return 10;
            return 0; // 16''01 en adelante
        } else {
            // MUJERES
            if ($t <= 14.00) return 100;
            if ($t <= 14.50) return 90;
            if ($t <= 15.00) return 80;
            if ($t <= 15.50) return 70;
            if ($t <= 16.00) return 60;
            if ($t <= 16.50) return 50;
            if ($t <= 17.00) return 40;
            if ($t <= 17.50) return 30;
            if ($t <= 18.00) return 20;
            if ($t <= 18.50) return 10;
            return 0; // 18''51 en adelante
        }
    }

/**
 * Calcula el puntaje de PRUEBA DE RESISTENCIA según sexo y tiempo.
 * $sexo: 0 = varón, 1 = mujer.
 * $tiempoCrudo: string que viene del formulario (ej. "12''15").
 */
public function calcularPuntajeResistencia(int $sexo, string $tiempoCrudo): int
{
    $tiempo = $this->normalizarTiempo($tiempoCrudo);

    if ($tiempo === null) {
        return 0;
    }

    $t = round($tiempo, 2);

    if ($sexo === 0) {
        // VARONES
        if ($t <= 11.30) return 100;
        if ($t <= 11.40) return 90;
        if ($t <= 11.50) return 80;
        if ($t <= 12.00) return 70;
        if ($t <= 12.10) return 60;
        if ($t <= 12.20) return 50;
        if ($t <= 12.30) return 40;
        if ($t <= 12.40) return 30;
        if ($t <= 12.50) return 20;
        if ($t <= 13.00) return 10;   // hasta 13''00
        return 0;                      // 13''01 en adelante
    } else {
        // MUJERES
        if ($t <= 12.00) return 100;
        if ($t <= 12.10) return 90;
        if ($t <= 12.20) return 80;
        if ($t <= 12.30) return 70;
        if ($t <= 12.40) return 60;
        if ($t <= 12.50) return 50;
        if ($t <= 13.00) return 40;
        if ($t <= 13.10) return 30;
        if ($t <= 13.20) return 20;
        if ($t <= 13.30) return 10;   // hasta 13''30
        return 0;                      // 13''31 en adelante
    }
}
/**
 * Calcula el puntaje de FLEXIONES EN BARRA según sexo y repeticiones.
 * $sexo: 0 = varón, 1 = mujer.
 * $repeticiones: número de repeticiones realizadas.
 */
public function calcularPuntajeBarra(int $sexo, int $repeticiones): int
{
    if ($sexo === 0) {
        // VARONES
        if ($repeticiones >= 12) return 100;
        if ($repeticiones === 11) return 90;
        if ($repeticiones === 10) return 80;
        if ($repeticiones === 9) return 70;
        if ($repeticiones === 8) return 60;
        if ($repeticiones === 7) return 50;
        if ($repeticiones === 6) return 40;
        if ($repeticiones === 5) return 30;
        if ($repeticiones === 4) return 20;
        if ($repeticiones === 3) return 10;
        return 0;  // Menos de 3 repeticiones
    } else {
        // MUJERES
        if ($repeticiones >= 8) return 100;
        if ($repeticiones === 7) return 90;
        if ($repeticiones === 6) return 80;
        if ($repeticiones === 5) return 70;
        if ($repeticiones === 4) return 60;
        if ($repeticiones === 3) return 50;
        if ($repeticiones === 2) return 30;
        if ($repeticiones === 1) return 10;
        return 0;  // Menos de 1 repetición
    }
}
/**
 * Calcula el puntaje de NATACIÓN según sexo y tiempo.
 * $sexo: 0 = varón, 1 = mujer.
 * $tiempo: tiempo en segundos (por ejemplo: 95 para 1 minuto 35 segundos).
 */
public function calcularPuntajeNatacion(int $sexo, float $tiempo): int
{
    if ($sexo === 0) {
        // VARONES
        if ($tiempo >= 100) return 100;
        if ($tiempo >= 90) return 90;
        if ($tiempo >= 80) return 80;
        if ($tiempo >= 70) return 70;
        if ($tiempo >= 60) return 60;
        if ($tiempo >= 50) return 50;
        if ($tiempo >= 40) return 40;
        if ($tiempo >= 30) return 30;
        if ($tiempo >= 20) return 20;
        if ($tiempo >= 10) return 10;
        return 0;
    } else {
        // MUJERES
        if ($tiempo >= 50) return 100;
        if ($tiempo >= 45) return 90;
        if ($tiempo >= 40) return 80;
        if ($tiempo >= 35) return 70;
        if ($tiempo >= 30) return 60;
        if ($tiempo >= 25) return 50;
        if ($tiempo >= 20) return 40;
        if ($tiempo >= 15) return 30;
        if ($tiempo >= 10) return 20;
        if ($tiempo >= 5) return 10;
        return 0;
    }
}
/**
 * Calcula el puntaje de CAPACIDAD ABDOMINAL según sexo y repeticiones.
 * $sexo: 0 = varón, 1 = mujer.
 * $repeticiones: número de repeticiones realizadas.
 */
public function calcularPuntajeAbdominales(int $sexo, int $repeticiones): int
{
    if ($sexo === 0) {
        // VARONES
        if ($repeticiones >= 80) return 100;
        if ($repeticiones >= 70) return 90;
        if ($repeticiones >= 60) return 80;
        if ($repeticiones >= 50) return 70;
        if ($repeticiones >= 40) return 60;
        if ($repeticiones >= 30) return 50;
        if ($repeticiones >= 25) return 40;
        if ($repeticiones >= 20) return 30;
        if ($repeticiones >= 15) return 20;
        if ($repeticiones >= 10) return 10;
        return 0;  // Menos de 10 repeticiones
    } else {
        // MUJERES
        if ($repeticiones >= 70) return 100;
        if ($repeticiones >= 60) return 90;
        if ($repeticiones >= 50) return 80;
        if ($repeticiones >= 40) return 70;
        if ($repeticiones >= 30) return 60;
        if ($repeticiones >= 20) return 50;
        if ($repeticiones >= 15) return 40;
        if ($repeticiones >= 10) return 30;
        if ($repeticiones >= 5) return 10;
        return 0;  // Menos de 5 repeticiones
    }
}
/**
 * Calcula el puntaje de FLEXIONES EN SUELO según sexo y repeticiones.
 * $sexo: 0 = varón, 1 = mujer.
 * $repeticiones: número de repeticiones realizadas.
 */
public function calcularPuntajeFlexiones(int $sexo, int $repeticiones): int
{
    if ($sexo === 0) {
        // VARONES
        if ($repeticiones >= 70) return 100;
        if ($repeticiones >= 65) return 90;
        if ($repeticiones >= 60) return 80;
        if ($repeticiones >= 55) return 70;
        if ($repeticiones >= 50) return 60;
        if ($repeticiones >= 45) return 50;
        if ($repeticiones >= 40) return 40;
        if ($repeticiones >= 30) return 30;
        if ($repeticiones >= 20) return 20;
        if ($repeticiones >= 10) return 10;
        return 0;  // Menos de 10 repeticiones
    } else {
        // MUJERES
        if ($repeticiones >= 50) return 100;
        if ($repeticiones >= 43) return 90;
        if ($repeticiones >= 35) return 80;
        if ($repeticiones >= 28) return 70;
        if ($repeticiones >= 20) return 60;
        if ($repeticiones >= 13) return 50;
        if ($repeticiones >= 11) return 40;
        if ($repeticiones >= 9) return 30;
        if ($repeticiones >= 7) return 20;
        if ($repeticiones >= 5) return 10;
        return 0;  // Menos de 5 repeticiones
    }
}
public function guardarEvaluacion(Request $request, $id_postulante)
{
    $postulante = Postulante::findOrFail($id_postulante);

    // Normalizar los tiempos antes de guardar
    $tiempoVelocidad = $this->normalizarTiempo($request->velocidad);
    $tiempoResistencia = $this->normalizarTiempo($request->prueba_resis);
    $tiempoNatacion = $this->normalizarTiempo($request->natacion);

    // Cálculos de notas
    $notaVelocidad = $this->calcularPuntajeVelocidad($postulante->sexo, $tiempoVelocidad);
    $notaResistencia = $this->calcularPuntajeResistencia($postulante->sexo, $tiempoResistencia);
    $notaBarra = $this->calcularPuntajeBarra($postulante->sexo, $request->barra);
    $notaNatacion = $this->calcularPuntajeNatacion($postulante->sexo, $tiempoNatacion);
    $notaAbdominales = $this->calcularPuntajeAbdominales($postulante->sexo, $request->cap_abdominal);
    $notaFlexiones = $this->calcularPuntajeFlexiones($postulante->sexo, $request->flexiones);

    // Guardamos PRUEBA
    $prueba = new Prueba();
    $prueba->id_postulante = $postulante->id_postulante;
    $prueba->observacion = $request->observacion;
    $prueba->nota_total = null;
    $prueba->conclusion = null;
    $prueba->ruta_pdf = '';
    $prueba->save();

    // Guardamos EVALUACION
    $evaluacion = new Evaluacion();
    $evaluacion->id_prueba = $prueba->id_prueba;
    $evaluacion->velocidad = $tiempoVelocidad;
    $evaluacion->nota_velocidad = $notaVelocidad;
    $evaluacion->prueba_resis = $tiempoResistencia;
    $evaluacion->nota_prueba = $notaResistencia;
    $evaluacion->barra = $request->barra;
    $evaluacion->nota_barra = $notaBarra;
    $evaluacion->natacion = $tiempoNatacion;
    $evaluacion->nota_natacion = $notaNatacion;
    $evaluacion->cap_abdominal = $request->cap_abdominal;
    $evaluacion->nota_cap = $notaAbdominales;
    $evaluacion->flexiones = $request->flexiones;
    $evaluacion->nota_flexiones = $notaFlexiones;
    $evaluacion->save();

    // Nota total y conclusión
    $notas = [
        $notaVelocidad, $notaResistencia, $notaBarra, $notaNatacion,
        $notaAbdominales, $notaFlexiones
    ];

    $promedio = array_sum($notas) / count($notas);
    $algunMenor51 = false;
foreach ($notas as $nota) {
    if ($nota < 51) {
        $algunMenor51 = true;
        break;
    }
}

// Determinar conclusión
if ($algunMenor51 || $promedio < 51) {
    $conclusion = 'REPROBADO';
} else {
    $conclusion = 'APROBADO';
}

    $prueba->nota_total = $promedio;
    $prueba->conclusion = $conclusion;
    $prueba->save();

    // ===============================
    //  OBTENER EVALUADOR (LOGUEADO)
    // ===============================
    $evaluadorId = session('id_usu');
    $evaluador = Usuario::find($evaluadorId);

    // ===============================
    //   GENERAR Y GUARDAR EL PDF
    // ===============================
    $mpdf = new \Mpdf\Mpdf([
        'tempDir' => 'C:/mpdf_temp'
    ]);

    // Pasamos TODOS los datos incluyendo el evaluador
    $html = view('pdf_evaluacion', [
    'postulante'  => $postulante,
    'prueba'      => $prueba,
    'evaluacion'  => $evaluacion,
    'evaluador'   => $evaluador,
    'debug'       => $evaluador ? 'SI TIENE' : 'NO TIENE'
])->render();


    $mpdf->WriteHTML($html);

    // Nombre del archivo
    $pdfFileName = 'evaluacion_postulante_' . $postulante->id_postulante . '_' . time() . '.pdf';

    // Ruta donde se guardará
    $savePath = storage_path('app/pdfs/' . $pdfFileName);

    // Guardarlo en disco
    $mpdf->Output($savePath, \Mpdf\Output\Destination::FILE);

    // Guardar la ruta en BD
    $prueba->ruta_pdf = 'pdfs/' . $pdfFileName;
    $prueba->save();

    return redirect()
        ->route('dash.registrador')
        ->with('success', 'Evaluación guardada y PDF generado correctamente.');
}


public function testPDF()
{
    // Directorio donde se guardará el PDF
    $storagePath = storage_path('app/pdfs');

    // Asegúrate de que la carpeta 'pdfs' exista, si no, crea la carpeta
    if (!file_exists($storagePath)) {
        mkdir($storagePath, 0777, true); // Crear la carpeta si no existe
    }

    // Directorio temporal donde mPDF guardará los archivos temporales
    $tempDir = storage_path('mpdf_temp');

    // Asegúrate de que la carpeta temporal también exista
    if (!file_exists($tempDir)) {
        mkdir($tempDir, 0777, true); // Crear la carpeta si no existe
    }

    // Crear una nueva instancia de mPDF y pasar el directorio temporal
    $mpdf = new Mpdf([
        'tempDir' => $tempDir // Establecemos el directorio temporal para mPDF
    ]);

    // Cargar la vista Blade como HTML
    $html = view('pdf_evaluacion', ['message' => 'Generando PDF de prueba'])->render();

    // Escribir el HTML en el documento
    $mpdf->WriteHTML($html);

    // Guardar el archivo PDF en la carpeta especificada
    $pdfFileName = 'evaluacion_postulante_' . uniqid() . '.pdf';
    $pdfFilePath = $storagePath . '/' . $pdfFileName;
    $mpdf->Output($pdfFilePath, 'F'); // 'F' guarda el archivo en el servidor

    // Retornar la ruta del archivo guardado
    return response()->json([
        'message' => 'PDF generado y guardado exitosamente.',
        'pdf_file' => $pdfFilePath
    ]);
}


public function verPDF($fileName)
{
    $pdfPath = storage_path('app/pdfs/' . $fileName);

    if (file_exists($pdfPath)) {
        return response()->file($pdfPath);
    } else {
        return abort(404, 'El archivo PDF no se encuentra.');
    }
}


public function descargarPDF($fileName)
{
    $path = storage_path('app/pdfs/' . $fileName);

    if (!file_exists($path)) {
        return abort(404, 'El PDF no existe.');
    }

    return response()->download($path);
}

public function perfil()
{
    $id = session('id_usu');

    if (!$id) {
        return redirect('/inicio')->with('error', 'Debes iniciar sesión.');
    }

    $usuario = \App\Models\Usuario::findOrFail($id);

    return view('perfil', compact('usuario'));
}

public function cambiarPassword(Request $request)
{
    $request->validate([
        'password_actual' => ['required'],
        'password_nueva' => [
            'required',
            'confirmed',
            'min:8',
            'regex:/[A-Z]/',      // al menos 1 mayúscula
            'regex:/[a-z]/',      // al menos 1 minúscula
            'regex:/[0-9]/',      // al menos 1 número
            'regex:/[^A-Za-z0-9]/' // al menos 1 símbolo
        ],
    ], [
        'password_nueva.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'password_nueva.regex' => 'La contraseña debe incluir mayúsculas, minúsculas, números y símbolos.',
        'password_nueva.confirmed' => 'Las contraseñas no coinciden.',
    ]);

    $usuario = Usuario::find(session('id_usu'));

    // Verificar contraseña actual
    if (!password_verify($request->password_actual, $usuario->password)) {
        return back()->with('error', 'La contraseña actual es incorrecta.');
    }

    // Guardar la nueva contraseña
    $usuario->password = bcrypt($request->password_nueva);
    $usuario->save();

    return back()->with('success', 'Tu contraseña fue actualizada correctamente.');
}

}
