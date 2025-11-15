@extends('template.layout')

@section('title', 'Tambah Tagihan')

@section('main')
    <div class="container mt-4">
        <h3>Tambah Tagihan</h3>
        <form action="{{ route('admin.tagihan.store') }}" method="POST">
            @csrf

            <div class="mt-3">
                <label>Bulan</label>
                <input type="text" name="bulan" class="form-control" required>
            </div>

            <div class="mt-3">
                <label>Tahun</label>
                <input type="number" name="tahun" class="form-control" required>
            </div>

            <div class="mt-3">
                <label>Jumlah Meter</label>
                <input type="number" name="jumlah_meter" class="form-control" required>
            </div>

            <div class="mt-3">
                <label>Tarif Per KWH</label>
                <input type="number" name="tarif_per_kwh" class="form-control" required>
            </div>

            <button class="btn btn-success mt-3">Simpan</button>
            <a href="{{ route('admin.tagihan.index') }}" class="btn btn-secondary mt-3">Kembali</a>
        </form>
    </div>
@endsection