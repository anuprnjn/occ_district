<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;
use TCPDF;

class PdfController extends Controller
{
    public function attachStampAndHeader(Request $request)
    {
        $relativeUrl = $request->input('pdf_path');
        $requestDate = $request->input('createdAt');
        $forceConvert = $request->input('force_convert', false);

        $relativePath = str_replace(asset('/'), '', $relativeUrl);
        $originalPdfPath = public_path($relativePath);
        $isTempFile = false;

        if (!file_exists($originalPdfPath)) {
            return response()->json(['error' => 'PDF file not found.'], 404);
        }

        // Force conversion if requested
        if ($forceConvert) {
            $originalPdfPath = $this->convertPdfToCompatible($originalPdfPath);
            $isTempFile = true;
        }

        $pdf = new Fpdi();

        try {
            $pageCount = $pdf->setSourceFile($originalPdfPath);
        } catch (\Exception $e) {
            $convertedPath = $this->convertPdfToCompatible($originalPdfPath);
            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile($convertedPath);
            $originalPdfPath = $convertedPath;
            $isTempFile = true;
        }

        $bottomStampY = $request->input('bottom_stamp_y', 60);
        $customX = $request->input('bottom_stamp_x');
        $authFee = $request->input('auth_fee');

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($templateId);

            // Header & Table
            $logoWidth = $size['width'] * 0.1;
            $pdf->Image(public_path('passets/images/top.jpeg'), 4, 7, $logoWidth);

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

            $pdf->SetXY($xStart, 10);
            $pdf->Cell($cellWidths[0], $cellHeight, 'Request Date', 1);
            $pdf->Cell($cellWidths[1], $cellHeight, 'Transaction No', 1);
            $pdf->Cell($cellWidths[2], $cellHeight, 'Transaction Date', 1);
            $pdf->Cell($cellWidths[3], $cellHeight, 'Authentication Fee Payable under court fee act Rs', 1);

            $pdf->Ln();
            $pdf->SetX($xStart);
            $pdf->SetFont('Arial', 'B', $fontSize);
            $pdf->Cell($cellWidths[0], $cellHeight, $requestDate, 1);
            $pdf->Cell($cellWidths[1], $cellHeight, 'TR24016060425125943', 1);
            $pdf->Cell($cellWidths[2], $cellHeight, '06-04-2025 12:59:43', 1);
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

    private function convertPdfToCompatible($originalPdfPath)
    {
        $imagick = new \Imagick();
        $imagick->setResolution(150, 150);
        $imagick->readImage($originalPdfPath);
        $imagick->setImageFormat('png');

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

        $uniqueFile = 'converted_' . uniqid() . '.pdf';
        $outputPath = storage_path("app/$uniqueFile");
        $tcpdf->Output($outputPath, 'F');

        return $outputPath;
    }
}