<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Postulante;
use App\Models\Evaluacion;
use App\Models\Prueba;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Mpdf\Mpdf;

class AdminController extends Controller
{
    // ============================================
    // PANEL PRINCIPAL DEL ADMIN
    // ============================================
    public function index(Request $request)
{
    $adminId = session('id_usu');
    $admin = Usuario::findOrFail($adminId);

    // === FILTROS ===
    $query = Postulante::query();

    if ($ci = $request->ci) {
        $query->where('ci', 'like', "%$ci%");
    }
    if ($apPat = $request->apellido_paterno) {
        $query->where('apellido_paterno', 'like', "%$apPat%");
    }
    if ($apMat = $request->apellido_materno) {
        $query->where('apellido_materno', 'like', "%$apMat%");
    }
    if ($inst = $request->instituto) {
        $query->where('instituto', $inst);
    }

    $query->orderBy('apellido_paterno')
          ->orderBy('apellido_materno')
          ->orderBy('nombres');

    // SOLO ATENDIDOS
    $postulantesAtendidos = (clone $query)
        ->whereHas('pruebas')
        ->with('pruebas')
        ->get();

    return view('dash_admin', compact(
        'admin',
        'postulantesAtendidos'
    ));
}


    // ============================================
    // FORMULARIO PARA EDITAR UNA EVALUACIÓN
    // ============================================
    public function editar($id_prueba)
    {
        $prueba = Prueba::findOrFail($id_prueba);
        $postulante = $prueba->postulante;
        $evaluacion = $prueba->evaluacion;

        return view('admin_editar_evaluacion', compact(
            'prueba',
            'postulante',
            'evaluacion'
        ));
    }

    // ============================================
    // ACTUALIZAR EVALUACIÓN + REGENERAR PDF
    // ============================================
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
        // GENERAR NUEVO PDF DEL ADMIN (evaluador = admin logueado)
        // ======================================================
        $evaluador = Usuario::find(session('id_usu'));

        $mpdf = new Mpdf([
            'tempDir' => 'C:/mpdf_temp'
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

        return redirect()->route('dash.admin')->with('success', 'Evaluación actualizada correctamente.');
    }

    // ============================================
    // ELIMINAR UNA EVALUACIÓN (prueba + evaluación + pdf)
    // ============================================
    public function eliminar($id_prueba)
    {
        $prueba = Prueba::findOrFail($id_prueba);

        // Borrar PDF
        if ($prueba->ruta_pdf) {
            $path = storage_path('app/' . $prueba->ruta_pdf);
            if (file_exists($path)) unlink($path);
        }

        // Borrar evaluación
        $prueba->evaluacion()->delete();

        // Borrar prueba
        $prueba->delete();

        return redirect()->route('dash.admin')->with('success', 'Evaluación eliminada correctamente.');
    }

public function usuarios() {
    $admin = Usuario::find(session('id_usu'));
    $usuarios = Usuario::all();

    return view('admin.admin_usuarios', compact('admin', 'usuarios'));
}

public function crearUsuario() {
    $admin = Usuario::find(session('id_usu'));
    return view('admin.admin_usuarios_crear', compact('admin'));
}

public function guardarUsuario(Request $request) {
    $request->validate([
        'usuario' => 'required|string|unique:usuario,usuario',
        'nombres' => 'required|string',
        'apellido_pat' => 'required|string',
        'apellido_mat' => 'required|string',
        'password' => [
    'required',
    'string',
    'min:8',
    'regex:/[A-Z]/',     // mayúscula
    'regex:/[a-z]/',     // minúscula
    'regex:/[0-9]/',     // número
    'regex:/[\W]/',      // símbolo
],
        'cargo' => 'required|integer'
    ]);

    Usuario::create([
    'usuario' => $request->usuario,
    'nombres' => $request->nombres,
    'apellido_pat' => $request->apellido_pat,
    'apellido_mat' => $request->apellido_mat,
    'password' => Hash::make($request->password),
    'cargo' => $request->cargo,
]);


    return redirect()->route('admin.usuarios')->with('success', 'Usuario creado correctamente.');
}

public function editarUsuario($id) {
    $admin = Usuario::find(session('id_usu'));
    $usuario = Usuario::findOrFail($id);

    return view('admin.admin_usuarios_editar', compact('admin', 'usuario'));
}

public function actualizarUsuario(Request $request, $id) {
    $usuario = Usuario::findOrFail($id);

    $request->validate([
        'nombres' => 'required|string',
        'apellido_pat' => 'required|string',
        'apellido_mat' => 'required|string',
        'cargo' => 'required|integer'
    ]);

    $usuario->nombres = $request->nombres;
    $usuario->apellido_pat = $request->apellido_pat;
    $usuario->apellido_mat = $request->apellido_mat;
    $usuario->cargo = $request->cargo;

    if ($request->filled('password')) {
        $usuario->password = Hash::make($request->password);
    }

    $usuario->save();

    return redirect()->route('admin.usuarios')->with('success', 'Usuario actualizado.');
}

public function eliminarUsuario($id) {
    Usuario::destroy($id);
    return redirect()->route('admin.usuarios')->with('success', 'Usuario eliminado.');
}

}
