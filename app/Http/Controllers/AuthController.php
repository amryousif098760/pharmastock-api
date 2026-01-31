<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Pharmacy;

class AuthController extends Controller
{
    private function dec(Request $r): array { return $r->attributes->get('dec', []); }

    public function register(Request $request)
{
    try {
        $p = $this->dec($request);

        $email = strtolower(trim($p['email'] ?? ''));
        $password = (string)($p['password'] ?? '');
        $name = trim($p['name'] ?? '');
        $phone = trim($p['phone'] ?? '');

        $phName = trim($p['pharmacyName'] ?? '');
        $loc = $p['pharmacyLocation'] ?? [];
        $lat = $loc['lat'] ?? null;
        $lng = $loc['lng'] ?? null;
        $addr = trim($loc['addressText'] ?? '');

        if (!$name || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6 || !$phName) {
            return response()->json(['ok'=>false,'message'=>'Invalid input'], 200);
        }

        if (User::where('email',$email)->exists()) {
            return response()->json(['ok'=>false,'message'=>'Email already exists'], 200);
        }

        $verifyToken = Str::random(60);

        $u = User::create([
            'name'=>$name,
            'email'=>$email,
            'phone'=>$phone,
            'password'=>Hash::make($password),
            'role'=>'pharmacist',
            'approval_status'=>'pending',
            'email_verify_token'=>$verifyToken,
        ]);

        Pharmacy::create([
            'user_id'=>$u->id,
            'name'=>$phName,
            'lat'=>$lat,
            'lng'=>$lng,
            'address'=>$addr,
        ]);

        $link = url("/api/auth/verify-email?token={$verifyToken}");

        try {
            Mail::raw("Verify your email: {$link}", function($m) use ($email) {
                $m->to($email)->subject("Verify Email");
            });
        } catch (\Throwable $e) {
            return response()->json([
                'ok'=>true,
                'message'=>'Registered but email failed',
                'mail_error'=>$e->getMessage(),
                'verify_link'=>$link
            ], 200);
        }

        return response()->json(['ok'=>true,'message'=>'Registered. Verification email sent.'], 200);

    } catch (\Throwable $e) {
        return response()->json([
            'ok'=>false,
            'message'=>'Server error',
            'error'=>$e->getMessage(),
        ], 200);
    }
}

    public function login(Request $request)
    {
        $p = $this->dec($request);
        $email = strtolower(trim($p['email'] ?? ''));
        $password = (string)($p['password'] ?? '');

        $u = User::where('email',$email)->first();
        if (!$u || !Hash::check($password, $u->password)) {
            return response()->json(['ok'=>false,'message'=>'Invalid credentials'], 200);
        }

        $token = $u->createToken('pharmastock')->plainTextToken;

        return response()->json(['ok'=>true,'token'=>$token], 200);
    }

    public function status(Request $request)
    {
        $u = $request->attributes->get('auth_user');
        if (!$u) return response()->json(['ok'=>false,'message'=>'Unauthorized'], 200);

        return response()->json([
            'ok'=>true,
            'data'=>[
                'emailVerified'=>!is_null($u->email_verified_at),
                'approvalStatus'=>$u->approval_status,
                'role'=>$u->role,
            ]
        ], 200);
    }

    public function resendVerification(Request $request)
    {
        $u = $request->attributes->get('auth_user');
        if (!$u) return response()->json(['ok'=>false,'message'=>'Unauthorized'], 200);

        if (!is_null($u->email_verified_at)) {
            return response()->json(['ok'=>true,'message'=>'Already verified'], 200);
        }

        if (!$u->email_verify_token) {
            $u->email_verify_token = Str::random(60);
            $u->save();
        }

        $link = url("/api/auth/verify-email?token={$u->email_verify_token}");
        Mail::raw("Verify your email: {$link}", function($m) use ($u) {
            $m->to($u->email)->subject("Verify Email");
        });

        return response()->json(['ok'=>true,'message'=>'Verification email resent'], 200);
    }

    // NOT encrypted (opened from email)
    public function verifyEmailLink(Request $request)
    {
        $token = (string)$request->query('token','');
        $u = User::where('email_verify_token',$token)->first();

        if (!$u) return response("Invalid token", 400);

        $u->email_verified_at = now();
        $u->email_verify_token = null;
        $u->save();

        return response("Email verified. Return to the app.", 200);
    }
}
