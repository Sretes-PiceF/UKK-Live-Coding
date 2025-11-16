<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Tagihan;
use App\Models\TotalTagihan;
use Carbon\Carbon as CarbonAlias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PelangganController extends Controller
{

    public function Tagihan()
    {
        $tagihan = Tagihan::all();
        return view('Pelanggan.tagihan', compact('tagihan'));
    }

    public function Total(Request $request)
    {

        $idPelanggan = auth()->guard('pelanggan')->user()->id_pelanggan;
        $bulanSekarang = date('F');
        $tahunSekarang = date('Y');

        // Array bulan untuk konversi
        $bulanMap = [
            'January' => 1,
            'February' => 2,
            'March' => 3,
            'April' => 4,
            'May' => 5,
            'June' => 6,
            'July' => 7,
            'August' => 8,
            'September' => 9,
            'October' => 10,
            'November' => 11,
            'December' => 12
        ];

        // Ambil tagihan dengan filter berdasarkan tanggal sekarang
        // HANYA mengambil data yang SUDAH ADA dan SUDAH JATUH TEMPO
        $tagihan = TotalTagihan::with(['tagihan', 'pelanggan'])
            ->where('id_pelanggan', $idPelanggan)
            ->whereHas('tagihan', function ($query) use ($tahunSekarang, $bulanSekarang, $bulanMap) {
                $query->where(function ($q) use ($tahunSekarang, $bulanSekarang, $bulanMap) {
                    // Tagihan tahun lalu atau tahun-tahun sebelumnya
                    $q->where('tahun', '<', $tahunSekarang)
                        // Atau tagihan tahun ini tapi bulan sudah lewat atau bulan sekarang
                        ->orWhere(function ($q2) use ($tahunSekarang, $bulanSekarang, $bulanMap) {
                            $q2->where('tahun', '=', $tahunSekarang)
                                ->whereRaw("FIELD(bulan, 'January','February','March','April','May','June','July','August','September','October','November','December') <= ?", [$bulanMap[$bulanSekarang]]);
                        });
                });
            })
            ->orderByRaw("FIELD(status_pembayaran, 'Belum bayar', 'Dibayar')")
            ->orderByDesc('created_at')
            ->get();

        // Hitung total tagihan yang belum bayar (hanya yang sudah jatuh tempo)
        $totalBelumBayar = TotalTagihan::where('id_pelanggan', $idPelanggan)
            ->where('status_pembayaran', 'Belum bayar')
            ->whereHas('tagihan', function ($query) use ($tahunSekarang, $bulanSekarang, $bulanMap) {
                $query->where(function ($q) use ($tahunSekarang, $bulanSekarang, $bulanMap) {
                    $q->where('tahun', '<', $tahunSekarang)
                        ->orWhere(function ($q2) use ($tahunSekarang, $bulanSekarang, $bulanMap) {
                            $q2->where('tahun', '=', $tahunSekarang)
                                ->whereRaw("FIELD(bulan, 'January','February','March','April','May','June','July','August','September','October','November','December') <= ?", [$bulanMap[$bulanSekarang]]);
                        });
                });
            })
            ->sum('total_bayar');

        $jumlahBelumBayar = TotalTagihan::where('id_pelanggan', $idPelanggan)
            ->where('status_pembayaran', 'Belum bayar')
            ->whereHas('tagihan', function ($query) use ($tahunSekarang, $bulanSekarang, $bulanMap) {
                $query->where(function ($q) use ($tahunSekarang, $bulanSekarang, $bulanMap) {
                    $q->where('tahun', '<', $tahunSekarang)
                        ->orWhere(function ($q2) use ($tahunSekarang, $bulanSekarang, $bulanMap) {
                            $q2->where('tahun', '=', $tahunSekarang)
                                ->whereRaw("FIELD(bulan, 'January','February','March','April','May','June','July','August','September','October','November','December') <= ?", [$bulanMap[$bulanSekarang]]);
                        });
                });
            })
            ->count();

        // Jika tidak ada tagihan yang sudah jatuh tempo
        if ($tagihan->isEmpty()) {
            return view('pelanggan.total', [
                'pesan' => 'Belum ada tagihan yang perlu dibayar untuk bulan ini.'
            ]);
        }

        return view('pelanggan.total', compact(
            'tagihan',
            'totalBelumBayar',
            'jumlahBelumBayar'
        ));
    }
    public function bayar($id)
    {
        $tagihan = TotalTagihan::findOrFail($id);

        $tagihan->status_pembayaran = "Dibayar";
        $tagihan->tanggal_bayar = CarbonAlias::now();
        $tagihan->save();

        return redirect()->back()->with('success', 'Tagihan berhasil dibayar.');
    }

    public function bayarSemua()
    {
        $idPelanggan = auth()->guard('pelanggan')->user()->id_pelanggan;

        TotalTagihan::where('id_pelanggan', $idPelanggan)
            ->where('status_pembayaran', 'Belum bayar')
            ->update([
                'status_pembayaran' => 'Dibayar',
                'tanggal_bayar' => CarbonAlias::now()
            ]);

        return redirect()->back()->with('success', 'Semua tagihan berhasil dibayar.');
    }
}
