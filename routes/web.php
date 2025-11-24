<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistradorController;
use App\Http\Middleware\RegistradorMiddleware;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminMiddleware;

// LOGIN
Route::get('/', fn() => redirect('/inicio'));
Route::get('/inicio', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/inicio', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// PANEL ADMIN
Route::get('/dash_admin', [AdminController::class, 'index'])
    ->middleware('admin')
    ->name('dash.admin');

// PANEL REGISTRADOR
Route::get('/dash_registrador', [RegistradorController::class, 'index'])
    ->middleware('registrador')
    ->name('dash.registrador');

// 🚀 FORMULARIO VISIBLE PARA ADMIN Y REGISTRADOR
Route::get('/postulantes/{id_postulante}/formulario',
    [RegistradorController::class, 'formulario'])
    ->middleware('usuario')  // <-- ambos pueden entrar
    ->name('postulante.form');

// 🚀 GUARDAR EVALUACIÓN PERMITIDO PARA AMBOS
Route::post('/postulantes/{id_postulante}/evaluacion',
    [RegistradorController::class, 'guardarEvaluacion'])
    ->middleware('usuario')  // <-- ambos pueden crear
    ->name('postulante.evaluacion');

// PERFIL
Route::get('/perfil', [RegistradorController::class, 'perfil'])
    ->middleware('usuario')
    ->name('perfil.ver');

Route::post('/perfil/password', [RegistradorController::class, 'cambiarPassword'])
    ->middleware('usuario')
    ->name('perfil.password');

// PDF
Route::get('/pdfs/{fileName}', [RegistradorController::class, 'verPDF'])->name('pdf.ver');
Route::get('/pdfs/{fileName}/download', [RegistradorController::class, 'descargarPDF'])->name('pdf.descargar');

// ADMIN: editar/eliminar evaluaciones
Route::get('/admin/postulantes', [AdminController::class, 'index'])
    ->middleware('admin')
    ->name('admin.postulantes');

Route::get('/admin/evaluacion/{id_prueba}/editar', [AdminController::class, 'editar'])
    ->middleware('admin')
    ->name('admin.evaluacion.editar');

Route::post('/admin/evaluacion/{id_prueba}/actualizar', [AdminController::class, 'actualizar'])
    ->middleware('admin')
    ->name('admin.evaluacion.actualizar');

Route::delete('/admin/evaluacion/{id_prueba}', [AdminController::class, 'eliminar'])
    ->middleware('admin')
    ->name('admin.evaluacion.eliminar');



// LISTA DE USUARIOS
Route::get('/admin/usuarios', [AdminController::class, 'usuarios'])
    ->middleware(AdminMiddleware::class)
    ->name('admin.usuarios');

// FORM CREAR USUARIO
Route::get('/admin/usuarios/crear', [AdminController::class, 'crearUsuario'])
    ->name('admin.usuarios.crear');

Route::post('/admin/usuarios/guardar', [AdminController::class, 'guardarUsuario'])
    ->name('admin.usuarios.guardar');


// FORM EDITAR USUARIO
Route::get('/admin/usuarios/{id}/editar', [AdminController::class, 'editarUsuario'])
    ->middleware(AdminMiddleware::class)
    ->name('admin.usuarios.editar');

// ACTUALIZAR USUARIO
Route::post('/admin/usuarios/{id}/actualizar', [AdminController::class, 'actualizarUsuario'])
    ->middleware(AdminMiddleware::class)
    ->name('admin.usuarios.actualizar');

// ELIMINAR USUARIO
Route::delete('/admin/usuarios/{id}/eliminar', [AdminController::class, 'eliminarUsuario'])
    ->middleware(AdminMiddleware::class)
    ->name('admin.usuarios.eliminar');

// EDITAR POSTULANTE (registrador)
// === EDITAR POSTULANTE (registrador) ===
Route::get('/pruebas/{id_prueba}/editar', 
    [RegistradorController::class, 'editar'])
    ->name('postulante.editar');

Route::post('/postulantes/{id_prueba}/actualizar', 
    [RegistradorController::class, 'actualizar'])
    ->middleware(RegistradorMiddleware::class)
    ->name('postulante.actualizar');
