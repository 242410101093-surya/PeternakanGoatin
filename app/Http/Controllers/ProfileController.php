<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return view('admin.profile', compact('user'));
        }
        return view('customer.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'whatsapp' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'foto_profil' => ['nullable', 'file', 'max:5120'], // 5MB max
            'alamat' => ['nullable', 'string'],
            'tipe_alamat' => ['nullable', 'string', 'in:Rumah,Kantor'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        if ($request->filled('password')) {
            if (!$request->filled('otp_code')) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'errors' => ['otp_code' => ['Kode OTP diperlukan untuk mengubah password.']]
                    ], 422);
                }
                return back()->withErrors(['otp_code' => 'Kode OTP diperlukan untuk mengubah password.']);
            }

            $otpRecord = \Illuminate\Support\Facades\DB::table('password_reset_codes')
                ->where('email', $user->email)
                ->where('code', $request->otp_code)
                ->first();

            if (!$otpRecord) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'errors' => ['otp_code' => ['Kode OTP verifikasi tidak valid.']]
                    ], 422);
                }
                return back()->withErrors(['otp_code' => 'Kode OTP verifikasi tidak valid.']);
            }

            // Check if expired (15 minutes)
            if (\Carbon\Carbon::parse($otpRecord->created_at)->addMinutes(15)->isPast()) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'errors' => ['otp_code' => ['Kode OTP telah kedaluwarsa. Silakan kirim ulang kode baru.']]
                    ], 422);
                }
                return back()->withErrors(['otp_code' => 'Kode OTP telah kedaluwarsa. Silakan kirim ulang kode baru.']);
            }

            // Delete verified OTP
            \Illuminate\Support\Facades\DB::table('password_reset_codes')->where('email', $user->email)->delete();
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
            'alamat' => $request->alamat,
            'tipe_alamat' => $request->tipe_alamat,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        try {
            // Perbaikan Bug: Cek request->file('foto')
            if ($request->hasFile('foto')) {
                if ($user->foto_profil && \Illuminate\Support\Facades\Storage::exists($user->foto_profil)) {
                    \Illuminate\Support\Facades\Storage::delete($user->foto_profil);
                }
                $path = $request->file('foto')->store('profile_photos');
                $user->foto_profil = $path;
            } elseif ($request->hasFile('foto_profil')) {
                if ($user->foto_profil && \Illuminate\Support\Facades\Storage::exists($user->foto_profil)) {
                    \Illuminate\Support\Facades\Storage::delete($user->foto_profil);
                }
                $path = $request->file('foto_profil')->store('profile_photos');
                $user->foto_profil = $path;
            }

            $user->fill($data);
            if ($request->email !== $user->email) {
                $user->email_verified_at = null;
            }
            $user->save();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Profile update failed: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'errors' => ['system' => [$e->getMessage()]]
                ], 500);
            }
            return back()->with('error', $e->getMessage());
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui.',
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'whatsapp' => $user->whatsapp ?? '-',
                    'foto_profil' => $user->foto_profil ? (\Illuminate\Support\Facades\Storage::url($user->foto_profil) . '?t=' . time()) : null,
                    'foto_profil_raw' => $user->foto_profil,
                    'email_verified' => $user->email_verified_at ? true : false,
                    'alamat' => $user->alamat,
                    'tipe_alamat' => $user->tipe_alamat,
                    'latitude' => $user->latitude,
                    'longitude' => $user->longitude,
                ]
            ]);
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function sendPasswordOtp(Request $request)
    {
        $user = auth()->user();
        $email = $user->email;
        $code = sprintf("%06d", mt_rand(1, 999999));

        // Save code to database
        \Illuminate\Support\Facades\DB::table('password_reset_codes')->updateOrInsert(
            ['email' => $email],
            ['code' => $code, 'created_at' => now()]
        );

        // Send Email
        try {
            \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($email, $code) {
                $message->to($email)
                        ->subject('Kode Verifikasi Perubahan Password Goatin')
                        ->html("<h3>Goatin Stewardship Portal</h3><p>Halo,</p><p>Berikut adalah kode OTP untuk melakukan perubahan password pada akun Anda:</p><h1 style='font-size:32px;letter-spacing:5px;color:#1e4e2f;font-weight:bold;'>$code</h1><p>Kode ini berlaku selama 15 menit. Jika Anda tidak meminta perubahan password ini, silakan segera ganti kredensial Anda.</p>");
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::info("Goatin OTP Verification Code for {$email}: {$code}. Mail sending failed: " . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kode OTP verifikasi telah dikirim (Cek laravel.log karena pengiriman email gagal).'
                ]);
            }
            return back()->with('success', 'Kode OTP verifikasi telah dikirim (Cek laravel.log karena pengiriman email gagal).');
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kode OTP verifikasi telah dikirim ke Gmail Anda.'
            ]);
        }

        return back()->with('success', 'Kode OTP verifikasi telah dikirim ke email Anda.');
    }

    public function sendVerificationCode(Request $request)
    {
        $user = auth()->user();
        $email = $user->email;
        $code = sprintf("%06d", mt_rand(1, 999999));

        // Save code to database
        \Illuminate\Support\Facades\DB::table('email_verification_codes')->updateOrInsert(
            ['email' => $email],
            ['code' => $code, 'created_at' => now()]
        );

        // Send Email
        try {
            \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($email, $code) {
                $message->to($email)
                        ->subject('Kode Verifikasi Email Goatin')
                        ->html("<h3>Goatin Stewardship Portal</h3><p>Halo,</p><p>Berikut adalah kode verifikasi untuk memverifikasi email profil Anda:</p><h1 style='font-size:32px;letter-spacing:5px;color:#1e4e2f;font-weight:bold;'>$code</h1><p>Kode ini berlaku selama 15 menit. Jika Anda tidak meminta verifikasi ini, silakan abaikan email ini.</p>");
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::info("Goatin Email Verification Code for {$email}: {$code}. Mail sending failed: " . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kode verifikasi telah dikirim (Cek laravel.log karena pengiriman email gagal).'
                ]);
            }
            return back()->with([
                'success' => 'Kode verifikasi telah dikirim (Cek laravel.log karena pengiriman email gagal).',
                'open_verify_modal' => true
            ]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kode verifikasi telah dikirim ke email Anda.'
            ]);
        }

        return back()->with([
            'success' => 'Kode verifikasi telah dikirim ke email Anda.',
            'open_verify_modal' => true
        ]);
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6'],
        ]);

        $user = auth()->user();
        $email = $user->email;

        $record = \Illuminate\Support\Facades\DB::table('email_verification_codes')
            ->where('email', $email)
            ->where('code', $request->code)
            ->first();

        if (!$record) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'errors' => ['code' => ['Kode verifikasi tidak valid.']]
                ], 422);
            }
            return back()->with([
                'open_verify_modal' => true
            ])->withErrors(['code' => 'Kode verifikasi tidak valid.']);
        }

        // Check if expired (15 minutes)
        if (\Carbon\Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'errors' => ['code' => ['Kode verifikasi telah kedaluwarsa. Silakan kirim ulang kode baru.']]
                ], 422);
            }
            return back()->with([
                'open_verify_modal' => true
            ])->withErrors(['code' => 'Kode verifikasi telah kedaluwarsa. Silakan kirim ulang kode baru.']);
        }

        // Update user
        $user->email_verified_at = now();
        $user->save();

        // Delete code
        \Illuminate\Support\Facades\DB::table('email_verification_codes')->where('email', $email)->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Email Anda berhasil diverifikasi!'
            ]);
        }

        return back()->with('success', 'Email Anda berhasil diverifikasi!');
    }
}
