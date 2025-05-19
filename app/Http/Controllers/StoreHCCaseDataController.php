<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\DB;

class StoreHCCaseDataController extends Controller
{
    public function storeHCCaseDetails(Request $request)
    {
      
        $caseDetails = $request->input('caseDetails');
        Session::put('HcCaseDetailsNapix', $caseDetails);
      
        return response()->json([
            'message' => 'Case details stored in session successfully',
            'redirectLocation' => url('/caseInformation') 
        ]);
    }

    public function setHcCaseInfoData(Request $request) { 
        $caseInfoDetails = $request->input('caseInfoDetails'); 
        // dd(session()->all());
        // exit();
         // Check if per_page_fee is available in the database
         $feePerPage = DB::table('fee_master')->where('fee_type', 'per_page_fee')->value('amount') ?? 5; // Default to 5 if not found
         $data = session('HcCaseDetailsNapix');
         $cino = $data['cino'];
         $pdfFolder = storage_path("app/public/napix_pdf/high_court/{$cino}");
     
         $orders = [];
     
         // Loop through selected orders to process PDF and calculate amount
         foreach ($caseInfoDetails['selectedOrders'] as $order) {
             $orderNo = $order['order_no'];
             $orderDate = $order['order_date'];
             $caseNo = $order['case_no'] ?? '';
             $filNo = $order['fil_no'] ?? '';
     
             // Construct the expected PDF file path
             $pdfPath = "{$pdfFolder}/{$cino}_{$orderNo}_napix.pdf";
     
             // Check if the PDF exists
             if (!file_exists($pdfPath)) {
                 return response()->json(['error' => "PDF not found for Order No: $orderNo"], 404);
             }
     
             // Attempt to read the PDF and count pages
             try {
                 $pdf = new Fpdi();
                 $pageCount = $pdf->setSourceFile($pdfPath);
             } catch (\Exception $e) {
                 return response()->json([
                     'error' => "Failed to read PDF for Order No: $orderNo",
                     'details' => $e->getMessage()
                 ], 500);
             }
     
             // Add order details along with page count and amount to the orders array
             $orders[] = [
                 'order_no' => $orderNo,
                 'order_date' => $orderDate,
                 'pages' => $pageCount,
                 'amount' => $pageCount * $feePerPage,
                 'case_no' => $caseNo,
                 'fil_no' => $filNo
             ];
         }
         $urgentFee = \DB::table('fee_master')
         ->where('fee_type', 'urgent_fee')
         ->value('amount');

         $caseInfoDetails['selectedOrders'] = $orders;
         $caseInfoDetails['urgent_fee'] = $urgentFee; 
        Session::put('caseInfoDetails', $caseInfoDetails);
        Session::save();
        Session::reflash();
       

        return response()->json([
            'message' => 'Case Info Data Stored Successfully!',
            'session_data' => Session::all(),
            'location' => '/occ/cd_pay'
        ]);
    }


    public function calculateFinalPayableAmount(Request $request)
    {
        $orders = $request->input('orders');
        $request_mode = $request->input('request_mode');
    
        // Step 1: Sum all amounts from orders
        $totalAmount = 0;
        foreach ($orders as $order) {
            $totalAmount += $order['amount'];
        }
    
        // Step 2: Get urgent_fee from the DB
        $urgentFee = \DB::table('fee_master')
            ->where('fee_type', 'urgent_fee')
            ->value('amount');
    
        // Step 3: Add urgent_fee to total
        if ($request_mode === 'urgent') {
            $finalAmount = $totalAmount + $urgentFee;
        } else {
            $finalAmount = $totalAmount;
            $urgentFee = 0; // no urgent fee in ordinary mode
        }

        // dd($finalAmount);
        // exit();
    
        // Step 4: Store total and fee in session
        session([
            'hc_final_amount_summary' => [
                'total_order_amount' => $totalAmount,
                'urgent_fee' => $urgentFee,
                'final_payable_amount' => $finalAmount
            ]
        ]);
    
        // Step 5: Return success response with data
        return response()->json([
            'status' => 'success',
            'message' => 'Total amount is calculated successfully',
            'data' => session('hc_final_amount_summary')
        ]);
    }  
    
     // function to initialte the final amount with security 

     public function initiatePayment()
     {
         $amount = session('hc_final_amount_summary.final_payable_amount');
 
         if (!$amount) {
             return response()->json([
                 'status' => 'error',
                 'message' => 'Payment amount not found in session.'
             ], 422);
         }
 
         return response()->json([
             'status' => 'ready',
             'amount' => $amount
         ]);
     }
}