<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('customer.auth.login');
    }

    public function showRegister()
    {
        return view('customer.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'whatsapp' => ['required', 'string', 'max:255'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.max' => 'Nama lengkap maksimal 255 karakter.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar. Silakan gunakan email lain.',
            'email.max' => 'Alamat email maksimal 255 karakter.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'whatsapp.max' => 'Nomor WhatsApp maksimal 255 karakter.'
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'role' => 'user', // Default role
                'last_active_at' => now(),
                'whatsapp' => $request->whatsapp,
            ]);

            Auth::login($user);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Selamat, Anda selesai registrasi akun!',
                    'redirect' => route('customer.dashboard')
                ]);
            }

            return redirect()->route('customer.dashboard')->with('success', 'Selamat datang di Goatin! Akun Anda berhasil dibuat.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Registration Error: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Terjadi kesalahan sistem saat mendaftar. Silakan coba lagi.'])->withInput();
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Attempt login using email
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            Auth::user()->update(['last_active_at' => now()]);

            // Redirect based on role
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('customer.dashboard');
        }

        // If email attempt fails, try attempting using name (username)
        if (Auth::attempt(['name' => $credentials['email'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            Auth::user()->update(['last_active_at' => now()]);

            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('customer.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau Password salah, silakan coba lagi.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to landing page after logout
        return redirect()->route('landing');
    }

    public function showForgotPassword()
    {
        return view('customer.auth.forgot-password');
    }

    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'exists:users,email'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.exists' => 'Email ini tidak ditemukan dalam sistem kami.'
        ]);

        $email = $request->email;
        $code = sprintf("%06d", mt_rand(1, 999999));

        try {
            // Save code to database
            DB::table('password_reset_codes')->updateOrInsert(
                ['email' => $email],
                ['code' => $code, 'created_at' => now()]
            );

            // Send Email
            try {
                \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($email, $code) {
                    $message->to($email)
                            ->subject('Kode Verifikasi Reset Password Goatin')
                            ->html("<h3>Goatin Stewardship Portal</h3><p>Halo,</p><p>Berikut adalah kode verifikasi reset password Anda:</p><h1 style='font-size:32px;letter-spacing:5px;color:#1e4e2f;font-weight:bold;'>$code</h1><p>Kode ini berlaku selama 15 menit. Jika Anda tidak meminta reset password ini, silakan abaikan email ini.</p>");
                });
                return redirect()->route('password.reset', ['email' => $email])->with('status', 'Kode verifikasi telah dikirim ke email Anda.');
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Mail Send Error: ' . $e->getMessage());
                if (config('app.env') === 'local') {
                    // Fallback for development: skip email and auto-fill the code
                    return redirect()->route('password.reset', ['email' => $email, 'dev_code' => $code]);
                }
                // Rollback the code if email failed
                DB::table('password_reset_codes')->where('email', $email)->delete();
                return back()->withErrors(['email' => 'Gagal mengirim email verifikasi. Pastikan konfigurasi server email benar atau coba lagi nanti.']);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Mail Send Critical Error: ' . $e->getMessage());
            DB::table('password_reset_codes')->where('email', $email)->delete();
            return back()->withErrors(['email' => 'Terjadi kesalahan internal.']);
        }
    }

    public function showResetPasswordForm(Request $request)
    {
        return view('customer.auth.reset-password', ['email' => $request->query('email')]);
    }

    public function resetPasswordWithCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'exists:users,email'],
            'code' => ['required', 'string', 'size:6'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.exists' => 'Email ini tidak ditemukan.',
            'code.required' => 'Kode verifikasi wajib diisi.',
            'code.size' => 'Kode verifikasi harus 6 digit.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        $resetRecord = DB::table('password_reset_codes')
            ->where('email', $request->email)
            ->where('code', $request->code)
            ->first();

        if (!$resetRecord) {
            return back()->withErrors(['code' => 'Kode verifikasi tidak valid.']);
        }

        // Check if expired (15 minutes)
        if (\Carbon\Carbon::parse($resetRecord->created_at)->addMinutes(15)->isPast()) {
            return back()->withErrors(['code' => 'Kode verifikasi telah kedaluwarsa. Silakan minta kode baru.']);
        }

        try {
            DB::beginTransaction();

            // Update password
            $user = User::where('email', $request->email)->firstOrFail();
            $user->update([
                'password' => $request->password
            ]);

            // Delete used code
            DB::table('password_reset_codes')->where('email', $request->email)->delete();

            DB::commit();

            return redirect()->route('login')->with('status', 'Password Anda berhasil direset. Silakan login dengan password baru.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Password Reset Error: ' . $e->getMessage());
            return back()->withErrors(['code' => 'Terjadi kesalahan sistem saat mereset password. Silakan coba lagi.']);
        }
    }

    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google and log them in.
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Google OAuth Error: ' . $e->getMessage());
            return redirect()->route('login')
                ->withErrors(['email' => 'Login dengan Google gagal. Silakan coba lagi.']);
        }

        // Find or create the user
        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            // New user → register automatically
            $user = User::create([
                'name'           => $googleUser->getName(),
                'email'          => $googleUser->getEmail(),
                'password'       => Hash::make(Str::random(24)),
                'role'           => 'user',
                'last_active_at' => now(),
                'whatsapp'       => '', // User can fill in later via profile
            ]);
        }

        Auth::login($user);
        $user->update(['last_active_at' => now()]);
        $request->session()->regenerate();

        // Redirect based on role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('customer.dashboard');
    }
}
