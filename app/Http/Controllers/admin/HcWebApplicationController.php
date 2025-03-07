<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HcWebApplicationController extends Controller
{
    // Fetch HC User List from database
    public function listHcWebApplication()
    {
        try {
            // Retrieve data using Laravel Query Builder
            $hcuserdata = DB::table('hc_order_copy_applicant_registration as apr')
                ->select(
                    'apr.*', // Select all columns from apr
                    DB::raw('COALESCE(ct1.type_name, ct2.type_name) AS type_name') // Handles case type and filing case type
                )
                ->leftJoin('high_court_case_type as ct1', 'ct1.case_type', '=', 'apr.case_type')
                ->leftJoin('high_court_case_type as ct2', 'ct2.case_type', '=', 'apr.filingcase_type')
                ->orderBy('apr.created_at', 'desc')
                ->get();

            // Return view with data using compact
            return view('admin.hc_web_copy.hc_web_application_list', compact('hcuserdata'));

        } catch (\Exception $e) {
            Log::error('Error fetching HC User data', ['error' => $e->getMessage()]);

            return view('admin.hc_web_copy.hc_web_application_list', compact('hcuserdata'))
                ->with('error', 'An error occurred while fetching data.');
        }
    }

    // View Application Details with Order Data
    public function viewHcWebApplication($encryptedAppNumber)
    {
        try {
            $appNumber = Crypt::decrypt($encryptedAppNumber);

            // Fetch HC User Data
            $hcuser = DB::table('hc_order_copy_applicant_registration as apr')
                ->select('apr.*', DB::raw('COALESCE(ct1.type_name, ct2.type_name) AS type_name'))
                ->leftJoin('high_court_case_type as ct1', 'ct1.case_type', '=', 'apr.case_type')
                ->leftJoin('high_court_case_type as ct2', 'ct2.case_type', '=', 'apr.filingcase_type')
                ->where('apr.application_number', $appNumber)
                ->first();

            if (!$hcuser) {
                return redirect()->route('hc-web-application.list')->with('error', 'Application not found.');
            }

            // Fetch Order Details
            $ordersdata = DB::table('hc_order_details')
                ->where('application_number', $appNumber)
                ->orderBy('order_number', 'asc')
                ->get();

            return view('admin.hc_web_copy.hc_web_application_view', compact('hcuser', 'ordersdata'));

        } catch (\Exception $e) {
            Log::error('Error fetching HC User details', ['error' => $e->getMessage()]);
            return redirect()->route('hc-web-application.list')->with('error', 'An error occurred.');
        }
    }

    // Upload Order PDF
    public function uploadOrderCopy(Request $request)
    {
        $request->validate([
            'order_number' => 'required|exists:hc_order_details,order_number',
            'pdf_file' => 'required|mimes:pdf|max:2048'
        ]);

        try {
            $order = DB::table('hc_order_details')->where('order_number', $request->order_number)->first();
            if (!$order) {
                return back()->with('error', 'Order not found.');
            }

            // Store PDF
            $fileName = 'order_' . $request->order_number . '_' . time() . '.pdf';
            $filePath = $request->file('pdf_file')->storeAs('public/order_copies', $fileName);

            // Update database
            DB::table('hc_order_details')
                ->where('order_number', $request->order_number)
                ->update([
                    'file_name' => $fileName,
                    'upload_status' => true
                ]);

            return back()->with('success', 'PDF uploaded successfully.');
        } catch (\Exception $e) {
            Log::error('Error uploading PDF', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to upload PDF.');
        }
    }

    // Download PDF
    public function downloadOrderCopy($fileName)
    {
        $filePath = storage_path('app/public/order_copies/' . $fileName);

        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            return back()->with('error', 'File not found.');
        }
    }

    // Delete PDF
    public function deleteOrderCopy($orderNumber)
    {
        try {
            $order = DB::table('hc_order_details')->where('order_number', $orderNumber)->first();
            if (!$order || !$order->file_name) {
                return back()->with('error', 'File not found.');
            }

            // Delete File from Storage
            Storage::delete('public/order_copies/' . $order->file_name);

            // Update Database
            DB::table('hc_order_details')
                ->where('order_number', $orderNumber)
                ->update([
                    'file_name' => null,
                    'upload_status' => false
                ]);

            return back()->with('success', 'File deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting PDF', ['error' => $e->getMessage()]);
            return back()->with('error', 'Failed to delete PDF.');
        }
    }

}
