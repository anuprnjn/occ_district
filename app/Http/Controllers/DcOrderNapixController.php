<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use setasign\Fpdi\TcpdfFpdi;
use setasign\Fpdi\PdfReader;

class DcOrderNapixController extends Controller
{
    public function getDcOrderPdf(Request $request)
    {
        $cino = $request->input('cino');
        $order_date = $request->input('order_date');
        $order_no = $request->input('order_no');
        $dist_name = $request->input('dist_name');

        $folder = "napix_pdf/district_court/{$dist_name}/{$cino}";
        $filename = "{$cino}_{$order_no}_napix.pdf";
        $relativePath = "{$folder}/{$filename}";
        $pdfPath = storage_path("app/public/{$relativePath}");

        // Step 0: Check if PDF already exists
        if (Storage::disk('public')->exists($relativePath)) {
            try {
                $pdf = new \setasign\Fpdi\Fpdi();
                $pageCount = $pdf->setSourceFile($pdfPath);

                $perPageFee = DB::table('fee_master')
                    ->where('fee_type', 'per_page_fee')
                    ->value('amount');

                $totalAmount = $pageCount * $perPageFee;

                return response()->json([
                    'status' => 'success',
                    'PdfUrl' => asset('storage/' . $relativePath),
                    'filename' => $relativePath,
                    'pages' => $pageCount,
                    'amount' => $totalAmount
                ]);
            } catch (\Exception $e) {
                // If reading PDF fails, fall back to API call
            }
        }

        // Step 1: Get Access Token
        $apikey = env('NAPIX_API_KEY');
        $secret_key = env('NAPIX_SECRET_KEY');
        $url = env('NAPIX_TOKEN_URL');
        $combined = base64_encode("$apikey:$secret_key");

        $tokenResponse = Http::withHeaders([
            'Authorization' => "Basic $combined",
            'Content-Type' => 'application/x-www-form-urlencoded'
        ])->asForm()->post($url, [
            'grant_type' => 'client_credentials',
            'scope' => 'napix'
        ]);

        if (!$tokenResponse->successful()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token fetch failed',
                'details' => $tokenResponse->body()
            ], 500);
        }

        $access_token = $tokenResponse->json()['access_token'];

        // Step 2: Prepare Encrypted Request
        $input_str = "cino=$cino|order_no=$order_no|order_date=$order_date";
        $hmac_secret = env('NAPIX_HMAC_SECRET');
        $aes_key = env('NAPIX_AES_KEY');
        $iv = env('NAPIX_AES_IV');

        $request_token = hash_hmac('sha256', $input_str, $hmac_secret);
        $encrypted = openssl_encrypt($input_str, 'AES-128-CBC', $aes_key, OPENSSL_RAW_DATA, $iv);
        $request_str = urlencode(base64_encode($encrypted));

        $dept_id = env('DEPT_ID');
        $version = env('VERSION');

        $api_url = "https://delhigw.napix.gov.in/nic/ecourts/dc-oreder-api?dept_id={$dept_id}&request_str={$request_str}&request_token={$request_token}&version={$version}";

        // Step 3: Call NAPIX API
        $response = Http::withToken($access_token)
                        ->withOptions(['verify' => false])
                        ->get($api_url);

        if (!$response->successful()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Main API call failed',
                'details' => $response->body()
            ], 500);
        }

        $response_str = $response->json()['response_str'] ?? null;

        if (!$response_str) {
            return response()->json([
                'status' => 'error',
                'message' => 'response_str missing'
            ], 400);
        }

        //  Step 4: Decrypt and Validate PDF
        $decrypted = openssl_decrypt(base64_decode($response_str), 'AES-128-CBC', $aes_key, OPENSSL_RAW_DATA, $iv);
        $pdf_content = base64_decode($decrypted);

        if (substr($pdf_content, 0, 5) !== '%PDF-') {
            return response()->json([
                'status' => 'error',
                'message' => 'Decrypted content is not a valid PDF'
            ], 400);
        }

        // Step 5: Store PDF inside napix_pdf/{cino}/ folder
        Storage::disk('public')->makeDirectory($folder);
        Storage::disk('public')->put($relativePath, $pdf_content);

        // Step 6: Count pages using FPDI
        try {
            $pdf = new \setasign\Fpdi\Fpdi();
            $pageCount = $pdf->setSourceFile($pdfPath);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to read PDF pages',
                'details' => $e->getMessage()
            ], 500);
        }

        //  Step 7: Calculate amount
        $perPageFee = DB::table('fee_master')
            ->where('fee_type', 'per_page_fee') 
            ->value('amount') ;

        $totalAmount = $pageCount * $perPageFee;

        //  Step 8: Return response
        return response()->json([
            'status' => 'success',
            'PdfUrl' => asset('storage/' . $relativePath),
            'filename' => $relativePath,
            'pages' => $pageCount,
            'amount' => $totalAmount
        ]);
    }
}