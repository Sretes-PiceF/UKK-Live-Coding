@extends('template.layout')

@section('title', 'Total Tagihan')

@section('header')
    @include('template.navbar_pelanggan')
@endsection

@section('main')
    <div id="layoutSidenav">
        @include('template.sidebar_pelanggan')
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Total Tagihan</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Halaman untuk melihat tagihan yang perlu dibayarkan</li>
                    </ol>

                    <!-- RINGKASAN -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6>Total Tagihan Belum Bayar</h6>
                                            <h3>Rp {{ number_format($totalBelumBayar ?? 0, 0, ',', '.') }}</h3>
                                        </div>
                                        <i class="fas fa-file-invoice-dollar fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6>Tagihan Belum Dibayar</h6>
                                            <h3>{{ $jumlahBelumBayar ?? 0 }} Bulan</h3>
                                        </div>
                                        <i class="fas fa-clock fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TABEL TAGIHAN -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-file-invoice-dollar me-1"></i> Semua data tagihan yang perlu dibayarkan
                        </div>

                        <div class="card-body">
                            @if (!isset($tagihan) || $tagihan->isEmpty())
                                <div class="text-center py-5">
                                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                                    <h4 class="text-muted">Belum Ada Tagihan</h4>
                                    <p class="text-muted">{{ $pesan ?? 'Saat ini belum ada tagihan yang perlu dibayar.' }}</p>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>No</th>
                                                <th>Bulan</th>
                                                <th>Tahun</th>
                                                <th>Jumlah Meter</th>
                                                <th>Tarif per kWh</th>
                                                <th>Biaya Admin</th>
                                                <th>Total Bayar</th>
                                                <th>Tanggal Bayar</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($tagihan as $index => $item)
                                                @php
                                                    // Hitung detail tagihan
                                                    $jumlahMeter = $item->pelanggan->jumlah_meter ?? 0;
                                                    $tarifPerKwh = $item->tagihan->tarif_per_kwh ?? 0;
                                                    $biayaAdmin = $item->tagihan->biaya_admin ?? 0;
                                                    $totalPerhitungan = ($jumlahMeter * $tarifPerKwh) + $biayaAdmin;
                                                    $bulan_tahun_string = $item->tagihan->bulan . ' ' . $item->tagihan->tahun;
                                                @endphp
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        @if(isset($item->tagihan->bulan))
                                                            {{ \Carbon\Carbon::createFromFormat('F Y', $bulan_tahun_string)->locale('id')->translatedFormat('F') }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if(isset($item->tagihan->tahun))
                                                            {{ $item->tagihan->tahun }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ number_format($jumlahMeter, 0, ',', '.') }} kWh
                                                    </td>
                                                    <td>
                                                        Rp {{ number_format($tarifPerKwh, 0, ',', '.') }}
                                                    </td>
                                                    <td>
                                                        Rp {{ number_format($biayaAdmin, 0, ',', '.') }}
                                                    </td>
                                                    <td>
                                                        <strong>Rp {{ number_format($item->total_bayar, 0, ',', '.') }}</strong>
                                                        @if($item->total_bayar != $totalPerhitungan)
                                                            <br>
                                                            <small class="text-danger">
                                                                <i class="fas fa-exclamation-triangle"></i>
                                                                Seharusnya: Rp {{ number_format($totalPerhitungan, 0, ',', '.') }}
                                                            </small>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($item->tanggal_bayar)
                                                            {{ \Carbon\Carbon::parse($item->tanggal_bayar)->translatedFormat('d F Y') }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($item->status_pembayaran === "Belum bayar")
                                                            <span class="badge bg-danger">Belum Bayar</span>
                                                        @else
                                                            <span class="badge bg-success">Dibayar</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($item->status_pembayaran === "Belum bayar")
                                                            <form action="{{ route('tagihan.bayar', $item->id_total_tagihan) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-sm btn-success">
                                                                    <i class="fas fa-credit-card me-1"></i>Bayar
                                                                </button>
                                                            </form>
                                                        @else
                                                            <span class="text-muted text-center d-block">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- DETAIL PERHITUNGAN -->
                                <div class="mt-4 p-3 bg-light rounded">
                                    <h6><i class="fas fa-calculator me-2"></i>Rumus Perhitungan Tagihan:</h6>
                                    <p class="mb-1">
                                        <strong>Total Bayar = (Jumlah Meter × Tarif per kWh) + Biaya Admin</strong>
                                    </p>
                                    <small class="text-muted">
                                        Contoh: ({{ number_format($jumlahMeter ?? 0, 0, ',', '.') }} kWh × Rp
                                        {{ number_format($tarifPerKwh ?? 0, 0, ',', '.') }}) + Rp
                                        {{ number_format($biayaAdmin ?? 0, 0, ',', '.') }} = Rp
                                        {{ number_format($totalPerhitungan ?? 0, 0, ',', '.') }}
                                    </small>
                                </div>

                                @if($jumlahBelumBayar > 0)
                                    <form action="{{ route('tagihan.bayar.semua') }}" method="POST" class="mt-3">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-credit-card me-1"></i>Bayar Semua Tagihan ({{ $jumlahBelumBayar }})
                                        </button>
                                    </form>
                                @endif
                            @endif
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