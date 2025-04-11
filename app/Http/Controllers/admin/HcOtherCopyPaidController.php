<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser; // Library for extracting PDF page count

class   HcOtherCopyPaidController extends Controller
{
    // Fetch HC Other Copy List
    public function paidHcOtherCopyList()
    {
        try {
            $hcuserdata = DB::table('high_court_applicant_registration as hc')
                ->select('hc.*', 'ct.type_name as case_type_name')
                ->leftJoin('high_court_case_type as ct', 'hc.case_type', '=', 'ct.case_type')
                ->where('document_status',1)
                ->where('payment_status',1)
                ->orderBy('hc.created_at', 'desc')
                ->get();

            return view('admin.hc_other_copy.hc_other_copy_paid_list', compact('hcuserdata'));
        } catch (\Exception $e) {
            Log::error('Error fetching HC Other Copy data', ['error' => $e->getMessage()]);
            return view('admin.hc_other_copy.hc_other_copy_paid_list', ['hcuserdata' => []])
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

            return view('admin.hc_other_copy.hc_paid_copy_view', compact('hcuser', 'documents'));

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
    
            foreach ($request->file('documents') as $key => $file) {
                $filename = $request->application_number . '_' . time(). '.pdf';
                $path = $file->storeAs('highcourt_other_copies', $filename, 'public');
    
                // Extract page count from PDF
                $pdf = $parser->parseFile($file->getPathname());
                $numberOfPages = count($pdf->getPages());
    
                // Automatically calculate amount (Example: ₹5 per page)
                $amount = $numberOfPages * 5;
    
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
            $monthName = strtolower(now()->format('Fy'));
    
            Log::info('Folder structure:', ['month' => $monthName]);
    
            // Define folder path for Certified Copies
            $folderPath = "highcourt_certified_other_copies/{$monthName}";
    
            // Generate a unique filename
            $filename = $request->application_number . '_' . time() . '.pdf';
    
            // Store file in the specified folder
            $path = $request->file('document')->storeAs($folderPath, $filename, 'public');
    
            Log::info('File stored successfully.', ['file_path' => $path]);
    
            // Update database
            $updated = DB::table('highcourt_applicant_document_detail')
                ->where('id', $id) // Update based on the specific ID
                ->update([
                    'certified_copy_file_name' => $filename,
                    'certified_copy_upload_status' => true,
                    'certified_copy_uploaded_date' => now(),
                    'certified_copy_uploaded_by' => session('user.id'),
                ]);
    
                if ($updated) {
                    Log::info('Database updated successfully for ID: ' . $request->id);
                
                    // Fetch updated row
                    $updatedData = DB::table('highcourt_applicant_document_detail')
                        ->where('id', $id)
                        ->first();
                
                    return response()->json([
                        'success' => 'Certified copy uploaded successfully!',
                        'id' => $updatedData->id,
                        'certified_copy_file_name' => $updatedData->certified_copy_file_name,
                        'certified_copy_upload_status' => $updatedData->certified_copy_upload_status,
                    ]);
                } else {
                Log::warning('No rows updated. Check if ID exists.', ['id' => $request->id]);
            }
    
            return response()->json(['success' => 'Certified copy uploaded successfully!']);
        } catch (\Exception $e) {
            Log::error('Error in uploading certified copy:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while uploading the certified copy.'], 500);
        }
    }
    
    
    public function deleteCertifiedCopy(Request $request, $id)
    {
        Log::info('Delete Certified Copy process started.', ['route_id' => $id]);
    
        try {
            $decryptedId = Crypt::decrypt($id);
            Log::info('Delete Certified Copy process started.', ['route_id' => $id]);
            $document = DB::table('highcourt_applicant_document_detail')->where('id', $decryptedId)->first();
    
           if (!$document) {
               return response()->json(['error' => 'Document not found.'], 404);
           }
           $date=$document->certified_copy_uploaded_date;
           $monthName = strtolower(Carbon::parse($date)->format('Fy'));
    
           // Delete file from storage
           Storage::disk('public')->delete("highcourt_certified_other_copies/{$monthName}/" . $document->certified_copy_file_name);
            // Update database
            $updated = DB::table('highcourt_applicant_document_detail')
                ->where('id', $decryptedId)
                ->update([
                    'certified_copy_file_name' => null,
                    'certified_copy_upload_status' => false,
                    'certified_copy_uploaded_date' => null,
                    'certified_copy_uploaded_by' => null,
                ]);
    
            if ($updated) {
                Log::info('Database updated for ID: ' . $decryptedId);
                $updatedData = DB::table('highcourt_applicant_document_detail')->where('id', $decryptedId)->first();
    
                return response()->json([
                    'success' => 'Certified copy deleted successfully!',
                    'id' => $updatedData->id,
                    'certified_copy_file_name' => $updatedData->certified_copy_file_name,
                    'certified_copy_upload_status' => $updatedData->certified_copy_upload_status,
                ]);
            } else {
                Log::warning('No rows updated. Check if ID exists.', ['id' => $decryptedId]);
                return response()->json(['error' => 'No changes made. ID may be incorrect.'], 404);
            }
        } catch (\Exception $e) {
            Log::error('Error deleting certified copy:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while deleting the certified copy.'], 500);
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
