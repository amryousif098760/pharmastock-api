<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DecryptGcmRequest
{
    public function handle(Request $request, Closure $next)
    {
        $request->attributes->set('dec', ['_mw' => 'hit']);
        return $next($request);
    }
}
