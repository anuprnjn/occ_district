<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GetPdfController extends Controller
{
    public function fetchPdf(Request $request)
    {
        $cino = $request->input('cino');
        $order_no = $request->input('order_no');

        $response = Http::withOptions(['verify' => false])->get('http://192.168.137.237/occ_api/high_court_order_copy/download_order_copy.php', [
            'cino' => $cino,
            'order_no' => $order_no
        ]);

        if ($response->ok()) {
            $pdfData = base64_encode($response->body());

            return response()->json([
                'message' => 'success',
                'pdf_data' => 'data:application/pdf;base64,' . $pdfData
            ]);
        }

        return response()->json(['message' => 'Failed to fetch PDF'], 500);
    }
}