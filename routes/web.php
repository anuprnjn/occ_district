<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DistrictController;

Route::get('/', function () {
    return view('index');
});

Route::get('/', [DistrictController::class, 'index']);