<?php 

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class GetPdfController extends Controller
{
    public function fetchPdf(Request $request)
{
    $cino = $request->input('cino');
    $order_no = $request->input('order_no');

    // Define relative file path based on inputs
    $filePath = "napix_pdf/high_court/{$cino}/{$cino}_{$order_no}_napix.pdf";

    // Check if file exists in 'public' disk (storage/app/public)
    if (Storage::disk('public')->exists($filePath)) {
        // Get the file contents
        $pdfContent = Storage::disk('public')->get($filePath);

        // Encode as base64
        $pdfBase64 = base64_encode($pdfContent);

        return response()->json([
            'message' => 'success',
            'pdf_data' => 'data:application/pdf;base64,' . $pdfBase64
        ]);
    }

    return response()->json([
        'message' => 'PDF not found at path: ' . $filePath
    ], 404);
}
}