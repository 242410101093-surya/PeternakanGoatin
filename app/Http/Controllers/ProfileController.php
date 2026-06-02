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
            'foto_profil' => ['nullable', 'image', 'max:5120'], // 5MB max
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto_profil')) {
            // Delete old photo if exists
            if ($user->foto_profil && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->foto_profil)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->foto_profil);
            }
            $path = $request->file('foto_profil')->store('profile_photos', 'public');
            $data['foto_profil'] = $path;
        }

        $user->fill($data);
        if ($request->email !== $user->email) {
            $user->email_verified_at = null;
        }
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
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
        \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($email, $code) {
            $message->to($email)
                    ->subject('Kode Verifikasi Email Goatin')
                    ->html("<h3>Goatin Stewardship Portal</h3><p>Halo,</p><p>Berikut adalah kode verifikasi untuk memverifikasi email profil Anda:</p><h1 style='font-size:32px;letter-spacing:5px;color:#1e4e2f;font-weight:bold;'>$code</h1><p>Kode ini berlaku selama 15 menit. Jika Anda tidak meminta verifikasi ini, silakan abaikan email ini.</p>");
        });

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
            return back()->with([
                'open_verify_modal' => true
            ])->withErrors(['code' => 'Kode verifikasi tidak valid.']);
        }

        // Check if expired (15 minutes)
        if (\Carbon\Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            return back()->with([
                'open_verify_modal' => true
            ])->withErrors(['code' => 'Kode verifikasi telah kedaluwarsa. Silakan kirim ulang kode baru.']);
        }

        // Update user
        $user->email_verified_at = now();
        $user->save();

        // Delete code
        \Illuminate\Support\Facades\DB::table('email_verification_codes')->where('email', $email)->delete();

        return back()->with('success', 'Email Anda berhasil diverifikasi!');
    }
}
