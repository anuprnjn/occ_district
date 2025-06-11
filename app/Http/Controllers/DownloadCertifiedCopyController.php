<?php 


namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Carbon\Carbon;
use ZipArchive;
use Illuminate\Support\Facades\File;

class DownloadCertifiedCopyController extends Controller
{

    public function highCourt(Request $request)
    {
        $applicationNumber = $request->input('application_number');

        // Case: HCW (Order Copy)
        if (Str::startsWith($applicationNumber, 'HCW')) {
            $applicant = DB::table('hc_order_copy_applicant_registration')
                ->where('application_number', $applicationNumber)
                ->first();

            if (!$applicant) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Application not found',
                ], 404);
            }

            $documents = DB::table('hc_order_details')
                ->where('application_number', $applicationNumber)
                ->get();

            $documents = $documents->map(function ($doc) {
                $formattedFolder = Carbon::parse($doc->certified_copy_uploaded_date)->format('Fy');
                $formattedFolder = strtolower($formattedFolder);
                $folder = "hc_certified_order_copies/{$formattedFolder}";
                $fileName = $doc->file_name;
                $filePath = "{$folder}/{$fileName}";

                $link = null;
                if (Storage::disk('public')->exists($filePath)) {
                    $link = url('/download-file/' . urlencode($fileName) . "?type=hc&folder={$formattedFolder}");
                }

                $doc->certified_copy_folder = $formattedFolder;
                $doc->certified_copy_links = $link ? [$link] : [];

                return $doc;
            });

            return response()->json([
                'status' => 'success',
                'message' => 'High Court data found',
                'data' => [
                    'applicant_details' => (array) $applicant,
                    'document_details' => $documents->toArray(),
                ],
            ]);
        }

        // Case: HC (Other Copy)
        $applicant = DB::table('high_court_applicant_registration')
            ->where('application_number', $applicationNumber)
            ->first();

        if (!$applicant) {
            return response()->json([
                'status' => 'error',
                'message' => 'Application not found',
            ], 404);
        }

        $documents = DB::table('highcourt_applicant_document_detail')
            ->where('application_number', $applicationNumber)
            ->get();

        $documents = $documents->map(function ($doc) {
            $formattedFolder = Carbon::parse($doc->certified_copy_uploaded_date)->format('Fy');
            $formattedFolder = strtolower($formattedFolder);
            $folder = "highcourt_certified_other_copies/{$formattedFolder}";
            $fileName = $doc->certified_copy_file_name;
            $filePath = "{$folder}/{$fileName}";

            $link = null;
            if (Storage::disk('public')->exists($filePath)) {
                $link = url('/download-file/' . urlencode($fileName) . "?type=highcourt&folder={$formattedFolder}");
            }

            $doc->certified_copy_folder = $formattedFolder;
            $doc->certified_copy_links = $link ? [$link] : [];

            return $doc;
        });

        return response()->json([
            'status' => 'success',
            'message' => 'High Court data found',
            'data' => [
                'applicant_details' => (array) $applicant,
                'document_details' => $documents->toArray(),
            ],
        ]);
    }
    
    public function downloadFile(Request $request, $filename)
    {
        $type = $request->query('type'); 
      
        $folderName = $request->query('folder'); 

        if (!$type || !$folderName) {
            abort(400, 'Missing type or folder information.');
        }

        if (!in_array($type, ['hc', 'highcourt'])) {
            abort(400, 'Invalid file type.');
        }

        // Set base folder
        if ($type === 'hc') {
            $folder = "hc_certified_order_copies/{$folderName}";
        } else {
            $folder = "highcourt_certified_other_copies/{$folderName}";
        }

        $filePath = $folder . '/' . $filename;

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found');
        }

        return response()->download(storage_path("app/public/{$filePath}"));
    }

//****************************************** */ civil court function to handel download certified copy ********************************

    public function civilCourt(Request $request)
    {
        $applicationNumber = $request->input('application_number');


        if (strlen($applicationNumber) >= 4 && strtoupper($applicationNumber[3]) === 'W') {
            $applicant = DB::table('district_court_order_copy_applicant_registration')
                ->where('application_number', $applicationNumber)
                ->first();

            if (!$applicant) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Application not found',
                ], 404);
            }

            $dist_code = $applicant->district_code;
            $districtName = DB::table('district_master')
                ->where('dist_code', $dist_code)
                ->value('dist_name') ?? 'unknown_district';

            $districtName = strtolower($districtName);

            // Step 2: Get document details
            $documents = DB::table('district_court_order_details')
                ->where('application_number', $applicationNumber)
                ->get();

            // Step 3: Add secure download links
            $documents = $documents->map(function ($doc) use ($applicationNumber) {
                $applicant = DB::table('district_court_order_copy_applicant_registration')
                    ->where('application_number', $applicationNumber)
                    ->first();

                $dist_code = $applicant->district_code;
                $districtName = DB::table('district_master')
                    ->where('dist_code', $dist_code)
                    ->value('dist_name') ?? 'unknown_district';

                // $districtName = strtolower($districtName);
                
                $formattedFolder = strtolower(Carbon::parse($doc->certified_copy_uploaded_date)->format('F') . substr(Carbon::parse($doc->certified_copy_uploaded_date)->format('Y'), -2));

                $folder = "dc_certified_order_copies/{$districtName}/{$formattedFolder}";
                $fileName = $doc->file_name;
                $filePath = "{$folder}/{$fileName}";

                $link = null;
                if (Storage::disk('public')->exists($filePath)) {
                    // Just return path instead of URL
                    // $link = $filePath;
                    // $link = url('/download-district-file/' . urlencode($fileName));
                    // without altering the file download link in dc order and judgement copies 
                    $link = Storage::url($filePath);
                } else {
                    Log::warning("File NOT FOUND at: " . $filePath);
                }

                $doc->certified_copy_folder = $folder;
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
                'message' => 'Civil court data found',
                'data' => $result,
            ]);
        }
        else{
            $applicant = DB::table('district_court_applicant_registration')
                ->where('application_number', $applicationNumber)
                ->first();

            if (!$applicant) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Application not found',
                ], 404);
            }

            $dist_code = $applicant->district_code;
            $districtName = DB::table('district_master')
                ->where('dist_code', $dist_code)
                ->value('dist_name') ?? 'unknown_district';

            $districtName = strtolower($districtName);

            // Step 2: Get document details
            $documents = DB::table('civilcourt_applicant_document_detail')
                ->where('application_number', $applicationNumber)
                ->get();

            // Step 3: Add secure download links
            $documents = $documents->map(function ($doc) use ($applicationNumber) {
                $applicant = DB::table('district_court_applicant_registration')
                    ->where('application_number', $applicationNumber)
                    ->first();

                $dist_code = $applicant->district_code;
                $districtName = DB::table('district_master')
                    ->where('dist_code', $dist_code)
                    ->value('dist_name') ?? 'unknown_district';

                $districtName = strtolower($districtName);
                
                $formattedFolder = strtolower(Carbon::parse($doc->certified_copy_uploaded_date)->format('F') . substr(Carbon::parse($doc->certified_copy_uploaded_date)->format('Y'), -2));

                $folder = "district_certified_other_copies/{$districtName}/{$formattedFolder}";
                $fileName = $doc->certified_copy_file_name;
                $filePath = "{$folder}/{$fileName}";

                // Log::info("Checking file: " . storage_path("app/public/{$filePath}"));

                $link = null;
                if (Storage::disk('public')->exists($filePath)) {
                    // Just return path instead of URL
                    // $link = $filePath;
                    $link = url('/download-district-file/' . urlencode($fileName));
                } else {
                    Log::warning("File NOT FOUND at: " . $filePath);
                }

                $doc->certified_copy_folder = $folder;
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
                'message' => 'Civil Court data found',
                'data' => $result,
            ]);
        }
    }

    public function downloadDistrictCourtFile(Request $request, $filename)
    {
        // Extract application number from filename (assuming underscore separated)
        $parts = explode('_', $filename);
        if (count($parts) < 1) {
            abort(404, 'Invalid file name');
        }
        $applicationNumber = $parts[0];

        // Query district court applicant to get district info
        $applicant = DB::table('district_court_applicant_registration')
            ->where('application_number', $applicationNumber)
            ->first();

        if (!$applicant) {
            abort(404, 'Application not found');
        }

        $dist_code = $applicant->district_code;
        $districtName = DB::table('district_master')
            ->where('dist_code', $dist_code)
            ->value('dist_name') ?? 'unknown_district';

        $districtName = strtolower($districtName);

        // Find document entry to get upload date for folder name
        $document = DB::table('civilcourt_applicant_document_detail')
            ->where('application_number', $applicationNumber)
            ->where('certified_copy_file_name', $filename)
            ->first();

        if (!$document) {
            abort(404, 'Document not found');
        }

        // Format folder name like "june25"
        $formattedFolder = strtolower(Carbon::parse($document->certified_copy_uploaded_date)->format('F') . substr(Carbon::parse($document->certified_copy_uploaded_date)->format('Y'), -2));

        $folder = "district_certified_other_copies/{$districtName}/{$formattedFolder}";
        $filePath = $folder . '/' . $filename;

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found');
        }

        // Return file download response
        return response()->download(storage_path('app/public/' . $filePath));
    }

}
