<?php

namespace App\Http\Controllers;
use App\Models\Usuario;
use App\Models\Postulante;
use App\Models\Evaluacion;
use App\Models\Prueba;
use Illuminate\Http\Request;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Log;
class RegistradorController extends Controller
{
    public function index(Request $request, $id_postulante = null)
{
    // Obtener el registrador (usuario)
    $registradorId = session('id_usu');
    $registrador = Usuario::findOrFail($registradorId);
    

    // Obtener postulantes atendidos y no atendidos
    $postulantesAtendidos = Postulante::whereHas('pruebas', function($query) {
        $query->whereNotNull('nota_total')->whereNotNull('ruta_pdf');
    })->get();

    $postulantesNoAtendidos = Postulante::whereDoesntHave('pruebas', function($query) {
        $query->whereNotNull('nota_total')->whereNotNull('ruta_pdf');
    })->get();

    // Obtener id_prueba para un postulante específico, si se pasa el id_postulante
    $id_prueba = null;
    if ($id_postulante) {
        $postulante = Postulante::find($id_postulante);
        if ($postulante && $postulante->prueba) {
            $id_prueba = $postulante->prueba->id_prueba;  // Obtener id_prueba relacionado
        }
    }

    // Pasamos los datos a la vista
    return view('dash_registrador', compact(
        'registrador', 
        'postulantesAtendidos', 
        'postulantesNoAtendidos', 
        'id_prueba'
    ));
}

public function aplicarFiltros(Request $request)
{
    // Realizamos la consulta para obtener los postulantes filtrados
    $query = Postulante::query();

    // Aplicar los filtros
    if ($request->filled('ci')) {
        $query->where('ci', 'like', "%{$request->ci}%");
    }

    if ($request->filled('apellido_paterno')) {
        $query->where('apellido_paterno', 'like', "%{$request->apellido_paterno}%");
    }

    if ($request->filled('apellido_materno')) {
        $query->where('apellido_materno', 'like', "%{$request->apellido_materno}%");
    }

    if ($request->filled('instituto')) {
        $query->where('instituto', $request->instituto);
    }

    // Obtener los postulantes atendidos y no atendidos
    $postulantesAtendidos = $query->whereHas('pruebas', function ($query) {
        $query->whereNotNull('nota_total')->whereNotNull('ruta_pdf');
    })->get();

    $postulantesNoAtendidos = $query->whereDoesntHave('pruebas', function ($query) {
        $query->whereNotNull('nota_total')->whereNotNull('ruta_pdf');
    })->get();

    // Retornar la vista parcial con los postulantes filtrados
    return view('partials.postulantes', compact('postulantesAtendidos', 'postulantesNoAtendidos'));
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
public function editar($id_prueba)
    {
        $prueba = Prueba::findOrFail($id_prueba);
        $postulante = $prueba->postulante;
        $evaluacion = $prueba->evaluacion;

        return view('registrador_editar_postulante', compact(
            'prueba',
            'postulante',
            'evaluacion'
        ));
    }
public function actualizar(Request $request, $id_prueba)
    {
        $prueba = Prueba::findOrFail($id_prueba);
        $postulante = $prueba->postulante;
        $evaluacion = $prueba->evaluacion;

        // === Reglas de validación ===
        $request->validate([
            'velocidad' => 'required',
            'prueba_resis' => 'required',
            'barra' => 'required|numeric',
            'natacion' => 'required',
            'cap_abdominal' => 'required|numeric',
            'flexiones' => 'required|numeric',
            'observacion' => 'nullable|string|max:400',
        ]);

        // === Usamos los métodos del RegistradorController ===
        $calc = app(RegistradorController::class);

        $ti_vel  = $calc->normalizarTiempo($request->velocidad);
        $ti_res  = $calc->normalizarTiempo($request->prueba_resis);
        $ti_nat  = $calc->normalizarTiempo($request->natacion);

        $notaVel = $calc->calcularPuntajeVelocidad($postulante->sexo, $ti_vel);
        $notaRes = $calc->calcularPuntajeResistencia($postulante->sexo, $ti_res);
        $notaBar = $calc->calcularPuntajeBarra($postulante->sexo, $request->barra);
        $notaNat = $calc->calcularPuntajeNatacion($postulante->sexo, $ti_nat);
        $notaAbd = $calc->calcularPuntajeAbdominales($postulante->sexo, $request->cap_abdominal);
        $notaFlex= $calc->calcularPuntajeFlexiones($postulante->sexo, $request->flexiones);

        // =============================
        //   ACTUALIZAR LA EVALUACIÓN
        // =============================
        $evaluacion->update([
            'velocidad' => $ti_vel,
            'nota_velocidad' => $notaVel,

            'prueba_resis' => $ti_res,
            'nota_prueba' => $notaRes,

            'barra' => $request->barra,
            'nota_barra' => $notaBar,

            'natacion' => $ti_nat,
            'nota_natacion' => $notaNat,

            'cap_abdominal' => $request->cap_abdominal,
            'nota_cap' => $notaAbd,

            'flexiones' => $request->flexiones,
            'nota_flexiones' => $notaFlex,
        ]);

        // Nota total
        $prom = ($notaVel + $notaRes + $notaBar + $notaNat + $notaAbd + $notaFlex) / 6;
        $prueba->nota_total = $prom;
        $prueba->conclusion = $prom >= 51 ? 'APROBADO' : 'REPROBADO';
        $prueba->observacion = $request->observacion;
        $prueba->save();

        // ======================================================
        // ======================================================
        $evaluador = Usuario::find(session('id_usu'));

        $mpdf = new Mpdf([
            'tempDir' => '/var/www/html/tmp/mpdf'
        ]);

        $html = view('pdf_evaluacion', compact(
            'postulante',
            'prueba',
            'evaluacion',
            'evaluador'
        ))->render();

        $mpdf->WriteHTML($html);

        $pdfName = 'evaluacion_postulante_' . $postulante->id_postulante . '_' . time() . '.pdf';
        $savePath = storage_path('app/pdfs/' . $pdfName);

        $mpdf->Output($savePath, \Mpdf\Output\Destination::FILE);

        $prueba->ruta_pdf = 'pdfs/' . $pdfName;
        $prueba->save();

        return redirect()->route('dash.registrador')->with('success', 'Evaluación actualizada correctamente.');
    }

public function guardarVelocidad(Request $request, $id_postulante)
{
    $request->validate([
        'velocidad' => 'required'
    ]);

    $postulante = Postulante::findOrFail($id_postulante);

    // Obtener PRUEBA + EVALUACION, creando si es necesario
    [$prueba, $eva] = $this->obtenerPruebaYEvaluacion($id_postulante);

    // Calcular nota
    $tiempo = $this->normalizarTiempo($request->velocidad);
    $nota = $this->calcularPuntajeVelocidad($postulante->sexo, $tiempo);

    // Guardar
    $eva->velocidad = $tiempo;
    $eva->nota_velocidad = $nota;
    $eva->save();

    return back()->with('success', 'Velocidad registrada.');
}

public function guardarResistencia(Request $request, $id_postulante)
{
    $request->validate([
        'prueba_resis' => 'required'
    ]);

    $postulante = Postulante::findOrFail($id_postulante);

    [$prueba, $eva] = $this->obtenerPruebaYEvaluacion($id_postulante);

    $tiempo = $this->normalizarTiempo($request->prueba_resis);
    $nota = $this->calcularPuntajeResistencia($postulante->sexo, $tiempo);

    $eva->prueba_resis = $tiempo;
    $eva->nota_prueba = $nota;
    $eva->save();

    return back()->with('success', 'Resistencia registrada.');
}

public function guardarBarra(Request $request, $id_postulante)
{
    $request->validate([
        'barra' => 'required|numeric|min:0'
    ]);

    $postulante = Postulante::findOrFail($id_postulante);

    [$prueba, $eva] = $this->obtenerPruebaYEvaluacion($id_postulante);

    $nota = $this->calcularPuntajeBarra($postulante->sexo, $request->barra);

    $eva->barra = $request->barra;
    $eva->nota_barra = $nota;
    $eva->save();

    return back()->with('success', 'Barra registrada.');
}


public function guardarNatacion(Request $request, $id_postulante)
{
    $request->validate([
        'natacion' => 'required'
    ]);

    $postulante = Postulante::findOrFail($id_postulante);

    // Obtener o crear prueba + evaluación
    [$prueba, $eva] = $this->obtenerPruebaYEvaluacion($id_postulante);

    // Normalizar tiempo
    $tiempo = $this->normalizarTiempo($request->natacion);

    // Calcular nota correcta
    $nota = $this->calcularPuntajeNatacion($postulante->sexo, $tiempo);

    // Guardar
    $eva->natacion = $tiempo;
    $eva->nota_natacion = $nota;
    $eva->save();

    return redirect()
        ->route('dash.registrador')
        ->with('success', 'Natación registrada correctamente.');
}

public function guardarAbdominal(Request $request, $id_postulante)
{
    $request->validate([
        'cap_abdominal' => 'required|numeric|min:0'
    ]);

    $postulante = Postulante::findOrFail($id_postulante);

    // Obtener o crear prueba + evaluación
    [$prueba, $eva] = $this->obtenerPruebaYEvaluacion($id_postulante);

    // Calcular nota
    $nota = $this->calcularPuntajeAbdominales($postulante->sexo, $request->cap_abdominal);

    // Guardar
    $eva->cap_abdominal = $request->cap_abdominal;
    $eva->nota_cap = $nota;
    $eva->save();

    return redirect()
        ->route('dash.registrador')
        ->with('success', 'Abdominales registrados correctamente.');
}

public function guardarFlexiones(Request $request, $id_postulante)
{
    $request->validate([
        'flexiones' => 'required|numeric|min:0'
    ]);

    $postulante = Postulante::findOrFail($id_postulante);

    // Obtener o crear prueba + evaluación
    [$prueba, $eva] = $this->obtenerPruebaYEvaluacion($id_postulante);

    // Calcular nota real
    $notaFlex = $this->calcularPuntajeFlexiones($postulante->sexo, $request->flexiones);

    // Guardar datos
    $eva->flexiones = $request->flexiones;
    $eva->nota_flexiones = $notaFlex;
    $eva->save();

    return redirect()
        ->route('dash.registrador')
        ->with('success', 'Flexiones registradas correctamente.');
}

public function obtenerEvaluacion($id_postulante)
{
    // Obtener la última prueba del postulante
    $postulante = Postulante::findOrFail($id_postulante);
    $ultimaPrueba = $postulante->pruebas()->latest()->first();

    if (!$ultimaPrueba) {
        return response()->json(['error' => 'No hay evaluación disponible para este postulante'], 404);
    }

    // Calcular el promedio de las notas
    $notas = [
        $ultimaPrueba->nota_velocidad,
        $ultimaPrueba->nota_prueba,
        $ultimaPrueba->nota_barra,
        $ultimaPrueba->nota_natacion,
        $ultimaPrueba->nota_cap,
        $ultimaPrueba->nota_flexiones
    ];

    $promedio = array_sum($notas) / count($notas);
    $conclusion = $promedio >= 51 ? 'APROBADO' : 'REPROBADO';

    // Retornar los datos en formato JSON
    return response()->json([
        'promedio' => $promedio,
        'conclusion' => $conclusion,
        'id_postulante' => $postulante->id_postulante,
        'nota_total' => $ultimaPrueba->nota_total,
        'evaluacion' => $ultimaPrueba
    ]);
}
// En el controlador RegistradorController
// En RegistradorController
public function obtenerIdPrueba($id_postulante)
{
    // Buscar el postulante
    $postulante = Postulante::find($id_postulante);

    if (!$postulante) {
        return response()->json(['error' => 'Postulante no encontrado'], 404);
    }

    // Obtener el id_prueba relacionado con el postulante
    $prueba = $postulante->prueba; // Esto obtiene la prueba asociada al postulante

    if ($prueba) {
        $id_prueba = $prueba->id_prueba;
        $evaluacion = $prueba->evaluacion;  // Obtener la evaluación asociada a esta prueba
    } else {
        $id_prueba = null;
        $evaluacion = null;
    }

    // Calcular el promedio y la conclusión
    $promedio = null;
    $conclusion = null;

    if ($evaluacion) {
        // Obtener las notas de la evaluación
        $notas = [
            $evaluacion->nota_velocidad,
            $evaluacion->nota_prueba,
            $evaluacion->nota_barra,
            $evaluacion->nota_cap,
            $evaluacion->nota_flexiones,
            $evaluacion->nota_natacion,
        ];

        // Filtrar las notas no nulas
        $notas_validas = array_filter($notas, function ($nota) {
            return $nota !== null;
        });

        // Calcular el promedio
        if (count($notas_validas) > 0) {
            $promedio = array_sum($notas_validas) / count($notas_validas);
        }

        // Determinar la conclusión
        $conclusion = $promedio >= 51 ? 'APROBADO' : 'REPROBADO';
    }

    return response()->json([
        'id_postulante' => $postulante->id_postulante,
        'id_prueba' => $id_prueba,
        'evaluacion' => $evaluacion,
        'promedio' => $promedio,
        'conclusion' => $conclusion,
    ]);
}
public function actualizarPrueba(Request $request, $id_postulante)
{
    try {
        // Validamos que los datos enviados estén presentes
        $request->validate([
            'nota_total' => 'required|numeric',
            'conclusion' => 'required|string',
            'observacion' => 'nullable|string',
        ]);

        // Buscar el postulante
        $postulante = Postulante::findOrFail($id_postulante);

        // Obtener la prueba relacionada con el postulante
        $prueba = $postulante->prueba;

        if (!$prueba) {
            return response()->json(['error' => 'Prueba no encontrada'], 404);
        }

        // Actualizamos la prueba con los datos nuevos
        $prueba->nota_total = $request->input('nota_total');
        $prueba->conclusion = $request->input('conclusion');
        $prueba->observacion = $request->input('observacion');

        // Guardar los cambios
        $prueba->save();

        // Llamar a la función para generar el PDF después de guardar
        $this->generarPDF($id_postulante);

        return response()->json([
            'message' => 'Prueba actualizada correctamente',
            'prueba' => $prueba,
        ]);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json(['error' => 'Postulante o prueba no encontrada', 'message' => $e->getMessage()], 404);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Error interno del servidor', 'message' => $e->getMessage()], 500);
    }
}
private function obtenerPruebaYEvaluacion($id_postulante) { 
    // Buscar prueba existente 
    $prueba = Prueba::where('id_postulante', $id_postulante) ->latest('id_prueba') ->first(); 
    if (!$prueba) { // → Primera prueba → crear PRUEBA y EVALUACION vacía 
    $prueba = Prueba::create([ 'id_postulante' => $id_postulante, 'observacion' => null, 'nota_total' => null, 'conclusion' => null, 'ruta_pdf' => '' ]); 
    $evaluacion = Evaluacion::create([ 'id_prueba' => $prueba->id_prueba ]); } else { // → Ya existe evaluación
    $evaluacion = Evaluacion::firstOrCreate([ 'id_prueba' => $prueba->id_prueba ]); } return [$prueba, $evaluacion]; 
}
public function generarPDF($id_postulante)
{
    // Obtener el postulante y la prueba
    $postulante = Postulante::findOrFail($id_postulante);
    $prueba = $postulante->prueba;

    // Obtener la evaluación relacionada
    $evaluacion = $postulante->evaluacion;

    // Asegúrate de tener el evaluador
    $evaluadorId = session('id_usu');
    $evaluador = Usuario::find($evaluadorId);

    // Crear el PDF usando DomPDF (o Mpdf si prefieres)
    $mpdf = new Mpdf([
        'tempDir' => '/var/www/html/tmp/mpdf'// Dirección temporal para el archivo mPDF
    ]);

    // Generar el HTML con la vista 'pdf_evaluacion'
    $html = view('pdf_evaluacion', compact(
        'postulante',
        'prueba',
        'evaluacion',
        'evaluador'
    ))->render();

    // Escribir el contenido HTML en el archivo PDF
    $mpdf->WriteHTML($html);

    // Nombre del archivo PDF
    $pdfName = 'evaluacion_postulante_' . $postulante->id_postulante . '_' . time() . '.pdf';

    // Ruta donde se guardará el PDF en storage
    $savePath = storage_path('app/pdfs/' . $pdfName);

    // Guardar el PDF en el sistema de archivos
    $mpdf->Output($savePath, \Mpdf\Output\Destination::FILE);

    // Guardar la ruta del archivo PDF en la columna 'ruta_pdf' de la tabla 'prueba'
    $prueba->ruta_pdf = 'pdfs/' . $pdfName;
    $prueba->save();

    // Devolver la respuesta
    return response()->json([
        'message' => 'PDF generado y guardado correctamente.',
        'ruta_pdf' => 'pdfs/' . $pdfName
    ]);
}

// En tu controlador RegistradorController
public function filtrarPostulantes(Request $request)
{
    // Filtros recibidos
    $query = Postulante::query();

    // Filtrar por CI
    if ($request->filled('ci')) {
        $query->where('ci', 'like', "%" . $request->ci . "%");
    }

    // Filtrar por apellido paterno
    if ($request->filled('apellido_paterno')) {
        $query->where('apellido_paterno', 'like', "%" . $request->apellido_paterno . "%");
    }

    // Filtrar por apellido materno
    if ($request->filled('apellido_materno')) {
        $query->where('apellido_materno', 'like', "%" . $request->apellido_materno . "%");
    }

    // Filtrar por instituto
    if ($request->filled('instituto')) {
        $query->where('instituto', $request->instituto);
    }

    // Obtener postulantes NO atendidos
    $postulantesNoAtendidos = $query->whereDoesntHave('pruebas', function($query) {
        $query->whereNotNull('nota_total')->whereNotNull('ruta_pdf');
    })->get();

    // Obtener postulantes ATENDIDOS
    $postulantesAtendidos = $query->whereHas('pruebas', function($query) {
        $query->whereNotNull('nota_total')->whereNotNull('ruta_pdf');
    })->get();

    // Retornar los datos en formato JSON para actualizar la tabla sin recargar
    return response()->json([
        'postulantesNoAtendidos' => $postulantesNoAtendidos,
        'postulantesAtendidos' => $postulantesAtendidos,
    ]);
}

}