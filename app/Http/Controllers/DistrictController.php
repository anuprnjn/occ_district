<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DistrictController extends Controller
{
    public function index()
    {
        $apiUrl = config('app.api.base_url') . '/district_dropdown.php';
        $response = Http::get($apiUrl);

        if ($response->failed()) {
            \Log::error('Failed to fetch districts: ' . $response->body());
            return back()->withErrors(['message' => 'Unable to fetch districts.']);
        }

        $districts = $response->json();
        return view('index', compact('districts'));
    }

    public function getEstablishments(Request $request)
    {
        $request->validate(['dist_code' => 'required|string']);

        $response = Http::asForm()->post(config('app.api.base_url') . '/establishment.php', [
            'dist_code' => $request->dist_code,
        ]);

        if ($response->failed()) {
            return response()->json(['error' => 'Unable to fetch establishments.'], 500);
        }

        return response()->json($response->json());
    }
}
