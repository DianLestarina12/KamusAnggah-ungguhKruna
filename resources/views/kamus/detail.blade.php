@extends('layouts.app')
 
@section('title', $kata->bentukUtama($tingkatan) . ' - Kamus Anggah-Ungguh Kruna Basa Bali')
 
@section('content')
    <div class="detail-kepala">
        <div>
            @isset($kembali)
                <a href="{{ $kembali['url'] }}" class="detail-kembali">
                    <i class="bi bi-arrow-left"></i> {{ $kembali['label'] }}
                </a>
            @endisset
            <h1 class="detail-kata">{{ $kata->bentukUtama($tingkatan) }}</h1>
            <p class="detail-arti">Bahasa Indonesia : {{ $kata->bahasa_indonesia }}</p>
        </div>
 
        <x-cari-kata placeholder="Cari Kata Lainnya" />
    </div>

    <div class="tabel-anggah">
        <div class="tabel-anggah-kepala">
            <span>Tingkatan Bahasa</span>
            <span>Anggah-Ungguh Kruna Basa Bali</span>
        </div>
 
        @foreach (\App\Models\Katas::tingkatanKeys() as $kolom)
            <div class="tabel-anggah-baris tingkat-{{ $loop->iteration }} {{ $kolom === $tingkatan ? 'aktif' : '' }}">
                <span class="tabel-anggah-label">{{ \App\Models\Katas::tingkatanLabel($kolom, true) }}</span>
                @php($adaBentuk = \App\Models\Katas::adaBentuk($kata->{$kolom}))
                <span class="tabel-anggah-nilai {{ $adaBentuk ? '' : 'kosong' }}">
                    {{ $adaBentuk ? $kata->{$kolom} : '- Tidak tersedia' }}
                </span>
            </div>
        @endforeach
 
        <div class="tabel-anggah-baris tingkat-indonesia">
            <span class="tabel-anggah-label">Bahasa Indonesia</span>
            <span class="tabel-anggah-nilai">{{ $kata->bahasa_indonesia }}</span>
        </div>
    </div>


    <div class="kotak-relasi-grup">
        <div class="kotak-relasi">
            <h2 class="kotak-relasi-judul">Sinonim</h2>

            @forelse ($sinonim as $terkait)
                @php($tingkatanTerkait = $tingkatan && \App\Models\Katas::adaBentuk($terkait->{$tingkatan}) ? $tingkatan : null)
                @php($tingkatanTampil = $tingkatanTerkait ?? $terkait->tingkatanUtama())

                <a class="kotak-relasi-baris" href="{{ route('kamus.detail', ['kata' => $terkait, 'tingkatan' => $tingkatanTerkait] + $konteks) }}">
                    <span class="kotak-relasi-kata">
                        {{ $terkait->bentukUtama($tingkatanTerkait) }}

                        @if ($tingkatanTampil)
                            <small class="kotak-relasi-tingkatan">{{ \App\Models\Katas::tingkatanLabel($tingkatanTampil) }}</small>
                        @endif
                    </span>
                    <span class="kotak-relasi-arti">{{ $terkait->bahasa_indonesia }}</span>
                </a>
            @empty
                <p class="kotak-relasi-kosong">Belum ada sinonim.</p>
            @endforelse
        </div>

        <div class="kotak-relasi">
            <h2 class="kotak-relasi-judul">Homonim</h2>

            @forelse ($homonim as $item)
                <a class="kotak-relasi-baris" href="{{ route('kamus.detail', ['kata' => $item['kata'], 'tingkatan' => $item['tingkatan']] + $konteks) }}">
                    <span class="kotak-relasi-kata">
                        {{ $item['bentuk'] }}
                        <small class="kotak-relasi-tingkatan">{{ \App\Models\Katas::tingkatanLabel($item['tingkatan']) }}</small>
                    </span>
                    <span class="kotak-relasi-arti">{{ $item['kata']->bahasa_indonesia }}</span>
                </a>
            @empty
                <p class="kotak-relasi-kosong">Belum ada homonim.</p>
            @endforelse
        </div>
    </div>

        @if ($sebelumnya || $berikutnya)
            <div class="detail-kaki">
        @if ($sebelumnya)
            <a href="{{ $sebelumnya }}" class="btn-navigasi">Sebelumnya</a>
        @endif

        @if ($berikutnya)
            <a href="{{ $berikutnya }}" class="btn-navigasi ms-auto">Selanjutnya</a>
        @endif
    </div>
    @endif
@endsection