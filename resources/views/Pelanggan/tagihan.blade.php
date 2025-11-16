@extends('template.layout')

@section('title', 'Tagihan')

@section('header')
    @include('template.navbar_pelanggan')
@endsection

@section('main')
    <div id="layoutSidenav">
        @include('template.sidebar_pelanggan')
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Tagihan</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Halaman untuk melihat tagihan yang perlu dibayarkan</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-file-invoice-dollar me-1"></i>
                            Data tagihan per-bulan
                        </div>
                        <div class="card-body">
                            @if($tagihan->isEmpty())
                                <div class="text-center py-5">
                                    <i class="fas fa-file-invoice-dollar fa-3x text-muted mb-3"></i>
                                    <h4 class="text-muted">Data tagihan belum ada</h4>
                                    <p class="text-muted">Belum ada tagihan yang perlu dibayarkan untuk saat ini.</p>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Bulan</th>
                                                <th>Tahun</th>
                                                <th>Jumlah Meter</th>
                                                <th>Tarif per kWh</th>
                                                <th>Biaya admin</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($tagihan as $index => $item)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ \Carbon\Carbon::create()->month($item->bulan)->locale('id')->monthName }}
                                                    </td>
                                                    <td>{{ $item->tahun }}</td>
                                                    <td>{{ number_format($item->jumlah_meter, 0, ',', '.') }} kWh</td>
                                                    <td>Rp {{ number_format($item->tarif_per_kwh, 0, ',', '.') }}</td>
                                                    <td>Rp {{ number_format($item->biaya_admin, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
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