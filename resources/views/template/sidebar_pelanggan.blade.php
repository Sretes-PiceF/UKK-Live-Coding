<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-headin">Menu</div>
                <a class="nav-link" href="{{ route('pelanggan.tagihan') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Tagihan
                </a>
                <a class="nav-link" href="{{ route('pelanggan.total') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-bolt"></i></div>
                    Total Tagihan
                </a>
                <a class="nav-link" href="{{ route('action.logout') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-right-from-bracket"></i></div>
                    Logout
                </a>
            </div>
        </div>
        {{-- <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            {{ Auth::user()->}}
        </div> --}}
    </nav>
</div>