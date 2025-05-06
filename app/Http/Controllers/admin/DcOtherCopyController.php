<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser; // Library for extracting PDF page count

class DcOtherCopyController extends Controller
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
                ->leftJoin('district_court_case_master as ct', 'dc.case_type', '=', 'ct.case_type')
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
                 ->leftJoin('district_court_case_master as ct', 'dc.case_type', '=', 'ct.case_type')
                 ->where('dc.application_number', $appNumber)
                 ->first();
 
             // Fetch uploaded documents
             $documents = DB::table('civilcourt_applicant_document_detail')
                 ->where('application_number', $appNumber)
                 ->get();
 
             return view('admin.dc_other_copy.dc_other_copy_application_view', compact('dcuser', 'documents'));
 
         } catch (\Exception $e) {
             Log::error('Error fetching HC Other Copy details', ['error' => $e->getMessage()]);
             return redirect()->back()->with('error', 'An error occurred while fetching application details.');
         }
     }

         // Upload Document (Automatically Calculates Pages & Amount)
    public function uploadDocument(Request $request)
    {
        $request->validate([
            'application_number' => 'required|string',
            'documents.*' => 'required|mimes:pdf|max:20480', // Only PDFs, max 5MB
            'document_types.*' => 'required|string|max:200',
        ]);
    
        try {
            $parser = new Parser(); // PDF parser instance
            $distName = strtolower(session('user.dist_name'));
            $monthName = strtolower(now()->format('Fy')); 

            $perPageAmount = DB::table('fee_master as fm')
                ->select('fm.amount')
                ->where('fm.fee_type', 'per_page_fee')
                ->first();
              $amount = ($perPageAmount ? $perPageAmount->amount : 5);
              Log::info('Per Page Amount: ' . $amount);
           
            foreach ($request->file('documents') as $key => $file) {
                $folderPath = "district_other_copies/{$distName}/{$monthName}";
                $filename = $request->application_number . '_' . time(). '.pdf';
                $path = $file->storeAs($folderPath, $filename, 'public');
    
                // Extract page count from PDF
                $pdf = $parser->parseFile($file->getPathname());
                $numberOfPages = count($pdf->getPages());
    
                // Automatically calculate amount (Example: ₹5 per page)
                $amount = $numberOfPages * $amount;
    
                // Insert document details into the database
                DB::table('civilcourt_applicant_document_detail')->insert([
                    'application_number' => $request->application_number,
                    'document_type' => $request->document_types[$key],
                    'number_of_page' => $numberOfPages,
                    'amount' => $amount,
                    'file_name' => $filename,
                    'upload_status' => true,
                    'uploaded_date' => now(),
                    'uploaded_by' => session('user.id'), // Replace with actual user ID
                    'created_at' => now(),
                ]);
            }
    
            return response()->json(['success' => 'Documents uploaded successfully.']);
    
        } catch (\Exception $e) {
            Log::error('Error uploading documents', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while uploading documents.'], 500);
        }
    }


    public function deleteDocument(Request $request)
    {
        try {
            $document = DB::table('civilcourt_applicant_document_detail')->where('id', $request->document_id)->first();
    
            if (!$document) {
                return response()->json(['error' => 'Document not found.'], 404);
            }

            $date=$document->uploaded_date;
            $distName = strtolower(session('user.dist_name'));
            $monthName = strtolower(Carbon::parse($date)->format('Fy'));
    
            // Delete file from storage
            Storage::disk('public')->delete("district_other_copies/{distName}/{$monthName}/" . $document->file_name);
    
            // Remove entry from database
            DB::table('civilcourt_applicant_document_detail')->where('id', $request->document_id)->delete();
    
            return response()->json(['success' => 'Document deleted successfully.']);
    
        } catch (\Exception $e) {
            Log::error('Error deleting document', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while deleting the document.'], 500);
        }
    }

    public function sendNotification(Request $request)
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

public function rejectApplication(Request $request)
{
    try {
        $applicationNumber = $request->input('application_number');
        $rejectionRemarks = $request->input('rejection_remarks');

        // Update the application status to 'rejected' and save rejection remarks
        DB::table('district_court_applicant_registration')
            ->where('application_number', $applicationNumber)
            ->update([
                'rejection_status' => 1,
                'rejection_remarks' => $rejectionRemarks,
                'rejection_date' => now(),
                'rejected_by' => session('user.id')
            ]);

        // (Optional) Fetch user details to send notification
        $user = DB::table('district_court_applicant_registration')
            ->where('application_number', $applicationNumber)
            ->first();

        // Example Notification Logic (Email or SMS)
        if ($user) {
            // Mail::to($user->email)->send(new ApplicationRejectedMail($user));
            // Notification::send($user, new ApplicationRejectedNotification());
        }

        // Redirect to the desired route
        return redirect()->route('dc_other_copy_rejected_application')->with('success', 'Application rejected successfully.');

    } catch (\Exception $e) {
        Log::error('Error rejecting application', ['error' => $e->getMessage()]);
        return redirect()->route('dc_other_copy_rejected_application')->with('error', 'An error occurred while rejecting the application.');
    }
}


public function rejectedDcOtherCopy()
{
    try {
          
        $dist_code = session('user.dist_code');
        $estd_code = session('user.estd_code');
        $dcuserdata = DB::table('district_court_applicant_registration as dc')
            ->select('dc.*', 'ct.type_name as case_type_name')
            ->leftJoin('district_court_case_master as ct', 'dc.case_type', '=', 'ct.case_type')
            ->where('dc.district_code', $dist_code)
            ->where('dc.establishment_code', $estd_code)
            ->where('rejection_status',1)
            ->orderBy('dc.created_at', 'desc')
            ->get();

        return view('admin.dc_other_copy.dc_other_copy_rejected_list', compact('dcuserdata'));
    } catch (\Exception $e) {
        Log::error('Error fetching HC Other Copy data', ['error' => $e->getMessage()]);
        return view('admin.dc_other_copy.dc_other_copy_rejected_list', ['dcuserdata' => []])
            ->with('error', 'An error occurred while fetching data.');
    }
}


// Fetch HC Other Copy List
public function paidDcOtherCopyList()
{
    try {
        $dist_code = session('user.dist_code');
        $estd_code = session('user.estd_code');
        $dcuserdata = DB::table('district_court_applicant_registration as dc')
            ->select('dc.*', 'ct.type_name as case_type_name')
            ->leftJoin('district_court_case_master as ct', 'dc.case_type', '=', 'ct.case_type')
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
    
 
}
