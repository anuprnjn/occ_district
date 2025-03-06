<?php 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Middleware\VerifyCsrfToken;
use App\Http\Controllers\OrderCopyController;



Route::match(['get', 'post'], '/occ/gras_resp_cc', function (Request $request) { 
    $data = $request->method() == 'POST' ? $request->json()->all() : $request->all();

    Log::info('Received Transaction Response:', $data);
    return view('transactionStatus')->with('responseData', $data);
})->name('transactionStatus');