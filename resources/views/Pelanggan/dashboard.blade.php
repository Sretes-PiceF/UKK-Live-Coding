@extends('template.layout')

@section('title', 'Dashboard - pelanggan')

@section('header')
    @include('template.navbar_pelanggan')
@endsection

@section('main')

    <style>
        /* Style untuk gambar */
        .image-container {
            width: 50%;
            /* Setengah lebar layar */
            margin: 20px;
            /* Pusatkan gambar dan beri jarak ke atas */
            position: left;
            /* Untuk kontrol posisi */
            display: flex;
            flex-direction: column;
            /* Agar teks ada di bawah gambar */
            justify-content: left;
            align-items: center;
            height: calc(50vh - 100px);
            /* Setengah tinggi layar */
            overflow: hidden;
        }
    </style>

    <div id="layoutSidenav">
        @include('template.sidebar_pelanggan')
        <div id="layoutSidenav_content">
            <main>
                <div class="image-container">
                    <h5>Selamat Datang Di Dashboard halaman Pelanggan. Disini pelanggan dapat melihat dan membayar
                        tagihan
                        listrik mereka.</h5>
                </div>
                <!-- Kontainer untuk gambar -->
                {{-- <div class="image-container">
                    <img src="{{ asset('/img/perpus21.jpg') }}" alt="Gambar"> --}}
                    <!-- Tambahkan teks selamat datang -->
                    {{--
                </div> --}}

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