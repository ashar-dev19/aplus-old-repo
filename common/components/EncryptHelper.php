<?php
class EncryptHelper {
    private static $key = 'your-secret-key';
    private static $method = 'AES-256-CBC';

    public static function encrypt($text) {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::$method));
        $encrypted = openssl_encrypt($text, self::$method, self::$key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    public static function decrypt($encryptedText) {
        $data = base64_decode($encryptedText);
        $ivLength = openssl_cipher_iv_length(self::$method);
        $iv = substr($data, 0, $ivLength);
        $cipherText = substr($data, $ivLength);
        return openssl_decrypt($cipherText, self::$method, self::$key, 0, $iv);
    }
}
