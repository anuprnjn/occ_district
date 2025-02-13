<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class JudgementController extends Controller
{
    public function fetchJudgementData(Request $request)
    {
        $search_type = $request->input('search_type');
        $selectedCaseTypeHc = $request->input('selectedCaseTypeHc');
        $reg_no = $request->input('HcCaseNo');
        $reg_year = $request->input('HcCaseYear');
        $fil_no = $request->input('HcFillingNo');
        $fil_year = $request->input('HcFillingYear');

        // Prepare request payload based on search type
        if ($search_type === 'case') {
            $apiData = [
                'search_type' => 'case',
                'reg_no' => $reg_no,
                'reg_year' => $reg_year,
                'regcase_type' => $selectedCaseTypeHc,
            ];
        } elseif ($search_type === 'filling') {
            $apiData = [
                'search_type' => 'filling',
                'fil_no' => $fil_no,
                'fil_year' => $fil_year,
                'filcase_type' => $selectedCaseTypeHc,
            ];
        } else {
            return response()->json(['status' => 'Error', 'message' => 'Invalid search type'], 400);
        }

        // Call the external API
        $apiUrl = 'http://192.168.58.197/occ_api/high_court_order_copy/case_search.php';
        $response = Http::post($apiUrl, $apiData);
        
        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['status' => 'Error', 'message' => 'Failed to get the case details'], 500);
        }
        
    }
}