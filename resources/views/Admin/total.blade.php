@extends('template.layout')

@section('title', 'Total Pembayaran')

@section('header')
    @include('template.navbar_admin')
@endsection

@section('main')
    <div id="layoutSidenav">
        @include('template.sidebar_admin')
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Daftar Total Pembayaran</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Halaman untuk melihat total pembayaran yang harus dibayarkan oleh
                            pelanggan</li>
                    </ol>

                    {{-- Notifikasi Success --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Formulir Pencarian --}}
                    <div class="mb-4">
                        <form action="#" method="GET" class="position-relative w-100">
                            <label for="q" class="sr-only">Cari</label>
                            <input id="q" name="q" type="search" placeholder="Cari pelanggan..."
                                class="form-control ps-5 py-2 rounded-pill border-0"
                                style="background-color: #f1f5f9; box-shadow: inset 0 1px 2px rgba(0,0,0,0.05); transition: all 0.3s ease;"
                                onfocus="this.style.backgroundColor='#e2e8f0';"
                                onblur="this.style.backgroundColor='#f1f5f9';" value="{{ request('q') }}" />
                            <i class="fa-solid fa-magnifying-glass text-muted position-absolute"
                                style="top: 50%; left: 16px; transform: translateY(-50%);"></i>
                        </form>
                    </div>

                    {{-- ✅ Tabel Data Total Pembayaran --}}
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle shadow-sm"
                            style="border-radius: 8px; overflow: hidden;">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="text-center">No</th>
                                    <th scope="col">Pelanggan</th>
                                    <th scope="col">Tagihan</th>
                                    <th scope="col">Tanggal Bayar</th>
                                    <th scope="col">Biaya Admin</th>
                                    <th scope="col">Total Biaya</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($total as $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            @if($item->pelanggan)
                                                {{ $item->pelanggan->nama_pelanggan }}<br>
                                                <small class="text-muted">No Meter: {{ $item->pelanggan->no_kwh ?? '-' }}</small>
                                            @else
                                                <span class="text-muted">Data pelanggan tidak ditemukan</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->tagihan)
                                                {{ $item->tagihan->bulan }} {{ $item->tagihan->tahun }}<br>
                                                <small class="text-muted">Meter: {{ $item->tagihan->jumlah_meter }} kWh</small>
                                            @else
                                                <span class="text-muted">Data tagihan tidak ditemukan</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($item->tanggal_bayar)
                                                {{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d/m/Y') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>Rp {{ number_format($item->biaya_admin ?? 0, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($item->total_bayar ?? 0, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            @if($item->status_pembayaran == 'lunas')
                                                <span class="badge bg-success">Lunas</span>
                                            @elseif($item->status_pembayaran == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @else
                                                <span class="badge bg-danger">Belum Bayar</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            {{-- Tombol Edit --}}
                                            <a href="{{ route('admin.total-pembayaran.edit', $item->id_total_tagihan) }}"
                                                class="btn btn-warning btn-sm">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>

                                            {{-- Tombol Hapus --}}
                                            <button type="button" class="btn btn-danger btn-sm ms-2" data-bs-toggle="modal"
                                                data-bs-target="#hapusModal{{ $item->id_total_tagihan }}">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>

                                            {{-- Modal Konfirmasi Hapus --}}
                                            <div class="modal fade" id="hapusModal{{ $item->id_total_tagihan }}" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5">Peringatan!</h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah anda yakin ingin menghapus data pembayaran?
                                                            @if($item->pelanggan)
                                                                <br><strong>Pelanggan:
                                                                    {{ $item->pelanggan->nama_pelanggan }}</strong>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <form
                                                                action="{{ route('admin.total-pembayaran.delete', $item->id_total_tagihan) }}"
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
                                        <td colspan="8" class="text-center text-muted py-5">
                                            <div class="empty-state">
                                                <i class="fas fa-file-invoice-dollar fa-3x mb-3 text-muted"></i>
                                                <h5 class="text-muted">Tidak ada data pembayaran</h5>
                                                <p class="text-muted mb-4">Belum ada data total pembayaran yang tercatat.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if(isset($total) && method_exists($total, 'links'))
                        <div class="d-flex justify-content-center mt-4">
                            {{ $total->links() }}
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
@endsection