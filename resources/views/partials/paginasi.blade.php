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

                    @unless ($halaman->onFirstPage())
                        <li><a class="paginasi-item" href="{{ $halaman->previousPageUrl() }}">Sebelumnya</a></li>
                    @endunless

                    @foreach ($tampil as $n)

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

                    @if ($halaman->hasMorePages())
                        <li><a class="paginasi-item" href="{{ $halaman->nextPageUrl() }}">Selanjutnya</a></li>
                    @endif

                </ul>
            </nav>
        @endif
    </div>
@endif