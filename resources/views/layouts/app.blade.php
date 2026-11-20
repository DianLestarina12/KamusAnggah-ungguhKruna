<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Kamus Anggah-Ungguh Kruna Basa Bali')</title>

    {{-- Head HARUS identik di app.blade.php dan template.blade.php --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Baskerville:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dasar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
</head>
<body>

    {{-- ============ NAVBAR (salin persis ke template.blade.php) ============
         PEMBEDA ROLE ADA DI SINI: @auth = admin sudah login, @else = pengunjung/user.
         (Kalau login admin memakai guard khusus: ganti @auth menjadi @auth('admin')) --}}
    <nav class="navbar navbar-expand-lg navbar-kamus sticky-top">
        <div class="container">

            {{-- Klik brand: admin ke Pengelolaan Kata, user ke Beranda --}}
            <a class="navbar-brand brand-kamus"
               href="{{ auth()->check() ? route('admin.kata.index') : route('kamus.beranda') }}">
                <span>Kamus Anggah-Ungguh</span>
                <span>Kruna Basa Bali</span>
            </a>

            <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navbarKamus"
                    aria-controls="navbarKamus" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarKamus">
                <ul class="navbar-nav ms-lg-auto align-items-lg-center mb-2 mb-lg-0">

                    @auth
                        {{-- ===== MENU ADMIN ===== --}}
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.kata.*') ? 'active' : '' }}"
                               href="{{ route('admin.kata.index') }}">Pengelolaan Kata</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('soal.*', 'add-kuis.*') ? 'active' : '' }}"
                               href="{{ route('soal.index') }}">Pengelolaan Kuis</a>
                        </li>
                    @else
                        {{-- ===== MENU USER / PENGUNJUNG ===== --}}
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('kamus.*') ? 'active' : '' }}"
                               href="{{ route('kamus.beranda') }}">Beranda</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('materi.*') ? 'active' : '' }}"
                               href="{{ route('materi.index') }}">Anggah-ungguhing Basa</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('kuis.*') ? 'active' : '' }}"
                               href="{{ route('kuis.index') }}">Kuis</a>
                        </li>
                        <li class="nav-item dropdown dropdown-hover">
                            <a class="nav-link dropdown-toggle {{ request()->routeIs('tentang.*') ? 'active' : '' }}"
                            href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Tentang
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('tentang.penulis') }}">Tentang Penulis</a></li>
                                <li><a class="dropdown-item" href="{{ route('tentang.aplikasi') }}">Tentang Aplikasi</a></li>
                            </ul>
                        </li>
                    @endauth

                    {{-- Pemisah sebelum tombol (sama untuk user & admin) --}}
                    <li class="nav-item d-none d-lg-flex align-items-center">
                        <span class="nav-separator"></span>
                    </li>
                </ul>

                {{-- ===== TOMBOL ===== --}}
                @auth
                    <form action="{{ route('logout') }}" method="POST" class="d-inline m-0 p-0">
                        @csrf
                        <button type="submit" class="btn btn-keluar">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-masuk">Masuk</a>
                @endauth
            </div>
        </div>
    </nav>
    {{-- ============ AKHIR NAVBAR ============ --}}

    <main class="container my-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>

    @yield('modal')

   <!-- <footer class="footer-kamus mt-5 py-4">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center text-center text-md-start gap-2">
            <div><strong>Kamus Anggah-Ungguh Kruna Basa Bali</strong></div>
            <div class="small">&copy; {{ date('Y') }} Kamus App. Seluruh hak cipta dilindungi.</div>
        </div>
    </footer> -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>