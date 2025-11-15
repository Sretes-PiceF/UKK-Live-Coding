@extends('template.layout')

@section('title', 'Pengaturan Biaya Admin') {{-- Judul diperbarui --}}

@section('header')
    @include('template.navbar_admin')
@endsection

@section('main')
    <div id="layoutSidenav">
        @include('template.sidebar_admin')

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">

                    {{-- Judul Halaman --}}
                    <h1 class="mt-4"><i class="fas fa-cog me-2"></i> Pengaturan Biaya Admin</h1>

                    {{-- Breadcrumb (Navigasi) --}}
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Pengaturan Biaya Admin</li>
                    </ol>

                    {{-- Notifikasi Success --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Kartu Utama untuk Form Pengaturan --}}
                    <div class="card shadow mb-4">
                        <div class="card-header">
                            <i class="fas fa-edit me-1"></i>
                            Form Biaya Admin Saat Ini
                        </div>
                        <div class="card-body">

                            {{-- Form akan mengirim data ke PengaturanController@update --}}
                            <form action="{{ route('admin.pengaturan.update') }}" method="POST">
                                @csrf
                                @method('PUT') {{-- Penting: Gunakan method PUT untuk update --}}

                                <div class="mb-3">
                                    <label for="biaya_admin" class="form-label fw-bold">Biaya Admin (Rp)</label>

                                    {{-- Input field dengan tipe number, memastikan nilai diambil dari database atau old
                                    input --}}
                                    <input type="number"
                                        class="form-control form-control-lg @error('biaya_admin') is-invalid @enderror"
                                        id="biaya_admin" name="biaya_admin"
                                        value="{{ old('biaya_admin', $biayaAdmin->value ?? '') }}"
                                        placeholder="Masukkan biaya admin dalam Rupiah (e.g., 2500)" required min="0">

                                    {{-- Pesan error validasi Laravel --}}
                                    @error('biaya_admin')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Nilai ini akan diterapkan ke semua transaksi pembayaran tagihan listrik berikutnya.
                                    </small>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg mt-3">
                                    <i class="fas fa-save me-1"></i> Simpan Perubahan
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </main>

            {{-- Footer --}}
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