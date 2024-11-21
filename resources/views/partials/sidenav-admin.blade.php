<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading pt-1.5">Pengelolaan</div>
                <a class="nav-link pb-0 {{request()->routeIs('main') ? 'active' : ''}}" href="{{route('main')}}">
                    <div class="sb-nav-link-icon"><i class="bx bx-tachometer"></i></div>
                    Dashboard
                </a>
                <a class="nav-link pb-0 {{request()->routeIs('main2') ? 'active' : ''}}" href="{{route('main2')}}">
                    <div class="sb-nav-link-icon"><i class="bx bx-tachometer"></i></div>
                    Dashboard Jungut
                </a>
                @if(Auth::user()->gid == 1)
                    <a class="nav-link pb-0 {{request()->routeIs(['jenis_retribusi', 'jenis_retribusi.*']) ? 'active' : ''}}" href="{{route('jenis_retribusi')}}">
                        <div class="sb-nav-link-icon"><i class="bx bx-layer"></i></div>
                        Jenis Retribusi
                    </a>
                    <a class="nav-link pb-0 {{request()->routeIs(['objek_retribusi', 'objek_retribusi.*']) ? 'active' : ''}}" href="{{route('objek_retribusi')}}">
                        <div class="sb-nav-link-icon"><i class="bx bx-cube"></i></div>
                        Objek Retribusi
                    </a>
                    <a class="nav-link pb-0 {{request()->routeIs(['wajib_retribusi', 'wajib_retribusi.*']) ? 'active' : ''}}" href="{{route('wajib_retribusi')}}">
                        <div class="sb-nav-link-icon"><i class="bx bx-cube"></i></div>
                        Wajib Retribusi
                    </a>
                @endif
                @if(Auth::user()->gid == 1 || Auth::user()->gid == 4 || Auth::user()->gid == 5)
                    <a class="nav-link pb-0 {{request()->routeIs(['karcis', 'karcis.*']) ? 'active' : ''}}" href="{{route('karcis')}}">
                        <div class="sb-nav-link-icon"><i class="bx bxs-coupon"></i></div>
                        Karcis
                    </a>
                @endif
                @if(Auth::user()->gid == 1 || Auth::user()->gid == 4)
                    <a class="nav-link pb-0 {{request()->routeIs(['pengembalian', 'pengembalian.*']) ? 'active' : ''}}" href="{{route('pengembalian')}}">
                        <div class="sb-nav-link-icon"><i class='bx bx-refresh'></i></div>
                        Pengembalian Karcis
                    </a>
                @endif
                @if(Auth::user()->gid == 1 || Auth::user()->gid == 4 || Auth::user()->gid == 5)
                    <a class="nav-link pb-0 {{request()->routeIs(['tagihan', 'tagihan.*']) ? 'active' : ''}}" href="{{route('tagihan')}}">
                        <div class="sb-nav-link-icon"><i class="bx bx-credit-card"></i></div>
                        SKRD
                    </a>
                @endif
                @if(Auth::user()->gid == 1 || Auth::user()->gid == 4)
                    <a class="nav-link pb-0 {{request()->routeIs(['penyerahan', 'penyerahan.*']) ? 'active' : ''}}" href="{{route('penyerahan')}}">
                        <div class="sb-nav-link-icon"><i class='bx bx-money-withdraw'></i></div>
                        Penyerahan Karcis / Tagihan
                    </a>
                @endif
                @if(Auth::user()->gid == 1 || Auth::user()->gid == 4 || Auth::user()->gid == 5)
                    <div class="sb-sidenav-menu-heading pt-1.5">Pembayaran</div>
                    <a class="nav-link pb-0 {{request()->routeIs('pembayaran') ? 'active' : ''}}" href="{{route('pembayaran')}}">
                        <div class="sb-nav-link-icon"><i class="bx bxs-bank"></i></div>
                        Data Pembayaran
                    </a>
                @endif
                @if(Auth::user()->gid == 1 || Auth::user()->gid == 5)
                    <a class="nav-link pb-0 {{request()->is('pembayaran/create/bulanan*') ? 'active' : ''}}" href="{{route('pembayaran.bulanan')}}">
                        <div class="sb-nav-link-icon"><i class="bx bx-dollar-circle"></i></div>
                        Pembayaran Bulanan
                    </a>
                    <a class="nav-link pb-0 {{request()->is('pembayaran/create/tagihan*') ? 'active' : ''}}" href="{{route('pembayaran.tagihan')}}">
                        <div class="sb-nav-link-icon"><i class="bx bxs-dollar-circle"></i></div>
                        Pembayaran Tagihan
                    </a>
                    <a class="nav-link pb-0 {{request()->routeIs('pembayaran.verifikasi*') ? 'active' : ''}}" href="{{route('pembayaran.verifikasi')}}">
                        <div class="sb-nav-link-icon"><i class="bx bxs-lock"></i></div>
                        Verifikasi Pembayaran
                    </a>
                    <a class="nav-link pb-0 {{request()->routeIs('pembayaran.batal') ? 'active' : ''}}" href="{{route('pembayaran.batal')}}">
                        <div class="sb-nav-link-icon"><i class="bx bxs-trash"></i></div>
                        Pembatalan Pembayaran
                    </a>
                @endif

                <div class="sb-sidenav-menu-heading pt-1.5">Log</div>
                <a class="nav-link pb-0 {{request()->routeIs('log_kunjungan') ? 'active' : ''}}" href="{{route('log_kunjungan')}}">
                    <div class="sb-nav-link-icon"><i class="bx bxs-bookmark"></i></div>
                    Log Kunjungan
                </a>
                
                @if(Auth::user()->gid == 1 || Auth::user()->gid == 4 || Auth::user()->gid == 5)
                    <div class="sb-sidenav-menu-heading pt-1.5">Laporan</div>
                    <a class="nav-link pb-0 {{request()->routeIs('laporan.data.retribusi') ? 'active' : ''}}" href="{{route('laporan.data.retribusi')}}">
                        <div class="sb-nav-link-icon"><i class="bx bx-cube"></i></div>
                        Data Retribusi
                    </a>
                    <a class="nav-link pb-0 {{request()->routeIs('laporan.data.penerimaan') ? 'active' : ''}}" href="{{route('laporan.data.penerimaan')}}">
                        <div class="sb-nav-link-icon"><i class="bx bxs-bookmarks"></i></div>
                        Data Penerimaan
                    </a>
                    <a class="nav-link pb-0 {{request()->routeIs('laporan.piutang.tagihan') ? 'active' : ''}}" href="{{route('laporan.piutang.tagihan')}}">
                        <div class="sb-nav-link-icon"><i class="bx bx-trophy"></i></div>
                        Piutang Per Tagihan /SKRD
                    </a>
                @endif
                @if(Auth::user()->gid == 1)
                    <div class="sb-sidenav-menu-heading pt-1.5">Addons</div>
                    <a class="nav-link pb-0 {{request()->routeIs('user') ? 'active' : ''}}" href="{{route('user')}}">
                        <div class="sb-nav-link-icon"><i class="bx bx-user"></i></div>
                        Pengguna
                    </a>
                    <a class="nav-link pb-0 {{request()->routeIs('wilayah') ? 'active' : ''}}" href="{{route('wilayah')}}">
                        <div class="sb-nav-link-icon"><i class="bx bx-area"></i></div>
                        Wilayah Kerja
                    </a>
                    <a class="nav-link pb-0 {{request()->routeIs('konfig') ? 'active' : ''}}" href="{{route('konfig')}}">
                        <div class="sb-nav-link-icon"><i class="bx bx-cog"></i></div>
                        Konfigurasi
                    </a>
                @endif
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            {{Auth::user()->name}}
        </div>
    </nav>
</div>