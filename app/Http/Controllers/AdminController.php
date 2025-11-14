<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class AdminController extends Controller
{


    public function TagihanAdmin()
    {
        return view('Admin.tagihan');
    }

    public function TotalAdmin()
    {
        return view('Admin.total');
    }

    //Menampilkan data pelanggan
    public function searchPelanggan(Request $request)
    {
        $query = $request->q;

        $pelanggan = Pelanggan::where('nama_pelanggan', 'like', "%$query%")
            ->orWhere('alamat', 'like', "%$query%")
            ->orWhere('no_kwh', 'like', "%$query%")
            ->orderBy('id_pelanggan', 'asc')
            ->get();

        return view('Admin.components.table_pelanggan', compact('pelanggan'));
    }

    public function PelangganAdmin(Request $request)
    {
        $query = Pelanggan::query();

        // Fitur pencarian
        if ($request->has('q') && $request->q != '') {
            $query->where('nama_pelanggan', 'like', '%' . $request->q . '%')
                ->orWhere('no_kwh', 'like', '%' . $request->q . '%')
                ->orWhere('alamat', 'like', '%' . $request->q . '%');
        }

        $pelanggan = $query->paginate(10);

        return view('Admin.pelanggan', compact('pelanggan'));
    }

    // UPDATE - Menampilkan form edit
    public function editPelanggan($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('Admin.update.pelanggan', compact('pelanggan'));
    }

    // UPDATE - Proses update data
    public function updatePelanggan(Request $request, $id)
    {
        $request->validate([
            'nama_pengguna' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_kwh' => 'required|string|max:20'
        ]);

        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->update($request->all());

        return redirect()->route('admin.pelanggan')
            ->with('success', 'Data pelanggan berhasil diupdate');
    }

    public function deletePelanggan($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();

        return redirect()->route('admin.pelanggan')
            ->with('success', 'Data pelanggan berhasil dihapus');
    }
}
