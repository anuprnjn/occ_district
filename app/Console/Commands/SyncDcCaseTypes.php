<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Helpers\Utility;
use Illuminate\Support\Facades\Log;

class SyncDcCaseTypes extends Command
{
    protected $signature = 'napix:sync-dc-case-types';
    protected $description = 'Fetch and sync DC court case types for all establishments';

    public function handle()
    {
        $this->info('[Scheduler] napix:sync-dc-case-types started at ' . now());

        $establishments = DB::table('establishment')->select('est_code')->get();

        foreach ($establishments as $establishment) {
            $dc_est_code = $establishment->est_code;

            try {
                // === Static credentials from env ===
                $dept_id     = env('DEPT_ID');
                $version     = env('VERSION');
                $hmac_secret = env('NAPIX_HMAC_SECRET');
                $aes_key     = env('NAPIX_AES_KEY');
                $iv          = env('NAPIX_AES_IV');
                $apikey      = env('NAPIX_API_KEY');
                $secret_key  = env('NAPIX_SECRET_KEY');
                $basicAuth   = base64_encode($apikey . ':' . $secret_key);

                // Step 1: Get Access Token
                $tokenResponse = Utility::getNapixAccessToken($basicAuth);
                $tokenData = json_decode($tokenResponse, true);

                if (!isset($tokenData['access_token'])) {
                    Log::warning("[Scheduler] Failed to fetch token for est_code $dc_est_code", $tokenData);
                    continue;
                }

                $accessToken = $tokenData['access_token'];

                // Step 2: Create request
                $input_str = "est_code={$dc_est_code}";
                $request_token = hash_hmac('sha256', $input_str, $hmac_secret);
                $encrypted_str = Utility::encryptString($input_str, $aes_key, $iv);
                $request_str = urlencode($encrypted_str);

                $url = "https://delhigw.napix.gov.in/nic/ecourts/dc-casetype-master-api/casetypeMaster?dept_id={$dept_id}&request_str={$request_str}&request_token={$request_token}&version={$version}";

                // Step 3: Make API Call
                $response = Utility::makeNapixApiCall($url, $accessToken);
                $responseArray = json_decode($response, true);

                if (!isset($responseArray['response_str'])) {
                    Log::warning("[Scheduler] Invalid response from NAPIX for est_code $dc_est_code", $responseArray);
                    continue;
                }

                // Step 4: Decrypt and parse data
                $decryptedData = Utility::decryptString($responseArray['response_str'], $aes_key, $iv);
                $parsedData = json_decode($decryptedData, true);

                if (!is_array($parsedData)) {
                    Log::warning("[Scheduler] Decryption failed or invalid JSON for est_code $dc_est_code", ['decrypted' => $decryptedData]);
                    continue;
                }

                // Step 5: Insert data into database
                $insertedCount = 0;

                foreach ($parsedData as $caseType) {
                    if (!isset($caseType['case_type'], $caseType['type_name'])) {
                        continue;
                    }

                    $exists = DB::table('district_court_case_master')
                        ->where('case_type', $caseType['case_type'])
                        ->where('est_code', $dc_est_code)
                        ->exists();

                    if (!$exists) {
                        DB::table('district_court_case_master')->insert([
                            'case_type' => $caseType['case_type'],
                            'type_name' => $caseType['type_name'],
                            'est_code'  => $dc_est_code,
                        ]);
                        $insertedCount++;
                    }
                }

                Log::info("[Scheduler] Synced case types for est_code $dc_est_code — Inserted: $insertedCount");

            } catch (\Exception $e) {
                Log::error("[Scheduler] Exception for est_code $dc_est_code: " . $e->getMessage());
            }
        }

        $this->info('[Scheduler] napix:sync-dc-case-types finished at ' . now());
    }
}