<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Tagihan;
use App\Models\TotalTagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            'biaya_admin'   => 'required|numeric',
        ]);

        Tagihan::create([
            'id_tagihan'    => Str::random(16),
            'bulan'         => $request->bulan,
            'tahun'         => $request->tahun,
            'jumlah_meter'  => $request->jumlah_meter,
            'tarif_per_kwh' => $request->tarif_per_kwh,
            'biaya_admin'   => $request->biaya_admin,
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

    public function index()
    {
        $tagihan = Tagihan::all();

        // Mapping bulan ke Indonesia
        $bulan_map = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember',
        ];

        // Transform data
        $tagihan->transform(function ($item) use ($bulan_map) {
            $item->bulan_indo = $bulan_map[$item->bulan] ?? $item->bulan ?? '-';
            return $item;
        });

        return view('admin.tagihan', compact('tagihan'));
    }



    public function TotalAdmin(Request $request)
    { // Query dasar dengan eager loading relasi
        $query = TotalTagihan::with(['pelanggan', 'tagihan']);

        // Filter berdasarkan bulan (dari relasi tagihan)
        if ($request->filled('bulan')) {
            $query->whereHas('tagihan', function ($q) use ($request) {
                $q->where('bulan', $request->bulan);
            });
        }

        // Filter berdasarkan tahun (dari relasi tagihan)
        if ($request->filled('tahun')) {
            $query->whereHas('tagihan', function ($q) use ($request) {
                $q->where('tahun', $request->tahun);
            });
        }

        // Filter berdasarkan status pembayaran
        if ($request->filled('status')) {
            $query->where('status_pembayaran', $request->status);
        }

        // Filter berdasarkan nama pelanggan (dari relasi pelanggan)
        if ($request->filled('q')) {
            $query->whereHas('pelanggan', function ($q) use ($request) {
                $q->where('nama_pelanggan', 'like', '%' . $request->q . '%');
            });
        }

        // Order by: belum bayar dulu, lalu terbaru
        $totalTagihan = $query
            ->orderByRaw("FIELD(status_pembayaran, 'Belum bayar', 'Dibayar')")
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->appends($request->all()); // Maintain query string di pagination

        // Hitung statistik
        $jumlahTotal = TotalTagihan::count();
        $jumlahBelumBayar = TotalTagihan::where('status_pembayaran', 'Belum bayar')->count();
        $jumlahSudahBayar = TotalTagihan::where('status_pembayaran', 'Dibayar')->count();
        $totalPendapatan = TotalTagihan::where('status_pembayaran', 'Dibayar')->sum('total_bayar');

        return view('admin.total', compact(
            'totalTagihan',
            'jumlahTotal',
            'jumlahBelumBayar',
            'jumlahSudahBayar',
            'totalPendapatan'
        ));
    }


    public function destroy($id)
    {
        try {
            $totalTagihan = TotalTagihan::findOrFail($id);

            if ($totalTagihan->status_pembayaran == 'Dibayar') {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus tagihan yang sudah dibayar!');
            }

            $totalTagihan->forceDelete();

            return redirect()->back()->with('success', 'Tagihan berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error("Hapus gagal: " . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
