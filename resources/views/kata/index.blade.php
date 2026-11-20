@extends('template')
@section('content')

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif

{{-- ===== Kepala halaman: judul di kiri, pencarian dan tombol di kanan (style.css bagian 4f) ===== --}}
<div class="ad-kepala">
    <div>
        <p class="ad-label">PANEL ADMIN</p>
        <h1 class="ad-judul">Pengelolaan Kata</h1>
    </div>

    <div class="ad-aksi">
        <!-- Baris 1: Kotak pencarian -->
        <form method="GET" action="{{ route('admin.kata.index') }}" class="ad-cari">
            <div class="ad-cari-kolom">
                <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari kata" aria-label="Cari kata">
                <i class="bi bi-search"></i>
            </div>
            <button type="submit" class="ad-btn ad-btn-utama">Cari</button>
        </form>

        <!-- Baris 2: Import CSV dan Tambah Kata -->
        <div class="ad-baris-tombol">
            <a href="{{ route('admin.kata.import.form') }}" class="ad-btn ad-btn-garis">
                <i class="bi bi-upload"></i> Import CSV
            </a>
            <button type="button" class="ad-btn ad-btn-lembut" data-bs-toggle="modal" data-bs-target="#tambahModal">
                Tambah Kata
            </button>
        </div>
    </div>
</div>

@php
    $columns = [
        'kruna_andap' => 'Kruna Andap',
        'kruna_asi' => 'Kruna Alus Singgih',
        'kruna_aso' => 'Kruna Alus Sor',
        'kruna_ami' => 'Kruna Alus Mider',
        'kruna_mider' => 'Kruna Mider',
        'kruna_kasar' => 'Kruna Kasar',
        'bahasa_indonesia' => 'Bahasa Indonesia',
    ];
@endphp

<div class="tabel-admin-wrap">
    <table class="tabel-admin">
        <thead>
            <tr>
                <th>No</th>
                @foreach ($columns as $field => $label)
                    @php
                        $nextDirection = ($sort === $field && $direction === 'asc') ? 'desc' : 'asc';
                    @endphp
                    <th>
                        <a href="{{ route('admin.kata.index', array_filter(['q' => $search, 'sort' => $field, 'direction' => $nextDirection])) }}">
                            {{ $label }}
                            @if ($sort === $field)
                                {{ $direction === 'asc' ? '▲' : '▼' }}
                            @endif
                        </a>
                    </th>
                @endforeach
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($katas as $index => $kata)
                <tr>
                    <td data-label="No">{{ $katas->firstItem() + $index }}</td>
                    <td data-label="Kruna Andap">{{ $kata->kruna_andap }}</td>
                    <td data-label="Kruna Alus Singgih">{{ $kata->kruna_asi }}</td>
                    <td data-label="Kruna Alus Sor">{{ $kata->kruna_aso }}</td>
                    <td data-label="Kruna Alus Mider">{{ $kata->kruna_ami }}</td>
                    <td data-label="Kruna Mider">{{ $kata->kruna_mider }}</td>
                    <td data-label="Kruna Kasar">{{ $kata->kruna_kasar }}</td>
                    <td data-label="Bahasa Indonesia">{{ $kata->bahasa_indonesia }}</td>
                    <td data-label="Aksi" class="kolom-aksi">
                        <div class="aksi">
                            {{-- Class edit-btn dipakai JavaScript di bawah; jangan dihapus --}}
                            <button type="button" class="edit-btn btn-edit-admin"
                                data-id="{{ $kata->id }}"
                                data-action="{{ route('admin.kata.update', $kata->id) }}"
                                data-kruna_andap="{{ $kata->kruna_andap }}"
                                data-kruna_asi="{{ $kata->kruna_asi }}"
                                data-kruna_aso="{{ $kata->kruna_aso }}"
                                data-kruna_ami="{{ $kata->kruna_ami }}"
                                data-kruna_mider="{{ $kata->kruna_mider }}"
                                data-kruna_kasar="{{ $kata->kruna_kasar }}"
                                data-bahasa_indonesia="{{ $kata->bahasa_indonesia }}">
                                Edit
                            </button>

                            <button type="button" class="btn-hapus-admin"
                                data-bs-toggle="modal"
                                data-bs-target="#hapusModal"
                                data-action="{{ route('admin.kata.destroy', $kata->id) }}"
                                data-nama="{{ $kata->bentukUtama() ?? $kata->kruna_andap }}">
                                Hapus
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Nomor halaman berbahasa Indonesia (menggantikan {{ $katas->links() }}) --}}
@include('partials.paginasi', ['halaman' => $katas, 'satuan' => 'baris kata'])

<!-- ===== Modal Tambah Kata ===== -->
<div class="modal fade modal-tambah-kata" id="tambahModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content p-2">

            <div class="modal-header position-relative">
                <h5 class="modal-title">Tambah Kata Baru</h5>
                <button type="button" class="btn-close position-absolute end-0 me-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Form ini akan mengirim data ke fungsi store di controller -->
            <form action="{{ route('admin.kata.store') }}" method="POST">
                @csrf

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tambah_kruna_andap" class="form-label">Kata Andap</label>
                        <input type="text" name="kruna_andap" id="tambah_kruna_andap" class="form-control" placeholder="masukan kata" required>
                    </div>
                    <div class="mb-3">
                        <label for="tambah_kruna_asi" class="form-label">Kata Alus Singgih (ASI)</label>
                        <input type="text" name="kruna_asi" id="tambah_kruna_asi" class="form-control" placeholder="masukan kata" required>
                    </div>
                    <div class="mb-3">
                        <label for="tambah_kruna_aso" class="form-label">Kata Alus Sor (ASO)</label>
                        <input type="text" name="kruna_aso" id="tambah_kruna_aso" class="form-control" placeholder="masukan kata" required>
                    </div>
                    <div class="mb-3">
                        <label for="tambah_kruna_ami" class="form-label">Kata Alus Mider (AMI)</label>
                        <input type="text" name="kruna_ami" id="tambah_kruna_ami" class="form-control" placeholder="masukan kata" required>
                    </div>
                    <div class="mb-3">
                        <label for="tambah_kruna_mider" class="form-label">Kata Mider</label>
                        <input type="text" name="kruna_mider" id="tambah_kruna_mider" class="form-control" placeholder="masukan kata" required>
                    </div>
                    <div class="mb-3">
                        <label for="tambah_kruna_kasar" class="form-label">Kasar</label>
                        <input type="text" name="kruna_kasar" id="tambah_kruna_kasar" class="form-control" placeholder="masukan kata" required>
                    </div>
                    <div class="mb-3">
                        <label for="tambah_bahasa_indonesia" class="form-label">Bahasa Indonesia</label>
                        <textarea name="bahasa_indonesia" id="tambah_bahasa_indonesia" class="form-control" rows="3" placeholder="masukan kata" required></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-batal" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-simpan">Simpan</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- ===== Modal Edit ===== -->
<div class="modal fade modal-tambah-kata" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content p-2">

            <div class="modal-header position-relative">
                <h5 class="modal-title">Edit Kata</h5>
                <button type="button" class="btn-close position-absolute end-0 me-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- HANYA BOLEH ADA SATU TAG FORM -->
            <form method="POST" id="editForm">
                @csrf
                @method('PUT')

                <input type="hidden" name="kata_id" id="edit_kata_id">

                <div class="modal-body">

                    <div class="mb-3">
                        <label for="edit_kruna_andap" class="form-label">Kata Andap</label>
                        <input type="text" name="kruna_andap" id="edit_kruna_andap" class="form-control">
                        @error('kruna_andap') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="edit_kruna_asi" class="form-label">Kata Alus Singgih (ASI)</label>
                        <input type="text" name="kruna_asi" id="edit_kruna_asi" class="form-control">
                        @error('kruna_asi') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="edit_kruna_aso" class="form-label">Kata Alus Sor (ASO)</label>
                        <input type="text" name="kruna_aso" id="edit_kruna_aso" class="form-control">
                        @error('kruna_aso') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="edit_kruna_ami" class="form-label">Kata Alus Mider (AMI)</label>
                        <input type="text" name="kruna_ami" id="edit_kruna_ami" class="form-control">
                        @error('kruna_ami') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="edit_kruna_mider" class="form-label">Kata Mider</label>
                        <input type="text" name="kruna_mider" id="edit_kruna_mider" class="form-control">
                        @error('kruna_mider') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="edit_kruna_kasar" class="form-label">Kasar</label>
                        <input type="text" name="kruna_kasar" id="edit_kruna_kasar" class="form-control">
                        @error('kruna_kasar') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="edit_bahasa_indonesia" class="form-label">Bahasa Indonesia</label>
                        <textarea name="bahasa_indonesia" id="edit_bahasa_indonesia" class="form-control" rows="4"></textarea>
                        @error('bahasa_indonesia') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-batal" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-simpan">Simpan Perubahan</button>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- ===== Modal Hapus ===== -->
<div class="modal fade modal-hapus" id="hapusModal" tabindex="-1" aria-labelledby="hapusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form method="POST" id="hapusForm">
                @csrf
                @method('DELETE')

                <div class="modal-body">
                    <p class="hapus-teks" id="hapusModalLabel">
                        Apakah anda yakin menghapus kata “<span id="hapus_nama_kata"></span>” beserta tingkatannya?
                    </p>

                    <div class="hapus-aksi">
                        <button type="submit" class="btn-hapus-ya">Hapus</button>
                        <button type="button" class="btn-hapus-batal" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- Script: isi modal Edit sesuai baris yang diklik -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.body.addEventListener('click', function(e) {
            const btn = e.target.closest('.edit-btn');
            if (btn) {
                const data = btn.dataset;
                const editForm = document.getElementById('editForm');

                editForm.action = data.action;
                document.getElementById('edit_kata_id').value = data.id || '';
                document.getElementById('edit_kruna_andap').value = data.kruna_andap || '';
                document.getElementById('edit_kruna_asi').value = data.kruna_asi || '';
                document.getElementById('edit_kruna_aso').value = data.kruna_aso || '';
                document.getElementById('edit_kruna_ami').value = data.kruna_ami || '';
                document.getElementById('edit_kruna_mider').value = data.kruna_mider || '';
                document.getElementById('edit_kruna_kasar').value = data.kruna_kasar || '';
                document.getElementById('edit_bahasa_indonesia').value = data.bahasa_indonesia || '';

                const editModalEl = document.getElementById('editModal');
                const modalInstance = bootstrap.Modal.getOrCreateInstance(editModalEl);
                modalInstance.show();
            }
        });
    });
</script>

<!-- Script: isi modal Hapus sesuai baris yang diklik -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const hapusModalEl = document.getElementById('hapusModal');

        hapusModalEl.addEventListener('show.bs.modal', function (event) {
            const tombol = event.relatedTarget;

            document.getElementById('hapus_nama_kata').textContent = tombol.dataset.nama || '';
            document.getElementById('hapusForm').action = tombol.dataset.action;
        });
    });
</script>
@endsection