<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - Rumah Baca</title>

@vite(['resources/css/app.css', 'resources/js/app.js'])

<style>
body {
    background: linear-gradient(to bottom right, #1f7a8c, #bfdbf7);
    font-family: 'Inter', sans-serif;
}

.register-card {
    background: white;
    max-width: 460px;
    margin: 80px auto;
    padding: 40px;
    border-radius: 24px;
    box-shadow: 0 20px 40px #05668d;
    transition: 0.3s ease;
}

.register-card:hover {
    transform: translateY(-3px);
}

.logo h1 {
    text-align: center;
    font-weight: 800;
    font-size: 28px;
    color: #05668d;
}

.welcome-text {
    text-align: center;
    margin-top: 12px;
    margin-bottom: 28px;
}

.welcome-text h2 {
    font-size: 22px;
    font-weight: 700;
}

.welcome-text p {
    color: #6b7280;
    font-size: 14px;
}

.form-group {
    margin-bottom: 16px;
}

.form-group label {
    font-size: 14px;
    font-weight: 600;
    display: block;
    margin-bottom: 6px;
}

.form-group input {
    width: 100%;
    padding: 12px 14px;
    border-radius: 12px;
    border: 1px solid #e5e7eb;
    transition: 0.2s ease;
}

.form-group input:focus {
    outline: none;
    border-color: #05668d;
    box-shadow: 0 0 0 3px rgba(5, 102, 141, 0.2);
}

.btn-register {
    width: 100%;
    background: #05668d;
    color: white;
    padding: 12px;
    border-radius: 14px;
    font-weight: 700;
    transition: 0.25s;
    margin-top: 8px;
}

.btn-register:hover {
    background: #034078;
    transform: scale(1.02);
}

.login-link {
    text-align: center;
    font-size: 14px;
    margin-top: 20px;
}

.login-link a {
    color: #034078;
    font-weight: 600;
}

.error-message {
    font-size: 12px;
    color: red;
}
</style>
</head>

<body>

<div class="register-card">

    <div class="logo">
        <h1>📚 Rumah Baca</h1>
    </div>

    <div class="welcome-text">
        <h2>Buat Akun Baru</h2>
        <p>Daftar untuk mulai meminjam buku</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-group">
            <label for="name">Nama Lengkap</label>
            <input type="text" id="name" name="name" placeholder="Nama lengkap" required value="{{ old('name') }}">
            @error('name')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="username" required value="{{ old('username') }}">
            @error('username')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="alamat">Alamat</label>
            <input type="text" id="alamat" name="alamat" placeholder="Alamat rumah" required value="{{ old('alamat') }}">
            @error('alamat')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="email@example.com" required value="{{ old('email') }}">
            @error('email')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="••••••••" required>
            @error('password')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Konfirmasi Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn-register">
            Daftar
        </button>
    </form>

    <div class="login-link">
        Sudah punya akun? <a href="{{ route('login') }}">Login sekarang</a>
    </div>

</div>

</body>
</html>
