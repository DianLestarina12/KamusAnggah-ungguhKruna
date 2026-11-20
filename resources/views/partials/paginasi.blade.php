{{-- Nomor halaman untuk SEMUA halaman bertabel atau berdaftar (satu komponen bersama).
     Aturan: di halaman pertama tombol "Sebelumnya" TIDAK ditampilkan,
             di halaman terakhir tombol "Selanjutnya" TIDAK ditampilkan.

     Cara pakai:
       Admin (dengan keterangan "Menampilkan 1-10 dari 4.997 kata"):
         @include('partials.paginasi', ['halaman' => $katas, 'satuan' => 'kata'])
       Daftar huruf (tanpa keterangan, karena sudah ada di atas daftar):
         @include('partials.paginasi', ['halaman' => $daftar, 'satuan' => 'kata', 'info' => false])

     Gaya ada di style.css bagian 14. --}}
@php
    $tampilkanInfo = $info ?? true;
@endphp

@if ($halaman->total() > 0)
    @php
        // Bawa parameter pencarian dan urutan (?q=...&sort=...) ke setiap tautan halaman.
        $halaman->appends(request()->query());

        $sekarang = $halaman->currentPage();
        $akhir    = $halaman->lastPage();

        // Tampilkan halaman 1, halaman terakhir, dan 2 halaman di kiri-kanan halaman sekarang.
        $tampil = collect(range(1, $akhir))
            ->filter(fn ($n) => $n === 1 || $n === $akhir || abs($n - $sekarang) <= 2);

        $sebelum = null;
    @endphp

    <div class="paginasi {{ $tampilkanInfo ? '' : 'paginasi-tengah' }}">
        @if ($tampilkanInfo)
            <span class="paginasi-info">
                Menampilkan {{ $halaman->firstItem() }}-{{ $halaman->lastItem() }}
                dari {{ number_format($halaman->total(), 0, ',', '.') }} {{ $satuan }}
            </span>
        @endif

        @if ($halaman->hasPages())
            <nav aria-label="Halaman {{ $satuan }}">
                <ul class="paginasi-daftar">

                    {{-- Sebelumnya: hanya ada bila BUKAN halaman pertama --}}
                    @unless ($halaman->onFirstPage())
                        <li><a class="paginasi-item" href="{{ $halaman->previousPageUrl() }}">Sebelumnya</a></li>
                    @endunless

                    @foreach ($tampil as $n)
                        {{-- Titik tiga bila ada halaman yang dilompati --}}
                        @if ($sebelum !== null && $n - $sebelum > 1)
                            <li><span class="paginasi-item mati">&hellip;</span></li>
                        @endif

                        <li>
                            <a class="paginasi-item {{ $n === $sekarang ? 'aktif' : '' }}"
                               href="{{ $halaman->url($n) }}"
                               @if ($n === $sekarang) aria-current="page" @endif>{{ $n }}</a>
                        </li>

                        @php $sebelum = $n; @endphp
                    @endforeach

                    {{-- Selanjutnya: hanya ada bila BUKAN halaman terakhir --}}
                    @if ($halaman->hasMorePages())
                        <li><a class="paginasi-item" href="{{ $halaman->nextPageUrl() }}">Selanjutnya</a></li>
                    @endif

                </ul>
            </nav>
        @endif
    </div>
@endif