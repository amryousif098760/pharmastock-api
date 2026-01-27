<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Nonce;

class VerifyNonceAndTimestamp
{
    public function handle(Request $request, Closure $next)
    {
        $appId = $request->header('X-App-Id', '');
        $ts = $request->header('X-TS', '');
        $nonce = $request->header('X-Req-Nonce', '');

        if ($appId !== env('ENC_APP_ID')) {
            return response()->json(['ok'=>false,'message'=>'Bad app id'], 200);
        }
        if (!$ts || !$nonce) {
            return response()->json(['ok'=>false,'message'=>'Missing TS/Nonce'], 200);
        }
        if (!ctype_digit($ts)) {
            return response()->json(['ok'=>false,'message'=>'Invalid TS'], 200);
        }

        $skew = (int) env('ENC_TS_SKEW_SECONDS', 300);
        $now = time();
        $tsInt = (int) $ts;

        if (abs($now - $tsInt) > $skew) {
            return response()->json(['ok'=>false,'message'=>'TS skew too large'], 200);
        }

        $exists = Nonce::where('app_id',$appId)->where('nonce',$nonce)->exists();
        if ($exists) {
            return response()->json(['ok'=>false,'message'=>'Replay detected'], 200);
        }

        Nonce::create(['app_id'=>$appId,'nonce'=>$nonce,'ts'=>$tsInt]);

        return $next($request);
    }
}
