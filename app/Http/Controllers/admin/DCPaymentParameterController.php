<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Helpers\ActivityLogger;

class DCPaymentParameterController extends Controller
{
    public function parameterList()
    {
        $payparameter = Http::get(config('app.api.admin_url') . '/fetch_jegras_merchant_details_dc.php');

        if ($payparameter->failed()) {
            Log::error('Failed to fetch Menu Data', ['response' => $payparameter->body()]);
            $payparameterdata = [];
        } else {
            Log::info('Menu fetched:', ['data' => $payparameter->json()]);
            $payparameterdata = $payparameter->json();
        }

        return view('admin.DCpaymentrequestperameter.payment_parameter_list_dc', compact('payparameterdata'));
    }

    public function update(Request $request)
{
    $validated = $request->validate([
        'dist_code' => 'required',
        'deptid' => 'required|string',
        'recieptheadcode' => 'required|string',
        'treascode' => 'required|string',
        'ifmsofficecode' => 'required|string',
        'securitycode' => 'required|string',
    ]);

    // API endpoint for updating data
    $apiUrl = config('app.api.admin_url') . '/update_jegras_merchant_details_dc.php';

    // Send PUT or POST request to the API
    $response = Http::post($apiUrl, [
        'dist_code' => $validated['dist_code'],
        'deptid' => $validated['deptid'],
        'recieptheadcode' => $validated['recieptheadcode'],
        'treascode' => $validated['treascode'],
        'ifmsofficecode' => $validated['ifmsofficecode'],
        'securitycode' => $validated['securitycode'],
    ]);

    // Handle API response
    if ($response->successful()) {
        ActivityLogger::log_dc('Update Payment Parameter', 'Update', session('user.id'), session('user.dist_code'), session('user.estd_code'), session('user.name'));
        return redirect()->route('payment_parameter_list_dc')->with('success', 'Payment Parameter updated successfully.');
    } else {
        return redirect()->route('payment_parameter_list_dc')->with('error', 'Failed to update Payment Parameter.')->withInput();
    }
}
}
