<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DistrictController;

Route::get('/', function () {
    return view('index');
});

Route::get('/', [DistrictController::class, 'index']);
Route::post('/get-establishments', [DistrictController::class, 'getEstablishments'])->name('get-establishments');
Route::get('/get-case-types', [DistrictController::class, 'getCaseTypes'])->name('get-case-types');