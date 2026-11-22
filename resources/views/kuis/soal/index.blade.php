@extends('template')
@section('content')
    <div class="">
        <div class="ad-kepala">
            <div>
                <p class="ad-label">PANEL ADMIN</p>
                <h1 class="ad-judul">Pengelolaan Kuis</h1>
            </div>

            <div class="ad-aksi">
                <button type="button" class="ad-btn ad-btn-lembut"
                    data-bs-toggle="modal"
                    data-bs-target="#tambahSoalModal">
                    Tambah Soal
                </button>
            </div>
        </div>

        <div class="tabel-admin-wrap">
            <table class="tabel-admin tabel-admin-lebar">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Soal</th>
                        <th>Jawaban</th>
                        <th>Opsi 1</th>
                        <th>Opsi 2</th>
                        <th>Opsi 3</th>
                        <th>Opsi 4</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($soals as $soal)
                        <tr>
                            <td data-label="No">{{ $soals->firstItem() + $loop->index }}</td>
                            <td data-label="Soal" class="kolom-soal">{{ $soal->soal }}</td>
                            <td data-label="Jawaban">{{ $soal->jawaban }}</td>
                            <td data-label="Opsi 1">{{ $soal->opsi1 }}</td>
                            <td data-label="Opsi 2">{{ $soal->opsi2 }}</td>
                            <td data-label="Opsi 3">{{ $soal->opsi3 }}</td>
                            <td data-label="Opsi 4">{{ $soal->opsi4 }}</td>
                            <td data-label="Aksi" class="kolom-aksi">
                                <div class="aksi">
                                    <button type="button" class="btn-edit-admin"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editSoalModal"
                                        data-action="{{ route('soal.update', $soal->id) }}"
                                        data-id="{{ $soal->id }}"
                                        data-soal="{{ $soal->soal }}"
                                        data-jawaban="{{ $soal->jawaban }}"
                                        data-opsi1="{{ $soal->opsi1 }}"
                                        data-opsi2="{{ $soal->opsi2 }}"
                                        data-opsi3="{{ $soal->opsi3 }}"
                                        data-opsi4="{{ $soal->opsi4 }}">
                                        Edit
                                    </button>

                                    <button type="button" class="btn-hapus-admin"
                                        data-bs-toggle="modal"
                                        data-bs-target="#hapusSoalModal"
                                        data-action="{{ route('soal.destroy', $soal->id) }}"
                                        data-nama="{{ \Illuminate\Support\Str::limit($soal->soal, 120) }}">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @include('partials.paginasi', ['halaman' => $soals, 'satuan' => 'soal'])
    </div>

<div class="modal fade modal-tambah-kata" id="editSoalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content p-2">

            <div class="modal-header position-relative">
                <h5 class="modal-title">Edit Soal</h5>
                <button type="button" class="btn-close position-absolute end-0 me-3" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <form method="POST" id="editSoalForm"
                  action="{{ old('_edit_id') ? route('soal.update', old('_edit_id')) : '' }}">
                @csrf
                @method('PUT')

                <input type="hidden" name="_edit_id" id="edit_soal_id" value="{{ old('_edit_id') }}">

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_soal" class="form-label">Soal</label>
                        <textarea name="soal" id="edit_soal" class="form-control" rows="3">{{ old('soal') }}</textarea>
                        @error('soal') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="edit_opsi1" class="form-label">Opsi 1</label>
                        <input type="text" name="opsi1" id="edit_opsi1" class="form-control" value="{{ old('opsi1') }}">
                        @error('opsi1') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="edit_opsi2" class="form-label">Opsi 2</label>
                        <input type="text" name="opsi2" id="edit_opsi2" class="form-control" value="{{ old('opsi2') }}">
                        @error('opsi2') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="edit_opsi3" class="form-label">Opsi 3</label>
                        <input type="text" name="opsi3" id="edit_opsi3" class="form-control" value="{{ old('opsi3') }}">
                        @error('opsi3') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="edit_opsi4" class="form-label">Opsi 4</label>
                        <input type="text" name="opsi4" id="edit_opsi4" class="form-control" value="{{ old('opsi4') }}">
                        @error('opsi4') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="edit_jawaban" class="form-label">Jawaban</label>
                        <input type="text" name="jawaban" id="edit_jawaban" class="form-control" value="{{ old('jawaban') }}">
                        <small class="text-muted">Harus sama persis dengan salah satu opsi di atas.</small>
                        @error('jawaban') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
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

<div class="modal fade modal-hapus" id="hapusSoalModal" tabindex="-1" aria-labelledby="hapusSoalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form method="POST" id="hapusSoalForm">
                @csrf
                @method('DELETE')

                <div class="modal-body">
                    <p class="hapus-teks" id="hapusSoalLabel">
                        Apakah anda yakin menghapus soal “<span id="hapus_nama_soal"></span>” ini?
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modalEl = document.getElementById('hapusSoalModal');

        modalEl.addEventListener('show.bs.modal', function (event) {
            const tombol = event.relatedTarget;

            document.getElementById('hapus_nama_soal').textContent = tombol.dataset.nama || '';
            document.getElementById('hapusSoalForm').action = tombol.dataset.action;
        });
    });
</script>

<div class="modal fade modal-tambah-kata" id="tambahSoalModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content p-2">

            <div class="modal-header position-relative">
                <h5 class="modal-title">Tambah Soal Baru</h5>
                <button type="button" class="btn-close position-absolute end-0 me-3" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>

            <form action="{{ route('soal.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_tambah" value="1">

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="tambah_soal" class="form-label">Soal</label>
                        <textarea name="soal" id="tambah_soal" class="form-control" rows="3" placeholder="masukan soal">{{ old('soal') }}</textarea>
                        @error('soal') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tambah_opsi1" class="form-label">Opsi 1</label>
                        <input type="text" name="opsi1" id="tambah_opsi1" class="form-control" placeholder="masukan opsi" value="{{ old('opsi1') }}">
                        @error('opsi1') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tambah_opsi2" class="form-label">Opsi 2</label>
                        <input type="text" name="opsi2" id="tambah_opsi2" class="form-control" placeholder="masukan opsi" value="{{ old('opsi2') }}">
                        @error('opsi2') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tambah_opsi3" class="form-label">Opsi 3</label>
                        <input type="text" name="opsi3" id="tambah_opsi3" class="form-control" placeholder="masukan opsi" value="{{ old('opsi3') }}">
                        @error('opsi3') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tambah_opsi4" class="form-label">Opsi 4</label>
                        <input type="text" name="opsi4" id="tambah_opsi4" class="form-control" placeholder="masukan opsi" value="{{ old('opsi4') }}">
                        @error('opsi4') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tambah_jawaban" class="form-label">Jawaban</label>
                        <input type="text" name="jawaban" id="tambah_jawaban" class="form-control" placeholder="masukan jawaban" value="{{ old('jawaban') }}">
                        <small class="text-muted">Harus sama persis dengan salah satu opsi di atas.</small>
                        @error('jawaban') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tambahEl = document.getElementById('tambahSoalModal');
        const editEl   = document.getElementById('editSoalModal');
        const hapusEl  = document.getElementById('hapusSoalModal');

        tambahEl.addEventListener('show.bs.modal', function (event) {
            if (!event.relatedTarget) return;

            tambahEl.querySelectorAll('input[type=text], textarea').forEach(el => el.value = '');
            tambahEl.querySelectorAll('.text-danger').forEach(el => el.remove());
        });

        editEl.addEventListener('show.bs.modal', function (event) {
            const tombol = event.relatedTarget;
            if (!tombol) return;

            const d = tombol.dataset;
            document.getElementById('editSoalForm').action = d.action;
            document.getElementById('edit_soal_id').value  = d.id || '';
            document.getElementById('edit_soal').value     = d.soal || '';
            document.getElementById('edit_opsi1').value    = d.opsi1 || '';
            document.getElementById('edit_opsi2').value    = d.opsi2 || '';
            document.getElementById('edit_opsi3').value    = d.opsi3 || '';
            document.getElementById('edit_opsi4').value    = d.opsi4 || '';
            document.getElementById('edit_jawaban').value  = d.jawaban || '';
        });

        hapusEl.addEventListener('show.bs.modal', function (event) {
            document.getElementById('hapusSoalForm').action = event.relatedTarget.dataset.action;
        });

        @if ($errors->any() && old('_tambah'))
            bootstrap.Modal.getOrCreateInstance(tambahEl).show();
        @elseif ($errors->any() && old('_edit_id'))
            bootstrap.Modal.getOrCreateInstance(editEl).show();
        @endif
    });
</script>

@endsection