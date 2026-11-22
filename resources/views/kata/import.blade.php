@extends('template')

@section('content')

    <div class="import-wrap">
        <div class="import-card">
            <div class="import-head">
                <h1>Import Kata dari CSV</h1>
            </div>

            <div class="import-body">
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                <p class="import-note mb-2">
                    Baris pertama adalah header, baris berikutnya satu kata per baris, dengan urutan kolom:
                </p>
                <div class="format-box mb-3">
                    <code>kruna_andap,kruna_asi,kruna_aso,kruna_ami,kruna_mider,kruna_kasar,bahasa_indonesia</code>
                </div>

                <form method="POST" action="{{ route('admin.kata.import') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="file" class="form-label fw-semibold">File CSV</label>
                        <input type="file" name="file" id="file"
                               class="form-control @error('file') is-invalid @enderror"
                               accept=".csv,text/csv">
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn-import-admin">Import</button>
                        <a href="{{ route('admin.kata.index') }}" class="btn-kembali-admin">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection