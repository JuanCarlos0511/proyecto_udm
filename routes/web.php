<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/admin', function () {
    return view('admin');
});

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
