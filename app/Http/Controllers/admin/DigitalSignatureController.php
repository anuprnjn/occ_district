<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\TcpdfFpdi;

class DigitalSignatureController extends Controller
{
    public function addDigitalSignature()
    {
        return view('admin.digital_signature');
    }

    public function generatePdf(Request $request)
    {
        $cino = $request->input('cino');
        $order_date = $request->input('order_date');
        $order_no = $request->input('order_no');

        $apikey = env('NAPIX_API_KEY');
        $secret_key = env('NAPIX_SECRET_KEY');
        $url = env('NAPIX_TOKEN_URL');
        $combined = base64_encode("$apikey:$secret_key");

        // Step 1: Get Access Token
        $tokenResponse = Http::withHeaders([
            'Authorization' => "Basic $combined",
            'Content-Type' => 'application/x-www-form-urlencoded'
        ])->asForm()->post($url, [
            'grant_type' => 'client_credentials',
            'scope' => 'napix'
        ]);

        if (!$tokenResponse->successful()) {
            return response()->json(['status' => 'error', 'message' => 'Token fetch failed', 'details' => $tokenResponse->body()], 500);
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
        $response = Http::withToken($access_token)->withOptions(['verify' => false])->get($api_url);

        if (!$response->successful()) {
            return response()->json(['status' => 'error', 'message' => 'Main API call failed', 'details' => $response->body()], 500);
        }

        $response_str = $response->json()['response_str'] ?? null;

        if (!$response_str) {
            return response()->json(['status' => 'error', 'message' => 'response_str missing'], 400);
        }

        // Step 4: Decrypt and Validate PDF
        $decrypted = openssl_decrypt(base64_decode($response_str), 'AES-128-CBC', $aes_key, OPENSSL_RAW_DATA, $iv);
        $pdf_content = base64_decode($decrypted);

        if (substr($pdf_content, 0, 5) !== '%PDF-') {
            return response()->json(['status' => 'error', 'message' => 'Decrypted content is not a valid PDF'], 400);
        }

        // Step 5: Store original PDF
        $filename = $cino . '_napix.pdf';
        $relativePath = "napix_pdf/{$filename}";
        Storage::disk('public')->put($relativePath, $pdf_content);

        // Step 6: Sign the PDF
        $signedPdfPath = $this->signPdf($filename);

        // Step 7: Return signed PDF path
        return response()->json([
            'status' => 'success',
            'PdfUrl' => asset('storage/' . $signedPdfPath),
            'filename' => $signedPdfPath
        ]);
    }

    public function signPdf($filename)
    {
        $originalPdfPath = storage_path('app/public/napix_pdf/' . $filename);

        if (!file_exists($originalPdfPath)) {
            return response()->json(['status' => 'error', 'message' => 'PDF file not found'], 404);
        }

        $signedDirectory = storage_path('app/public/napix_pdf/signed_pdf/');
        $signedFilename = 'signed_' . $filename;
        $fullSignedPath = $signedDirectory . $signedFilename;

        // Ensure directory exists
        if (!file_exists($signedDirectory)) {
            mkdir($signedDirectory, 0755, true);
        }

        $certificate = 'file://' . base_path('storage/app/certificates/Tushar.crt');
        $privateKey  = 'file://' . base_path('storage/app/certificates/Tushar.key');

        $info = [
            'Name' => 'Tushar Anand',
            'Location' => 'Ranchi',
            'Reason' => 'Demo Digital Signature',
            'ContactInfo' => '',
        ];

        $pdf = new TcpdfFpdi();
        $pdf->SetAutoPageBreak(false);
        $pageCount = $pdf->setSourceFile($originalPdfPath);

        $pdf->setSignature($certificate, $privateKey, 'tcpdfdemo', '', 2, $info);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->SetCreator('Tushar Anand');

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $tpl = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($tpl);
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($tpl);

            if ($pageNo === $pageCount) {
                $x = $size['width'] - 70;
                $y = $size['height'] - 30;
                $w = 60;
                $h = 20;

                $pdf->SetXY($x, $y);
                $pdf->SetFont('helvetica', 'B', 10);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->MultiCell($w, $h,
                    "Digitally Signed By:\n" .
                    "Tushar Anand\n" .
                    "Location: Ranchi\n" .
                    "Reason: Demo Digital Signature\n" .
                    "Date: " . now()->format('Y-m-d H:i:s'),
                    0, 'C'
                );

                $pdf->setSignatureAppearance($x, $y, $w, $h);
            }
        }

        $pdf->Output($fullSignedPath, 'F');

        return 'napix_pdf/signed_pdf/' . $signedFilename;
    }
}