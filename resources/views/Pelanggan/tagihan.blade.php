@extends('template.layout')

@section('title', 'Tagihan')

@section('header')
    @include('template.navbar_pelanggan')
@endsection

@section('main')


    <div id="layoutSidenav">
        @include('template.sidebar_pelanggan')
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Tagihan</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Halaman untuk melihat tagihan yang perlu dibayarkan</li>
                    </ol>
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