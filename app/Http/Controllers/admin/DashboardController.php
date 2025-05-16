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
        
        // Main queries
        $hcOrderQuery = DB::table('hc_order_copy_applicant_registration');
        $hcWebQuery   = DB::table('high_court_applicant_registration');

        if ($selectedDate) {
            $hcOrderQuery->whereDate('created_at', $selectedDate);
            $hcWebQuery->whereDate('created_at', $selectedDate);
        }

        // Pending and Delivered Counts - High Court Order
        $hcOrderPending = DB::table('hc_order_copy_applicant_registration')
            ->when($selectedDate, fn($query) => $query->whereDate('created_at', $selectedDate))
            ->where('certified_copy_ready_status', '0')
            ->count();

        $hcOrderDelivered = DB::table('hc_order_copy_applicant_registration')
            ->when($selectedDate, fn($query) => $query->whereDate('created_at', $selectedDate))
            ->where('certified_copy_ready_status', '1')
            ->count();

        // Pending and Delivered Counts - High Court Web
        $hcWebPending = DB::table('high_court_applicant_registration')
            ->when($selectedDate, fn($query) => $query->whereDate('created_at', $selectedDate))
            ->where('certified_copy_ready_status', '0')
            ->count();

        $hcWebDelivered = DB::table('high_court_applicant_registration')
            ->when($selectedDate, fn($query) => $query->whereDate('created_at', $selectedDate))
            ->where('certified_copy_ready_status', '1')
            ->count();

        $dcOrderQuery = DB::table('district_court_order_copy_applicant_registration')
            ->when($selectedDate, fn($query) => $query->whereDate('created_at', $selectedDate))
            ->where('district_code',$district_code)
            ->count();

        // Pending and Delivered Counts - District Court Order
        $dcOrderPending = DB::table('district_court_order_copy_applicant_registration')
            ->when($selectedDate, fn($query) => $query->whereDate('created_at', $selectedDate))
            ->where('certified_copy_ready_status', '0')
            ->where('district_code',$district_code)
            ->count();

        $dcOrderDelivered = DB::table('district_court_order_copy_applicant_registration')
            ->when($selectedDate, fn($query) => $query->whereDate('created_at', $selectedDate))
            ->where('certified_copy_ready_status', '1')
            ->where('district_code',$district_code)
            ->count();
            
        $dcWebQuery = DB::table('district_court_applicant_registration')
            ->when($selectedDate, fn($query) => $query->whereDate('created_at', $selectedDate))
            ->where('certified_copy_ready_status', '0')
            ->where('district_code',$district_code)
            ->count();    

        // Pending and Delivered Counts - District Court Web
        $dcWebPending = DB::table('district_court_applicant_registration')
            ->when($selectedDate, fn($query) => $query->whereDate('created_at', $selectedDate))
            ->where('certified_copy_ready_status', '0')
            ->where('district_code',$district_code)
            ->count();

        $dcWebDelivered = DB::table('district_court_applicant_registration')
            ->when($selectedDate, fn($query) => $query->whereDate('created_at', $selectedDate))
            ->where('certified_copy_ready_status', '1')
            ->where('district_code',$district_code)
            ->count();

        return view('admin.dashboard', [
            // Total counts
            'hcOrderCopyCount' => $hcOrderQuery->count(),
            'hcWebCopyCount'   => $hcWebQuery->count(),

            // Pending and delivered counts
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

            'selectedDate'     => $selectedDate ?? now()->toDateString(),
        ]);
    }

}