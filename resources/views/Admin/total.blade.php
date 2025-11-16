@extends('template.layout')

@section('title', 'Total Tagihan')

@section('header')
    @include('template.navbar_admin')
@endsection

@section('main')
    <div id="layoutSidenav">
        @include('template.sidebar_admin')
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Total Tagihan Pelanggan</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Halaman untuk melihat semua total tagihan pelanggan</li>
                    </ol>

                    <!-- Card Statistik -->
                    <div class="row mb-4">
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <div class="small">Total Tagihan</div>
                                            <div class="h4">{{ $jumlahTotal }}</div>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-file-invoice fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-warning text-white mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <div class="small">Belum Bayar</div>
                                            <div class="h4">{{ $jumlahBelumBayar }}</div>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-clock fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-success text-white mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <div class="small">Sudah Bayar</div>
                                            <div class="h4">{{ $jumlahSudahBayar }}</div>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-check-circle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-info text-white mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <div class="small">Total Pendapatan</div>
                                            <div class="h5">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</div>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-money-bill-wave fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Section -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('admin.total') }}" method="GET" class="row g-2">
                                <div class="col-md-2">
                                    <select name="bulan" id="bulan" class="form-select form-select-sm">
                                        <option value="">Semua Bulan</option>
                                        @foreach(range(1, 12) as $month)
                                            @php
                                                $monthName = \Carbon\Carbon::createFromDate(null, $month, null)->locale('id')->monthName;
                                            @endphp
                                            <option value="{{ $month }}" {{ request('bulan') == $month ? 'selected' : '' }}>
                                                {{ $monthName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="tahun" id="tahun" class="form-control form-control-sm"
                                        value="{{ request('tahun') ?: date('Y') }}" placeholder="Tahun" min="2020"
                                        max="2030">
                                </div>
                                <div class="col-md-2">
                                    <select name="status" id="status" class="form-select form-select-sm">
                                        <option value="">Semua Status</option>
                                        <option value="Belum bayar" {{ request('status') == 'Belum bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                        <option value="Dibayar" {{ request('status') == 'Dibayar' ? 'selected' : '' }}>Sudah
                                            Bayar</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="text" name="q" id="q" class="form-control form-control-sm"
                                        value="{{ request('q') }}" placeholder="Cari pelanggan...">
                                </div>
                                <div class="col-md-2">
                                    <select name="per_page" id="per_page" class="form-select form-select-sm">
                                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 Data
                                        </option>
                                        <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25 Data
                                        </option>
                                        <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50 Data
                                        </option>
                                        <option value="0" {{ request('per_page', 10) == 0 ? 'selected' : '' }}>Semua Data
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-primary btn-sm w-100">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Tabel Total Tagihan -->
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-table me-1"></i>
                                Data Tagihan
                                @if(request()->anyFilled(['bulan', 'tahun', 'status', 'q']))
                                    <small class="text-muted">(Difilter)</small>
                                @endif
                            </div>
                            <div class="text-muted small">
                                Total: {{ $totalTagihan->total() }} data
                            </div>
                        </div>
                        <div class="card-body">
                            @if($totalTagihan->isEmpty())
                                <div class="text-center py-5">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Tidak ada data tagihan</h5>
                                    @if(request()->anyFilled(['bulan', 'tahun', 'status', 'q']))
                                        <p class="text-muted mb-0">Tidak ada data yang sesuai dengan filter</p>
                                        <a href="{{ route('admin.total') }}" class="btn btn-sm btn-outline-primary mt-2">
                                            Tampilkan Semua
                                        </a>
                                    @endif
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover table-sm">
                                        <thead>
                                            <tr>
                                                <th width="50">No</th>
                                                <th>Pelanggan</th>
                                                <th>Periode</th>
                                                <th class="text-end">Total</th>
                                                <th class="text-center">Status</th>
                                                <th width="80" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($totalTagihan as $index => $item)
                                                @php
                                                    // Konversi bulan ke nama
                                                    $bulanNama = '';
                                                    if (isset($item->tagihan->bulan) && is_numeric($item->tagihan->bulan)) {
                                                        try {
                                                            $bulanNama = \Carbon\Carbon::createFromDate(null, $item->tagihan->bulan, null)->locale('id')->monthName;
                                                        } catch (Exception $e) {
                                                            $bulanNama = 'Bulan ' . $item->tagihan->bulan;
                                                        }
                                                    } else {
                                                        $bulanNama = $item->tagihan->bulan ?? '-';
                                                    }
                                                @endphp
                                                <tr
                                                    class="{{ $item->status_pembayaran == 'Belum bayar' ? 'table-warning' : 'table-default' }}">
                                                    <td class="text-muted">
                                                        @if(request('per_page', 10) == 0)
                                                            {{ $index + 1 }}
                                                        @else
                                                            {{ $totalTagihan->firstItem() + $index }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="fw-bold text-dark">{{ $item->pelanggan->nama_pelanggan ?? '-' }}
                                                        </div>
                                                        <small
                                                            class="text-muted">{{ $item->pelanggan->id_pelanggan ?? '-' }}</small>
                                                    </td>
                                                    <td>
                                                        <small class="text-dark">{{ $bulanNama }}
                                                            {{ $item->tagihan->tahun ?? '-' }}</small>
                                                    </td>
                                                    <td class="text-end">
                                                        <span class="fw-bold text-primary">Rp
                                                            {{ number_format($item->total_bayar ?? 0, 0, ',', '.') }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        @if($item->status_pembayaran == 'Belum bayar')
                                                            <span class="badge bg-warning">
                                                                <i class="fas fa-clock me-1"></i>Belum
                                                            </span>
                                                        @else
                                                            <span class="badge bg-success">
                                                                <i class="fas fa-check me-1"></i>Lunas
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#detailModal{{ $item->id_total_tagihan }}"
                                                            title="Lihat Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                @if(request('per_page', 10) != 0 && $totalTagihan->hasPages())
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div class="text-muted small">
                                            Menampilkan {{ $totalTagihan->firstItem() }} - {{ $totalTagihan->lastItem() }}
                                            dari {{ $totalTagihan->total() }} data
                                        </div>
                                        <div>
                                            {{ $totalTagihan->appends(request()->query())->links() }}
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                </div>
            </main>

            <!-- Modal Detail untuk setiap item -->
            @foreach($totalTagihan as $item)
                @php
                    $bulanNama = '';
                    if (isset($item->tagihan->bulan) && is_numeric($item->tagihan->bulan)) {
                        try {
                            $bulanNama = \Carbon\Carbon::createFromDate(null, $item->tagihan->bulan, null)->locale('id')->monthName;
                        } catch (Exception $e) {
                            $bulanNama = 'Bulan ' . $item->tagihan->bulan;
                        }
                    } else {
                        $bulanNama = $item->tagihan->bulan ?? '-';
                    }

                    // Hitung detail
                    $jumlahMeter = $item->pelanggan->jumlah_meter ?? 0;
                    $tarifPerKwh = $item->tagihan->tarif_per_kwh ?? 0;
                    $biayaAdmin = $item->tagihan->biaya_admin ?? 0;
                    $biayaListrik = $jumlahMeter * $tarifPerKwh;
                    $totalBayar = $biayaListrik + $biayaAdmin;
                @endphp

                <div class="modal fade" id="detailModal{{ $item->id_total_tagihan }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">
                                    <i class="fas fa-file-invoice me-2"></i>Detail Tagihan
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Info Pelanggan -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="fas fa-user me-2"></i>Data Pelanggan
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-sm table-borderless">
                                                    <tr>
                                                        <td width="40%" class="text-muted">Nama</td>
                                                        <td><strong>{{ $item->pelanggan->nama_pelanggan ?? '-' }}</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">ID Pelanggan</td>
                                                        <td><code>{{ $item->pelanggan->id_pelanggan ?? '-' }}</code></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Alamat</td>
                                                        <td>{{ $item->pelanggan->alamat ?? '-' }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-sm table-borderless">
                                                    <tr>
                                                        <td width="40%" class="text-muted">Nomor Meter</td>
                                                        <td>{{ $item->pelanggan->no_kwh ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Daya</td>
                                                        <td>{{ $item->pelanggan->daya ?? '-' }} VA</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-muted">Jumlah Meter</td>
                                                        <td><strong>{{ number_format($jumlahMeter, 0, ',', '.') }} kWh</strong>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Detail Tagihan -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="fas fa-receipt me-2"></i>Detail Tagihan
                                        </h6>
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <div class="row text-center">
                                                    <div class="col-md-4 border-end">
                                                        <small class="text-muted">Periode</small>
                                                        <div class="fw-bold text-dark">{{ $bulanNama }}
                                                            {{ $item->tagihan->tahun ?? '-' }}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 border-end">
                                                        <small class="text-muted">Status</small>
                                                        <div>
                                                            @if($item->status_pembayaran == 'Belum bayar')
                                                                <span class="badge bg-warning">
                                                                    <i class="fas fa-clock me-1"></i>Belum Bayar
                                                                </span>
                                                            @else
                                                                <span class="badge bg-success">
                                                                    <i class="fas fa-check-circle me-1"></i>Lunas
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <small class="text-muted">Tanggal Bayar</small>
                                                        <div class="fw-bold text-dark">
                                                            @if($item->tanggal_bayar)
                                                                {{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d/m/Y H:i') }}
                                                            @else
                                                                -
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Rincian Biaya -->
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="fas fa-calculator me-2"></i>Rincian Biaya
                                        </h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <tbody>
                                                    <tr>
                                                        <td width="60%">Pemakaian Listrik</td>
                                                        <td class="text-end">{{ number_format($jumlahMeter, 0, ',', '.') }} kWh
                                                        </td>
                                                        <td class="text-end">× Rp {{ number_format($tarifPerKwh, 0, ',', '.') }}
                                                        </td>
                                                        <td class="text-end fw-bold">Rp
                                                            {{ number_format($biayaListrik, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Biaya Admin</td>
                                                        <td class="text-end">-</td>
                                                        <td class="text-end">-</td>
                                                        <td class="text-end fw-bold">Rp
                                                            {{ number_format($biayaAdmin, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                    <tr class="table-primary">
                                                        <td class="fw-bold">TOTAL TAGIHAN</td>
                                                        <td class="text-end">-</td>
                                                        <td class="text-end">-</td>
                                                        <td class="text-end fw-bold fs-6 text-primary">
                                                            Rp {{ number_format($totalBayar, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                @if($item->status_pembayaran == 'Belum bayar')
                                    <form action="{{ route('admin.total.delete', $item->id_total_tagihan) }}" method="POST"
                                        class="me-auto">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('Yakin hapus tagihan ini?')">
                                            <i class="fas fa-trash me-1"></i>Hapus
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-success btn-sm"
                                        onclick="markAsPaid('{{ $item->id_total_tagihan }}')">
                                        <i class="fas fa-check me-1"></i>Tandai Lunas
                                    </button>
                                @endif
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i>Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Web Pembayaran Tagihan Listrik 2025</div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function markAsPaid(id) {
            if (confirm('Tandai tagihan ini sebagai sudah dibayar?')) {
                // Implementasi mark as paid
                alert('Fitur mark as paid untuk ID: ' + id);
            }
        }
    </script>
@endpush