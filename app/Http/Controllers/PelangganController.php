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
        $currentYear = date('Y'); // Tahun sekarang

        $tagihan = Tagihan::where('tahun', $currentYear)
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        return view('Pelanggan.tagihan', compact('tagihan'));
    }

    private function autoGenerateTagihan($idPelanggan)
    {
        $sekarang = CarbonAlias::now();
        $bulanTagihanObj = $sekarang->copy()->subMonth();
        $bulanNama = $bulanTagihanObj->format('F');
        $tahunAngka = $bulanTagihanObj->year;

        $pelanggan = Pelanggan::find($idPelanggan);
        if (!$pelanggan) {
            return;
        }

        // <<< BARU: Ambil Golongan Meter Pelanggan
        $golonganMeterPelanggan = $pelanggan->jumlah_meter ?? 0;

        // ==============================================================
        // 1. Cek & Dapatkan Tagihan Master untuk bulan target
        // ==============================================================
        $tagihanMaster = Tagihan::where('bulan', $bulanNama)
            ->where('tahun', $tahunAngka)
            // <<< PENTING: Filter Tagihan Master berdasarkan Golongan Pelanggan
            ->where('jumlah_meter', $golonganMeterPelanggan)
            ->first();

        // Jika Tagihan Master bulan target belum dibuat oleh Admin:
        if (!$tagihanMaster) {
            // Cari Tagihan Master TERAKHIR yang PERNAH dibuat Admin DENGAN GOLONGAN YANG SAMA
            $tagihanMasterTerakhir = Tagihan::where('jumlah_meter', $golonganMeterPelanggan) // <<< Filter Golongan Meter
                ->orderBy('tahun', 'desc')
                ->orderByRaw("FIELD(bulan, 'January','February','March','April','May','June','July','August','September','October','November','December') DESC")
                ->first();

            if ($tagihanMasterTerakhir) {
                // Gunakan data dari Tagihan Master terakhir ini sebagai acuan untuk membuat Tagihan Master baru

                $tagihanMaster = Tagihan::create([
                    'id_tagihan'    => Str::random(16),
                    'bulan'         => $bulanNama,
                    'tahun'         => $tahunAngka,
                    'jumlah_meter'  => $tagihanMasterTerakhir->jumlah_meter,      // Akan selalu sama dengan $golonganMeterPelanggan
                    'tarif_per_kwh' => $tagihanMasterTerakhir->tarif_per_kwh,
                    'biaya_admin'   => $tagihanMasterTerakhir->biaya_admin
                ]);
            }
        }

        if (!$tagihanMaster) {
            return;
        }
        $existingTotalTagihan = TotalTagihan::where('id_pelanggan', $idPelanggan)
            ->where('id_tagihan', $tagihanMaster->id_tagihan)
            ->where('deleted_by_admin', false)
            ->exists();

        if (!$existingTotalTagihan) {
            $pemakaianKwhPelanggan = $pelanggan->jumlah_meter ?? 0;

            $tarifPerKwhMaster = $tagihanMaster->tarif_per_kwh ?? 0;
            $biayaAdmin = $tagihanMaster->biaya_admin ?? 0;

            $totalBayar = ($pemakaianKwhPelanggan * $tarifPerKwhMaster) + $biayaAdmin;

            // Buat Total Tagihan
            TotalTagihan::create([
                'id_total_tagihan'  => Str::random(16),
                'id_pelanggan'      => $idPelanggan,
                'id_tagihan'        => $tagihanMaster->id_tagihan,
                'total_bayar'       => $totalBayar,
                'biaya_admin'       => $biayaAdmin,
                'status_pembayaran' => 'Belum bayar',
                'tanggal_bayar'     => null,
            ]);
        }
    }

    public function Total(Request $request)
    {
        $idPelanggan = auth()->guard('pelanggan')->user()->id_pelanggan;
        // 1. Auto-generate tagihan (Ini tetap berjalan normal)
        $this->autoGenerateTagihan($idPelanggan);

        $sekarang = CarbonAlias::now();

        // 2. Ambil data total tagihan dengan relasi dan filter tanggal
        $tagihan = \App\Models\TotalTagihan::with(['tagihan', 'pelanggan'])
            ->where('id_pelanggan', $idPelanggan)
            // =======================================================
            // PERBAIKAN PENTING: Eksklusi tagihan yang dihapus admin
            ->where('deleted_by_admin', false)
            // =======================================================
            ->get()
            // ... (Filter koleksi dan sorting lainnya tetap sama)
            ->filter(function ($item) use ($sekarang) {
                // ... (Logika filter tanggal tetap sama)
                if (!$item->tagihan) return false;

                $bulanNama = $item->tagihan->bulan;
                $tahunAngka = (int)$item->tagihan->tahun;
                try {
                    $tanggalTagihan = CarbonAlias::createFromFormat('F', $bulanNama)->day(1)->year($tahunAngka);
                } catch (\Exception $e) {
                    return false;
                }

                // FILTER: Tagihan hanya boleh tampil jika tanggalnya SAMA atau SEBELUM bulan saat ini
                return $tanggalTagihan->lessThanOrEqualTo($sekarang->copy()->startOfMonth());
            })
            ->sortBy(function ($item) {
                // Sortir 1: Status (Belum bayar: 0, Dibayar: 1)
                return $item->status_pembayaran == 'Belum bayar' ? 0 : 1;
            })
            ->sortByDesc(function ($item) {
                // Sortir 2: Tanggal (Terbaru duluan)
                $bulanNama = $item->tagihan->bulan;
                $tahunAngka = (int)$item->tagihan->tahun;
                return CarbonAlias::createFromFormat('F', $bulanNama)->day(1)->year($tahunAngka)->timestamp;
            })
            ->values();

        // 3. Hitung ulang total dan jumlah Belum Bayar dari hasil filter yang bersih
        $totalBelumBayar = $tagihan->where('status_pembayaran', 'Belum bayar')->sum('total_bayar');
        $jumlahBelumBayar = $tagihan->where('status_pembayaran', 'Belum bayar')->count();

        if ($tagihan->isEmpty()) {
            // Kirim collection kosong jika tidak ada data yang difilter
            return view('pelanggan.total', [
                'tagihan' => collect([]),
                'totalBelumBayar' => 0,
                'jumlahBelumBayar' => 0,
                'pesan' => 'Belum ada tagihan yang perlu dibayar.'
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
