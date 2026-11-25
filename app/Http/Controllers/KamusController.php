<?php
 
namespace App\Http\Controllers;
 
use App\Models\Katas;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 
use Illuminate\Pagination\LengthAwarePaginator;

class KamusController extends Controller
{
    public function beranda()
    {
        return view('kamus.beranda');
    }

    public function materi()
    {
        return view('materi.index');
    }
 
    public function cari(Request $request)
    {
        $search = trim((string) $request->query('q', '')); 

        if ($search === '') {
            return redirect()->route('kamus.beranda');
        }

        $grup = $this->hasilPencarian($search);

        return view('kamus.hasil', compact('grup', 'search'));
    }

    public function huruf(string $huruf)
    {

        $huruf = mb_strtoupper($huruf);

        $semua = $this->daftarHuruf($huruf);

        $perHalaman = 10;
        $halaman = LengthAwarePaginator::resolveCurrentPage();   

        $daftar = new LengthAwarePaginator(
            $semua->forPage($halaman, $perHalaman)->values(),    
            $semua->count(),                                     
            $perHalaman,
            $halaman,
            ['path' => route('kamus.huruf', $huruf)]            
        );

        if ($semua->isNotEmpty() && $daftar->isEmpty()) {
            return redirect()->route('kamus.huruf', $huruf);
        }

        return view('kamus.huruf', compact('daftar', 'huruf'));
    }
    

protected function daftarHuruf(string $huruf)
{
    $huruf = mb_strtoupper($huruf);

    $tingkatanKeys = Katas::tingkatanKeys();

    $hasil = [];
    $terlihat = [];

    foreach (Katas::cariTanpaAksen($huruf)->get() as $kata) {

        foreach ($tingkatanKeys as $kolom) {

            $nilai = $kata->{$kolom};

            if (!Katas::adaBentuk($nilai)) {
                continue;
            }

            $daftarBentuk = preg_split('/\s*,\s*/u', $nilai);

            foreach ($daftarBentuk as $bentuk) {

                $bentuk = trim($bentuk);

                if (!Katas::adaBentuk($bentuk)) {
                    continue;
                }

                $hurufAwal = mb_strtoupper(
                    mb_substr(Str::ascii($bentuk), 0, 1)
                );

                if ($hurufAwal !== $huruf) {
                    continue;
                }

                $kunci = $kata->id . '|' . $kolom . '|' . mb_strtolower($bentuk);

                if (isset($terlihat[$kunci])) {
                    continue;
                }

                $terlihat[$kunci] = true;

                $hasil[] = [
                    'kata' => $kata,
                    'bentuk' => $bentuk,
                    'tingkatan' => $kolom
                ];
            }
        }
    }

    return collect($hasil)
        ->sortBy(
            fn ($item) => mb_strtolower($item['bentuk']),
            SORT_NATURAL
        )
        ->values();
}


public function show(Request $request, Katas $kata)
{
    $sinonim = $kata->sinonimSearti();

    $tingkatan = $request->query('tingkatan');
    if (! in_array($tingkatan, Katas::tingkatanKeys(), true)) {
        $tingkatan = null;
    }

    $homonim = $kata->homonimSebentuk($tingkatan);

    $q = trim((string) $request->query('q', ''));

    $huruf = strtoupper(trim((string) $request->query('huruf', '')));
    if (! preg_match('/^[A-Z]$/', $huruf)) {
        $huruf = '';
    }

    [$sebelumnya, $berikutnya] = $this->navigasi($kata, $tingkatan, $q, $huruf);

    $kembali = null;
    if ($q !== '') {
        $kembali = [
            'url'   => url('/cari?' . http_build_query(['q' => $q])),
            'label' => 'Kembali ke hasil pencarian',
        ];
    } elseif ($huruf !== '') {
        $kembali = [
            'url'   => route('kamus.huruf', $huruf),
            'label' => 'Kembali ke daftar huruf ' . $huruf,
        ];
    }

    $konteks = array_filter(
        ['q' => $q, 'huruf' => $huruf],
        fn ($v) => $v !== ''
    );

    return view('kamus.detail', compact(
        'kata', 'sinonim', 'homonim', 'tingkatan',
        'sebelumnya', 'berikutnya', 'kembali', 'konteks'
    ));
}
 
    protected function navigasi(Katas $kata, ?string $tingkatan, string $search, string $huruf = ''): array
    {
        $hasil = null;
        $param = [];   

        if ($search !== '') {
            $hasil = $this->hasilPencarian($search)->flatten(1)->values();
            $param = ['q' => $search];
        } elseif ($huruf !== '') {
            $hasil = $this->daftarHuruf($huruf);
            $param = ['huruf' => $huruf];
        }

        if ($hasil !== null) {
            $posisi = $hasil->search(fn ($item) => $item['kata']->id === $kata->id
                && ($tingkatan === null || $item['tingkatan'] === $tingkatan));

            if ($posisi !== false) {
                $tautan = fn ($item) => $item
                    ? route('kamus.detail', ['kata' => $item['kata'], 'tingkatan' => $item['tingkatan']] + $param)
                    : null;

                return [
                    $posisi > 0 ? $tautan($hasil->get($posisi - 1)) : null,
                    $tautan($hasil->get($posisi + 1)),
                ];
            }
        }

        $sebelumnya = Katas::where('id', '<', $kata->id)->orderByDesc('id')->first();
        $berikutnya = Katas::where('id', '>', $kata->id)->orderBy('id')->first();

        return [
            $sebelumnya ? route('kamus.detail', $sebelumnya) : null,
            $berikutnya ? route('kamus.detail', $berikutnya) : null,
        ];
    }
 
    protected function hasilPencarian(string $search)
    {
        $tingkatanKeys = Katas::tingkatanKeys();
        $artiDicari = Katas::daftarArti($search);
        $searchPolos = mb_strtolower(Str::ascii($search));
        $katas = Katas::cariTanpaAksen($search)->get();
        $hasil = [];
        $terlihat = [];
 
        $tambah = function (Katas $kata, string $bentuk, string $kolom, bool $cocokArti) use (&$hasil, &$terlihat) {
            $kunci = $kata->id . '|' . mb_strtolower($bentuk);
 
            if (isset($terlihat[$kunci])) {
                return;
            }
 
            $terlihat[$kunci] = true;
            $hasil[] = ['kata' => $kata, 'bentuk' => $bentuk, 'tingkatan' => $kolom, 'cocok_arti' => $cocokArti];
        };
 
        foreach ($katas as $kata) {
            foreach ($tingkatanKeys as $kolom) {
                $bentuk = $kata->{$kolom};
                if (Katas::adaBentuk($bentuk) && str_contains(mb_strtolower(Str::ascii($bentuk)), $searchPolos)) {
                    $tambah($kata, $bentuk, $kolom, false);
                }
            }
 
            $utama = $kata->tingkatanUtama();
 
            if ($utama && array_intersect($artiDicari, Katas::daftarArti($kata->bahasa_indonesia)) !== []) {
                $tambah($kata, $kata->{$utama}, $utama, true);
            }
        }
 
        return collect($hasil)
            ->sortBy(fn ($item) => mb_strtolower($item['bentuk']), SORT_NATURAL)
            ->groupBy(fn ($item) => mb_strtoupper(mb_substr($item['bentuk'], 0, 1)))
            ->sortKeys();
    }
}