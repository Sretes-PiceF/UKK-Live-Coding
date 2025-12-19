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

        // --- Langkah 1: Ambil nama bulan dalam bahasa Inggris ---
        $bulanNamaInggris = $bulanTagihanObj->format('F');

        $tahunAngka = $bulanTagihanObj->year;

        // --- Langkah 2: Konversi nama bulan ke Bahasa Indonesia ---
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

        // Variabel ini yang akan digunakan untuk pencarian dan penyimpanan di database
        $bulanNama = $bulan_map[$bulanNamaInggris] ?? $bulanNamaInggris;

        // Pastikan jika $bulanNamaInggris tidak terdaftar, maka tetap gunakan yang Inggris (sebagai fallback)

        $pelanggan = Pelanggan::find($idPelanggan);
        if (!$pelanggan) {
            return;
        }

        // Ambil Golongan Meter Pelanggan (Meter Pemakaian Pelanggan)
        $meterPemakaianPelanggan = $pelanggan->jumlah_meter ?? 0;

        // --- LOGIKA PENENTUAN GOLONGAN METER UNTUK TAGIHAN MASTER (Sama seperti sebelumnya) ---
        $golonganMeterAcuan = $meterPemakaianPelanggan;

        if ($meterPemakaianPelanggan > 100 && $meterPemakaianPelanggan < 200) {
            $golonganMeterAcuan = 100;
        }
        // -------------------------------------------------------------------------------------

        // 1. Cek & Dapatkan Tagihan Master untuk bulan target
        // PENCARIAN SEKARANG MENGGUNAKAN NAMA BULAN BAHASA INDONESIA (contoh: 'Oktober')
        $tagihanMaster = Tagihan::where('bulan', $bulanNama) // Menggunakan $bulanNama (Indo)
            ->where('tahun', $tahunAngka)
            ->where('jumlah_meter', $golonganMeterAcuan)
            ->first();

        // ... (Logika selanjutnya sama: mencari Tagihan Master Terakhir jika tidak ada, 
        //      lalu membuat Tagihan Master baru jika diperlukan)

        if (!$tagihanMaster) {
            // Cari Tagihan Master TERAKHIR yang PERNAH dibuat Admin DENGAN GOLONGAN ACUAN YANG SAMA
            $tagihanMasterTerakhir = Tagihan::where('jumlah_meter', $golonganMeterAcuan)
                ->orderBy('tahun', 'desc')
                ->orderByRaw("FIELD(bulan, 'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember') DESC")
                // PERHATIKAN: Saya juga MENGGANTI nama bulan di orderByRaw ke Bahasa Indonesia
                ->first();

            if ($tagihanMasterTerakhir) {
                // Gunakan data dari Tagihan Master terakhir sebagai acuan untuk membuat yang baru
                $tagihanMaster = Tagihan::create([
                    'id_tagihan'    => Str::random(16),
                    'bulan'         => $bulanNama, // DISIMPAN DALAM BAHASA INDONESIA
                    'tahun'         => $tahunAngka,
                    'jumlah_meter'  => $tagihanMasterTerakhir->jumlah_meter,
                    'tarif_per_kwh' => $tagihanMasterTerakhir->tarif_per_kwh,
                    'biaya_admin'   => $tagihanMasterTerakhir->biaya_admin
                ]);
            }
        }

        if (!$tagihanMaster) {
            return;
        }

        // Cek apakah Total Tagihan sudah ada
        $existingTotalTagihan = TotalTagihan::where('id_pelanggan', $idPelanggan)
            ->where('id_tagihan', $tagihanMaster->id_tagihan)
            ->where('deleted_by_admin', false)
            ->exists();

        if (!$existingTotalTagihan) {

            // **!!! PENTING !!!**
            // Pemakaian KWH yang digunakan untuk perhitungan adalah meter pemakaian **PELANGGAN**
            // (bukan $tagihanMaster->jumlah_meter$)
            $pemakaianKwhUntukHitungan = $meterPemakaianPelanggan; // Misal 125

            $tarifPerKwhMaster = $tagihanMaster->tarif_per_kwh ?? 0; // Tarif diambil dari Master 100
            $biayaAdmin = $tagihanMaster->biaya_admin ?? 0;

            // Perhitungan Total Bayar: (Meter Pemakaian Pelanggan * Tarif Master) + Biaya Admin
            // Contoh: (125 * Tarif Master 100) + Biaya Admin
            $totalBayar = ($pemakaianKwhUntukHitungan * $tarifPerKwhMaster) + $biayaAdmin;

            // Buat Total Tagihan
            TotalTagihan::create([
                'id_total_tagihan'  => Str::random(16),
                'id_pelanggan'      => $idPelanggan,
                'id_tagihan'        => $tagihanMaster->id_tagihan, // Referensi ke Master 100
                'total_bayar'       => $totalBayar,
                'biaya_admin'       => $biayaAdmin,
                'status_pembayaran' => 'Belum bayar',
                'tanggal_bayar'     => null,
                // Anda mungkin perlu menambahkan kolom 'pemakaian_kwh' ke TotalTagihan 
                // untuk menyimpan $pemakaianKwhUntukHitungan (125) secara eksplisit
                // agar fitur melihat detail tagihan bisa menampilkan pemakaian KWH yang sebenarnya.
                // Saat ini saya asumsikan pemakaian KWH dilihat dari $pelanggan->jumlah_meter$ 
                // pada saat melihat detail Tagihan.
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

        // 2. Query dasar - gunakan query builder untuk menghindari masalah
        $query = TotalTagihan::with(['tagihan', 'pelanggan'])
            ->where('id_pelanggan', $idPelanggan)
            ->where('deleted_by_admin', false);

        // Debug: Cek apakah ada data
        $debugData = $query->get();
        // \Log::info('Debug Total Tagihan:', ['count' => $debugData->count(), 'pelanggan_id' => $idPelanggan]);

        $tagihan = $query->get()
            ->filter(function ($item) use ($sekarang) {
                if (!$item->tagihan) {
                    // \Log::warning('Tagihan tidak ditemukan untuk total_tagihan:', ['id' => $item->id_total_tagihan]);
                    return false;
                }

                $bulanNama = $item->tagihan->bulan;
                $tahunAngka = (int)$item->tagihan->tahun;

                try {
                    $tanggalTagihan = CarbonAlias::createFromFormat('F', $bulanNama)->day(1)->year($tahunAngka);
                } catch (\Exception $e) {
                    // \Log::error('Error parsing tanggal:', ['bulan' => $bulanNama, 'tahun' => $tahunAngka, 'error' => $e->getMessage()]);
                    return false;
                }

                return $tanggalTagihan->lessThanOrEqualTo($sekarang->copy()->startOfMonth());
            })
            // Filter search
            ->when($search, function ($collection) use ($search) {
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
                    $bulanInggris = $item->tagihan->bulan ?? '';
                    $bulanIndo = $bulan_map[$bulanInggris] ?? '';

                    return stripos($bulanInggris, $search) !== false ||
                        stripos($bulanIndo, $search) !== false ||
                        stripos($item->tagihan->tahun, $search) !== false ||
                        stripos($item->status_pembayaran, $search) !== false ||
                        stripos((string)$item->total_bayar, $search) !== false;
                });
            })
            ->sortBy(function ($item) {
                return $item->status_pembayaran == 'Belum bayar' ? 0 : 1;
            })
            ->sortByDesc(function ($item) {
                if (!$item->tagihan) return 0;

                $bulanNama = $item->tagihan->bulan;
                $tahunAngka = (int)$item->tagihan->tahun;

                try {
                    return CarbonAlias::createFromFormat('F', $bulanNama)->day(1)->year($tahunAngka)->timestamp;
                } catch (\Exception $e) {
                    return 0;
                }
            })
            ->values();

        // Transform bulan ke Indonesia
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

        // Hitung total
        $totalBelumBayar = $tagihan->where('status_pembayaran', 'Belum bayar')->sum('total_bayar');
        $jumlahBelumBayar = $tagihan->where('status_pembayaran', 'Belum bayar')->count();
        $totalSemuaTagihan = $tagihan->sum('total_bayar');
        $jumlahSemuaTagihan = $tagihan->count();
        $totalSudahBayar = $tagihan->where('status_pembayaran', 'Sudah bayar')->sum('total_bayar');
        $jumlahSudahBayar = $tagihan->where('status_pembayaran', 'Sudah bayar')->count();

        // Debug final data
        // \Log::info('Final Tagihan Data:', [
        //     'total_records' => $tagihan->count(),
        //     'pelanggan_id' => $idPelanggan,
        //     'total_belum_bayar' => $totalBelumBayar,
        //     'total_semua' => $totalSemuaTagihan
        // ]);

        if ($tagihan->isEmpty()) {
            return view('pelanggan.total', [
                'tagihan' => collect([]),
                'totalBelumBayar' => 0,
                'jumlahBelumBayar' => 0,
                'totalSemuaTagihan' => 0,
                'jumlahSemuaTagihan' => 0,
                'totalSudahBayar' => 0,
                'jumlahSudahBayar' => 0,
                'search' => $search,
                'pesan' => $search ? "Tidak ada hasil untuk pencarian '{$search}'" : 'Belum ada tagihan yang perlu dibayar.'
            ]);
        }

        return view('pelanggan.total', compact(
            'tagihan',
            'totalBelumBayar',
            'jumlahBelumBayar',
            'totalSemuaTagihan',
            'jumlahSemuaTagihan',
            'totalSudahBayar',
            'jumlahSudahBayar',
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
