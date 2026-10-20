<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];

        if ($request->filled('password')) {
            $rules['current_password'] = ['required', 'current_password'];
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed', 'different:current_password'];
        }

        $data = $request->validate($rules);

        $avatarBaru = null;
        if ($request->hasFile('avatar')) {
            $avatarBaru = $request->file('avatar')->store('avatars', 'public');
            if (!$avatarBaru) {
                throw new RuntimeException('Foto profil gagal disimpan.');
            }

            $data['avatar'] = $avatarBaru;
        }

        $avatarLama = $user->avatar;
        try {
            $user->update($data);
        } catch (Throwable $exception) {
            if ($avatarBaru) {
                Storage::disk('public')->delete($avatarBaru);
            }

            throw $exception;
        }

        if ($avatarBaru && $avatarLama && Storage::disk('public')->exists($avatarLama)) {
            if (!Storage::disk('public')->delete($avatarLama)) {
                throw new RuntimeException('Foto profil lama gagal dihapus dari penyimpanan.');
            }
        }

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}
