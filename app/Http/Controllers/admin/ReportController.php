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
public function paymentReportDC(Request $request)
{
    $from = $request->input('from_date');
    $to = $request->input('to_date');

    $query = DB::table('transaction_master_dc');

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

    return view('admin.occ_report_dc.payment_report_dc', compact(
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

public function deliveredReportDC(Request $request)
{
    $from = $request->input('from_date');
    $to = $request->input('to_date');

    $applications = collect(); // Start with empty collection

    $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
    $toDate = $to ? Carbon::parse($to)->endOfDay() : null;

    if ($fromDate && $toDate) {
        $hcOrderData = DB::table('district_court_order_copy_applicant_registration')
            ->select('application_number', 'cino', 'applicant_name', 'mobile_number', 'email', 'updated_at')
            ->where('certified_copy_ready_status', 1)
            ->whereBetween('updated_at', [$fromDate, $toDate]);

        $hcCopyData = DB::table('district_court_applicant_registration')
            ->select('application_number', DB::raw('NULL as cino'), 'applicant_name', 'mobile_number', 'email', 'updated_at')
            ->where('certified_copy_ready_status', 1)
            ->whereBetween('updated_at', [$fromDate, $toDate]);

        $applications = $hcOrderData->unionAll($hcCopyData)->get();
    }

    return view('admin.occ_report_dc.delivered_report_dc', [
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

public function pendingReportDC(Request $request)
{
    $from = $request->input('from_date');
    $to = $request->input('to_date');

    $applications = collect(); // Empty collection by default
    $fromDate = $from ? Carbon::parse($from)->startOfDay() : null;
    $toDate = $to ? Carbon::parse($to)->endOfDay() : null;

    if ($fromDate && $toDate) {
        $hcOrderData = DB::table('district_court_order_copy_applicant_registration')
            ->select('application_number', 'cino', 'applicant_name', 'mobile_number', 'email', 'created_at')
            ->where('certified_copy_ready_status', 0)
            ->whereBetween('created_at', [$fromDate, $toDate]);

        $hcCopyData = DB::table('district_court_applicant_registration')
            ->select('application_number', DB::raw('NULL as cino'), 'applicant_name', 'mobile_number', 'email', 'created_at')
            ->where('certified_copy_ready_status', 0)
            ->whereBetween('created_at', [$fromDate, $toDate]);

        $applications = $hcOrderData->unionAll($hcCopyData)->get();
    }

    return view('admin.occ_report_dc.pending_report_dc', [
        'from' => $from,
        'to' => $to,
        'applications' => $applications,
        'totalDelivered' => $applications->count(),
    ]);
}

public function logsReport(Request $request)
{
    $logs = collect(); // Empty collection by default

    // Only run the query if from_date or to_date is provided
    if ($request->from_date || $request->to_date) {
        $query = DB::table('log_activity_hc');

        if ($request->from_date) {
            $query->whereDate('log_date', '>=', Carbon::parse($request->from_date));
        }

        if ($request->to_date) {
            $query->whereDate('log_date', '<=', Carbon::parse($request->to_date));
        }

        $logs = $query->orderBy('log_date', 'desc')->get();
    }

    return view('admin.occ_report.activity_log_report', compact('logs'));
}

 
}
