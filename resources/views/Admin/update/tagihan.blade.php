@extends('template.layout')

@section('title', 'Edit Tagihan')

@section('header')
    @include('template.navbar_admin')
@endsection

@section('main')
    <div id="layoutSidenav">
        @include('template.sidebar_admin')
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Edit Data Tagihan</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="{{ route('admin.tagihan') }}">Tagihan</a></li>
                        <li class="breadcrumb-item active">Edit Data</li>
                    </ol>

                    {{-- Notifikasi Success --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Notifikasi Error --}}
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <i class="fas fa-edit me-1"></i>
                            Form Edit Tagihan
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.tagihan.update', $tagihan->id_tagihan) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="bulan" class="form-label">Bulan</label>
                                        <select name="bulan" id="bulan" class="form-select" required>
                                            <option value="">Pilih Bulan</option>
                                            <option value="Januari" {{ $tagihan->bulan == 'Januari' ? 'selected' : '' }}>
                                                Januari</option>
                                            <option value="Februari" {{ $tagihan->bulan == 'Februari' ? 'selected' : '' }}>
                                                Februari</option>
                                            <option value="Maret" {{ $tagihan->bulan == 'Maret' ? 'selected' : '' }}>Maret
                                            </option>
                                            <option value="April" {{ $tagihan->bulan == 'April' ? 'selected' : '' }}>April
                                            </option>
                                            <option value="Mei" {{ $tagihan->bulan == 'Mei' ? 'selected' : '' }}>Mei</option>
                                            <option value="Juni" {{ $tagihan->bulan == 'Juni' ? 'selected' : '' }}>Juni
                                            </option>
                                            <option value="Juli" {{ $tagihan->bulan == 'Juli' ? 'selected' : '' }}>Juli
                                            </option>
                                            <option value="Agustus" {{ $tagihan->bulan == 'Agustus' ? 'selected' : '' }}>
                                                Agustus</option>
                                            <option value="September" {{ $tagihan->bulan == 'September' ? 'selected' : '' }}>
                                                September</option>
                                            <option value="Oktober" {{ $tagihan->bulan == 'Oktober' ? 'selected' : '' }}>
                                                Oktober</option>
                                            <option value="November" {{ $tagihan->bulan == 'November' ? 'selected' : '' }}>
                                                November</option>
                                            <option value="Desember" {{ $tagihan->bulan == 'Desember' ? 'selected' : '' }}>
                                                Desember</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="tahun" class="form-label">Tahun</label>
                                        <input type="number" class="form-control" id="tahun" name="tahun"
                                            value="{{ old('tahun', $tagihan->tahun) }}" min="2020" max="2030" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="jumlah_meter" class="form-label">Jumlah Meter (kWh)</label>
                                        <input type="number" class="form-control" id="jumlah_meter" name="jumlah_meter"
                                            value="{{ old('jumlah_meter', $tagihan->jumlah_meter) }}" min="0" step="0.01"
                                            required>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="tarif_per_kwh" class="form-label">Tarif Per KWH (Rp)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number" class="form-control" id="tarif_per_kwh"
                                                name="tarif_per_kwh"
                                                value="{{ old('tarif_per_kwh', $tagihan->tarif_per_kwh) }}" min="0"
                                                step="0.01" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="biaya_admin" class="form-label">Biaya Admin (Rp)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" id="biaya_admin" name="biaya_admin"
                                            value="{{ old('biaya_admin', $tagihan->biaya_admin) }}" min="0" step="0.01"
                                            required>
                                    </div>
                                </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update Data
                            </button>
                            <a href="{{ route('admin.tagihan') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                        </form>
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