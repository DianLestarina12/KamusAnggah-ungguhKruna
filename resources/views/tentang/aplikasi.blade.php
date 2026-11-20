@extends('layouts.app')

@section('content')
@php
    // Data pengembang. Ubah di sini jika ada perubahan.
    $dev = [
        'nama'    => 'Ni Putu Dian Lestarina Putri',
        'prodi'   => 'Informatika',
        'email'   => 'kamusanggahungguhkrunabasabali@gmail.com',
        'tahun'   => 2026,
    ];
@endphp

<main class="ta-wrap">
    <section class="ta-card">
        <h1 class="ta-title">Tentang Aplikasi</h1>

        <p class="ta-desc">
            Aplikasi ini adalah sistem informasi berbasis web untuk kamus
            Anggah-Ungguh Kruna Basa Bali. Aplikasi memuat pencarian kata
            beserta tingkatan bahasanya, materi Anggah-Ungguhing Basa, dan
            Kuis untuk menguji penguasaan materi.
        </p>

        {{-- Panduan --}}
        <h2 class="ta-heading">Panduan Aplikasi</h2>
        <div class="ta-grid">
            <article class="ta-box">
                <div class="ta-box-head">
                    <span class="ta-icon ta-icon-brown">P</span>
                    <h3>Untuk Pengguna</h3>
                </div>
                <ol class="ta-list">
                    <li>Cari kata pada kolom pencarian di halaman Kamus.</li>
                    <li>Pilih huruf untuk menampilkan daftar kata berdasarkan huruf awal.</li>
                    <li>
                        Perhatikan cara membaca huruf e dalam kamus ini:
                        <ol class="ta-sublist" type="a">
                            <li>Huruf <strong>e</strong> dibaca seperti pada kata ‘ember’.</li>
                            <li>Huruf <strong>é</strong> dibaca seperti pada kata ‘sate’.</li>
                        </ol>
                    </li>
                    <li>Buka menu Anggah-Ungguhing Basa untuk mempelajari materi per tingkatan.</li>
                    <li>Buka menu Kuis untuk menguji pemahaman tanpa perlu login.</li>
                </ol>
            </article>

            <article class="ta-box">
                <div class="ta-box-head">
                    <span class="ta-icon ta-icon-gold">A</span>
                    <h3>Untuk Admin</h3>
                </div>
                <ol class="ta-list">
                    <li>Masuk melalui halaman login menggunakan akun admin.</li>
                    <li>Kelola data kata pada menu pengelolaan kata: tambah, ubah, dan hapus.</li>
                    <li>Kelola soal pada menu pengelolaan Kuis.</li>
                    <li>Keluar dari akun setelah selesai mengelola data.</li>
                </ol>
            </article>
        </div>

        {{-- Pengembang --}}
        <h2 class="ta-heading">Pengembang Aplikasi</h2>

            <div class="ta-dev-info">
                <h3>{{ $dev['nama'] }}</h3>
                <table class="ta-table">
                    <tr>
                        <th>Program Studi</th>
                        <td>{{ $dev['prodi'] }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><a href="mailto:{{ $dev['email'] }}">{{ $dev['email'] }}</a></td>
                    </tr>
                    <tr>
                        <th>Tahun</th>
                        <td>{{ $dev['tahun'] }}</td>
                    </tr>
                </table>
            </div>
        </article>
    </section>
</main>
@endsection