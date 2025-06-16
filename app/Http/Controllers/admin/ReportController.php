<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    
public function paymentReport(Request $request)
{
    $from = $request->input('from_date');
    $to = $request->input('to_date');

    $query = DB::table('transaction_master_hc');

    if ($from && $to) {
        $query->whereRaw(
            "DATE(created_at AT TIME ZONE 'UTC' AT TIME ZONE 'Asia/Kolkata') BETWEEN ? AND ?", 
            [$from, $to]
        );
    }

    $payments = $query->get();

    $totalAmount = $payments->where('payment_status', 1)->sum('amount');
    $deficitAmount = $payments->where('deficit_payment', 1)->sum('amount');
    $grandTotal = $totalAmount + $deficitAmount;
    $totalApplications = $payments->count();

    return view('admin.occ_report.payment_report', compact(
        'totalAmount', 'deficitAmount', 'grandTotal', 'totalApplications', 'from', 'to'
    ));
}

public function deliveredReport(Request $request)
{
    $from = $request->input('from_date');
    $to = $request->input('to_date');

    $applications = collect(); // Start with empty collection

    $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
    $toDate = $to ? Carbon::parse($to)->endOfDay() : null;

    if ($fromDate && $toDate) {
        $hcOrderData = DB::table('hc_order_copy_applicant_registration')
            ->select('application_number', 'cino', 'applicant_name', 'mobile_number', 'email', 'updated_at')
            ->where('certified_copy_ready_status', 1)
            ->whereBetween('updated_at', [$fromDate, $toDate]);

        $hcCopyData = DB::table('high_court_applicant_registration')
            ->select('application_number', DB::raw('NULL as cino'), 'applicant_name', 'mobile_number', 'email', 'updated_at')
            ->where('certified_copy_ready_status', 1)
            ->whereBetween('updated_at', [$fromDate, $toDate]);

        $applications = $hcOrderData->unionAll($hcCopyData)->get();
    }

    return view('admin.occ_report.delivered_report', [
        'from' => $from,
        'to' => $to,
        'applications' => $applications,
        'totalDelivered' => $applications->count(),
    ]);
}

public function pendingReport(Request $request)
{
    $from = $request->input('from_date');
    $to = $request->input('to_date');

    $applications = collect(); // Empty collection by default
    $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
    $toDate = $to ? Carbon::parse($to)->endOfDay() : null;

    if ($fromDate && $toDate) {
        $hcOrderData = DB::table('hc_order_copy_applicant_registration')
            ->select('application_number', 'cino', 'applicant_name', 'mobile_number', 'email', 'created_at')
            ->where('certified_copy_ready_status', 0)
            ->whereBetween('created_at', [$fromDate, $toDate]);

        $hcCopyData = DB::table('high_court_applicant_registration')
            ->select('application_number', DB::raw('NULL as cino'), 'applicant_name', 'mobile_number', 'email', 'created_at')
            ->where('certified_copy_ready_status', 0)
            ->whereBetween('created_at', [$fromDate, $toDate]);

        $applications = $hcOrderData->unionAll($hcCopyData)->get();
    }

    return view('admin.occ_report.pending_report', [
        'from' => $from,
        'to' => $to,
        'applications' => $applications,
        'totalDelivered' => $applications->count(),
    ]);
}

public function logsReport(Request $request)
{
    return view('admin.occ_report.activity_log_report');
}

 
}
