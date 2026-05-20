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
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Default role
        ]);

        Auth::login($user);

        return redirect()->route('customer.dashboard');
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

            // Redirect based on role
            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('customer.dashboard');
        }

        // If email attempt fails, try attempting using name (username)
        if (Auth::attempt(['name' => $credentials['email'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('customer.dashboard');
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

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string'],
        ]);

        // Find user by username
        $user = User::where('name', $request->username)->first();

        if (!$user) {
            return back()->withErrors(['username' => 'Username tidak ditemukan.']);
        }

        // Send reset link to user's email
        $status = Password::sendResetLink(['email' => $user->email]);

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Tautan reset password telah dikirim ke email Anda.')
            : back()->withErrors(['username' => 'Gagal mengirim tautan reset. Silakan coba lagi.']);
    }

    public function showResetPasswordForm($token)
    {
        return view('customer.auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        // Get email from password reset token
        $tokenRecord = DB::table('password_reset_tokens')
            ->where('token', hash('sha256', $request->token))
            ->first();

        if (!$tokenRecord) {
            return back()->withErrors(['token' => 'Token tidak valid atau telah kadaluarsa.']);
        }

        $status = Password::reset(
            [
                'email' => $tokenRecord->email,
                'password' => $request->password,
                'password_confirmation' => $request->password,
                'token' => $request->token,
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', 'Password Anda berhasil diubah. Silakan login dengan password baru.')
            : back()->withErrors(['password' => ['Gagal mereset password. Silakan coba lagi.']]);
    }
}
