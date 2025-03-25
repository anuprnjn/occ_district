<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser; // Library for extracting PDF page count

class HcOtherCopyController extends Controller
{
    // Fetch HC Other Copy List
    public function listHcOtherCopy()
    {
        try {
            $hcuserdata = DB::table('high_court_applicant_registration as hc')
                ->select('hc.*', 'ct.type_name as case_type_name')
                ->leftJoin('high_court_case_type as ct', 'hc.case_type', '=', 'ct.case_type')
                ->where('rejection_status',0)
                ->orderBy('hc.created_at', 'desc')
                ->get();

            return view('admin.hc_other_copy.hc_other_copy_application_list', compact('hcuserdata'));
        } catch (\Exception $e) {
            Log::error('Error fetching HC Other Copy data', ['error' => $e->getMessage()]);
            return view('admin.hc_other_copy.hc_other_copy_application_list', ['hcuserdata' => []])
                ->with('error', 'An error occurred while fetching data.');
        }
    }

    // View HC Other Copy Details
    public function ViewHcOtherCopy($encryptedAppNumber)
    {
        try {
            $appNumber = Crypt::decrypt($encryptedAppNumber);

            $hcuser = DB::table('high_court_applicant_registration as hc')
                ->select('hc.*', 'ct.type_name as case_type_name')
                ->leftJoin('high_court_case_type as ct', 'hc.case_type', '=', 'ct.case_type')
                ->where('hc.application_number', $appNumber)
                ->first();

            // Fetch uploaded documents
            $documents = DB::table('highcourt_applicant_document_detail')
                ->where('application_number', $appNumber)
                ->get();

            return view('admin.hc_other_copy.hc_other_copy_application_view', compact('hcuser', 'documents'));

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
            'documents.*' => 'required|mimes:pdf|max:5120', // Only PDFs, max 5MB
            'document_types.*' => 'required|string|max:200',
        ]);
    
        try {
            $parser = new Parser(); // PDF parser instance
    
            $perPageAmount = DB::table('fee_master as fm')
                ->select('fm.amount')
                ->where('fm.fee_type', 'per_page_fee')
                ->first();
            $amount = ($perPageAmount ? $perPageAmount->amount : 5);
            Log::info('Per Page Amount: ' . $amount);

            foreach ($request->file('documents') as $key => $file) {
                $filename = $request->application_number . '_' . time(). '.pdf';
                $path = $file->storeAs('highcourt_other_copies', $filename, 'public');
    
                // Extract page count from PDF
                $pdf = $parser->parseFile($file->getPathname());
                $numberOfPages = count($pdf->getPages());
    
                // Automatically calculate amount (Example: ₹5 per page)
                $amount = $numberOfPages * $amount;
    
                // Insert document details into the database
                DB::table('highcourt_applicant_document_detail')->insert([
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
            $document = DB::table('highcourt_applicant_document_detail')->where('id', $request->document_id)->first();
    
            if (!$document) {
                return response()->json(['error' => 'Document not found.'], 404);
            }
    
            // Delete file from storage
            Storage::disk('public')->delete('highcourt_other_copies/' . $document->file_name);
    
            // Remove entry from database
            DB::table('highcourt_applicant_document_detail')->where('id', $request->document_id)->delete();
    
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
        DB::table('high_court_applicant_registration')
            ->where('application_number', $applicationNumber)
            ->update(['document_status' => 1]);

        // (Optional) Fetch user details to send notification
        $user = DB::table('high_court_applicant_registration')
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
        DB::table('high_court_applicant_registration')
            ->where('application_number', $applicationNumber)
            ->update([
                'rejection_status' => 1,
                'rejection_remarks' => $rejectionRemarks,
                'rejection_date' => now(),
                'rejected_by' => session('user.id')
            ]);

        // (Optional) Fetch user details to send notification
        $user = DB::table('high_court_applicant_registration')
            ->where('application_number', $applicationNumber)
            ->first();

        // Example Notification Logic (Email or SMS)
        if ($user) {
            // Mail::to($user->email)->send(new ApplicationRejectedMail($user));
            // Notification::send($user, new ApplicationRejectedNotification());
        }

        // Redirect to the desired route
        return redirect()->route('hc_other_copy_rejected_application')->with('success', 'Application rejected successfully.');

    } catch (\Exception $e) {
        Log::error('Error rejecting application', ['error' => $e->getMessage()]);
        return redirect()->route('hc_other_copy_rejected_application')->with('error', 'An error occurred while rejecting the application.');
    }
}

public function rejectedHcOtherCopy()
    {
        try {
            $hcuserdata = DB::table('high_court_applicant_registration as hc')
                ->select('hc.*', 'ct.type_name as case_type_name')
                ->leftJoin('high_court_case_type as ct', 'hc.case_type', '=', 'ct.case_type')
                ->where('rejection_status',1)
                ->orderBy('hc.created_at', 'desc')
                ->get();

            return view('admin.hc_other_copy.hc_other_copy_rejected_list', compact('hcuserdata'));
        } catch (\Exception $e) {
            Log::error('Error fetching HC Other Copy data', ['error' => $e->getMessage()]);
            return view('admin.hc_other_copy.hc_other_copy_rejected_list', ['hcuserdata' => []])
                ->with('error', 'An error occurred while fetching data.');
        }
    }


}
