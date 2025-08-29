<?php

namespace App\Helpers;

use Exception;

class clsEncrypt
{
    private $cipher = 'AES-128-CBC'; // Ensure correct AES-128-CBC mode

    public function __construct()
    {
        if (!function_exists('openssl_encrypt')) {
            throw new Exception('OpenSSL extension is required for encryption.');
        }
    }

    /**
     * Generate a 16-byte key and IV from a given secret key
     */
    private function getKeyAndIV($secretKey)
    {
        // Ensure the key is exactly 16 bytes long
        $key = str_pad(substr($secretKey, 0, 16), 16, "\0");

        // Ensure the IV is exactly 16 bytes long
        $iv = str_pad(substr($secretKey, 0, 16), 16, "\0");

        return [$key, $iv];
    }

    /**
     * Encrypts a plain text string
     */
    public function encrypt($requestParameter, $key)
    {    
        $iv=$key;
        $cipher   = 'AES-128-CBC';
        $enc_val  = @openssl_encrypt($requestParameter, $cipher, $key, 0, $iv); 
        return $enc_val; 
    }
    /**
     * Decrypts an encrypted string
     */
    public function decrypt($encryptedText, $secretKey)
    {
        list($key, $iv) = $this->getKeyAndIV($secretKey);
        $encryptedData = base64_decode($encryptedText);
        return openssl_decrypt($encryptedData, $this->cipher, $key, OPENSSL_RAW_DATA, $iv);
    }
   
}