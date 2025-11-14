@extends('template.layout')

@section('title', 'Total')

@section('header')
    @include('template.navbar_admin')
@endsection

@section('main')


    <div id="layoutSidenav">
        @include('template.sidebar_admin')
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Total Tagihan</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Halaman untuk melihat total tagihan yang sudah dibayarkan</li>
                    </ol>
                </div>

                <div class="max-w-3xl mx-auto p-4">
                    <form action="#" method="GET" class="flex gap-2">
                        <label for="q" class="sr-only">Cari</label>
                        <input id="q" name="q" type="search" value="{{ old('q', request('q')) }}" placeholder="Mencari"
                            class="flex-1 border rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" />
                        <button type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>
                    <br>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Pelanggan</th>
                                    <th scope="col">Tagihan</th>
                                    <th scope="col">Pembayar</th>
                                    <th scope="col">Biaya admin</th>
                                    <th scope="col">Total biaya</th>
                                    <th scope="col">Aksi</th>
                                    {{-- <th scope="col">User</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Modal Hapus -->
                                <div class="modal fade">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5">Peringatan!!!</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Apakah anda yakin ingin menghapus data ini?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Tutup</button>
                                                <!-- Form Hapus dengan Metode DELETE -->
                                                <form>
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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