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

        // Ambil keyword search
        $search = $request->input('q');

        // 1. Auto-generate tagihan (Ini tetap berjalan normal)
        $this->autoGenerateTagihan($idPelanggan);
        $sekarang = CarbonAlias::now();

        // 2. Ambil data total tagihan dengan relasi dan filter tanggal
        $tagihan = \App\Models\TotalTagihan::with(['tagihan', 'pelanggan'])
            ->where('id_pelanggan', $idPelanggan)
            ->where('deleted_by_admin', false)
            ->get()
            ->filter(function ($item) use ($sekarang) {
                if (!$item->tagihan) return false;
                $bulanNama = $item->tagihan->bulan;
                $tahunAngka = (int)$item->tagihan->tahun;
                try {
                    $tanggalTagihan = CarbonAlias::createFromFormat('F', $bulanNama)->day(1)->year($tahunAngka);
                } catch (\Exception $e) {
                    return false;
                }
                return $tanggalTagihan->lessThanOrEqualTo($sekarang->copy()->startOfMonth());
            })
            // ===== TAMBAHAN: FILTER SEARCH (SETELAH FILTER TANGGAL) =====
            ->when($search, function ($collection) use ($search) {
                // Mapping bulan untuk search bahasa Indonesia
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

                return $collection->filter(function ($item) use ($search, $bulan_map) {
                    // Search di bulan (Inggris dan Indonesia)
                    $bulanInggris = $item->tagihan->bulan ?? '';
                    $bulanIndo = $bulan_map[$bulanInggris] ?? '';

                    // Search di berbagai field
                    return stripos($bulanInggris, $search) !== false ||
                        stripos($bulanIndo, $search) !== false ||
                        stripos($item->tagihan->tahun, $search) !== false ||
                        stripos($item->status_pembayaran, $search) !== false ||
                        stripos($item->total_bayar, $search) !== false;
                });
            })
            // ============================================================
            ->sortBy(function ($item) {
                return $item->status_pembayaran == 'Belum bayar' ? 0 : 1;
            })
            ->sortByDesc(function ($item) {
                $bulanNama = $item->tagihan->bulan;
                $tahunAngka = (int)$item->tagihan->tahun;
                return CarbonAlias::createFromFormat('F', $bulanNama)->day(1)->year($tahunAngka)->timestamp;
            })
            ->values();

        // ===== TAMBAHAN: TRANSFORM BULAN KE INDONESIA =====
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

        $tagihan->transform(function ($item) use ($bulan_map) {
            if (isset($item->tagihan->bulan)) {
                $item->tagihan->bulan_indo = $bulan_map[$item->tagihan->bulan] ?? $item->tagihan->bulan;
            }
            return $item;
        });
        // ==================================================

        // 3. Hitung ulang total dan jumlah Belum Bayar dari hasil filter yang bersih
        $totalBelumBayar = $tagihan->where('status_pembayaran', 'Belum bayar')->sum('total_bayar');
        $jumlahBelumBayar = $tagihan->where('status_pembayaran', 'Belum bayar')->count();

        if ($tagihan->isEmpty()) {
            return view('pelanggan.total', [
                'tagihan' => collect([]),
                'totalBelumBayar' => 0,
                'jumlahBelumBayar' => 0,
                'search' => $search,
                'pesan' => $search ? "Tidak ada hasil untuk pencarian '{$search}'" : 'Belum ada tagihan yang perlu dibayar.'
            ]);
        }

        return view('pelanggan.total', compact(
            'tagihan',
            'totalBelumBayar',
            'jumlahBelumBayar',
            'search'
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
