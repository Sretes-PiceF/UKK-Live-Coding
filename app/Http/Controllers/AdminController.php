<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Tagihan;
use App\Models\TotalTagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{

    //Logika pelanggan
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


    //Logika Tagihan
    public function TagihanAdmin()
    {
        $query = Tagihan::query();

        $tagihan = $query->paginate(10);
        return view('admin.tagihan', compact('tagihan'));
    }

    public function createTagihan()
    {
        return view('Admin.create.tagihan');
    }

    public function storeTagihan(Request $request)
    {
        $request->validate([
            'bulan'         => 'required',
            'tahun'         => 'required|numeric',
            'jumlah_meter'  => 'required|numeric',
            'tarif_per_kwh' => 'required|numeric',
        ]);

        Tagihan::create([
            'id_tagihan'    => Str::random(16),
            'bulan'         => $request->bulan,
            'tahun'         => $request->tahun,
            'jumlah_meter'  => $request->jumlah_meter,
            'tarif_per_kwh' => $request->tarif_per_kwh,
        ]);

        return redirect()->route('admin.tagihan')->with('success', 'Tagihan berhasil ditambahkan!');
    }

    public function editTagihan($id_tagihan)
    {
        // Untuk string ID, gunakan where() bukan findOrFail()
        $tagihan = Tagihan::where('id_tagihan', $id_tagihan)->firstOrFail();
        return view('admin.update.tagihan', compact('tagihan'));
    }

    public function updateTagihan(Request $request, $id_tagihan)
    {
        $request->validate([
            'bulan'         => 'required',
            'tahun'         => 'required|numeric',
            'jumlah_meter'  => 'required|numeric',
            'tarif_per_kwh' => 'required|numeric',
        ]);

        // Gunakan where() untuk string ID
        $tagihan = Tagihan::where('id_tagihan', $id_tagihan)->firstOrFail();

        $tagihan->update([
            'bulan'         => $request->bulan,
            'tahun'         => $request->tahun,
            'jumlah_meter'  => $request->jumlah_meter,
            'tarif_per_kwh' => $request->tarif_per_kwh,
        ]);

        return redirect()->route('admin.tagihan')->with('success', 'Tagihan berhasil diperbarui!');
    }

    public function deleteTagihan($id_tagihan)
    {
        Tagihan::where('id_tagihan', $id_tagihan)->delete();
        return redirect()->route('admin.tagihan')->with('success', 'Tagihan berhasil dihapus!');
    }



    public function TotalAdmin()
    {
        $querry = TotalTagihan::query();
        $total = $querry->paginate(10);
        return view('Admin.total', compact('total'));
    }
}
