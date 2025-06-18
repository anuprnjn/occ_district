<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log_hc($logDesc, $logAction, $userId = null, $username)
    {
        DB::table('log_activity_hc')->insert([
            'log_desc' => $logDesc,
            'log_date' => now(),
            'log_action' => $logAction,
            'user_id' => $userId,
            'username' => $username
        ]);
    }
    public static function log_dc($logDesc, $logAction, $userId = null,$distCode=null,$estCode,$username)
    {
        DB::table('log_activity_dc')->insert([
            'log_desc' => $logDesc,
            'log_date' => now(),
            'log_action' => $logAction,
            'user_id' => $userId,
            'dist_code' => $distCode,
            'est_code' => $estCode,
            'username' => $username
        ]);
    }
}
