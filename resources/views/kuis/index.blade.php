@extends('layouts.app')

@section('title', 'Kuis - Kamus Anggah-Ungguh Kruna Basa Bali')

@section('content')
    <div class="kuis-wrap">
        <section class="ta-card kuis-intro">
            @if (session('error'))
                <div class="alert alert-warning">{{ session('error') }}</div>
            @endif

            <h1 class="kuis-intro-judul">Kuis</h1>

            <p class="kuis-intro-teks">
                Ayo coba kuis berikut untuk mengukur pengetahuan kamu tentang anggah-ungguh kruna basa Bali!
                Tapi sebelum itu harus mempelajari materi Anggah-ungguhing Basa.
            </p>

            <form method="POST" action="{{ route('kuis.mulai') }}">
                @csrf
                <button type="submit" class="kuis-intro-btn">Mulai Kuis</button>
            </form>
        </section>
    </div>
@endsection