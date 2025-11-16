@extends('template.layout')

@section('title', 'Tagihan')

@section('header')
    @include('template.navbar_admin')
@endsection

@section('main')


    <div id="layoutSidenav">
        @include('template.sidebar_admin')
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Tagihan</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Halaman untuk melihat tagihan yang perlu dibayarkan</li>
                    </ol>


                    <!-- FORM SEARCH -->
                    <div class="mb-4">
                        <form action="{{ route('admin.tagihan') }}" method="GET" class="position-relative w-100">
                            <label for="q" class="sr-only">Cari</label>
                            <input id="q" name="q" type="search" value="{{ old('q', request('q')) }}"
                                placeholder="Cari berdasarkan bulan, tahun, atau jumlah meter..." 
                                class="form-control ps-5 py-2 rounded-pill border-0"
                                style="
                                    background-color: #f1f5f9;
                                    box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
                                    transition: all 0.3s ease;
                                "
                                onfocus="this.style.backgroundColor='#e2e8f0';"
                                onblur="this.style.backgroundColor='#f1f5f9';" />
                            <i class="fa-solid fa-magnifying-glass text-muted position-absolute"
                                style="top: 50%; left: 16px; transform: translateY(-50%);"></i>
                            
                            <!-- Tombol Clear Search (muncul jika ada keyword) -->
                            @if(request('q'))
                                <a href="{{ route('admin.tagihan') }}" 
                                   class="btn btn-sm btn-outline-secondary position-absolute"
                                   style="top: 50%; right: 16px; transform: translateY(-50%);">
                                    <i class="fas fa-times"></i> Clear
                                </a>
                            @endif
                        </form>
                        
                        <!-- Info hasil pencarian -->
                        @if(request('q'))
                            <div class="mt-2">
                                <small class="text-muted">
                                    Menampilkan hasil pencarian untuk: <strong>"{{ request('q') }}"</strong>
                                    ({{ $tagihan->count() }} data ditemukan)
                                </small>
                            </div>
                        @endif
                    </div>

                    <a href="{{ route('admin.tagihan.create') }}" class="btn btn-success mb-3">
                        <i class="fas fa-plus"></i> Tambah Tagihan
                    </a>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle shadow-sm"
                            style="border-radius: 8px; overflow: hidden;">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Bulan</th>
                                    <th scope="col">Tahun</th>
                                    <th scope="col">Jumlah Meter</th>
                                    <th scope="col">Tarif PerKWH</th>
                                    <th scope="col">Biaya Admin</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tagihan as $index => $item)
                                    <tr>

                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ $item->bulan }}
                                        </td>
                                        <td>{{ $item->tahun }}</td>
                                        <td>{{ $item->jumlah_meter }}</td>
                                        <td>Rp {{ number_format($item->biaya_admin, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($item->tarif_per_kwh, 0, ',', '.') }}</td>
                                        <td>
                                            <!-- Tombol Edit -->
                                            <a href="{{ route('admin.tagihan.edit', $item->id_tagihan) }}"
                                                class="btn btn-warning">
                                                <i class="fas fa-pencil"></i>
                                            </a>

                                            <!-- Tombol Hapus -->
                                            <button type="button" class="btn btn-danger ms-2" data-bs-toggle="modal"
                                                data-bs-target="#hapusModal{{ $item->id_tagihan }}">
                                                <i class="fas fa-trash"></i>
                                            </button>

                                            <!-- Modal Hapus -->
                                            <div class="modal fade" id="hapusModal{{ $item->id_tagihan }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5">Peringatan!!!</h1>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah anda yakin ingin menghapus tagihan ini?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Tutup</button>

                                                            <form
                                                                action="{{ route('admin.tagihan.delete', $item->id_tagihan) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">
                                                                    Hapus
                                                                </button>
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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