<?php

use Illuminate\Support\Facades\Route;
 use App\Http\Controllers\AuthController;
    use App\Http\Controllers\BukuController;

Route::get('/', function () {
    return view('welcome');
});
