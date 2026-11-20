@php $abjad = range('A', 'Z'); @endphp

<nav class="bk-abjad" aria-label="Pintasan abjad">
    <div class="container">
        <div class="bk-huruf-grid">
            @foreach ($abjad as $h)
                <a href="{{ route('kamus.huruf', $h) }}"
                   class="bk-huruf {{ ($aktif ?? '') === $h ? 'active' : '' }}"
                   @if (($aktif ?? '') === $h) aria-current="true" @endif>{{ $h }}</a>
            @endforeach
        </div>
    </div>
</nav>