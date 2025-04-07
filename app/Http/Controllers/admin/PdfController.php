<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Storage;

class PdfController extends Controller
{
    public function attachStampAndHeader(Request $request)
    {
        $relativeUrl = $request->input('pdf_path');
        $requestDate = $request->input('createdAt');

        // Strip domain from full URL to get relative path
        $relativePath = str_replace(asset('/'), '', $relativeUrl);
        $existingPdfPath = public_path($relativePath);

        if (!file_exists($existingPdfPath)) {
            return response()->json(['error' => 'PDF file not found.'], 404);
        }

        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($existingPdfPath);

        $bottomStampY = $request->input('bottom_stamp_y', 40);
        $customX = $request->input('bottom_stamp_x'); 

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);

            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($templateId);

            // Add header and table on first page
            if ($pageNo === 1) {
                $pdf->Image(public_path('passets/images/top.jpeg'), 7, 7, 17);

                $tableWidth = 150;
                $xStart = ($size['width'] - $tableWidth) / 2;

                $pdf->SetFont('Arial', 'B', 7);
                $pdf->SetTextColor(0, 0, 255);
                $pdf->SetLineWidth(0.5);

                $pdf->SetXY($xStart, 10);
                $pdf->Cell(35, 5, 'Request Date', 1);
                $pdf->Cell(35, 5, 'Transaction No', 1);
                $pdf->Cell(30, 5, 'Transaction Date', 1);
                $pdf->Cell(63, 5, 'Authentication Fee Payable under court fee act Rs.', 1);

                $pdf->Ln();
                $pdf->SetX($xStart);
                $pdf->SetFont('Arial', '', 7);
                $pdf->Cell(35, 5, $requestDate, 1);
                $pdf->Cell(35, 5, 'TR24016060425125943', 1);
                $pdf->Cell(30, 5, '06-04-2025 12:59:43', 1);
                $pdf->Cell(63, 5, '15', 1);

                $pdf->SetTextColor(0, 0, 0);
            }

            // Add bottom stamp on last page
            if ($pageNo === $pageCount) {
                $stampPath = public_path('passets/images/bottom.jpeg');
                $stampWidth = 50;

                if ($stampWidth > $size['width']) {
                    $stampWidth = $size['width'] - 50;
                }

                // New: Center-based X offset logic
                $centerX = ($size['width'] - $stampWidth) / 2;

                if (is_numeric($customX)) {
                    // Offset from center
                    $xStamp = $centerX + floatval($customX);
                } else {
                    // Default center
                    $xStamp = $centerX;
                }

                // Clamp to valid X range
                $xStamp = max(0, min($xStamp, $size['width'] - $stampWidth));

                // Y positioning
                $yStamp = $size['height'] - floatval($bottomStampY);
                $stampHeight = 20;
                $yStamp = max(0, min($yStamp, $size['height'] - $stampHeight));

                $pdf->Image($stampPath, $xStamp, $yStamp, $stampWidth);
            }
        }

        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf');
    }
}