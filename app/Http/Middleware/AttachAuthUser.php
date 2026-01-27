<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

class AttachAuthUser
{
    public function handle(Request $request, Closure $next)
    {
        $p = $request->attributes->get('dec', []);
        $token = (string)($p['token'] ?? '');

        $u = $this->userFromToken($token);
        if ($u) $request->attributes->set('auth_user', $u);

        return $next($request);
    }

    private function userFromToken(string $plainTextToken): ?User
    {
        if (!$plainTextToken) return null;

        $parts = explode('|', $plainTextToken, 2);
        if (count($parts) !== 2) return null;

        $pat = PersonalAccessToken::find((int)$parts[0]);
        if (!$pat) return null;

        if (!hash_equals($pat->token, hash('sha256', $parts[1]))) return null;

        return $pat->tokenable instanceof User ? $pat->tokenable : null;
    }
}
