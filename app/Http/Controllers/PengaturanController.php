<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function edit()
    {
        // Cari record 'biaya_admin'. Jika belum ada, buat dengan nilai awal 0.
        $biayaAdmin = Pengaturan::firstOrCreate(
            ['key' => 'biaya_admin'],
            ['value' => 0] // Nilai default saat record baru dibuat
        );

        return view('admin.pengaturan.edit', compact('biayaAdmin'));
    }

    public function update(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'biaya_admin' => 'required|integer|min:0', // Harus angka, tidak boleh negatif
        ], [
            'biaya_admin.required' => 'Biaya Admin wajib diisi.',
            'biaya_admin.integer' => 'Biaya Admin harus berupa angka (tanpa titik/koma).',
            'biaya_admin.min' => 'Biaya Admin tidak boleh bernilai negatif.',
        ]);

        // 2. Update atau Buat Record
        // Kita menggunakan updateOrCreate untuk memastikan record 'biaya_admin' selalu ada dan terupdate
        Pengaturan::updateOrCreate(
            ['key' => 'biaya_admin'],
            ['value' => $request->biaya_admin]
        );

        // 3. Redirect dengan pesan sukses
        return redirect()->route('admin.pengaturan.edit')
            ->with('success', 'Biaya Admin berhasil diperbarui!');
    }
}
