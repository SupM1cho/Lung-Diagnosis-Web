<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Diagnosis;

class UserController extends Controller
{
    public function history()
    {
        $user = Auth::user();

        $diagnoses = Diagnosis::where('user_id', $user->id)
            ->with(['labels', 'symptoms'])
            ->orderByDesc('diagnosis_date')
            ->get();

        return view('user', compact('diagnoses', 'user'));
    }

    public function settings()
    {
        $user = Auth::user();
        return view('settings', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . $user->user_id . ',user_id',
            'email' => 'required|email|max:100|unique:users,email,' . $user->user_id . ',user_id',
            'password' => 'nullable|string|min:6'
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan oleh user lain.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh user lain.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        try {
            $user->username = $request->username;
            $user->email = $request->email;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            return redirect()
                ->route('user.dashboard', ['view' => 'settings'])
                ->with('success', 'Profil berhasil diperbarui.');
                
        } catch (\Exception $e) {
            return back()
                ->withErrors(['general' => 'Terjadi kesalahan saat memperbarui profil.'])
                ->withInput();
        }
    }
}