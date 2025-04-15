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
    public function encrypt($plainText, $secretKey)
    {
        list($key, $iv) = $this->getKeyAndIV($secretKey);
        $encrypted = openssl_encrypt($plainText, $this->cipher, $key, OPENSSL_RAW_DATA, $iv);

        return base64_encode($encrypted); // Base64 encode for readability
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
    // public function decrypt()
    // {
    //     // dd($this->cipher);
    //     // $encryptedText = 'kdUDf+/ZxxRnRGDotlk6x/2CsbIONnFDTwNIPjhSjjteczI4hC/C52WkSbgvDSss1Evdyh0GidObr2c1WTdWTts7UZv1Fs/uqFfbbgqa6X2ijLynIYilBTqx0u0zazotp67Chxhv7u9nXKhT7YQcNA7G3RJN49Ss151+bBe/pR7P3+zqwQpOBmruVLJqnxeFdWtZGgAuDox6RqbpbYrDhjpGFECiq+DtBUYZO21oknnZIDjbOBSs6DzE1l2VDB5uqJOgi1nZF1r0pC+V0xuzqvkjzcl0xzsJNTQ3J1aV/U4=';
    //     $encryptedText = '/LafgEmh7gCGMPfS6EAhctYpyczE6liafYZNPKcIS+FdrLpAcbUtP9uBqSpV+haWORn0BUvLKCoyhlpWaHA/M/yFF6n1dUDGW/PBYH7LCPUmYo7rmSLKQ9FbGefEnhMZ1Fd+o/J5ph/9ZVGLJAz9W8Xy/vpw69VIUfafvejRMP1iYQZf9dmco+aXmw7xvttDnV994GDywj1QiKVdgLUHt6164IVLRwEKYtd1hCzDCTh6bGziZrvKGoQQfQLp3RmYMOMDhoW2uE16E5KmeaUxBS/6EyWE+HoEq5Aphzi8pKrjB6j5MaqAztqxlkHJRGW2G8TPOsah3+aq4DhxHJmSqA==';

    //     // $secretKey = 'Ky@5432#';
    //     $secretKey = 'sec1234';
    //     list($key, $iv) = $this->getKeyAndIV($secretKey);
    //     // dd(bin2hex($key), bin2hex($iv));
    //     // dd($key, $iv);
    //     $encryptedData = base64_decode($encryptedText);
    //     // dd($encryptedData);
    //     $val = openssl_decrypt($encryptedData, $this->cipher, $key, OPENSSL_RAW_DATA, $iv);
    //     if ($val === false) {
    //         dd(openssl_error_string());
    //     }
    //     dd($val);
    // }
}