<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/pets/', 'PetController@index');

