<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class LupaPasswordController extends Controller
{
    // Tampilkan form input email
    public function formEmail()
    {
        return view('admin.lupa_password');
    }

    // Kirim tautan reset ke email
    public function kirimTautan(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::broker('admins')->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Tautan reset kata sandi sudah dikirim ke email Anda.');
        }

        return back()->withErrors([
            'email' => 'Email tidak terdaftar, atau Anda terlalu sering meminta reset. Coba lagi nanti.',
        ]);
    }

    // Tampilkan form kata sandi baru
    public function formReset(Request $request, string $token)
    {
        return view('admin.reset_password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    // Simpan kata sandi baru
    public function reset(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::broker('admins')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Admin $admin, string $password) {
                $admin->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')
                ->with('status', 'Kata sandi berhasil diubah. Silakan login.');
        }

        return back()->withErrors([
            'email' => 'Token tidak valid atau sudah kedaluwarsa. Silakan minta tautan baru.',
        ]);
    }
}