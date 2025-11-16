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
        // Ambil tanggal saat ini
        $sekarang = CarbonAlias::now();

        // Tentukan bulan dan tahun untuk tagihan yang SEHARUSNYA DIBUAT (yaitu bulan sebelumnya)
        $bulanTagihanObj = $sekarang->copy()->subMonth();
        $bulanNama = $bulanTagihanObj->format('F'); // Contoh: November
        $tahunAngka = $bulanTagihanObj->year;     // Contoh: 2025

        // ==============================================================
        // 1. Cek apakah Tagihan Master untuk bulan ini sudah ada di tabel 'tagihan'
        // ==============================================================
        $tagihanMaster = Tagihan::where('bulan', $bulanNama)
            ->where('tahun', $tahunAngka)
            ->first();

        // Jika Tagihan Master belum dibuat oleh Admin untuk bulan sebelumnya:
        if (!$tagihanMaster) {
            // Cari Tagihan Master TERAKHIR yang pernah dibuat Admin
            $tagihanMasterTerakhir = Tagihan::orderBy('tahun', 'desc')
                ->orderByRaw("FIELD(bulan, 'January','February','March','April','May','June','July','August','September','October','November','December') DESC")
                ->first();

            if ($tagihanMasterTerakhir) {
                // Gunakan data dari Tagihan Master terakhir ini sebagai acuan untuk membuat Tagihan Master bulan sebelumnya

                // Catatan: Jika tagihan master dibuat di sini, ini hanya meniru data KWh dan Tarif
                // Jika KWh (golongan) harus di-update admin per bulan, Admin harus melakukannya
                $tagihanMaster = Tagihan::create([
                    'id_tagihan'    => Str::random(16), // Gunakan fungsi ID yang sesuai
                    'bulan'         => $bulanNama,
                    'tahun'         => $tahunAngka,
                    'biaya_admin'   => $tagihanMasterTerakhir->biaya_admin,
                    'jumlah_meter'  => $tagihanMasterTerakhir->jumlah_meter, // Misal: 100 kWH (untuk golongan tarif)
                    'tarif_per_kwh' => $tagihanMasterTerakhir->tarif_per_kwh // Misal: 1234
                ]);
            }
        }

        // ==============================================================
        // 2. Generate TotalTagihan (Tagihan Pelanggan) jika Tagihan Master ditemukan/dibuat
        // ==============================================================
        if ($tagihanMaster) {
            // Cek apakah TotalTagihan untuk Pelanggan ini pada bulan ini sudah ada
            $existingTotalTagihan = TotalTagihan::where('id_pelanggan', $idPelanggan)
                ->where('id_tagihan', $tagihanMaster->id_tagihan)
                ->exists();

            if (!$existingTotalTagihan) {
                $pelanggan = Pelanggan::find($idPelanggan);

                if (!$pelanggan) {
                    return;
                }

                // Pemakaian KWH Pelanggan diambil dari tabel Pelanggan
                $pemakaianKwhPelanggan = $pelanggan->jumlah_meter ?? 0; // Contoh: 123 kWh

                // Tarif per kWh diambil dari Tagihan Master
                $tarifPerKwhMaster = $tagihanMaster->tarif_per_kwh ?? 0; // Contoh: 1234

                // Biaya admin diasumsikan tetap 2500 atau diambil dari Tagihan Master (jika ada kolom)
                $biayaAdmin = 2500;

                // RUMUS PERHITUNGAN: (Pemakaian Pelanggan * Tarif Master) + Biaya Admin
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
    }


    public function Total(Request $request)
    {
        $idPelanggan = auth()->guard('pelanggan')->user()->id_pelanggan;

        // 1. Auto-generate tagihan bulan sebelumnya
        $this->autoGenerateTagihan($idPelanggan);

        $sekarang = CarbonAlias::now();

        // 2. Ambil data total tagihan dengan relasi dan filter tanggal
        $tagihan = TotalTagihan::with(['tagihan', 'pelanggan'])
            ->where('id_pelanggan', $idPelanggan)
            ->get() // Ambil semua data lalu filter secara koleksi
            ->filter(function ($item) use ($sekarang) {
                // Jika relasi tagihan hilang/error, abaikan
                if (!$item->tagihan) return false;

                // Ubah Bulan dan Tahun Tagihan menjadi objek Carbon
                // Asumsi Bulan di DB adalah nama (e.g., 'January', 'November')
                $bulanNama = $item->tagihan->bulan;
                $tahunAngka = (int)$item->tagihan->tahun;

                try {
                    // Buat tanggal 1 di bulan dan tahun tagihan
                    $tanggalTagihan = CarbonAlias::createFromFormat('F', $bulanNama)->day(1)->year($tahunAngka);
                } catch (\Exception $e) {
                    // Jika format bulan salah, abaikan
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
            ->values(); // Reset kunci array setelah filter dan sort

        // 3. Hitung ulang total dan jumlah Belum Bayar dari hasil filter
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
