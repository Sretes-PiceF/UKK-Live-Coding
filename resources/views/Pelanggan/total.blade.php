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

                    <!-- Card Ringkasan Tagihan -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="fw-normal">Total Tagihan Belum Bayar</h6>
                                            <h3 class="mb-0">Rp 0</h3>
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
                                            <h6 class="fw-normal">Tagihan Belum Dibayar</h6>
                                            <h3 class="mb-0">0 Bulan</h3>
                                        </div>
                                        <i class="fas fa-clock fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-file-invoice-dollar me-1"></i>
                            Semua data tagihan yang perlu dibayarkan
                        </div>
                        <div class="card-body">
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                                <h4 class="text-muted">Belum Ada Tagihan</h4>
                                <p class="text-muted">
                                    {{ $pesan ?? 'Saat ini belum ada tagihan yang perlu dibayar.' }}
                                </p>
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
    </style>
@endpush