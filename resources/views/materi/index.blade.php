@extends('layouts.app')

@section('content')

@php
    // Urutan halaman materi (sama dengan sidebar), dipakai untuk menandai menu yang sedang aktif.
    $urutanMateri = [
        'materi.latar_belakang',
        'materi.kruna_alus',
        'materi.alus_singgih',
        'materi.alus_sor',
        'materi.alus_mider',
        'materi.alus_madia',
        'materi.kruna_nenten_alus',
        'materi.kruna_mider',
        'materi.kruna_andap',
        'materi.kruna_kasar',
        'materi.kesimpulan',
    ];

    $posisi = array_search(request()->route()->getName(), $urutanMateri, true);
    $aktif = $urutanMateri[$posisi === false ? 0 : $posisi];
@endphp

<div class="mt-4 mb-5">
    <h1 class="materi-title">Materi Anggah-Ungguh Kruna Basa Bali</h1>
    <p class="materi-subtitle">
        Bahasa daerah Bali merupakan alat komunikasi yang masih hidup dan digunakan sebagai alat komunikasi intrasuku Bali. Berikut merupakan Anggah-ungguhing Basa Anggah-Ungguh Kruna Basa Bali untuk lebih memahami cara penggunaan dan tingkatan bahasa Bali.
    </p>

    <div class="row align-items-stretch">

        <div class="col-lg-3 col-md-4 mb-4">
            <div class="materi-box materi-sidebar">
                <div class="materi-sidebar-header">Pilihan Materi</div>
                <ul class="materi-menu">
                    <li>
                        <a href="{{ route('materi.latar_belakang') }}" class="{{ $aktif === 'materi.latar_belakang' ? 'menu-active' : '' }}">Latar Belakang</a>
                    </li>

                    <li>
                        <a href="#">Identifikasi Anggah-Ungguh Kruna Basa Bali</a>
                    </li>

                    <li>
                        <div class="materi-sub-title">
                            <a href="{{ route('materi.kruna_alus') }}" class="{{ $aktif === 'materi.kruna_alus' ? 'menu-active' : '' }}">Kruna Alus</a>
                        </div>
                        <ul class="materi-sub-menu">
                            <li><a href="{{ route('materi.alus_singgih') }}" class="{{ $aktif === 'materi.alus_singgih' ? 'menu-active' : '' }}">Kruna Alus Singgih (ASI)</a></li>
                            <li><a href="{{ route('materi.alus_sor') }}" class="{{ $aktif === 'materi.alus_sor' ? 'menu-active' : '' }}">Kruna Alus Sor (ASO)</a></li>
                            <li><a href="{{ route('materi.alus_mider') }}" class="{{ $aktif === 'materi.alus_mider' ? 'menu-active' : '' }}">Kruna Alus Mider (AMI)</a></li>
                            <li><a href="{{ route('materi.alus_madia') }}" class="{{ $aktif === 'materi.alus_madia' ? 'menu-active' : '' }}">Kruna Alus Madya (AMA)</a></li>
                        </ul>
                    </li>

                    <li>
                        <div class="materi-sub-title">
                            <a href="{{ route('materi.kruna_nenten_alus') }}" class="{{ $aktif === 'materi.kruna_nenten_alus' ? 'menu-active' : '' }}">Kruna Nenten Alus</a>
                        </div>
                        <ul class="materi-sub-menu">
                            <li><a href="{{ route('materi.kruna_mider') }}" class="{{ $aktif === 'materi.kruna_mider' ? 'menu-active' : '' }}">Kruna Mider</a></li>
                            <li><a href="{{ route('materi.kruna_andap') }}" class="{{ $aktif === 'materi.kruna_andap' ? 'menu-active' : '' }}">Kruna Andap</a></li>
                            <li><a href="{{ route('materi.kruna_kasar') }}" class="{{ $aktif === 'materi.kruna_kasar' ? 'menu-active' : '' }}">Kruna Kasar</a></li>
                        </ul>
                    </li>

                    <li class="mt-2">
                        <a href="{{ route('materi.kesimpulan') }}" class="{{ $aktif === 'materi.kesimpulan' ? 'menu-active' : '' }}">Kesimpulan</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-lg-9 col-md-8 mb-4">
            <div class="materi-box materi-content">

                @hasSection('isi_materi')
                    @yield('isi_materi')
                @else
                    <h3>Latar Belakang <em>Anggah-Ungguh Kruna</em></h3>

                    <p>
                        Stratifikasi atau status sosial masyarakat suku Bali tergolong unik karena dilihat dari segi kelahiran,
                        ada keturunan bangsawan (<em>brahmana dan ksatria</em>),
                        ada sisanya merupakan masyarakat orang kebanyakan yang terlahir dari masyarakat berkasta sudra yang disebut <em>wangsa jaba</em>.
                        Keturunan bangsawan atau orang-orang yang memiliki jabatan penting disebut <em>sang singgih</em> (golongan atas) dan mereka yang keturunan orang kebanyakan atau yang menjadi rakyat kecil disebut <em>sang sor</em> (golongan bawah).
                        Hal inilah yang membawa konsekuensi terdapatnya sistem bicara bahasa Bali yang unik dengan <em>anggah-ungguh kruna</em>-nya.
                    </p>

                    <p>
                        Selanjutnya ketika masing-masing individu memasuki dunia kerja atau dunia industri,
                        ada yang berkedudukan sebagai pejabat, ada pula yang menjadi pegawai kecil, pedagang, nlatan atau buruh lainnya.
                        Orang-orang yang memiliki jabatan penting disebut <em>sang singgih</em> (golongan atas), sedangkan mereka yang menjadi rakyat atau pegawai kecil serta buruh-buruh pekerja kasar disebut <em>sang sor</em> (golongan bawah).
                    </p>

                    <p>
                        Status sosial inilah yang menyebabkan adanya tingkatan bicara dalam bahasa Bali.
                        Masyarakat golongan bawah (<em>sang sor</em>) yang berbicara terhadap golongan atas (<em>sang singgih</em>) akan berbahasa Bali tingkatan <em>alus</em> (menghormat) dan sebaliknya masyarakat golongan atas (<em>sang singgih</em>) dapat berbahasa <em>andap</em> atau tingkatan biasa (lepas hormat) terhadap golongan bawah (<em>sang sor</em>).
                        Dengan demikian muncullah tingkat-tingkatan bicara yang dalam bahasa Bali disebut <em>Anggah-Ungguhing Basa Bali</em>.
                        Anggah-ungguh basa Bali dibentuk oleh kalimat-kalimat, dan kalimat dibentuk oleh kata-kata bahasa daerah Bali yang bervariasi yang disebut <em>anggah-ungguh kruna</em>.
                    </p>

                    <p>
                        Setiap kata bahasa Bali memiliki nilai rasa yang berbeda-beda.
                        Keadaan kosakata bahasa Bali seperti itulah yang dirasakan menjadi penyebab sulitnya berkomunikasi dengan bahasa Bali.
                        Kesulitan tersebut akan dapat diatasi jika dilakukan upaya serius untuk mempelajari kata demi kata, terutama kosakata yang memiliki bentuk halus.
                        Berdasarkan nilai rasanya, secara umum kosakata bahasa Bali dibedakan atas kata atau <em>kruna alus</em> dan kata atau <em>kruna nenten alus</em>.
                    </p>
                @endif

            </div>
        </div> 

    </div> 
</div>
@endsection