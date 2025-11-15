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


                    <div class="mb-4">
                        <form action="#" method="GET" class="position-relative w-100">
                            <label for="q" class="sr-only">Cari</label>
                            <input id="q" name="q" type="search" value="{{ old('q', request('q')) }}"
                                placeholder="Cari Tagihan..." class="form-control ps-5 py-2 rounded-pill border-0" style="
                                            background-color: #f1f5f9; /* abu lembut */
                                            box-shadow: inset 0 1px 2px rgba(0,0,0,0.05); /* dalam, bukan luar */
                                            transition: all 0.3s ease;
                                        " onfocus="this.style.backgroundColor='#e2e8f0';"
                                onblur="this.style.backgroundColor='#f1f5f9';" />
                            <i class="fa-solid fa-magnifying-glass text-muted position-absolute"
                                style="top: 50%; left: 16px; transform: translateY(-50%);"></i>
                        </form>
                    </div>
                    <div class="mb-4">
                        <a href="{{ route('admin.pengaturan.edit') }}" class="btn btn-primary shadow-sm">
                            <i class="fas fa-money-bill-wave me-2"></i> Atur Biaya Admin
                        </a>
                    </div>

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
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Januari</td>
                                    <td>2009</td>
                                    <td>90</td>
                                    <td>Rp 1.500</td>
                                    <td>
                                        <!-- Tombol Edit -->
                                        <a href="#" class="btn btn-warning">
                                            <i class="fas fa-pencil"></i>
                                        </a>

                                        <!-- Tombol Hapus -->
                                        <button type="button" class="btn btn-danger ms-2" data-bs-toggle="modal"
                                            data-bs-target="#hapusModal">
                                            <i class="fas fa-trash"></i>
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="hapusModal" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5">Peringatan!!!</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Apakah anda sudah yakin ingin menghapus data ini?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Tutup</button>
                                                        <!-- Form Hapus dengan Metode DELETE -->
                                                        <form action="#"> <button type="submit" class="btn btn-danger">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
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