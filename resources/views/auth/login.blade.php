<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Rumah Baca</title>

@vite(['resources/css/app.css', 'resources/js/app.js'])

<style>
body {
    background: linear-gradient(135deg, #1e3a8a 0%, #eff6ff 100%);
    font-family: 'Inter', sans-serif;
    min-height: 100vh;
}

.login-card {
    background: white;
    max-width: 420px;
    margin: 80px auto;
    padding: 40px;
    border-radius: 24px;
    box-shadow: 0 20px 40px rgba(30, 58, 138, 0.15);
    transition: 0.3s ease;
}

.login-card:hover {
    transform: translateY(-3px);
}

.logo h1 {
    text-align: center;
    font-weight: 800;
    font-size: 28px;
    color: #1e3a8a;
    letter-spacing: -0.025em;
}

.welcome-text {
    text-align: center;
    margin-top: 12px;
    margin-bottom: 28px;
}

.welcome-text h2 {
    font-size: 22px;
    font-weight: 700;
    color: #1e293b;
}

.welcome-text p {
    color: #6b7280;
    font-size: 14px;
}

.form-group {
    margin-bottom: 18px;
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
    border-color: #2563eb;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
}

.remember-forgot {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13px;
    margin-bottom: 20px;
    color: #374151;
}

.btn-login {
    width: 100%;
    background: #1e3a8a;
    color: white;
    padding: 14px;
    border-radius: 14px;
    font-weight: 700;
    transition: all 0.25s;
    box-shadow: 0 10px 20px rgba(30, 58, 138, 0.2);
}

.btn-login:hover {
    background: #1e40af;
    transform: translateY(-2px);
    box-shadow: 0 15px 25px rgba(30, 58, 138, 0.3);
}

.register-link {
    text-align: center;
    font-size: 14px;
    margin-top: 20px;
}

.register-link a {
    color: #2563eb;
    font-weight: 700;
    text-decoration: none;
}

.register-link a:hover {
    text-decoration: underline;
}

.error-message {
    font-size: 12px;
    color: red;
}
</style>

</head>
<body>

<div class="login-card">

    <div class="logo">
        <h1 style="display:flex;align-items:center;justify-content:center;gap:8px;"><svg style="width:28px;height:28px;color:#1e3a8a;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg> Rumah Baca</h1>
    </div>

    <div class="welcome-text">
        <h2>Selamat Datang Kembali</h2>
        <p>Masuk untuk melanjutkan peminjaman buku</p>
    </div>

    @if (session('status'))
        <div class="text-green-600 text-sm text-center mb-4">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        
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

        <div class="remember-forgot">
            <label>
                <input type="checkbox" name="remember"> Remember me
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="font-semibold">Forgot Password?</a>
            @endif
        </div>

        <button type="submit" class="btn-login">
            Login
        </button>
    </form>

    <div class="register-link">
        Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
    </div>

</div>

</body>
</html>
