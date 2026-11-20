@props(['teks', 'cari' => ''])
@php
    $teks = (string) $teks;
    $cari = trim((string) $cari);

    // Ubah tiap huruf menjadi versi polos (è -> e). Satu huruf tetap satu huruf,
    // jadi posisi di teks asli dan teks polos selalu sejajar.
    $polos = function (string $s): string {
        $hasil = '';
        foreach (mb_str_split($s) as $huruf) {
            $ascii = \Illuminate\Support\Str::ascii($huruf);
            $hasil .= mb_strtolower($ascii !== '' ? mb_substr($ascii, 0, 1) : $huruf);
        }
        return $hasil;
    };

    // Posisi dicari di versi polos, bukan di teks asli.
    $posisi = $cari === '' ? false : mb_strpos($polos($teks), $polos($cari));
    $panjang = mb_strlen($cari);
@endphp
@if ($posisi === false){{ $teks }}@else{{ mb_substr($teks, 0, $posisi) }}<strong>{{ mb_substr($teks, $posisi, $panjang) }}</strong>{{ mb_substr($teks, $posisi + $panjang) }}@endif