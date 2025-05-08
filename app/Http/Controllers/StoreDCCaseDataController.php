<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\DB;

class StoreDCCaseDataController extends Controller
{
    public function storeCaseDetails(Request $request)
    {
      
        $caseDetails = $request->input('caseDetails');
        Session::put('DcCaseDetailsNapix', $caseDetails);
        
      
        return response()->json([
            'message' => 'Case details stored in session successfully',
            'redirectLocation' => url('/caseInformationDc') 
        ]);
    }
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
                'orders' => $orders
            ]
        ]);
            // dd(Session::all());

        return response()->json([
            'status' => 'success',
            'message' => 'Data processed and stored in session successfully.',
            'location' => '/occ/cd_pay'
        ]);
    }
}