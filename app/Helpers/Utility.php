<?php

namespace App\Helpers;

use Exception;

class Utility
{

    public static function getNapixAccessToken($basicAuth)
    {
        $url = env('NAPIX_TOKEN_URL');
        $postFields = "grant_type=client_credentials&scope=napix";
    
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => [
                "Authorization: Basic {$basicAuth}",
                "Content-Type: application/x-www-form-urlencoded"
            ],
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public static function makeNapixApiCall($url, $accessToken)
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$accessToken}",
            ],
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
    
        return $response;
    }

    public static function encryptString($string, $key, $iv)
    {
        $encrypted = openssl_encrypt($string, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return base64_encode($encrypted);
    }
    
    public static function decryptString($encrypted_base64, $key, $iv)
    {
        $encryptedData = base64_decode($encrypted_base64);
        return openssl_decrypt($encryptedData, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
    }
}