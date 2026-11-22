<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
 
class Katas extends Model
{
    use SoftDeletes;
 
    public const TINGKATAN = [
        'kruna_asi' => ['label' => 'Kruna Alus Singgih', 'singkatan' => 'ASI'],
        'kruna_aso' => ['label' => 'Kruna Alus Sor', 'singkatan' => 'ASO'],
        'kruna_ami' => ['label' => 'Kruna Alus Mider', 'singkatan' => 'AMI'],
        'kruna_mider' => ['label' => 'Kruna Mider', 'singkatan' => null],
        'kruna_andap' => ['label' => 'Kruna Andap', 'singkatan' => null],
        'kruna_kasar' => ['label' => 'Kruna Kasar', 'singkatan' => null],
    ];
 
    protected $fillable = [
        'kruna_asi', 'kruna_aso', 'kruna_ami', 'kruna_mider', 'kruna_andap', 'kruna_kasar', 'bahasa_indonesia',
    ];
 
    protected static function booted(): void
    {
        static::saving(function (self $kata) {
            $kata->sidik = self::hitungSidik($kata->getAttributes());
        });
    }
 
    public static function tingkatanKeys(): array
    {
        return array_keys(self::TINGKATAN);
    }

    public function scopeCariTanpaAksen(Builder $query, string $kata): Builder
    {
        $kolomCari = [...self::tingkatanKeys(), 'bahasa_indonesia'];
        $pola = '%' . addcslashes($kata, '%_\\') . '%';
 
        return $query->where(function ($q) use ($kolomCari, $pola) {
            foreach ($kolomCari as $kolom) {
                $q->orWhereRaw("`$kolom` COLLATE utf8mb4_unicode_ci LIKE ?", [$pola]);
            }
        });
    }
 
    public static function hitungSidik(array $nilai): string
    {
        $bagian = array_map(
            fn ($kolom) => mb_strtolower(trim((string) ($nilai[$kolom] ?? ''))),
            [...self::tingkatanKeys(), 'bahasa_indonesia']
        );
 
        return hash('sha256', implode("\x1f", $bagian));
    }
 
    public static function tingkatanLabel(?string $key, bool $withSingkatan = false): ?string
    {
        $tingkatan = self::TINGKATAN[$key] ?? null;
 
        if (! $tingkatan) {
            return null;
        }
 
        return $withSingkatan && $tingkatan['singkatan']
            ? "{$tingkatan['label']} ({$tingkatan['singkatan']})"
            : $tingkatan['label'];
    }
 
    public static function adaBentuk(?string $nilai): bool
    {
        return filled($nilai) && trim($nilai) !== '-';
    }
 
    public function tingkatanUtama(): ?string
    {
        foreach (self::tingkatanKeys() as $key) {
            if (self::adaBentuk($this->{$key})) {
                return $key;
            }
        }
 
        return null;
    }

    public function bentukUtama(?string $tingkatan = null): ?string
    {
        if ($tingkatan && self::adaBentuk($this->{$tingkatan} ?? null)) {
            return $this->{$tingkatan};
        }
 
        $utama = $this->tingkatanUtama();
 
        return $utama ? $this->{$utama} : null;
    }

    public static function daftarArti(?string $arti): array
    {
        $bagian = array_map(fn ($makna) => mb_strtolower(trim($makna)), explode(',', (string) $arti));

        return array_values(array_unique(array_filter($bagian, fn ($makna) => $makna !== '')));
    }

    public function sinonimSearti()
    {
        $arti = self::daftarArti($this->bahasa_indonesia);

        if ($arti === []) {
            return collect();
        }

        return self::query()
            ->whereKeyNot($this->getKey())
            ->where(function ($query) use ($arti) {
                foreach ($arti as $makna) {
                    $query->orWhere('bahasa_indonesia', 'like', '%' . addcslashes($makna, '%_\\') . '%');
                }
            })
            ->get()
            ->filter(fn (self $kata) => array_intersect($arti, self::daftarArti($kata->bahasa_indonesia)) !== [])
            ->sortBy(fn (self $kata) => mb_strtolower((string) $kata->bentukUtama()), SORT_NATURAL)
            ->values();
    }

    public function homonim(): BelongsToMany
    {
        return $this->relasi('homonim');
    }
 
    public static function daftarBentuk(?string $nilai): array
    {
        $bagian = array_map(fn ($bentuk) => mb_strtolower(trim($bentuk)), preg_split('/[,\/]/', (string) $nilai));

        return array_values(array_unique(array_filter($bagian, fn ($bentuk) => self::adaBentuk($bentuk))));
    }

    public function homonimSebentuk(?string $tingkatan = null)
    {
        $bentukDicari = self::daftarBentuk($this->bentukUtama($tingkatan));

        if ($bentukDicari === []) {
            return collect();
        }

        $katas = self::query()
            ->whereKeyNot($this->getKey())
            ->where(function ($query) use ($bentukDicari) {
                foreach (self::tingkatanKeys() as $kolom) {
                    foreach ($bentukDicari as $bentuk) {
                        $query->orWhere($kolom, 'like', '%' . addcslashes($bentuk, '%_\\') . '%');
                    }
                }
            })
            ->get();

        $hasil = [];

        foreach ($katas as $kata) {
            foreach (self::tingkatanKeys() as $kolom) {
                if (array_intersect($bentukDicari, self::daftarBentuk($kata->{$kolom})) !== []) {
                    $hasil[] = ['kata' => $kata, 'bentuk' => $kata->{$kolom}, 'tingkatan' => $kolom];
                }
            }
        }

        return collect($hasil)
            ->sortBy(fn ($item) => mb_strtolower($item['bentuk'] . ' ' . $item['kata']->bahasa_indonesia), SORT_NATURAL)
            ->values();
    }

    protected function relasi(string $tipe): BelongsToMany
    {
        return $this->belongsToMany(self::class, 'kata_relasi', 'kata_id', 'kata_terkait_id')
            ->withPivot(['id', 'tipe', 'tingkatan'])
            ->wherePivot('tipe', $tipe)
            ->withTimestamps();
    }
}
