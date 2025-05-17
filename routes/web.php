<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\FollowUpController;
use App\Http\Middleware\CheckAuthenticated;
use App\Http\Middleware\CheckAdminDoctor;

// Rutas públicas - accesibles sin autenticación
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

// Ruta para procesar el cierre de sesión
Route::post('/logout', 'App\Http\Controllers\Auth\LogoutController@logout')->name('logout');

// Rutas para autenticación con Google
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    // Rutas de perfil
    Route::get('/perfil', [ProfileController::class, 'show'])->name('profile');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/perfil/photo', [ProfileController::class, 'deletePhoto'])->name('profile.delete-photo');
    
    // Rutas para pacientes
    Route::get('/citas', [AppointmentController::class, 'index'])->name('user.appointments');
    Route::get('/citas/historial', [AppointmentController::class, 'history'])->name('user.appointment.history');
    Route::post('/citas/cancelar/{id}', [AppointmentController::class, 'cancel'])->name('user.appointment.cancel');
    Route::post('/citas', [AppointmentController::class, 'store'])->name('user.appointment.store');
    
    // Seguimientos para pacientes
    Route::get('/mis-seguimientos', [\App\Http\Controllers\Public\PatientFollowUpController::class, 'index'])->name('patient.followups');
    
    Route::get('/settings', function () {
        return view('settings');
    });
    
    Route::get('/logout', function () {
        return view('logout');
    });
    
    // Rutas de administración - Solo para administradores y doctores
    Route::middleware([CheckAdminDoctor::class])->group(function () {
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
        Route::get('/admin/doctores', 'App\Http\Controllers\Admin\DoctorController@index')->name('admin.doctors.index');
        Route::get('/admin/doctors/{id}/edit', 'App\Http\Controllers\Admin\DoctorController@edit')->name('admin.doctors.edit');
        Route::put('/admin/doctors/{id}', 'App\Http\Controllers\Admin\DoctorController@update')->name('admin.doctors.update');
        Route::post('/admin/doctores', 'App\Http\Controllers\Admin\DoctorController@store')->name('admin.doctors.store');
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
    });
    
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
    Route::get('/admin/informacion-facturar', function() {
        return view('admin.billing.generate-bills');
    })->name('admin.bills.generate');
    
    Route::post('/admin/informacion-facturar', 'App\Http\Controllers\Admin\BillController@store')->name('admin.bills.store');
    Route::get('/admin/facturas', function() {
        return view('admin.bills.bills-list');
    })->name('admin.bills.index');
    
    // Ruta para obtener los datos de facturas en formato JSON
    Route::get('/admin/bills/data', 'App\Http\Controllers\Admin\BillController@getBillsData')->name('admin.bills.data');
    
    Route::resource('/admin/facturas', 'App\Http\Controllers\Admin\BillController', ['as' => 'admin'])->except(['index', 'store']);
    
    // Rutas para gestión de doctores
    Route::resource('/admin/doctores', 'App\Http\Controllers\Admin\DoctorController', ['as' => 'admin'])->except(['index']);
    Route::get('/admin/doctores-data', 'App\Http\Controllers\Admin\DoctorController@getDoctorsData')->name('admin.doctors.data');
    
    // Rutas para citas
    Route::get('/admin/tablero/citas-todas', 'App\Http\Controllers\Admin\AppointmentController@index')->name('admin.appointments.index');
    // Ruta PUT para actualizar citas directamente desde la vista
    Route::put('/admin/tablero/citas-todas', 'App\Http\Controllers\Admin\AppointmentController@updateFromView');
    
    // Define explicit PUT route for appointment updates to bypass any routing issues
    Route::put('/admin/citas/{id}', 'App\Http\Controllers\Admin\AppointmentController@update')->name('admin.appointments.update');
    
    Route::resource('/admin/citas', 'App\Http\Controllers\Admin\AppointmentController', ['as' => 'admin'])->except(['index', 'update']);
    Route::get('/admin/citas-data', 'App\Http\Controllers\Admin\AppointmentController@getAppointmentsData')->name('admin.appointments.data');
    Route::post('/admin/appointments/{id}/accept', 'App\Http\Controllers\Admin\AppointmentController@accept')->name('admin.appointments.accept');
    Route::post('/admin/appointments/{id}/cancel', 'App\Http\Controllers\Admin\AppointmentController@cancel')->name('admin.appointments.cancel');
    Route::get('/admin/citas-domicilio', 'App\Http\Controllers\Admin\AppointmentController@createHomeAppointment')->name('admin.appointments.home');
    Route::get('/admin/citas-consultorio', 'App\Http\Controllers\Admin\AppointmentController@createClinicAppointment')->name('admin.appointments.clinic');
    
    // Rutas para pacientes en seguimiento
    Route::get('admin/tablero/seguimiento-todos', function() {
        $user = auth()->user();
        $followUps = [];
        
        if ($user->role === 'doctor') {
            // Para doctores, mostrar solo sus propios seguimientos
            $followUps = \App\Models\FollowUp::with('user')
                ->active()
                ->byUser($user->id)
                ->get();
        } else {
            // Para administradores, mostrar todos los seguimientos donde el usuario es doctor
            $followUps = \App\Models\FollowUp::with('user')
                ->active()
                ->byUserRole('doctor')
                ->get();
        }
            
        return view('admin.dashboard.all-patient-followups', compact('followUps'));
    })->name('admin.all-patient-followups');
    
    // Rutas para gestión de seguimientos
    Route::resource('admin/seguimiento', 'App\Http\Controllers\FollowUpController', ['as' => 'follow-ups', 'names' => [
        'index' => 'follow-ups.index',
        'create' => 'follow-ups.create',
        'store' => 'follow-ups.store',
        'show' => 'follow-ups.show',
        'edit' => 'follow-ups.edit',
        'update' => 'follow-ups.update',
        'destroy' => 'follow-ups.destroy'
    ]]);
    Route::get('admin/seguimiento/crear/{patient_id}', [FollowUpController::class, 'createForPatient'])->name('follow-ups.create-for-patient');
    Route::get('admin/seguimiento/por-doctor/{doctor_id}', [FollowUpController::class, 'getByDoctor'])->name('follow-ups.by-doctor');
    Route::get('admin/seguimiento/por-paciente/{patient_id}', [FollowUpController::class, 'getByPatient'])->name('follow-ups.by-patient');
    
    Route::get('/admin/pacientes', function() {
        return view('admin.patients.patients-list');
    })->name('admin.patients.index');
    Route::resource('/admin/pacientes', 'App\Http\Controllers\Admin\PatientController', ['as' => 'admin'])->except(['index']);
    Route::get('/admin/pacientes-data', 'App\Http\Controllers\Admin\PatientController@getPatientsData')->name('admin.patients.data');
    Route::get('/admin/pacientes-search', 'App\Http\Controllers\Admin\PatientSearchController@search')->name('admin.patients.search');
    Route::get('/admin/pacientes/{id}/perfil-info', 'App\Http\Controllers\Admin\PatientController@addProfileInfo')->name('admin.patients.profile-info');
    Route::put('/admin/pacientes/{id}/perfil-info', 'App\Http\Controllers\Admin\PatientController@updateProfileInfo')->name('admin.patients.update-profile-info');
    // Rutas de citas para usuarios normales
    Route::get('/appointment', function () {
        return view('appointment');
    });
    
    // New appointment routes
    Route::get('/appointment-clinic', [AppointmentController::class, 'create'])->name('appointment.clinic');
    Route::get('/appointment-home', function () {
    $followUps = \App\Models\FollowUp::with(['doctor', 'patient'])
        ->active()
        ->get()
        ->map(function($followUp) {
            return [
                'doctor' => $followUp->doctor->name,
                'patient' => $followUp->patient->name,
                'treatment' => $followUp->notes,
                'next_appointment' => $followUp->end_date ? $followUp->end_date->format('d/m/Y') : 'Sin fecha definida'
            ];
        });
    
    return view('appointment-home', ['followUps' => $followUps]);
})->name('appointment-home');
    
    Route::get('/history', function () {
        return view('history');
    });
    
    Route::get('/schedule', function () {
        return view('schedule');
    });
    
    // Rutas públicas para seguimientos
    Route::get('/seguimiento', [FollowUpController::class, 'myDoctors'])->name('follow-ups.my-doctors');
    Route::get('/seguimiento/{id}', [FollowUpController::class, 'showPublic'])->name('follow-ups.show-public');
    
    Route::get('/doctor-history', function () {
        return view('doctor-history');
    });
    
    Route::get('/doctor-appointment', function () {
        return view('doctor-appointment');
    });
});

// Ruta alternativa para el perfil (para depuración)
Route::get('/mi-perfil', function() {
    if (Auth::check()) {
        return redirect('/perfil');
    } else {
        return redirect('/login')->with('error', 'Debes iniciar sesión para acceder a tu perfil');
    }
});
