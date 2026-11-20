@extends('layouts.app')

@section('title', 'Kamus Anggah-Ungguh Kruna Basa Bali')

@section('content')
    <section class="bk-hero">
        <div class="container bk-hero-inner">
            <p class="bk-eyebrow">Kamus Bahasa Bali-Indonesia</p>
            <h1 class="bk-title">Anggah-Ungguh Kruna Basa Bali</h1>
            <p class="bk-sub">Ayo temukan kata dengan tingkatan bahasa Bali dan belajar tingkatan bahasa Bali dengan mudah!</p>

            <div class="bk-cari">
                <x-cari-kata class="form-cari" />
            </div>
        </div>
    </section>

    @include('kamus._abjad')
@endsection