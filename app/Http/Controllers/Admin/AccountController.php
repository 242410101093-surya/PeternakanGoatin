<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the user accounts.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $role = $request->input('role');
            if ($role === 'admin' || $role === 'user') {
                $query->where('role', $role);
            }
        }

        // Filter by status
        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->where('last_active_at', '>=', now()->subMonth());
            } elseif ($status === 'inactive') {
                $query->where(function($q) {
                    $q->whereNull('last_active_at')
                      ->orWhere('last_active_at', '<', now()->subMonth());
                });
            }
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.accounts.index', compact('users'));
    }

    /**
     * Store a newly created account.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:admin,user',
            'whatsapp' => 'nullable|string|max:255',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role,
            'last_active_at' => now(), // Active by default
            'whatsapp' => $request->whatsapp,
        ]);

        return redirect()->route('admin.accounts.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    /**
     * Update the specified account.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|string|in:admin,user',
            'whatsapp' => 'nullable|string|max:255',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'whatsapp' => $request->whatsapp,
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $user->update($data);

        return redirect()->route('admin.accounts.index')->with('success', 'Akun berhasil diperbarui.');
    }

    /**
     * Remove the specified account.
     */
    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Prevent admin from deleting their own account
        if ($user->id === auth()->id()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Anda tidak dapat menghapus akun Anda sendiri.'], 400);
            }
            return redirect()->route('admin.accounts.index')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Akun berhasil dihapus.']);
        }
        return redirect()->route('admin.accounts.index')->with('success', 'Akun berhasil dihapus.');
    }
}
