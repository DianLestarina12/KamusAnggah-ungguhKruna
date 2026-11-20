@extends('layouts.app')

@section('content')
@php
    // Daftar buku sumber data. Untuk menambah buku, cukup tambahkan satu blok di sini.
    $buku = [
        [
            'judul'    => 'Kamus Anggah-Ungguhing Basa Bali',
            'tahun'    => 2008,
            'depan'    => 'images/Kamus-Prof-1.jpeg',
            'belakang' => 'images/Riwayat-Urip-Pangripta-Kamus-1.jpeg',
        ],
        [
            'judul'    => 'Kamus Anggah-Ungguh Kruna Bali - Indonesia dan Indonesia - Bali',
            'tahun'    => 2018,
            'depan'    => 'images/Kamus-Prof-2.jpeg',
            'belakang' => 'images/Riwayat-Urip-Pangripta-Kamus-2.jpeg',
        ],
    ];

    // Daftar buku yang sudah ditulis. Untuk menambah judul, cukup tambahkan satu baris di sini.
    $karya = [
        'Widia Sari, Paket Bahasa Bali SLTA (1, 2, 3)',
        'Mustika Sari, Paket Bahasa Bali SD (1, 2, 3, 4, 5, 6)',
        'Tata Titi Mabaos Bali (2014)',
        'Pasang Aksara Bali (2015)',
        'Ejaan Bali Latin (2015)',
        'Kuliah Wacana Basa Bali (2017)',
        'Kamus A-U Kruna Bali-Indonesia Indonesia-Bali (2018)',
        'Kumpulan Satua (Dongeng Rakyat Bali) (2019)',
        'Wacika Sari, Paket Mapel Bahasa Bali SMP (2019)',
        'Bahasa dan Sastra Bali SD dan SMA/SMK Pr. Yudhistira (2018)',
        'Nilai-Nilai Pendidikan Karakter Satua-Satua Bali (2020)',
        'Mapidarta Basa Bali (Pawiwahan Adat Bali) (2023)',
        'Kasusastran Bali miwah Basita Paribasa Bali (2025)',
        'Terampil Menulis Sesuai Ejaan Bali Latin (2026)',
        'Wacana Bahasa Bali, Sembrawama Wecana, dan Naskah Dharma Wecana (2026)',
    ];
@endphp

<main class="ta-wrap">
    <section class="ta-card">
        <h1 class="ta-title">Tentang Penulis Kamus</h1>

        <div class="ta-profil">
            {{-- Kolom kiri: foto dan data singkat --}}
            <aside class="ta-side">
                <img src="{{ asset('images/Foto-Prof-Suwija.jpeg') }}"
                     alt="Prof. Dr. Drs. I Nyoman Suwija"
                     class="ta-foto-penulis">

                <h2 class="ta-nama">Prof. Dr. Drs. I Nyoman Suwija, M.Hum.</h2>
                <p class="ta-jabatan">Profesor bidang Ilmu Bahasa dan Sastra Bali</p>

                <table class="ta-table">
                    <tr>
                        <th>Lahir</th>
                        <td>Badung, 31 Desember 1963</td>
                    </tr>
                    <tr>
                        <th>Pendidikan</th>
                        <td>S3 (Doktor)</td>
                    </tr>
                    <tr>
                        <th>Instansi</th>
                        <td>Dosen PNS di bawah naungan LLDikti Wilayah VIII, dipekerjakan (dpk) pada Universitas PGRI Mahadewa Indonesia</td>
                    </tr>
                </table>
            </aside>

            {{-- Kolom kanan: uraian dalam bentuk paragraf --}}
            <div class="ta-uraian">
                <section class="ta-sec">
                    <h3>Riwayat Pendidikan</h3>
                    <p> Beliau menempuh pendidikannya dari SD N. Sobangan (1974), SMPN Blahkiuh (1977), SPG TP 45 Denpasar (1981), Diploma 1 Pariwisata Unud (1982), S1 Jurusan Bahasa dan Sastra Bali Fakultas Sastra Unud (1987), Diploma 2 Perpustakaan Universitas Terbuka (2003), S2 Prodi Wacana Sastra Udayana (2003), hingga S3 Prodi Wacana Naratif Udayana (2008).</p>
                </section>

                <section class="ta-sec">
                    <h3>Riwayat Pekerjaan</h3>
                    <p>Setamat SPG TP 45 (1981), beliau mengabdi di SD selama 3 bulan. Pada tahun 1982 beliau melamar CPNS di Universitas Udayana dengan ijazah SMP dan diterima sebagai CPNS Gol. I/b yang bertugas di Perpustakaan Fakultas Hukum Unud. Setelah wisuda sarjana pada Januari 1987, golongannya disesuaikan dari Gol. I/b ke Gol. I/c ke III/a, dan sejak 1987 beliau juga mengajar di SMEA TP 45 Denpasar. Pada tahun 2000 beliau pindah sebagai honorer ke SMAN 7 Denpasar, STIPSI Wirabhakti, Stikom Bali, Stikes Wiramedika, dan Universitas Bali Internasional. Pada Januari 2005 statusnya beralih dari Pustakawan Unud menjadi Dosen Kopertis Wilayah VIII dpk. IKIP PGRI Bali, dan pada tahun 2013–2016 beliau menjabat Kepala SMK Kesehatan Bali Dewata.</p>
                </section>

                <section class="ta-sec">
                    <h3>Prestasi dan Penghargaan</h3>
                    <p>Pada tahun 2011 beliau terpilih sebagai Nominasi I Dosen Berprestasi Kopertis Wilayah VIII dan Finalis (10 besar) Dosen Berprestasi Tingkat Nasional. Pada tahun 2022 beliau meraih Hindu Books Reader Community Award, pada tahun 2023 jabatan Guru Besar (Profesor) Ilmu Bahasa dan Sastra Bali, dan pada tahun 2025 LLDIKTI Award Ilmuwan Senior Terbaik I Sosiohumaniora. Pada tahun 2026 beliau menerima Penghargaan Akademisi, Praktisi, Pengembang, dan Pelestari Budaya, Bahasa, dan Kearifan Lokal Bali.</p>
                </section>

                <section class="ta-sec">
                    <h3>Buku-Buku yang Sudah Ditulis</h3>
                    <p>Karya beliau antara lain {{ implode('; ', $karya) }}.</p>
                    <p>Dua di antaranya digunakan sebagai data pada website ini, yaitu Kamus Anggah-Ungguhing Basa Bali (2008) dan Kamus Anggah-Ungguh Kruna Bali - Indonesia dan Indonesia - Bali (2018).</p>
                </section>
            </div>
        </div>

        {{-- Buku sumber data --}}
        <h2 class="ta-heading">Buku Sumber Data</h2>
        <div class="ta-grid">
            @foreach ($buku as $item)
                <article class="ta-box">
                    <div class="ta-box-head">
                        <span class="ta-icon ta-icon-sm {{ $loop->first ? 'ta-icon-brown' : 'ta-icon-gold' }}">
                            {{ $item['tahun'] }}
                        </span>
                        <h3>{{ $item['judul'] }}</h3>
                    </div>

                    <div class="ta-cover-row">
                        <img src="{{ asset($item['depan']) }}" class="ta-cover"
                             alt="Sampul depan {{ $item['judul'] }}">
                        <img src="{{ asset($item['belakang']) }}" class="ta-cover"
                             alt="Sampul belakang {{ $item['judul'] }}">
                    </div>
                </article>
            @endforeach
        </div>
    </section>
</main>
@endsection