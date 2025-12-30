<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    /**
     * Show forgot password form
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();

        // Generate reset token
        $token = Str::random(60);
        $user->reset_token = $token;
        $user->reset_token_expires = now()->addHours(1);
        $user->save();

        // For demo purposes, show the reset link
        // In production, you would send this via email
        $resetLink = url("/reset-password/{$token}");

        return response()->json([
            'success' => true,
            'message' => 'Link reset password telah dibuat',
            'reset_link' => $resetLink, // Only for demo
            'note' => 'Dalam production, link akan dikirim via email'
        ]);
    }

    /**
     * Show reset password form
     */
    public function showResetForm($token)
    {
        $user = User::where('reset_token', $token)
            ->where('reset_token_expires', '>', now())
            ->first();

        if (!$user) {
            return redirect('/forgot-password')->with('error', 'Link reset tidak valid atau sudah kadaluarsa');
        }

        return view('auth.reset-password', ['token' => $token]);
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request, $token)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::where('reset_token', $token)
            ->where('reset_token_expires', '>', now())
            ->first();

        if (!$user) {
            return back()->with('error', 'Link reset tidak valid atau sudah kadaluarsa');
        }

        $user->password = Hash::make($request->password);
        $user->reset_token = null;
        $user->reset_token_expires = null;
        $user->save();

        return redirect('/login')->with('success', 'Password berhasil direset. Silakan login dengan password baru.');
    }

    /**
     * Manual reset password untuk admin (development only)
     */
    public function manualReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'new_password' => 'required|min:6'
        ]);

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password untuk ' . $user->email . ' telah direset menjadi: ' . $request->new_password
        ]);
    }
}
