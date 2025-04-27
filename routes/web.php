<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AppointmentController;

Route::get('/', function () {
    return view('index');
});

Route::get('/sobre-nosotros', function () {
    return view('about');
})->name('about');

Route::get('/login', function () {
    return view('login');
});

// Rutas de registro y verificación
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
Route::get('/verify', [RegisterController::class, 'showVerificationForm'])->name('verification.show');
Route::post('/verify', [RegisterController::class, 'verify'])->name('verification.verify');
Route::get('/verify/resend', [RegisterController::class, 'resendCode'])->name('verification.resend');

// Rutas de perfil (protegidas con autenticación)
Route::middleware(['auth'])->group(function () {
    Route::get('/perfil', [ProfileController::class, 'show'])->name('profile');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/settings', function () {
    return view('settings');
});

Route::get('/logout', function () {
    return view('logout');
});

// Ruta para procesar el cierre de sesión
Route::post('/logout', 'App\Http\Controllers\Auth\LogoutController@logout')->name('logout');

// Rutas de administración
Route::get('/admin', function () {
    return redirect('/admin/tablero');
});

Route::get('/admin/tablero', 'App\Http\Controllers\Admin\DashboardController@index');
Route::get('/admin/tablero/actualizar', 'App\Http\Controllers\Admin\DashboardController@refresh');

// Ruta de perfil de administrador
Route::get('/admin/perfil', function () {
    return view('admin.profile');
});

// Rutas de historial de administrador
Route::get('/admin/historial-citas', function () {
    return view('admin.history-appointments');
});

Route::get('/admin/historial-facturas', function () {
    return view('admin.history-bills');
});

// Ruta de generación de reportes de administrador
Route::get('/admin/reportes/generar', function () {
    return view('admin.reports-generate');
});

// Ruta de generación de facturas de administrador
Route::get('/admin/generar-facturas', function () {
    return view('admin.generate-bills');
});

// Ruta para gestión de doctores
Route::get('/admin/doctores', function () {
    return view('admin.doctores');
});

// Rutas para el tablero
Route::get('/admin/tablero/citas-todas', function () {
    return view('admin.tablero-citas-todas');
});

Route::get('/admin/tablero/seguimiento-todos', function () {
    return view('admin.tablero-seguimiento-todos');
});

// Rutas para perfil
Route::get('/admin/perfil/editar', function () {
    return view('admin.perfil-editar');
});

Route::get('/admin/perfil/actividad-toda', function () {
    return view('admin.perfil-actividad-toda');
});

Route::get('/admin/perfil/compensaciones-todas', function () {
    return view('admin.perfil-compensaciones-todas');
});

// Add routes for all other screens
Route::get('/appointment', function () {
    return view('appointment');
});

// New appointment routes
Route::get('/appointment-clinic', [AppointmentController::class, 'create'])->name('appointment.clinic');
Route::get('/appointment-home', function () {
    return view('appointment-home');
});

Route::get('/history', function () {
    return view('history');
});



Route::get('/schedule', function () {
    return view('schedule');
});

Route::get('/seguimiento', function () {
    return view('seguimiento');
});

Route::get('/doctor-history', function () {
    return view('doctor-history');
});

Route::get('/doctor-appointment', function () {
    return view('doctor-appointment');
});

// Rutas para autenticación con Google
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
