<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser; // Library for extracting PDF page count

class DcOtherCopyPaidController extends Controller
{
    // Fetch DC Other Copy List from database
    public function listDcOtherCopy()
    {
        try { 
            $dist_code = session('user.dist_code');
            $estd_code = session('user.estd_code');

            // Retrieve data using Laravel Query Builder
            $dcuserdata = DB::table('district_court_applicant_registration as dc')
                ->select(
                    'dc.*', // Select all columns from dc
                    'ct.type_name as case_type_name' // Fetch case type name from case type table
                )
                ->leftJoin('districts_case_type as ct', 'dc.case_type', '=', 'ct.case_type')
                ->where('dc.district_code', $dist_code) // Filter by district_code
                ->where('dc.establishment_code', $estd_code) // Filter by establishment_code
                ->orderBy('dc.created_at', 'desc')
                ->get();

                //Log::info('DC User Data:', $dcuserdata->toArray());

            // Return view with data using compact
            return view('admin.dc_other_copy.dc_other_copy_application_list', compact('dcuserdata'));

        } catch (\Exception $e) {
            Log::error('Error fetching DC Other Copy data', ['error' => $e->getMessage()]);

            return view('admin.dc_other_copy.dc_other_copy_application_list', ['dcuserdata' => []])
                ->with('error', 'An error occurred while fetching data.');
        }
    }

     // View DC Other Copy Details
     public function ViewDcOtherCopy($encryptedAppNumber)
     {
         try {
             $appNumber = Crypt::decrypt($encryptedAppNumber);
 
             $dcuser = DB::table('district_court_applicant_registration as dc')
                 ->select('dc.*', 'ct.type_name as case_type_name')
                 ->leftJoin('districts_case_type as ct', 'dc.case_type', '=', 'ct.case_type')
                 ->where('dc.application_number', $appNumber)
                 ->first();
 
             // Fetch uploaded documents
             $documents = DB::table('civilcourt_applicant_document_detail')
                 ->where('application_number', $appNumber)
                 ->get();
 
             return view('admin.dc_other_copy.dc_paid_copy_view', compact('dcuser', 'documents'));
 
         } catch (\Exception $e) {
             Log::error('Error fetching HC Other Copy details', ['error' => $e->getMessage()]);
             return redirect()->back()->with('error', 'An error occurred while fetching application details.');
         }
     }


    public function deleteCertifiedCopy(Request $request)
    {
        try {
            $document = DB::table('civilcourt_applicant_document_detail')->where('id', $request->document_id)->first();
    
            if (!$document) {
                return response()->json(['error' => 'Document not found.'], 404);
            }
    
            // Delete file from storage
            Storage::disk('public')->delete('districtcourt_other_copies/' . $document->file_name);
    
            // Remove entry from database
            DB::table('civilcourt_applicant_document_detail')->where('id', $request->document_id)->delete();
    
            return response()->json(['success' => 'Document deleted successfully.']);
    
        } catch (\Exception $e) {
            Log::error('Error deleting document', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while deleting the document.'], 500);
        }
    }



// Fetch DC Other Copy List
public function paidDcOtherCopyList()
{
    try {
        $dist_code = session('user.dist_code');
        $estd_code = session('user.estd_code');
        $dcuserdata = DB::table('district_court_applicant_registration as dc')
            ->select('dc.*', 'ct.type_name as case_type_name')
            ->leftJoin('districts_case_type as ct', 'dc.case_type', '=', 'ct.case_type')
            ->where('dc.district_code', $dist_code)
            ->where('dc.establishment_code', $estd_code)
            ->where('document_status',1)
            ->where('payment_status',1)
            ->orderBy('dc.created_at', 'desc')
            ->get();

        return view('admin.dc_other_copy.dc_other_copy_paid_list', compact('dcuserdata'));
    } catch (\Exception $e) {
        Log::error('Error fetching HC Other Copy data', ['error' => $e->getMessage()]);
        return view('admin.dc_other_copy.dc_other_copy_paid_list', ['dcuserdata' => []])
            ->with('error', 'An error occurred while fetching data.');
    }
}



public function uploadCertifiedCopy(Request $request)
{
    Log::info('Upload Certified Copy process started.', ['request_data' => $request->all()]);

    
    $request->validate([
        'id' => 'required',
        'document' => 'required|mimes:pdf|max:2048',
        'application_number' => 'required',
        'document_id' => 'required'
    ]);

    try {
        $id = Crypt::decrypt($request->id);
        // Get District and Month Name for Folder Structure
        $distName = strtolower(session('user.dist_name'));
        $monthName = strtolower(now()->format('Fy'));

        Log::info('Folder structure:', ['district' => $distName, 'month' => $monthName]);

        // Define folder path for Certified Copies
        $folderPath = "district_certified_other_copies/{$distName}/{$monthName}";

        // Generate a unique filename
        $filename = $request->application_number . '_' . time() . '.pdf';

        // Store file in the specified folder
        $path = $request->file('document')->storeAs($folderPath, $filename, 'public');

        Log::info('File stored successfully.', ['file_path' => $path]);

        // Update database
        $updated = DB::table('civilcourt_applicant_document_detail')
            ->where('id', $id) // Update based on the specific ID
            ->update([
                'certified_copy_file_name' => $filename,
                'certified_copy_upload_status' => true,
                'certified_copy_uploaded_date' => now(),
                'certified_copy_uploaded_by' => session('user.id'),
            ]);

        if ($updated) {
            Log::info('Database updated successfully for ID: ' . $request->id);
        } else {
            Log::warning('No rows updated. Check if ID exists.', ['id' => $request->id]);
        }

        return response()->json(['success' => 'Certified copy uploaded successfully!']);
    } catch (\Exception $e) {
        Log::error('Error in uploading certified copy:', ['error' => $e->getMessage()]);
        return response()->json(['error' => 'An error occurred while uploading the certified copy.'], 500);
    }
}

public function sendCertifiedCopyNotification(Request $request)
{
try {
    $applicationNumber = $request->input('application_number');

    // Update the document_status column to 1
    DB::table('district_court_applicant_registration')
        ->where('application_number', $applicationNumber)
        ->update(['document_status' => 1]);

    // (Optional) Fetch user details to send notification
    $user = DB::table('districct_court_applicant_registration')
        ->where('application_number', $applicationNumber)
        ->first();

    // Example Notification Logic (Email or SMS)
    if ($user) {
        // You can send an email or SMS notification here
        // Mail::to($user->email)->send(new DocumentNotificationMail($user));
        // or
        // Notification::send($user, new DocumentStatusNotification());
    }

    return redirect()->back()->with('success', 'Notification sent successfully and document status updated.');
} catch (\Exception $e) {
    Log::error('Error sending notification', ['error' => $e->getMessage()]);
    return redirect()->back()->with('error', 'An error occurred while sending the notification.');
}
}
    
 
}
