<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
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

        .register-container {
            background-color: #1e293b;
            border: 1px solid #0ea5e9;
            border-radius: 12px;
            padding: 2rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 0 20px rgba(14, 165, 233, 0.2);
            color: white;
        }

        h2 {
            text-align: center;
            margin-bottom: 0.5rem;
            font-size: 1.8rem;
        }

        p {
            text-align: center;
            color: #9ca3af;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #cbd5e1;
        }

        input {
            width: 100%;
            padding: 10px 12px;
            border-radius: 6px;
            border: 1px solid #475569;
            background-color: #334155;
            color: white;
            font-size: 14px;
        }

        input:focus {
            border-color: #0ea5e9;
            outline: none;
        }

        .register-btn {
            width: 100%;
            background: linear-gradient(to right, #14b8a6, #0284c7);
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            padding: 10px;
            margin-top: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .register-btn:hover {
            background: linear-gradient(to right, #0d9488, #0369a1);
        }

        .alert {
            border-radius: 6px;
            padding: 10px 15px;
            margin-bottom: 1rem;
        }

        .alert-success {
            background-color: #16a34a;
            color: white;
        }

        .alert-danger {
            background-color: #dc2626;
            color: white;
        }

        .login-text {
            text-align: center;
            color: #9ca3af;
            margin-top: 1rem;
            font-size: 14px;
        }

        .login-text a {
            color: #818cf8;
            text-decoration: none;
        }

        .login-text a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <h2>Join Us Today!</h2>
        <p>Create your account</p>

        {{-- ✅ Pesan sukses --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- ✅ Pesan error --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('action.register-pelanggan') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="nama_pelanggan">Nama Pengguna</label>
                <input type="text" id="nama_pelanggan" name="nama_pelanggan" placeholder="Masukan Nama Pengguna Baru"
                    value="{{ old('nama_pelanggan') }}">
            </div>

            <div class="form-group">
                <label for="alamat">Alamat</label>
                <input type="text" id="alamat" name="alamat" placeholder="Masukan Alamat" value="{{ old('alamat') }}">
            </div>

            <div class="form-group">
                <label for="no_kwh">No Kwh</label>
                <input type="number" id="no_kwh" name="no_kwh" placeholder="Masukan Nomor KWH"
                    value="{{ old('no_kwh') }}">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukan Kata Sandi">
            </div>

            <button type="submit" class="register-btn">Register</button>

            <p class="login-text">
                Already have an account?
                <a href="{{ route('login.pelanggan') }}">Login</a>
            </p>
        </form>
    </div>
</body>

</html>