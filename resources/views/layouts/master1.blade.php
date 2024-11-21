@include('layouts.header1')
@include('sweetalert::alert')
	<body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-gerak-jalan">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3 small" href="index.html">SIM RET SAMPAH</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
                <i class='bx bx-menu'></i>
            </button>
            <!-- Navbar Search-->
            <div class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <span class="badge bg-dark">Halo, {{Auth::user()->name}}</span>
            </div>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-user"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!" data-bs-toggle="modal" data-bs-target="#gantiPasswordModal" >Ganti Password</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li>
                        	<form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item" href="{{route('logout')}}">Logout</button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            @include('partials.sidenav-admin')
            <div id="layoutSidenav_content">
    	        <main>
    	            <div class="container-fluid px-4">
    	                <h2 class="mt-4 d-flex justify-content-between">
                            @yield('title')
                            @php($back = request()->segment(1))
                            @if($back == 'penilaian' || $back == 'diskualifikasi')
                                <a href="{{ url()->previous() }}" class="link link-danger"><i class="bx bx-x-circle"></i></a>
                            @else
                                <a href="{{ Route::has($back) ? route($back) : url()->previous() }}" class="link link-danger"><i class="bx bx-x-circle"></i></a>
                            @endif
                        </h2>
    	                <ol class="breadcrumb mb-2">
    	                    <li class="breadcrumb-item active">@yield('subtitle')</li>
    	                </ol>
    	            </div>
    	            @yield('content')    
    	        </main>
    	        <footer class="py-4 bg-light mt-auto">
    	            <div class="container-fluid px-4">
    	                <div class="d-flex align-items-center justify-content-between small">
    	                    <div class="text-muted">Copyright &copy; Dinas Lingkungan Hidup 2024</div>
    	                    <div>
    	                        <a href="#">Privacy Policy</a>
    	                        &middot;
    	                        <a href="#">Terms &amp; Conditions</a>
    	                    </div>
    	                </div>
    	            </div>
    	        </footer>
    	    </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="gantiPasswordModal" aria-labelledby="gantiPasswordModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="gantiPasswordModal">Ganti Password</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{route('password.reset1')}}">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="id" id="id" value="{{Auth::id()}}">
                            <div class="row mb-3">
                                <label for="password" class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control form-control-sm" id="password" name="password" value="">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="password_confirmation" class="col-sm-2 col-form-label">Ulangi Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control form-control-sm" id="password_confirmation" name="password_confirmation" value="">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
@yield('js-content')
