@extends('layouts.app')

@section('title', 'Kata berawalan huruf ' . $huruf)

@section('content')
    @include('kamus._abjad', ['aktif' => $huruf])

    <div class="container ta-wrap">
        <section class="ta-card">
            <p class="ta-eyebrow">DAFTAR KATA</p>
            <h1 class="ta-title">Huruf {{ $huruf }}</h1>

            @if ($daftar->isEmpty())
                <p class="ta-desc">Belum ada kata berawalan huruf {{ $huruf }}.</p>
            @else
                <p class="ta-desc">
                    Menampilkan {{ $daftar->firstItem() }}-{{ $daftar->lastItem() }}
                    dari {{ $daftar->total() }} kata berawalan huruf {{ $huruf }}.
                </p>

                <div class="bh-list">
                    @foreach ($daftar as $item)
                        <a href="{{ route('kamus.detail', ['kata' => $item['kata'], 'tingkatan' => $item['tingkatan'], 'huruf' => $huruf]) }}"
                        class="bh-baris">
                            <span class="bh-baris-isi">
                                <span class="bh-kata-kata">{{ $item['bentuk'] }}</span>
                                <span class="bh-kata-arti">{{ $item['kata']->bahasa_indonesia }}</span>
                            </span>
                            <span class="badge-tingkatan">{{ Str::headline($item['tingkatan']) }}</span>
                        </a>
                    @endforeach
                </div>

                @include('partials.paginasi', ['halaman' => $daftar, 'satuan' => 'kata', 'info' => false])
            @endif
        </section>
    </div>
@endsection