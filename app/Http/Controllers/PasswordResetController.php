<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\PasswordResetMail;
use App\Models\User;

class PasswordResetController extends Controller
{
    /**
     * Send a password reset link to the user's registered email.
     * Called from the Pengurus UKM profile page.
     */
    public function sendResetLink(Request $request)
    {
        $user = auth()->user();
        $email = $user->email;

        if (!$email) {
            return back()->with('error', 'Akun Anda tidak memiliki email terdaftar. Hubungi Kemahasiswaan untuk bantuan.');
        }

        // Rate limit: only allow 1 reset request per 5 minutes
        $recent = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->whereNull('used_at')
            ->where('created_at', '>=', now()->subMinutes(5))
            ->first();

        if ($recent) {
            return back()->with('error', 'Link reset password sudah dikirim. Silakan cek email Anda atau tunggu 5 menit untuk mengirim ulang.');
        }

        // Create token
        $token = Str::random(64);

        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => now(),
        ]);

        // Build reset URL
        $resetUrl = route('password.reset', ['token' => $token, 'email' => $email]);

        // Get organization name for context
        $orgName = $user->organization->name ?? $user->name ?? 'Organisasi';

        // Send email
        try {
            Mail::to($email)->send(new PasswordResetMail($resetUrl, $orgName));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim email. Silakan coba lagi nanti. Error: ' . $e->getMessage());
        }

        // Mask email for display
        $parts = explode('@', $email);
        $maskedLocal = substr($parts[0], 0, 3) . str_repeat('*', max(0, strlen($parts[0]) - 3));
        $maskedEmail = $maskedLocal . '@' . $parts[1];

        return back()->with('status', "Link reset password telah dikirim ke {$maskedEmail}. Silakan cek inbox atau folder spam Anda.");
    }

    /**
     * Show the reset password form (public page, accessed from email link).
     */
    public function showResetForm(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');

        if (!$token || !$email) {
            abort(404, 'Link reset password tidak valid.');
        }

        // Validate token
        $record = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->where('email', $email)
            ->whereNull('used_at')
            ->first();

        if (!$record) {
            abort(404, 'Link reset password tidak valid atau sudah digunakan.');
        }

        // Check expiration (60 minutes)
        if (now()->diffInMinutes($record->created_at) > 60) {
            abort(410, 'Link reset password sudah kedaluwarsa. Silakan minta link baru dari halaman profil.');
        }

        // Get org name for display
        $user = User::where('email', $email)->first();
        $orgName = $user && $user->organization ? $user->organization->name : null;

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
            'orgName' => $orgName,
        ]);
    }

    /**
     * Handle the actual password update from the reset form.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Validate token again
        $record = DB::table('password_reset_tokens')
            ->where('token', $request->token)
            ->where('email', $request->email)
            ->whereNull('used_at')
            ->first();

        if (!$record) {
            return back()->withErrors(['token' => 'Link reset password tidak valid atau sudah digunakan.']);
        }

        if (now()->diffInMinutes($record->created_at) > 60) {
            return back()->withErrors(['token' => 'Link reset password sudah kedaluwarsa. Silakan minta link baru.']);
        }

        // Update user password
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Akun tidak ditemukan.']);
        }

        $user->update(['password' => $request->password]);

        // Mark token as used
        DB::table('password_reset_tokens')
            ->where('token', $request->token)
            ->update(['used_at' => now()]);

        return redirect()->route('login')->with('success', 'Password berhasil diubah! Silakan login dengan password baru Anda.');
    }
}
