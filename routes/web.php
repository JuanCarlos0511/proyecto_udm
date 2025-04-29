<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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

// Rutas de autenticación
Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Rutas de registro
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Rutas de perfil (protegidas con autenticación)
// Usamos el middleware web para asegurar que las sesiones funcionen correctamente
Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/perfil', [ProfileController::class, 'show'])->name('profile');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('profile.update');
});

// Ruta alternativa para el perfil (para depuración)
Route::get('/mi-perfil', function() {
    if (Auth::check()) {
        return redirect('/perfil');
    } else {
        return redirect('/login')->with('error', 'Debes iniciar sesión para acceder a tu perfil');
    }
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

// Rutas del tablero
Route::get('/admin/tablero', 'App\Http\Controllers\Admin\DashboardController@index')->name('admin.dashboard');
Route::get('/admin/tablero/actualizar', 'App\Http\Controllers\Admin\DashboardController@refresh')->name('admin.dashboard.refresh');

// Rutas de perfil de administrador
Route::get('/admin/perfil', function () {
    return view('admin.profile.user-profile');
})->name('admin.profile');

Route::get('/admin/perfil/editar', function () {
    return view('admin.profile.edit-profile');
})->name('admin.profile.edit');

Route::get('/admin/perfil/actividad-toda', function () {
    return view('admin.profile.all-activity');
})->name('admin.profile.activity');

Route::get('/admin/perfil/compensaciones-todas', function () {
    return view('admin.profile.all-compensations');
})->name('admin.profile.compensations');

// Rutas de historial de administrador
Route::get('/admin/historial-citas', function() {
    return view('admin.appointments.appointment-history');
})->name('admin.appointments.history');

Route::get('/admin/historial-facturas', function() {
    return view('admin.billing.billing-history');
})->name('admin.billing.history');

// Rutas de generación de reportes de administrador
Route::get('/admin/reportes/generar', 'App\Http\Controllers\Admin\ReportController@index')->name('admin.reports.index');
Route::post('/admin/reportes/data', 'App\Http\Controllers\Admin\ReportController@getAppointmentData')->name('admin.reports.data');

// Rutas de facturas de administrador
Route::get('/admin/generar-facturas', function() {
    return view('admin.billing.generate-bills');
})->name('admin.bills.generate');


Route::post('/admin/generar-facturas', 'App\Http\Controllers\Admin\BillController@store')->name('admin.bills.store');
Route::get('/admin/facturas', function() {
    return view('admin.bills.bills-list');
})->name('admin.bills.index');


Route::resource('/admin/facturas', 'App\Http\Controllers\Admin\BillController', ['as' => 'admin'])->except(['index', 'store']);

// Rutas para gestión de doctores
Route::get('/admin/doctores', function() {
    return view('admin.doctors.doctors-list');
})->name('admin.doctors.index');
Route::resource('/admin/doctores', 'App\Http\Controllers\Admin\DoctorController', ['as' => 'admin'])->except(['index']);
Route::get('/admin/doctores-data', 'App\Http\Controllers\Admin\DoctorController@getDoctorsData')->name('admin.doctors.data');

// Rutas para citas
Route::get('/admin/tablero/citas-todas', function() {
    return view('admin.appointments.all-appointments');
})->name('admin.appointment-all-appointments');


Route::resource('/admin/citas', 'App\Http\Controllers\Admin\AppointmentController', ['as' => 'admin'])->except(['index']);
Route::get('/admin/citas-data', 'App\Http\Controllers\Admin\AppointmentController@getAppointmentsData')->name('admin.appointments.data');
Route::get('/admin/citas-domicilio', 'App\Http\Controllers\Admin\AppointmentController@createHomeAppointment')->name('admin.appointments.home');
Route::get('/admin/citas-consultorio', 'App\Http\Controllers\Admin\AppointmentController@createClinicAppointment')->name('admin.appointments.clinic');

// Rutas para pacientes en seguimiento
Route::get('admin/tablero/seguimiento-todos', function() {
    return view('admin.dashboard.all-patient-followups');
})->name('admin.all-patient-followups');



Route::get('/admin/pacientes', function() {
    return view('admin.patients.patients-list');
})->name('admin.patients.index');
Route::resource('/admin/pacientes', 'App\Http\Controllers\Admin\PatientController', ['as' => 'admin'])->except(['index']);
Route::get('/admin/pacientes-data', 'App\Http\Controllers\Admin\PatientController@getPatientsData')->name('admin.patients.data');
Route::get('/admin/pacientes/{id}/perfil-info', 'App\Http\Controllers\Admin\PatientController@addProfileInfo')->name('admin.patients.profile-info');
Route::put('/admin/pacientes/{id}/perfil-info', 'App\Http\Controllers\Admin\PatientController@updateProfileInfo')->name('admin.patients.update-profile-info');



// Add routes for all other screens
Route::get('/appointment', function () {
    return view('appointment');
});

// New appointment routes
Route::get('/appointment-clinic', [AppointmentController::class, 'create'])->name('appointment.clinic');
Route::get('/appointment-home', function () {
    return view('appointment-home');
});
Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointment.store');

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
