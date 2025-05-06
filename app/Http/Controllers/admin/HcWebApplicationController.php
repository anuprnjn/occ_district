<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;

class HcWebApplicationController extends Controller
{
    // Fetch HC User List
    public function listHcWebApplication()
    {
        try {
            $hcuserdata = DB::table('hc_order_copy_applicant_registration as apr')
                ->select(
                    'apr.*',
                    DB::raw('COALESCE(ct1.type_name, ct2.type_name) AS type_name')
                )
                ->leftJoin('high_court_case_master as ct1', 'ct1.case_type', '=', 'apr.case_type')
                ->leftJoin('high_court_case_master as ct2', 'ct2.case_type', '=', 'apr.filingcase_type')
                ->orderBy('apr.created_at', 'desc')
                ->get();

            return view('admin.hc_web_copy.hc_web_application_list', compact('hcuserdata'));
        } catch (\Exception $e) {
            Log::error('Error fetching HC User data', ['error' => $e->getMessage()]);
            return back()->with('error', 'An error occurred while fetching data.');
        }
    }

    // View Application Details with Order Data
    public function viewHcWebApplication($encryptedAppNumber)
    {
        try {
            $appNumber = Crypt::decrypt($encryptedAppNumber);

            $hcuser = DB::table('hc_order_copy_applicant_registration as apr')
                ->select('apr.*', DB::raw('COALESCE(ct1.type_name, ct2.type_name) AS type_name'))
                ->leftJoin('high_court_case_master as ct1', 'ct1.case_type', '=', 'apr.case_type')
                ->leftJoin('high_court_case_master as ct2', 'ct2.case_type', '=', 'apr.filingcase_type')
                ->where('apr.application_number', $appNumber)
                ->first();

            $transaction_details = DB::table('transaction_master_hc')
            ->where('application_number', $appNumber)
            ->where('payment_status', '1')
            ->first();     
           

            if (!$hcuser) {
                return redirect()->route('hc-web-application.list')->with('error', 'Application not found.');
            }

            $ordersdata = DB::table('hc_order_details')
                ->where('application_number', $appNumber)
                ->orderBy('order_number', 'asc')
                ->get();
             // Calculate total difference using raw SQL
    $totaldiff = DB::table('hc_order_details')
    ->where('application_number', $appNumber)
    ->selectRaw('SUM(new_page_no) - SUM(number_of_page) AS totaldiff')
    ->value('totaldiff'); // Fetch only the computed value 
    
    $perpagefee = DB::table('fee_master')
            ->where('fee_type', 'per_page_fee')
            ->value('amount');

            return view('admin.hc_web_copy.hc_web_application_view', compact('hcuser', 'ordersdata','totaldiff','perpagefee','transaction_details'));
        } catch (\Exception $e) {
            Log::error('Error fetching HC User details', ['error' => $e->getMessage()]);
            return redirect()->route('hc-web-application.list')->with('error', 'An error occurred.');
        }
    }

    // Upload Order PDF and Count Pages
    public function uploadOrderCopy(Request $request)
{ 
    $request->validate([
        'application_number' => 'required',
        'order_number' => 'required',
        'pdf_file' => 'required|mimes:pdf|max:2048'
    ]);

    try {
        // Fetch the per page cost from fee_master table
        $perPageFee = DB::table('fee_master')
            ->where('fee_type', 'per_page_fee')
            ->value('amount');

        if (!$perPageFee) {
            return back()->with('error', 'Per page fee not found in fee master.');
        }

        // Check if the order exists
        $order = DB::table('hc_order_details')
            ->where('application_number', $request->application_number)
            ->where('order_number', $request->order_number)
            ->first();

        if (!$order) {
            return back()->with('error', 'Order details not found.');
        }
        // Delete old file if exists
        if ($order->file_name) {
            Storage::disk('public')->delete('order_copies/' . $order->file_name);
        }

        // Store PDF
        $fileName = $request->application_number . '_' . $request->order_number . '_' . time() . '.pdf';
        $filePath = $request->file('pdf_file')->storeAs('order_copies', $fileName, 'public');

        // Log file path
        Log::info('File stored at:', ['path' => Storage::disk('public')->path('order_copies/' . $fileName)]);

        // Count PDF pages
        $parser = new Parser();
        $pdf = $parser->parseFile(Storage::disk('public')->path('order_copies/' . $fileName));
        $pageCount = count($pdf->getPages());

        // Calculate the new page amount
        $newPageAmount = $perPageFee * $pageCount;

        // Update database
        DB::table('hc_order_details')
            ->where('application_number', $request->application_number)
            ->where('order_number', $request->order_number)
            ->update([
                'file_name' => $fileName,
                'upload_status' => true,
                'new_page_no' => $pageCount,
                'new_page_amount' => $newPageAmount, // Update with calculated amount
            ]);

        // Check if all orders for this application have been uploaded
        $allOrdersUploaded = DB::table('hc_order_details')
            ->where('application_number', $request->application_number)
            ->where('upload_status', false)
            ->doesntExist();

        if ($allOrdersUploaded) {
            // Update document_status in hc_order_copy_applicant_registration table
            DB::table('hc_order_copy_applicant_registration')
                ->where('application_number', $request->application_number)
                ->update(['document_status' => 1]);
        }

        return back()->with('success', 'PDF uploaded successfully with ' . $pageCount . ' pages. Total amount: ' . number_format($newPageAmount, 2));
    } catch (\Exception $e) {
        Log::error('Error uploading PDF', ['error' => $e->getMessage()]);
        return back()->with('error', 'Failed to upload PDF. Error: ' . $e->getMessage());
    }
}
    


    // Download Order PDF
    public function downloadOrderCopy($fileName)
{
    $filePath = Storage::disk('public')->path('order_copies/' . $fileName);
    
    if (file_exists($filePath)) {
        return response()->file($filePath);
    }

    return back()->with('error', 'File not found.');
}
    // Delete Order PDF
    // Delete PDF
    public function deleteOrderCopy($applicationNumber, $orderNumber)
{
    try {
        $order = DB::table('hc_order_details')
            ->where('application_number', $applicationNumber)
            ->where('order_number', $orderNumber)
            ->first();

        if (!$order || !$order->file_name) {
            return back()->with('error', 'File not found.');
        }

        // Delete File from Storage
        Storage::disk('public')->delete('order_copies/' . $order->file_name);

        // Update Database
        DB::table('hc_order_details')
            ->where('application_number', $applicationNumber)
            ->where('order_number', $orderNumber)
            ->update([
                'file_name' => null,
                'upload_status' => false,
                'new_page_no' => null,
                'new_page_amount' => null,
            ]);

        // Check if at least one order has been deleted (upload_status = false exists)
        $atLeastOneDeleted = DB::table('hc_order_details')
            ->where('application_number', $applicationNumber)
            ->where('upload_status', false)
            ->exists();

        if ($atLeastOneDeleted) {
            // Update document_status in hc_order_copy_applicant_registration table to 0
            DB::table('hc_order_copy_applicant_registration')
                ->where('application_number', $applicationNumber)
                ->update(['document_status' => 0]);
        }

        return back()->with('success', 'File deleted successfully.');
    } catch (\Exception $e) {
        Log::error('Error deleting PDF', ['error' => $e->getMessage()]);
        return back()->with('error', 'Failed to delete PDF.');
    }
}
public function sendDeficitNotification(Request $request)
{
    try {
        $appNumber = $request->input('application_number');
        $totalDeficitAmount = $request->input('total_deficit_amount');

        // Update deficit_status = 1 and total_deficit_amount
        DB::table('hc_order_copy_applicant_registration')
            ->where('application_number', $appNumber)
            ->update([
                'deficit_status' => 1,
                'deficit_amount' => $totalDeficitAmount
            ]);

        return back()->with('success', 'Deficit notification sent successfully.');
    } catch (\Exception $e) {
        Log::error('Error sending deficit notification', ['error' => $e->getMessage()]);
        return back()->with('error', 'Failed to send notification.');
    }
}

public function sendReadyNotification(Request $request)
{
    try {
        $appNumber = $request->input('application_number');

        // Update deficit_status = 1 and total_deficit_amount
        DB::table('hc_order_copy_applicant_registration')
            ->where('application_number', $appNumber)
            ->update([
                'certified_copy_ready_status' => 1,
                'user_id' => session('user.id')
               
            ]);

        return back()->with('success', 'Download notification sent successfully to Applicant.');
    } catch (\Exception $e) {
        Log::error('Error sending Download notification', ['error' => $e->getMessage()]);
        return back()->with('error', 'Failed to send Download notification.');
    }
}

}
