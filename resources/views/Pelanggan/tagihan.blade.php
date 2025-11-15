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
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Bulan</th>
                                            <th>Tahun</th>
                                            <th>Jumlah Meter</th>
                                            <th>Tarif per kWh</th>
                                            <th>Total Tagihan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($tagihan as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $item->bulan }}</td>
                                                <td>{{ $item->tahun }}</td>
                                                <td>{{ $item->jumlah_meter }}</td>
                                                <td>Rp {{ number_format($item->tarif_per_kwh, 0, ',', '.') }}</td>
                                                <td>Rp
                                                    {{ number_format($item->jumlah_meter * $item->tarif_per_kwh, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if($tagihan->isEmpty())
                                            <tr>
                                                <td colspan="6" class="text-center">Belum ada tagihan.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
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