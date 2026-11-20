<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lupa Kata Sandi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container" style="max-width:420px; margin-top:80px;">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h4 class="mb-3" style="color:#6E491C;">Lupa Kata Sandi</h4>
            <p class="text-muted small">Masukkan Email admin. Kami akan mengirim tautan untuk membuat kata sandi baru.</p>

            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror" required autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn w-100 text-white" style="background:#6E491C;">Kirim Tautan Reset</button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="link-kembali-login">Kembali ke login</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>