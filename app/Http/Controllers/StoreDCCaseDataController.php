<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\DB;

class StoreDCCaseDataController extends Controller
{

    // function to store the case details of initial page of dc 

    public function storeCaseDetails(Request $request)
    {
      
        $caseDetails = $request->input('caseDetails');
        Session::put('DcCaseDetailsNapix', $caseDetails);
        
      
        return response()->json([
            'message' => 'Case details stored in session successfully',
            'redirectLocation' => url('/caseInformationDc') 
        ]);
    }

    // function to store the details of orders and users in the session 

    public function store(Request $request)
    {
        // Get all incoming data
        $data = $request->all();
    
        // Basic validation for required fields
        if (
            empty($data['name']) ||
            empty($data['mobile']) ||
            empty($data['email']) ||
            empty($data['confirm_email']) ||
            empty($data['applied_by']) ||
            empty($data['request_mode']) ||
            empty($data['selected_orders']) ||
            empty($data['case_details']) ||
            empty($data['case_details']['cino'])
        ) {
            return response()->json(['error' => 'Required data is missing.'], 422);
        }
    
        // Check if per_page_fee is available in the database
        $feePerPage = DB::table('fee_master')->where('fee_type', 'per_page_fee')->value('amount') ?? 5; // Default to 5 if not found
        $cino = $data['case_details']['cino'];
        $pdfFolder = storage_path("app/public/napix_pdf/{$cino}");
    
        $orders = [];
    
        // Loop through selected orders to process PDF and calculate amount
        foreach ($data['selected_orders'] as $order) {
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

    
        // Store the processed data in the session
        session([
            'dc_review_form_userDetails' => [
                'user_info' => [
                    'name' => $data['name'],
                    'mobile' => $data['mobile'],
                    'email' => $data['email'],
                    'confirm_email' => $data['confirm_email'],
                    'applied_by' => $data['applied_by'],
                    'adv_reg_no' => $data['adv_reg_no'] ?? null,
                    'request_mode' => $data['request_mode']
                ],
                'case_details' => $data['case_details'],
                'orders' => $orders,
                'urgent_fee' => $urgentFee
            ],
            'active_payment_source' => 'dc_review_form_userDetails' // set this flag
        ]);
            // dd(Session::all());

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed and stored in session successfully.',
            'location' => '/occ/cd_pay'
        ]);
    }

    // function to calculate the total amount on server side 

    public function calculateFinalAmount(Request $request)
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
    
        // Step 4: Store total and fee in session
        session([
            'dc_final_amount_summary' => [
                'orders_total' => $totalAmount,
                'urgent_fee' => $urgentFee,
                'final_total_amount_DC' => $finalAmount
            ]
        ]);
    
        // Step 5: Return success response with data
        return response()->json([
            'status' => 'success',
            'message' => 'Total amount is calculated successfully',
            'data' => session('dc_final_amount_summary')
        ]);
    }

    // function to initialte the final amount with security 

    public function initiatePayment()
    {
        $amount = session('dc_final_amount_summary.final_total_amount_DC');

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