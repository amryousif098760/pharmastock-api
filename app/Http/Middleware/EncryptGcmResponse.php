<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Support\GcmEnvelope;

class EncryptGcmResponse
{
    public function handle(Request $request, Closure $next)
    {
        $resp = $next($request);

        $aad = $request->attributes->get('aad');
        if (!$aad) return $resp;

        $data = $resp->getData(true);
        $plain = json_encode($data, JSON_UNESCAPED_UNICODE);

        $env = GcmEnvelope::encryptPayload($plain, $aad);
        return response()->json($env, 200);
    }
}
