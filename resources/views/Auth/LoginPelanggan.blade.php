<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pelanggan</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #0f172a;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background-color: #1e293b;
            border: 1px solid #6366f1;
            border-radius: 12px;
            padding: 2rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.2);
            color: white;
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 0.5rem;
            font-size: 1.8rem;
        }

        .login-container p {
            text-align: center;
            color: #9ca3af;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #cbd5e1;
        }

        .form-group input {
            width: 100%;
            padding: 10px 12px;
            border-radius: 6px;
            border: 1px solid #475569;
            background-color: #334155;
            color: white;
            font-size: 14px;
        }

        .form-group input:focus {
            border-color: #6366f1;
            outline: none;
        }

        .login-btn {
            width: 100%;
            background: linear-gradient(to right, #6366f1, #8b5cf6);
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            padding: 10px;
            margin-top: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .login-btn:hover {
            background: linear-gradient(to right, #4f46e5, #7c3aed);
        }

        .signup-text {
            text-align: center;
            color: #9ca3af;
            margin-top: 1rem;
            font-size: 14px;
        }

        .signup-text a {
            color: #818cf8;
            text-decoration: none;
        }

        .signup-text a:hover {
            text-decoration: underline;
        }

        .alert {
            background-color: #dc2626;
            padding: 10px;
            color: white;
            text-align: center;
            border-radius: 6px;
            margin-bottom: 1rem;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Selamat Datang Pelanggan</h2>
        <p>Silakan masuk menggunakan akun Anda</p>

        {{-- Pesan Error --}}
        @if(session('error'))
            <div class="alert">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('action.login') }}" method="POST">
            @csrf
            @method('POST')
            <div class="form-group">
                <label for="nama_pelanggan">Nama Pengguna</label>
                <input type="text" id="nama_pelanggan" name="nama_pelanggan" placeholder="Masukkan Nama Pengguna">
                @error('nama_pelanggan')
                    <small style="color: #fca5a5;">{{ $message }}</small>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Kata Sandi</label>
                <input type="password" id="password" name="password" placeholder="Masukkan Kata Sandi">
                @error('password')
                    <small style="color: #fca5a5;">{{ $message }}</small>
                @enderror
            </div>

            <button type="submit" class="login-btn">Log in</button>

            <p class="signup-text">
                Belum punya akun?
                <a href="{{ route('register') }}">Daftar</a>
            </p>
        </form>
    </div>
</body>

</html>