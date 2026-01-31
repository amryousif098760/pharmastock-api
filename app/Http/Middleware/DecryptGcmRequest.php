<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Support\GcmEnvelope;

class DecryptGcmRequest
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('GET')) {
            return $next($request);
        }

        $body = $request->getContent();
        if ($body === '' || $body === null) {
            return response()->json(['ok' => false, 'message' => 'Empty body'], 200);
        }

        $env = json_decode($body, true);
        if (!is_array($env)) {
            return response()->json(['ok' => false, 'message' => 'Invalid JSON body'], 200);
        }

        if (!isset($env['alg'], $env['iv'], $env['tag'], $env['ciphertext'])) {
            return response()->json(['ok' => false, 'message' => 'Not encrypted envelope'], 200);
        }

        $appId = (string)$request->header('X-App-Id', '');
        $ts    = (string)$request->header('X-TS', '');
        $nonce = (string)$request->header('X-Req-Nonce', '');

        $path = $request->getPathInfo();
        $aad  = GcmEnvelope::aadFromRequest($path, $ts, $nonce, $appId);

        try {
            $ptJson = GcmEnvelope::decryptEnvelope($env, $aad);
            $payload = json_decode($ptJson, true);

            if (!is_array($payload)) {
                return response()->json(['ok' => false, 'message' => 'Decrypted payload not JSON'], 200);
            }

            $request->attributes->set('dec', $payload);
            $request->attributes->set('aad', $aad);

            return $next($request);

        } catch (\Throwable $e) {
            return response()->json([
                'ok' => false,
                'message' => 'Decrypt failed',
                'err' => $e->getMessage(),
            ], 200);
        }
    }
}
