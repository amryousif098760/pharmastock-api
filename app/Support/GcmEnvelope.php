\
<?php

namespace App\Support;

class GcmEnvelope
{
    public static function keyBytes(): string
    {
        $keyB64 = env('ENC_KEY_B64', '');
        $key = base64_decode($keyB64, true);
        if ($key === false || strlen($key) !== 32) {
            throw new \RuntimeException("ENC_KEY_B64 must decode to 32 bytes");
        }
        return $key;
    }

    public static function aadFromRequest(string $path, string $ts, string $nonce, string $appId): string
    {
        return "path={$path};ts={$ts};nonce={$nonce};app={$appId}";
    }

    private static function b64d(string $s): string
    {
        $x = base64_decode($s, true);
        if ($x === false) throw new \RuntimeException("Bad base64");
        return $x;
    }

    private static function b64e(string $s): string
    {
        return base64_encode($s);
    }

    public static function decryptEnvelope(array $env, string $aad): string
    {
        if (($env['alg'] ?? '') !== 'AES-256-GCM') {
            throw new \RuntimeException("Unsupported alg");
        }

        $iv  = self::b64d((string)($env['iv'] ?? ''));
        $tag = self::b64d((string)($env['tag'] ?? ''));
        $ct  = self::b64d((string)($env['ciphertext'] ?? ''));

        if (strlen($iv) !== 12) throw new \RuntimeException("Invalid IV length");
        if (strlen($tag) !== 16) throw new \RuntimeException("Invalid tag length");

        $key = self::keyBytes();

        $pt = openssl_decrypt(
            $ct,
            'aes-256-gcm',
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag,
            $aad
        );

        if ($pt === false) throw new \RuntimeException("Decrypt failed");
        return $pt;
    }

    public static function encryptPayload(string $plaintextJson, string $aad): array
    {
        $iv  = random_bytes(12);
        $key = self::keyBytes();
        $tag = '';

        $ct = openssl_encrypt(
            $plaintextJson,
            'aes-256-gcm',
            $key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag,
            $aad,
            16
        );

        if ($ct === false || strlen($tag) !== 16) {
            throw new \RuntimeException("Encrypt failed");
        }

        return [
            'alg' => 'AES-256-GCM',
            'iv' => self::b64e($iv),
            'tag' => self::b64e($tag),
            'ciphertext' => self::b64e($ct),
            // diagnostic only
            'aad' => self::b64e($aad),
        ];
    }
}
