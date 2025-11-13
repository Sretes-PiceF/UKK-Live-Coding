<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
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

        .register-container h2 {
            text-align: center;
            margin-bottom: 0.5rem;
            font-size: 1.8rem;
        }

        .register-container p {
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

        <form>
            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" placeholder="Masukan Nama Lengkap" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Masukan Email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukan Kata Sandi" required>
            </div>

            {{--
            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password</label>
                <input type="password" id="confirm_password" name="confirm_password"
                    placeholder="Masukan Konfirmasi Password" required>
            </div> --}}

            <button type="submit" class="register-btn">Register</button>

            <p class="login-text">
                Already have an account?
                <a href="{{route('login') }}">Login</a>
            </p>
        </form>
    </div>
</body>

</html>