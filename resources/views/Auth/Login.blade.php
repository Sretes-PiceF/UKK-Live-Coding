<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Page</title>
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

        /* .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #cbd5e1;
            margin-top: 5px;
        } */

        /* .forgot-password {
            display: block;
            text-align: right;
            font-size: 14px;
            color: #818cf8;
            text-decoration: none;
            margin-top: 5px;
        } */
        /* 
        .forgot-password:hover {
            text-decoration: underline;
        } */

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
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Selamat Datang</h2>
        <p>Silahkan jika sudah memiliki akun</p>

        <form>
            <div class="form-group">
                <label for="name">Nama Pengguna</label>
                <input type="name" id="username" name="username" placeholder="Masukan Nama Pengguna" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukan Kata Sandi" required>
            </div>

            <button type="submit" class="login-btn">Log in</button>

            <p class="signup-text">
                Don't have an account?
                <a href="register">Sign Up</a>
            </p>
        </form>
    </div>
</body>

</html>