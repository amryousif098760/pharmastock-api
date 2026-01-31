<?php

namespace App\Support;

use Exception;

class GcmEnvelope
{
    public static function decryptEnvelope(array $env, string $aad): string
    {
        $keyB64 = env('ENC_KEY_B64', '');
        $key = base64_decode($keyB64, true);
        if ($key === false || strlen($key) !== 32) {
            throw new Exception('Invalid ENC_KEY_B64');
        }

        foreach (['iv', 'tag', 'ciphertext'] as $k) {
            if (empty($env[$k])) {
                throw new Exception("Missing {$k}");
            }
        }

        $iv  = base64_decode($env['iv'], true);
        $tag = base64_decode($env['tag'], true);
        $ct  = base64_decode($env['ciphertext'], true);

        if ($iv === false || $tag === false || $ct === false) {
            throw new Exception('Base64 decode failed');
        }

        $plain = openssl_decrypt(
            $ct,
            'aes-256-gcm',
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag,
            $aad
        );

        if ($plain === false) {
            throw new Exception('Decrypt failed');
        }

        return $plain;
    }
}
