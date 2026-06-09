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
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Default role
            'last_active_at' => now(),
            'whatsapp' => $request->whatsapp,
        ]);

        Auth::login($user);

        return redirect()->route('customer.produk');
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

            return redirect()->route('customer.produk');
        }

        // If email attempt fails, try attempting using name (username)
        if (Auth::attempt(['name' => $credentials['email'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            Auth::user()->update(['last_active_at' => now()]);

            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('customer.produk');
        }

        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showForgotPassword()
    {
        return view('customer.auth.forgot-password');
    }

    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'exists:users,email'],
        ]);

        $email = $request->email;
        $code = sprintf("%06d", mt_rand(1, 999999));

        // Save code to database
        DB::table('password_reset_codes')->updateOrInsert(
            ['email' => $email],
            ['code' => $code, 'created_at' => now()]
        );

        // Send Email
        \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($email, $code) {
            $message->to($email)
                    ->subject('Kode Verifikasi Reset Password Goatin')
                    ->html("<h3>Goatin Stewardship Portal</h3><p>Halo,</p><p>Berikut adalah kode verifikasi reset password Anda:</p><h1 style='font-size:32px;letter-spacing:5px;color:#1e4e2f;font-weight:bold;'>$code</h1><p>Kode ini berlaku selama 15 menit. Jika Anda tidak meminta reset password ini, silakan abaikan email ini.</p>");
        });

        return redirect()->route('password.reset', ['email' => $email])->with('status', 'Kode verifikasi telah dikirim ke email Anda.');
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

        // Update password
        $user = User::where('email', $request->email)->firstOrFail();
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // Delete used code
        DB::table('password_reset_codes')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('status', 'Password Anda berhasil direset. Silakan login dengan password baru.');
    }

    public function redirectToGoogle()
    {
        return view('customer.auth.google-mock');
    }

    public function handleGoogleCallback(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'name' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make(Str::random(16)),
                'role' => 'user',
                'last_active_at' => now(),
                'whatsapp' => '6281234567890', // Default mock whatsapp
            ]);
        }

        Auth::login($user);
        
        $user->update(['last_active_at' => now()]);
        $request->session()->regenerate();

        return redirect()->route('customer.produk');
    }
}
