<?php 


namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DownloadCertifiedCopyController extends Controller
{
    public function getDistricts()
    {
        // Select both dist_name and dist_code from the table
        $districts = DB::table('district_master')
            ->select('dist_name', 'dist_code')
            ->orderBy('dist_name')
            ->get();

        return response()->json($districts);
    }

    // public function highCourt(Request $request)
    // {
    //     $applicationNumber = $request->input('application_number');

    //     // Step 1: Get applicant details
    //     $applicant = DB::table('high_court_applicant_registration')
    //         ->where('application_number', $applicationNumber)
    //         ->first();

    //     if (!$applicant) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Application not found',
    //         ], 404);
    //     }

    //     // Step 2: Get document details
    //     $documents = DB::table('highcourt_applicant_document_detail')
    //         ->where('application_number', $applicationNumber)
    //         ->get();

    //     // Step 3: Add folder + PDF links to each document detail
    //     $documents = $documents->map(function ($doc) use ($applicationNumber) {
    //         $formattedFolder = Carbon::parse($doc->certified_copy_uploaded_date)->format('My');
    //         $formattedFolder = strtolower($formattedFolder);

    //         $folder = "highcourt_certified_other_copies/{$formattedFolder}";

    //         // Get all files from the folder on the 'public' disk
    //         $files = Storage::disk('public')->files($folder);

    //         // Match files by application number and generate public URLs
    //         $matchingLinks = [];
    //         foreach ($files as $filePath) {
    //             $fileName = basename($filePath);
    //             if (Str::startsWith($fileName, $applicationNumber)) {
    //                 $matchingLinks[] = asset('storage/' . $filePath);
    //             }
    //         }

    //         $doc->certified_copy_folder = $formattedFolder;
    //         $doc->certified_copy_links = $matchingLinks;

    //         return $doc;
    //     });

    //     // Step 4: Build final result
    //     $result = [
    //         'applicant_details' => (array) $applicant,
    //         'document_details' => $documents->toArray(),
    //     ];

    //     // Return JSON response
    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'High Court data found',
    //         'data' => $result,
    //     ]);
    // }


    public function highCourt(Request $request)
    {
        $applicationNumber = $request->input('application_number');

        // Step 1: Get applicant details
        $applicant = DB::table('high_court_applicant_registration')
            ->where('application_number', $applicationNumber)
            ->first();

        if (!$applicant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Application not found',
            ], 404);
        }

        // Step 2: Get document details
        $documents = DB::table('highcourt_applicant_document_detail')
            ->where('application_number', $applicationNumber)
            ->get();

        // Step 3: Add secure download links
        $documents = $documents->map(function ($doc) use ($applicationNumber) {
            $formattedFolder = Carbon::parse($doc->certified_copy_uploaded_date)->format('My');
            $formattedFolder = strtolower($formattedFolder);
            $folder = "highcourt_certified_other_copies/{$formattedFolder}";

            $fileName = $doc->certified_copy_file_name;
            $filePath = "{$folder}/{$fileName}";

            $link = null;
            if (Storage::disk('public')->exists($filePath)) {
                // Instead of direct URL, return an internal download link
                $link = url('/download-file/' . urlencode($fileName));
            }

            $doc->certified_copy_folder = $formattedFolder;
            $doc->certified_copy_links = $link ? [$link] : [];

            return $doc;
        });

        // Step 4: Final result
        $result = [
            'applicant_details' => (array) $applicant,
            'document_details' => $documents->toArray(),
        ];

        return response()->json([
            'status' => 'success',
            'message' => 'High Court data found',
            'data' => $result,
        ]);
    }
    public function downloadFile($filename)
    {
        $folders = Storage::disk('public')->directories('highcourt_certified_other_copies');

        foreach ($folders as $folder) {
            $fullPath = $folder . '/' . $filename;
            if (Storage::disk('public')->exists($fullPath)) {
                return response()->download(storage_path('app/public/' . $fullPath));
            }
        }
        abort(404, 'File not found');
    }


    public function civilCourt(Request $request)
    {
        $applicationNumber = $request->input('application_number');
        $districtCode = $request->input('district_code');
        $districtName = $request->input('district_name');
        dd([
            $applicationNumber,
            $districtName,
            $districtCode
        ]);
        exit();
        // Logic to fetch certified copy for DC
        return response()->json([
            'status' => 'success',
            'message' => 'Civil Court data received',
            'application_number' => $applicationNumber,
            'district_code' => $districtCode,
            'district_name' => $districtName,
        ]);
    }
}
