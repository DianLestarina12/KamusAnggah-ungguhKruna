@extends('template')
@section('content')


<div class="login-split">

    <div class="login-merek">
        <p class="login-merek-label">PANEL ADMIN</p>
        <h1 class="login-merek-judul">Kamus Anggah-Ungguh Kruna Basa Bali</h1>
        <p class="login-merek-teks">Kelola kata dan soal kuis dari satu tempat.</p>
    </div>

    <div class="login-form-wrap">
        <form class="login-form" action="{{ route('login.proses') }}" method="POST">
            @csrf

            <h2 class="login-judul">Admin</h2>

            @if ($errors->any())
                <div class="login-error" role="alert">{{ $errors->first() }}</div>
            @endif

            <!-- Input Email -->
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan Email"
                   required autocomplete="email" class="login-input">

            <div class="login-sandi">
                <input type="password" name="password" id="passwordInput" placeholder="Kata Sandi"
                       required autocomplete="current-password" class="login-input">
                <button type="button" class="login-mata" onclick="togglePassword()"
                        aria-label="Tampilkan atau sembunyikan kata sandi">
                    <i class="bi bi-eye-slash-fill" id="toggleIcon"></i>
                </button>
            </div>

            <div class="login-lupa">
                <a href="{{ route('password.request') }}">Lupa kata sandi?</a>
            </div>

            <button type="submit" class="login-submit">Masuk</button>
        </form>
    </div>

</div>

<script>
    function togglePassword() {
        var passwordInput = document.getElementById("passwordInput");
        var toggleIcon = document.getElementById("toggleIcon");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleIcon.classList.remove("bi-eye-slash-fill");
            toggleIcon.classList.add("bi-eye-fill");
        } else {
            passwordInput.type = "password";
            toggleIcon.classList.remove("bi-eye-fill");
            toggleIcon.classList.add("bi-eye-slash-fill");
        }
    }
</script>

@endsection