<?php

namespace App\Http\Controllers\AdminWeb;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function show()
    {
        if (Auth::check() && (Auth::user()->role ?? 'pharmacy') === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
{
    $data = $request->validate([
        'email' => ['required','email'],
        'password' => ['required','string'],
    ]);

    $email = strtolower(trim($data['email']));

    $u = \App\Models\User::where('email', $email)->first();

    if (!$u) {
        return back()->withErrors(['email' => 'User not found'])->withInput();
    }

    if (!\Illuminate\Support\Facades\Hash::check($data['password'], $u->password)) {
        return back()->withErrors(['email' => 'Password mismatch (hash)'])->withInput();
    }

    if (!\Illuminate\Support\Facades\Auth::attempt(['email' => $email, 'password' => $data['password']])) {
        return back()->withErrors(['email' => 'Auth::attempt failed (guard/provider)'])->withInput();
    }

    $request->session()->regenerate();

    $u = $request->user();
    if (($u->role ?? 'pharmacy') !== 'admin') {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return back()->withErrors(['email' => 'Not allowed'])->withInput();
    }

    return redirect()->route('admin.dashboard');
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
