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
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="fw-normal">Total Tagihan</h6>
                                            <h4 class="mb-0">{{ $jumlahTotal }}</h4>
                                        </div>
                                        <i class="fas fa-file-invoice fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="fw-normal">Belum Bayar</h6>
                                            <h4 class="mb-0">{{ $jumlahBelumBayar }}</h4>
                                        </div>
                                        <i class="fas fa-clock fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="fw-normal">Sudah Bayar</h6>
                                            <h4 class="mb-0">{{ $jumlahSudahBayar }}</h4>
                                        </div>
                                        <i class="fas fa-check-circle fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        </div>

                        <!-- Filter Section -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <form action="{{ route('admin.total') }}" method="GET" class="row g-3">
                                    <div class="col-md-3">
                                        <label for="bulan" class="form-label">Filter Bulan</label>
                                        <select name="bulan" id="bulan" class="form-select">
                                            <option value="">Semua Bulan</option>
                                            <option value="January" {{ request('bulan') == 'January' ? 'selected' : '' }}>Januari
                                            </option>
                                            <option value="February" {{ request('bulan') == 'February' ? 'selected' : '' }}>
                                                Februari</option>
                                            <option value="March" {{ request('bulan') == 'March' ? 'selected' : '' }}>Maret
                                            </option>
                                            <option value="April" {{ request('bulan') == 'April' ? 'selected' : '' }}>April
                                            </option>
                                            <option value="May" {{ request('bulan') == 'May' ? 'selected' : '' }}>Mei</option>
                                            <option value="June" {{ request('bulan') == 'June' ? 'selected' : '' }}>Juni</option>
                                            <option value="July" {{ request('bulan') == 'July' ? 'selected' : '' }}>Juli</option>
                                            <option value="August" {{ request('bulan') == 'August' ? 'selected' : '' }}>Agustus
                                            </option>
                                            <option value="September" {{ request('bulan') == 'September' ? 'selected' : '' }}>
                                                September</option>
                                            <option value="October" {{ request('bulan') == 'October' ? 'selected' : '' }}>Oktober
                                            </option>
                                            <option value="November" {{ request('bulan') == 'November' ? 'selected' : '' }}>
                                                November</option>
                                            <option value="December" {{ request('bulan') == 'December' ? 'selected' : '' }}>
                                                Desember</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="tahun" class="form-label">Filter Tahun</label>
                                        <input type="number" name="tahun" id="tahun" class="form-control"
                                            value="{{ request('tahun') }}" placeholder="2025">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="status" class="form-label">Filter Status</label>
                                        <select name="status" id="status" class="form-select">
                                            <option value="">Semua Status</option>
                                            <option value="Belum bayar" {{ request('status') == 'Belum bayar' ? 'selected' : '' }}>Belum Bayar</option>
                                            <option value="Dibayar" {{ request('status') == 'Dibayar' ? 'selected' : '' }}>Sudah
                                                Bayar</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="q" class="form-label">Cari Pelanggan</label>
                                        <input type="text" name="q" id="q" class="form-control" value="{{ request('q') }}"
                                            placeholder="Nama pelanggan...">
                                    </div>
                                    <div class="col-md-1 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Tabel Total Tagihan -->
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <i class="fas fa-table me-1"></i>
                                Data Total Tagihan Pelanggan
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Pelanggan</th>
                                                <th>Alamat</th>
                                                <th>Bulan</th>
                                                <th>Tahun</th>
                                                <th>Jumlah Meter (kWh)</th>
                                                <th>Tarif per kWh</th>
                                                <th>Biaya Admin</th>
                                                <th>Total Bayar</th>
                                                <th>Tanggal Bayar</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($totalTagihan as $index => $item)
                                                <tr>
                                                    <td>{{ $totalTagihan->firstItem() + $index }}</td>
                                                    <td>
                                                        <strong>{{ $item->pelanggan->nama_pelanggan }}</strong><br>
                                                        <small class="text-muted">ID: {{ $item->pelanggan->id_pelanggan }}</small>
                                                    </td>
                                                    <td>{{ $item->pelanggan->alamat }}</td>
                                                    <td>{{ $item->tagihan->bulan }}</td>
                                                    <td>{{ $item->tagihan->tahun }}</td>
                                                    <td>{{ number_format($item->pelanggan->jumlah_meter, 0, ',', '.') }}</td>
                                                    <td>Rp {{ number_format($item->tagihan->tarif_per_kwh, 0, ',', '.') }}</td>
                                                    <td>Rp {{ number_format($item->biaya_admin, 0, ',', '.') }}</td>
                                                    <td class="fw-bold text-primary">
                                                        Rp {{ number_format($item->total_bayar, 0, ',', '.') }}
                                                    </td>
                                                    <td>
                                                        @if($item->tanggal_bayar)
                                                            {{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d/m/Y H:i') }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($item->status_pembayaran == 'Belum bayar')
                                                            <span class="badge bg-warning">
                                                                <i class="fas fa-clock me-1"></i>Belum Bayar
                                                            </span>
                                                        @else
                                                            <span class="badge bg-success">
                                                                <i class="fas fa-check-circle me-1"></i>Dibayar
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                                            data-bs-target="#detailModal{{ $item->id_total_tagihan }}">
                                                            <i class="fas fa-eye"></i>
                                                        </button>

                                                        @if($item->status_pembayaran == 'Belum bayar')
                                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                                data-bs-target="#hapusModal{{ $item->id_total_tagihan }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <!-- Modal Detail -->
                                                <div class="modal fade" id="detailModal{{ $item->id_total_tagihan }}" tabindex="-1">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-primary text-white">
                                                                <h5 class="modal-title">Detail Total Tagihan</h5>
                                                                <button type="button" class="btn-close btn-close-white"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <h6 class="fw-bold">Data Pelanggan</h6>
                                                                        <table class="table table-sm">
                                                                            <tr>
                                                                                <td width="40%">Nama</td>
                                                                                <td>: {{ $item->pelanggan->nama_pelanggan }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>ID Pelanggan</td>
                                                                                <td>: {{ $item->pelanggan->id_pelanggan }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Alamat</td>
                                                                                <td>: {{ $item->pelanggan->alamat }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Nomor Meter</td>
                                                                                <td>: {{ $item->pelanggan->nomor_meter }}</td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <h6 class="fw-bold">Data Tagihan</h6>
                                                                        <table class="table table-sm">
                                                                            <tr>
                                                                                <td width="40%">Periode</td>
                                                                                <td>: {{ $item->tagihan->bulan }}
                                                                                    {{ $item->tagihan->tahun }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Jumlah Meter</td>
                                                                                <td>:
                                                                                    {{ number_format($item->pelanggan->jumlah_meter, 0, ',', '.') }}
                                                                                    kWh</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Tarif per kWh</td>
                                                                                <td>: Rp
                                                                                    {{ number_format($item->tagihan->tarif_per_kwh, 0, ',', '.') }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Biaya Listrik</td>
                                                                                <td>: Rp
                                                                                    {{ number_format($item->pelanggan->jumlah_meter * $item->tagihan->tarif_per_kwh, 0, ',', '.') }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td>Biaya Admin</td>
                                                                                <td>: Rp
                                                                                    {{ number_format($item->biaya_admin, 0, ',', '.') }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr class="fw-bold">
                                                                                <td>Total Bayar</td>
                                                                                <td>: Rp
                                                                                    {{ number_format($item->total_bayar, 0, ',', '.') }}
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <hr>
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <h6 class="fw-bold">Status Pembayaran</h6>
                                                                        <p class="mb-1">
                                                                            Status:
                                                                            @if($item->status_pembayaran == 'Belum bayar')
                                                                                <span class="badge bg-warning">Belum Bayar</span>
                                                                            @else
                                                                                <span class="badge bg-success">Dibayar</span>
                                                                            @endif
                                                                        </p>
                                                                        @if($item->tanggal_bayar)
                                                                            <p class="mb-0">Tanggal Bayar:
                                                                                {{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d F Y, H:i') }}
                                                                            </p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Tutup</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Modal Hapus -->
                                                @if($item->status_pembayaran == 'Belum bayar')
                                                    <div class="modal fade" id="hapusModal{{ $item->id_total_tagihan }}" tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-danger text-white">
                                                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                                    <button type="button" class="btn-close btn-close-white"
                                                                        data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <p>Apakah Anda yakin ingin menghapus total tagihan ini?</p>
                                                                    <div class="alert alert-warning">
                                                                        <strong>Pelanggan:</strong>
                                                                        {{ $item->pelanggan->nama_pelanggan }}<br>
                                                                        <strong>Periode:</strong> {{ $item->tagihan->bulan }}
                                                                        {{ $item->tagihan->tahun }}<br>
                                                                        <strong>Total:</strong> Rp
                                                                        {{ number_format($item->total_bayar, 0, ',', '.') }}
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-bs-dismiss="modal">Batal</button>
                                                                    <form
                                                                        action="{{ route('admin.total.delete', $item->id_total_tagihan) }}" method="POST">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                @endif

                                            @empty
                                                <tr>
                                                    <td colspan="12" class="text-center py-4">
                                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                        <p class="text-muted">Tidak ada data total tagihan.</p>
                                                    </td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Pagination -->
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <div>
                                            Menampilkan {{ $totalTagihan->firstItem() ?? 0 }} - {{ $totalTagihan->lastItem() ?? 0 }} 
                                            dari {{ $totalTagihan->total() }} data
                                        </div>
                                        <div>
                                            {{ $totalTagihan->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </main>
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

@push('styles')
    <style>
        .card {
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            border-radius: 8px 8px 0 0 !important;
            font-weight: 600;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }
    </style>
@endpush