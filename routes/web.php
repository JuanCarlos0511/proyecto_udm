<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function () {
    return view('register');
});

Route::get('/profile', function () {
    return view('profile');
});

Route::get('/settings', function () {
    return view('settings');
});

Route::get('/logout', function () {
    return view('logout');
});

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
Route::get('/appointment-clinic', function () {
    return view('appointment-clinic');
});

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
