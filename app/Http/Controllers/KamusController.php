<?php
 
namespace App\Http\Controllers;
 
use App\Models\Katas;
use Illuminate\Http\Request;
use Illuminate\Support\Str; //BARU
use Illuminate\Pagination\LengthAwarePaginator;

class KamusController extends Controller
{
    // Beranda: hero + kolom pencarian
    public function beranda()
    {
        return view('kamus.beranda');
    }

    public function materi()
    {
        return view('materi.index');
    }
 
    // Hasil pencarian: setiap bentuk kata yang cocok tampil sebagai baris tersendiri,
    // dikelompokkan menurut huruf awal.
    public function cari(Request $request)
    {
        // 1. Ambil parameter pencarian dari kata yang di input.
        $search = trim((string) $request->query('q', '')); 

        // 2. Jika parameter (inputan) kosong, redirect ke beranda.
        if ($search === '') {
            return redirect()->route('kamus.beranda');
        }
 
        // 3. Ketika parameter tidak kosong, lakukan pencarian di database dengan fungsi hasilPencarian.
        $grup = $this->hasilPencarian($search);
 
        // 4. Data hasil pencarian dikirim ke view untuk ditampilkan.
        return view('kamus.hasil', compact('grup', 'search'));
    }

    //BARU
    // Daftar kata menurut huruf awal. Dipanggil dari pintasan abjad di beranda (route: kamus.huruf).
    // Setiap bentuk kata di semua tingkatan yang berawalan huruf itu tampil sebagai satu entri,
    // sama seperti cara hasil pencarian dikelompokkan. Ditampilkan 10 kata per halaman.
    public function huruf(string $huruf)
    {
        // 1. Seragamkan huruf menjadi kapital agar /huruf/a sama dengan /huruf/A.
        $huruf = mb_strtoupper($huruf);

        // 2. Ambil seluruh kata berawalan huruf itu, sudah terurut (lihat daftarHuruf di bawah).
        $semua = $this->daftarHuruf($huruf);

        // 3. Bagi menjadi 10 kata per halaman.
        //    Datanya dibangun manual (bukan dari query), jadi dibuatkan paginator sendiri.
        $perHalaman = 10;
        $halaman = LengthAwarePaginator::resolveCurrentPage();   // nomor halaman dari ?page=...

        $daftar = new LengthAwarePaginator(
            $semua->forPage($halaman, $perHalaman)->values(),    // kata untuk halaman ini saja
            $semua->count(),                                     // jumlah seluruh kata
            $perHalaman,
            $halaman,
            ['path' => route('kamus.huruf', $huruf)]             // alamat dasar tautan halaman
        );

        // 4. Nomor halaman yang melebihi batas (misalnya ?page=999) dikembalikan ke halaman pertama.
        if ($semua->isNotEmpty() && $daftar->isEmpty()) {
            return redirect()->route('kamus.huruf', $huruf);
        }

        // 5. Kirim ke view resources/views/kamus/huruf.blade.php.
        return view('kamus.huruf', compact('daftar', 'huruf'));
    }

    //BARU
    // Seluruh bentuk kata yang berawalan $huruf, terurut abjad.
    // Dipakai oleh huruf() untuk daftar, dan oleh navigasi() untuk tombol Sebelumnya/Selanjutnya,
    // supaya urutan di kedua tempat itu selalu sama.
    protected function daftarHuruf(string $huruf)
    {
        $huruf = mb_strtoupper($huruf);

        // Ambil daftar kolom tingkatan (andap, asi, aso, dan seterusnya) dari model.
        $tingkatanKeys = Katas::tingkatanKeys();

        $hasil = [];     // menampung entri hasil akhir
        $terlihat = [];  // mencatat bentuk yang sudah masuk agar tidak muncul dua kali

        // 1. Ambil baris yang memuat huruf itu (tanpa membedakan aksen) lewat scope cariTanpaAksen.
        //    Hasilnya masih longgar (huruf bisa berada di bagian mana saja dari kata),
        //    jadi disaring lagi pada langkah 2.
        foreach (Katas::cariTanpaAksen($huruf)->get() as $kata) {
            foreach ($tingkatanKeys as $kolom) {
                $bentuk = $kata->{$kolom};

                // 2a. Lewati kolom tingkatan yang kosong atau tidak punya bentuk.
                if (! Katas::adaBentuk($bentuk)) {
                    continue;
                }

                // 2b. Ambil hanya bentuk yang HURUF PERTAMANYA sama dengan huruf yang dipilih.
                //     Str::ascii membuang aksen, jadi kata berawalan "é" ikut masuk huruf E.
                if (mb_strtoupper(mb_substr(Str::ascii($bentuk), 0, 1)) !== $huruf) {
                    continue;
                }

                // 2c. Satu kata bisa punya bentuk identik di beberapa tingkatan; tampilkan sekali saja
                //     (aturan yang sama dengan hasilPencarian).
                $kunci = $kata->id . '|' . mb_strtolower($bentuk);

                if (isset($terlihat[$kunci])) {
                    continue;
                }

                $terlihat[$kunci] = true;
                $hasil[] = ['kata' => $kata, 'bentuk' => $bentuk, 'tingkatan' => $kolom];
            }
        }

        // 3. Urutkan menurut abjad, tanpa membedakan huruf besar atau kecil.
        return collect($hasil)
            ->sortBy(fn ($item) => mb_strtolower($item['bentuk']), SORT_NATURAL)
            ->values();
    }
 
    // Detail katas: tabel anggah-ungguh lengkap + kata terkait
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
 
    // Tautan "Sebelumnya" dan "Selanjutnya": entri di samping kata ini pada hasil pencarian /cari?q=...,
    // atau pada daftar huruf /huruf/A bila dibuka dari pintasan abjad,
    // atau kata dengan id di sebelahnya bila halaman tidak dibuka dari keduanya.
    protected function navigasi(Katas $kata, ?string $tingkatan, string $search, string $huruf = ''): array
    {
        //BARU: tentukan daftar acuan. Pencarian diutamakan; kalau tidak ada, pakai daftar huruf.
        $hasil = null;
        $param = [];   // parameter yang ikut di tautan agar urutan acuannya tetap terbawa

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
 
    // Setiap bentuk kata yang cocok sebagai satu entri, dikelompokkan menurut huruf awal.
    // Kata dicari di bentuk Bali (cocok sebagian) dan di arti bahasa Indonesia (makna yang sama persis).
    protected function hasilPencarian(string $search)
    {
        $tingkatanKeys = Katas::tingkatanKeys();
        $artiDicari = Katas::daftarArti($search);

        $searchPolos = mb_strtolower(Str::ascii($search));

        //CODE LAMA
       /*$katas = Katas::query()
            ->where(function ($query) use ($tingkatanKeys, $search) {
                foreach ($tingkatanKeys as $kolom) {
                    $query->orWhere($kolom, 'like', '%' . $search . '%');
                }
 
                $query->orWhere('bahasa_indonesia', 'like', '%' . $search . '%');
            })
            ->get();*/
        
        $katas = Katas::cariTanpaAksen($search)->get(); //NEW CODE
        $hasil = [];
        $terlihat = [];
 
        $tambah = function (Katas $kata, string $bentuk, string $kolom, bool $cocokArti) use (&$hasil, &$terlihat) {
            // Satu kata bisa punya bentuk identik di beberapa tingkatan; tampilkan sekali saja.
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
                //awalnya str_contains(mb_strtolower($bentuk), mb_strtolower($search)) diganti dengan str_contains(mb_strtolower(Str::ascii($bentuk)), $searchPolos)
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