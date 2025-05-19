<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\FollowUpController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Las rutas relacionadas con la sesión web deben estar en web.php

// User routes
Route::apiResource('users', UserController::class);

// NOTA: Temporalmente desactivamos la autenticación para pruebas de desarrollo
// En producción, estas rutas deben estar protegidas con middleware 'auth'

// Appointment routes - accesibles sin autenticación para pruebas
Route::apiResource('appointments', AppointmentController::class);

// Additional routes for filtering appointments
Route::get('/appointments/user/{user}', [AppointmentController::class, 'getByUser']);
Route::get('/appointments/status/{status}', [AppointmentController::class, 'getByStatus']);

// FollowUp routes - accesibles sin autenticación para pruebas
// En producción, estas rutas deben estar protegidas con middleware 'auth'
Route::apiResource('follow-ups', FollowUpController::class)->names([
    'index' => 'api.follow-ups.index',
    'store' => 'api.follow-ups.store',
    'show' => 'api.follow-ups.show',
    'update' => 'api.follow-ups.update',
    'destroy' => 'api.follow-ups.destroy'
]);

// Rutas adicionales para seguimientos
Route::get('/follow-ups/doctor', [FollowUpController::class, 'getFollowUpsForDoctor']);
Route::get('/follow-ups/patient', [FollowUpController::class, 'getMyDoctors']);
Route::get('/appointments/follow-up-doctors', [FollowUpController::class, 'getAppointmentsWithFollowUpDoctors']);
