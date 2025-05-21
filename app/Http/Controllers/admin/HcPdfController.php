<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;
use TCPDF;
use Illuminate\Support\Facades\Log;


class HcPdfController extends Controller
{
    public function attachStampAndHeader(Request $request)
    {
        $relativeUrl = $request->input('pdf_path');
        $requestDate = $request->input('createdAt');
        $application_number = $request->input('application_number');
        $doc_id = $request->input('doc_id');
        $trn_no = $request->input('transaction_no','TRNTEST12345');
        // $trn_date = $request->input('transaction_date','21-05-2025');
        $trn_date = $request->input('transaction_date');
        if (empty($trn_date)) {
            $trn_date = 'N/A';
        }

        $forceConvert = $request->input('force_convert', false);

        // dd([
        //     'pdf_path' => $request->input('pdf_path'),
        //     'createdAt' => $request->input('createdAt'),
        //     'application_number' => $request->input('application_number'),
        //     'doc_id' => $request->input('doc_id'),
        //     'transaction_no' => $request->input('transaction_no', 'TRNTEST12345'),
        //     'transaction_date' => $request->input('transaction_date', '09-04-2025'),
        // ]);



        $relativePath = str_replace(asset('/'), '', $relativeUrl);
        $originalPdfPath = public_path($relativePath);
        $isTempFile = false;


        if (!file_exists($originalPdfPath)) {
            return response()->json(['error' => 'PDF file not found.'], 404);
        }

        // Force conversion if requested
        if ($forceConvert) {
            $originalPdfPath = $this->convertPdfToCompatible($originalPdfPath, $doc_id, $application_number);
            $isTempFile = true;
        }

        $pdf = new Fpdi();

        try {
            $pageCount = $pdf->setSourceFile($originalPdfPath);
        } catch (\Exception $e) {
            $convertedPath = $this->convertPdfToCompatible($originalPdfPath, $doc_id, $application_number);
            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile($convertedPath);
            $originalPdfPath = $convertedPath;
            $isTempFile = true;
        }

        $bottomStampY = $request->input('y', 60);
        $customX = $request->input('x');
        $authFee = $request->input('auth_fee');

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($templateId);

            // Header & Table
            $logoWidth = $size['width'] * 0.1;
            $pdf->Image(public_path('passets/images/top.jpeg'), 2, 2, $logoWidth);

            $tableWidth = $size['width'] * 0.75;
            $xStart = ($size['width'] - $tableWidth) / 2;
            $cellWidths = [
                $tableWidth * 0.233,
                $tableWidth * 0.233,
                $tableWidth * 0.2,
                $tableWidth * 0.440,
            ];

            $fontSize = max(8, $size['height'] * 0.018);
            $cellHeight = max(7, $size['height'] * 0.018);

            $pdf->SetFont('Arial', 'B', $fontSize);
            $pdf->SetTextColor(0, 0, 255);
            $pdf->SetLineWidth(0.5);

            $pdf->SetXY($xStart, 4);
            $pdf->Cell($cellWidths[0], $cellHeight, 'Applied Date', 1);
            // $pdf->Cell($cellWidths[0], $cellHeight, 'Application Number', 1);
            $pdf->Cell($cellWidths[1], $cellHeight, 'Transaction No', 1);
            $pdf->Cell($cellWidths[2], $cellHeight, 'Transaction Date', 1);
            $pdf->Cell($cellWidths[3], $cellHeight, 'Authentication Fee Payable under court fee act Rs', 1);

            $pdf->Ln();
            $pdf->SetX($xStart);
            $pdf->SetFont('Arial', 'B', $fontSize);
            $pdf->Cell($cellWidths[0], $cellHeight, $requestDate, 1);
            // $pdf->Cell($cellWidths[0], $cellHeight, $application_number, 1);
            $pdf->Cell($cellWidths[1], $cellHeight, $trn_no, 1);
            $pdf->Cell($cellWidths[2], $cellHeight, $trn_date, 1);  
            $pdf->Cell($cellWidths[3], $cellHeight, $authFee, 1);
            $pdf->SetTextColor(0, 0, 0);

            // Bottom stamp only on last page
            if ($pageNo === $pageCount) {
                $stampPath = public_path('passets/images/bottom.jpeg');
                $stampWidth = $size['width'] * 0.25;
                $stampHeight = $stampWidth * 0.4;

                $centerX = ($size['width'] - $stampWidth) / 2;
                $xStamp = is_numeric($customX) ? $centerX + floatval($customX) : $centerX;

                $xStamp = max(0, min($xStamp, $size['width'] - $stampWidth));
                $yStamp = $size['height'] - floatval($bottomStampY);
                $yStamp = max(0, min($yStamp, $size['height'] - $stampHeight));

                $pdf->Image($stampPath, $xStamp, $yStamp, $stampWidth);
            }
        }

        $pdfContent = $pdf->Output('', 'S');

        // Clean up converted PDF if it's temporary
        if ($isTempFile && file_exists($originalPdfPath)) {
            unlink($originalPdfPath);
        }

        return response($pdfContent, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="converted.pdf"');
    }



    public function checkPdfCompatibility(Request $request)
    {
        $relativeUrl = $request->input('pdf_path');
        $relativePath = str_replace(asset('/'), '', $relativeUrl);
        $originalPdfPath = public_path($relativePath);

        if (!file_exists($originalPdfPath)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        try {
            $pdf = new Fpdi();
            $pdf->setSourceFile($originalPdfPath);
            return response()->json(['compatible' => true]);
        } catch (\Exception $e) {
            return response()->json(['compatible' => false]);
        }
    }

    private function convertPdfToCompatible($originalPdfPath, $doc_id, $application_number)
    {
        // Define the directory
        $outputDir = storage_path("app/temp_converted_pdf");

        // Make sure the directory exists
        if (!file_exists($outputDir)) {
            mkdir($outputDir, 0755, true);
        }

        // Delete all previous PDFs in the folder
        foreach (glob($outputDir . '/*.pdf') as $oldPdf) {
            unlink($oldPdf);
        }

        // Convert using Imagick
        $imagick = new \Imagick();
        $imagick->setResolution(200, 200);
        $imagick->readImage($originalPdfPath);
        $imagick->setImageFormat('png');

        // Initialize TCPDF
        $tcpdf = new TCPDF();
        $tcpdf->SetPrintHeader(false);
        $tcpdf->SetPrintFooter(false);

        $i = 0;
        foreach ($imagick as $page) {
            $tcpdf->AddPage();
            $imagePath = storage_path("app/tmp_page_" . $i . ".png");
            $page->writeImage($imagePath);

            $width = $tcpdf->getPageWidth();
            $height = $tcpdf->getPageHeight();
            $tcpdf->Image($imagePath, 0, 0, $width, $height);

            unlink($imagePath); 
            $i++;
        }

        $uniqueFile = $application_number  . '_' . $doc_id . '.pdf';
        $outputPath = $outputDir . '/' . $uniqueFile;
        $tcpdf->Output($outputPath, 'F');
        return $outputPath;
    }
}