@extends('template.layout')

@section('title', 'Edit Pelanggan')

@section('header')
    @include('template.navbar_admin')
@endsection

@section('main')
    <div id="layoutSidenav">
        @include('template.sidebar_admin')
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Edit Data Pelanggan</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="{{ route('admin.pelanggan') }}">Pelanggan</a></li>
                        <li class="breadcrumb-item active">Edit Data</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('admin.pelanggan.update', $pelanggan->id_pelanggan) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <div class="mb-3">
                                    <label for="nama_pengguna" class="form-label">Nama Pengguna</label>
                                    <input type="text" class="form-control" id="nama_pengguna" name="nama_pengguna"
                                        value="{{ old('nama_pengguna', $pelanggan->nama_pelanggan) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="3"
                                        required>{{ old('alamat', $pelanggan->alamat) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="no_kwh" class="form-label">No KWH</label>
                                    <input type="text" class="form-control" id="no_kwh" name="no_kwh"
                                        value="{{ old('no_kwh', $pelanggan->no_kwh) }}" required>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">Update Data</button>
                                    <a href="{{ route('admin.pelanggan') }}" class="btn btn-secondary">Kembali</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection