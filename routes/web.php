<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AppointmentController;

Route::get('/', function () {
    return view('index');
});

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
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/settings', function () {
    return view('settings');
});

Route::get('/logout', function () {
    return view('logout');
});

// Ruta para procesar el cierre de sesión
Route::post('/logout', 'App\Http\Controllers\Auth\LogoutController@logout')->name('logout');

// Admin routes
Route::get('/admin', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', 'App\Http\Controllers\Admin\DashboardController@index');
Route::get('/dashboard/refresh', 'App\Http\Controllers\Admin\DashboardController@refresh');

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
