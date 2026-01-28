<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Support\GcmEnvelope;

class DecryptGcmRequest
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('GET') && $request->is('api/auth/verify-email')) {
            return $next($request);
        }
        $appId = $request->header('X-App-Id', '');
        $ts = $request->header('X-TS', '');
        $nonce = $request->header('X-Req-Nonce', '');

        $path = $request->getPathInfo(); // e.g. /api/auth/login
        $aad = GcmEnvelope::aadFromRequest($path, $ts, $nonce, $appId);

        $body = $request->getContent();
        $env = json_decode($body, true);
        if (!is_array($env)) {
            return response()->json(['ok'=>false,'message'=>'Invalid JSON body'], 200);
        }

        try {
            $ptJson = GcmEnvelope::decryptEnvelope($env, $aad);
            $payload = json_decode($ptJson, true);
            if (!is_array($payload)) {
                return response()->json(['ok'=>false,'message'=>'Decrypted payload not JSON'], 200);
            }
            $request->attributes->set('dec', $payload);
            $request->attributes->set('aad', $aad);
        } catch (\Throwable $e) {
            return response()->json(['ok'=>false,'message'=>'Decrypt failed'], 200);
        }

        return $next($request);
    }
}
