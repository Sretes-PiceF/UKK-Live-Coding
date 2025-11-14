<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-headin">Menu</div>
                <a class="nav-link" href="{{ route('admin.pelanggan') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    Pelanggan
                </a>
                <a class="nav-link" href="{{ route('admin.tagihan') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                    Tagihan
                </a>
                <a class="nav-link" href="{{ route('admin.total') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
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