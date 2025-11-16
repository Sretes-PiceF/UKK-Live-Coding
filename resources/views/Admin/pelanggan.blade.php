@extends('template.layout')

@section('title', 'Pelanggan')

@section('header')
    @include('template.navbar_admin')
@endsection

@section('main')
    <div id="layoutSidenav">
        @include('template.sidebar_admin')
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Daftar Pelanggan</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Halaman untuk melihat pelanggan yang terdaftar</li>
                    </ol>

                    {{-- Notifikasi Success --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- FORM SEARCH -->
                    <div class="mb-4">
                        <form action="{{ route('admin.pelanggan') }}" method="GET" class="position-relative w-100">
                            <label for="q" class="sr-only">Cari</label>
                            <input id="q" name="q" type="search" value="{{ old('q', request('q')) }}"
                                placeholder="Cari berdasarkan nama, alamat, no KWH, atau jumlah meter..."
                                class="form-control ps-5 py-2 rounded-pill border-0" style="
                                        background-color: #f1f5f9;
                                        box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);
                                        transition: all 0.3s ease;
                                    " onfocus="this.style.backgroundColor='#e2e8f0';"
                                onblur="this.style.backgroundColor='#f1f5f9';" />
                            <i class="fa-solid fa-magnifying-glass text-muted position-absolute"
                                style="top: 50%; left: 16px; transform: translateY(-50%);"></i>

                            <!-- Tombol Clear Search -->
                            @if(request('q'))
                                <a href="{{ route('admin.pelanggan') }}"
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
                                    ({{ $pelanggan->total() }} data ditemukan)
                                </small>
                            </div>
                        @endif
                    </div>

                    {{-- ✅ Tabel Data Pelanggan --}}
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle shadow-sm"
                            style="border-radius: 8px; overflow: hidden;">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="text-center">No</th>
                                    <th scope="col">Nama Pelanggan</th>
                                    <th scope="col">Alamat</th>
                                    <th scope="col">No KWH</th>
                                    <th scope="col">Jumlah Meter</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pelanggan as $p)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $p->nama_pelanggan }}</td>
                                        <td>{{ $p->alamat }}</td>
                                        <td>{{ $p->no_kwh }}</td>
                                        <td>{{ $p->jumlah_meter }}</td>
                                        <td class="text-center">
                                            {{-- Tombol Edit --}}
                                            <a href="{{ route('admin.pelanggan.edit', $p->id_pelanggan) }}"
                                                class="btn btn-warning btn-sm" class="btn btn-warning btn-sm">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>

                                            {{-- Tombol Hapus --}}
                                            <button type="button" class="btn btn-danger btn-sm ms-2" data-bs-toggle="modal"
                                                data-bs-target="#hapusModal{{ $p->id_pelanggan }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>

                                            {{-- Modal Konfirmasi Hapus --}}
                                            <div class="modal fade" id="hapusModal{{ $p->id_pelanggan }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5">Peringatan!</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah anda yakin ingin menghapus data pelanggan
                                                            <strong>{{ $p->nama_pelanggan }}</strong>?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <form
                                                                action="{{ route('admin.pelanggan.delete', $p->id_pelanggan) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="fas fa-users fa-2x mb-3"></i><br>
                                            Tidak ada data pelanggan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($pelanggan->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $pelanggan->links() }}
                        </div>
                    @endif
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
    <script>
        function liveSearch() {
            let query = document.getElementById('q').value;

            fetch("{{ route('admin.pelanggan.search') }}?q=" + query)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('table-body').innerHTML = html;
                });
        }
    </script>

@endsection