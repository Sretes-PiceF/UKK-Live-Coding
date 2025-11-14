<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function  ViewLoginPelanggan()
    {
        return view('Auth.LoginPelanggan');
    }

    public function ViewLoginAdmin()
    {
        return view('Auth.LoginAdmin');
    }

    public function ViewRegisterPelanggan()
    {
        return view('Auth.RegisterPelanggan');
    }

    public function ViewRegisterAdmin()
    {
        return view('Auth.RegisterAdmin');
    }

    public function ActionRegisterPelanggan(Request $request)
    {
        // ✅ Validasi input
        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'no_kwh' => 'required|digits_between:6,12|unique:pelanggan,no_kwh',
            'password' => 'required|string|min:6|max:255',
        ], [
            'nama_pelanggan.required' => 'Nama pelanggan wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'no_kwh.required' => 'Nomor KWH wajib diisi.',
            'no_kwh.digits_between' => 'Nomor KWH harus terdiri dari 6-12 digit angka.',
            'no_kwh.unique' => 'Nomor KWH sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        try {
            // ✅ Gunakan UUID atau random id yang aman
            $id = mt_rand(1000000000000000, 9999999999999999);

            $user = Pelanggan::create([
                'id_pelanggan' => $id,
                'nama_pelanggan' => $validated['nama_pelanggan'],
                'alamat' => $validated['alamat'],
                'no_kwh' => $validated['no_kwh'],
                'password' => bcrypt($validated['password']),
            ]);

            if ($user) {
                return redirect()->route('login.pelanggan')->with('success', 'Registrasi berhasil! Silakan login.');
            }

            return back()->withInput()->withErrors(['msg' => 'Registrasi gagal. Silakan coba lagi.']);
        } catch (\Throwable $e) {
            // Tangkap error dan tampilkan pesan
            return back()->withInput()->withErrors(['msg' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function ActionRegisterAdmin(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:admin,username',
            'password' => 'required|string|min:6|max:255',
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        try {
            // ✅ Gunakan UUID atau random id yang aman
            $id = mt_rand(1000000000000000, 9999999999999999);

            $user = Admin::create([
                'id_admin' => $id,
                'username' => $validated['username'],
                'password' => bcrypt($validated['password']),
            ]);

            if ($user) {
                return redirect()->route('login.admin')->with('success', 'Registrasi berhasil! Silakan login.');
            }

            return back()->withInput()->withErrors(['msg' => 'Registrasi gagal. Silakan coba lagi.']);
        } catch (\Throwable $e) {
            // Tangkap error dan tampilkan pesan
            return back()->withInput()->withErrors(['msg' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function ActionLogin(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'nullable|string',
            'username' => 'nullable|string',
            'password' => 'required|string',
        ]);

        //Login akun pelanggan
        $pelanggan = $request->only('nama_pelanggan', 'password');

        if (Auth::guard('pelanggan')->attempt($pelanggan)) {
            $request->session()->regenerate();
            return redirect()->intended('/pelanggan/tagihan')->with('success', 'Login berhasil sebagai Pelanggan.');
        }


        // Login akun admin

        $admin = $request->only('username', 'password');
        if (Auth::guard('admin')->attempt($admin)) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/pelanggan')->with('success', 'Login berhasil sebagai Admin.');
        }

        //Troubleshooting jika login gagal
        return back()->withErrors([
            'login' => 'Nama atau username dan password tidak sesuai.',
        ])->onlyInput([
            'nama_pelanggan',
            'username',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        Auth::guard('pelanggan')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login-pelanggan')->with('success', 'Anda telah logout.');
    }
}
