<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->input('date');
        $district_code = session('user.dist_code');
        $estd_code = session('user.estd_code');

        $startDate = Carbon::now()->subDays(14)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // High Court Order Copy Chart Data
        $orderCopyData = DB::table('hc_order_copy_applicant_registration')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date');

        // High Court Other Copy Chart Data
        $otherCopyData = DB::table('high_court_applicant_registration')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date');

        // District Court Order Copy Chart Data
        $dcOrderCopyData = DB::table('district_court_order_copy_applicant_registration')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('district_code', $district_code)
            ->where('establishment_code', $estd_code)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date');

        // District Court Other Copy Chart Data
        $dcOtherCopyData = DB::table('district_court_applicant_registration')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('district_code', $district_code)
            ->where('establishment_code', $estd_code)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date');

        // Prepare chart arrays
        $dates = collect();
        $orderCounts = collect();
        $otherCounts = collect();
        $dcOrderCounts = collect();
        $dcOtherCounts = collect();

        foreach (Carbon::parse($startDate)->daysUntil(Carbon::parse($endDate)) as $date) {
            $formatted = $date->format('Y-m-d');
            $dates->push($formatted);
            $orderCounts->push($orderCopyData[$formatted] ?? 0);
            $otherCounts->push($otherCopyData[$formatted] ?? 0);
            $dcOrderCounts->push($dcOrderCopyData[$formatted] ?? 0);
            $dcOtherCounts->push($dcOtherCopyData[$formatted] ?? 0);
        }

        // High Court Order/Web Queries
        $hcOrderQuery = DB::table('hc_order_copy_applicant_registration');
        $hcWebQuery   = DB::table('high_court_applicant_registration');

        if ($selectedDate) {
            $hcOrderQuery->whereDate('created_at', $selectedDate);
            $hcWebQuery->whereDate('created_at', $selectedDate);
        }

        // High Court Order Pending / Delivered
        $hcOrderPending = DB::table('hc_order_copy_applicant_registration')
            ->when($selectedDate, fn($query) => $query->whereDate('created_at', $selectedDate))
            ->where('certified_copy_ready_status', '0')
            ->count();

        $hcOrderDelivered = DB::table('hc_order_copy_applicant_registration')
            ->when($selectedDate, fn($query) => $query->whereDate('created_at', $selectedDate))
            ->where('certified_copy_ready_status', '1')
            ->count();

        // High Court Web Pending / Delivered
        $hcWebPending = DB::table('high_court_applicant_registration')
            ->when($selectedDate, fn($query) => $query->whereDate('created_at', $selectedDate))
            ->where('certified_copy_ready_status', '0')
            ->count();

        $hcWebDelivered = DB::table('high_court_applicant_registration')
            ->when($selectedDate, fn($query) => $query->whereDate('created_at', $selectedDate))
            ->where('certified_copy_ready_status', '1')
            ->count();

        // ✅ District Court Queries with estd_code
        $dcOrderQuery = DB::table('district_court_order_copy_applicant_registration')
            ->when($selectedDate, fn($query) => $query->whereDate('created_at', $selectedDate))
            ->where('district_code', $district_code)
            ->where('establishment_code', $estd_code)
            ->count();

        $dcOrderPending = DB::table('district_court_order_copy_applicant_registration')
            ->when($selectedDate, fn($query) => $query->whereDate('created_at', $selectedDate))
            ->where('certified_copy_ready_status', '0')
            ->where('district_code', $district_code)
            ->where('establishment_code', $estd_code)
            ->count();

        $dcOrderDelivered = DB::table('district_court_order_copy_applicant_registration')
            ->when($selectedDate, fn($query) => $query->whereDate('created_at', $selectedDate))
            ->where('certified_copy_ready_status', '1')
            ->where('district_code', $district_code)
            ->where('establishment_code', $estd_code)
            ->count();

        $dcWebQuery = DB::table('district_court_applicant_registration')
            ->when($selectedDate, fn($query) => $query->whereDate('created_at', $selectedDate))
            ->where('district_code', $district_code)
            ->where('establishment_code', $estd_code)
            ->count();

        $dcWebPending = DB::table('district_court_applicant_registration')
            ->when($selectedDate, fn($query) => $query->whereDate('created_at', $selectedDate))
            ->where('certified_copy_ready_status', '0')
            ->where('district_code', $district_code)
            ->where('establishment_code', $estd_code)
            ->count();

        $dcWebDelivered = DB::table('district_court_applicant_registration')
            ->when($selectedDate, fn($query) => $query->whereDate('created_at', $selectedDate))
            ->where('certified_copy_ready_status', '1')
            ->where('district_code', $district_code)
            ->where('establishment_code', $estd_code)
            ->count();

        return view('admin.dashboard', [
            'hcOrderCopyCount' => $hcOrderQuery->count(),
            'hcWebCopyCount'   => $hcWebQuery->count(),
            'hcOrderPending'   => $hcOrderPending,
            'hcOrderDelivered' => $hcOrderDelivered,
            'hcWebPending'     => $hcWebPending,
            'hcWebDelivered'   => $hcWebDelivered,

            'dcOrderCopyCount' => $dcOrderQuery,
            'dcOrderPending'   => $dcOrderPending,
            'dcOrderDelivered' => $dcOrderDelivered,
            'dcWebCopyCount'   => $dcWebQuery,
            'dcWebPending'     => $dcWebPending,
            'dcWebDelivered'   => $dcWebDelivered,

            'dates'            => $dates,
            'hcOrderCounts'    => $orderCounts,
            'hcOtherCounts'    => $otherCounts,
            'dcOrderCounts'    => $dcOrderCounts,
            'dcOtherCounts'    => $dcOtherCounts,
            'selectedDate'     => $selectedDate ?? now()->toDateString(),
        ]);
    }

}