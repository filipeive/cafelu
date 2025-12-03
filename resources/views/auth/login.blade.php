<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - Lu & Yosh Catering</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('{{ asset('assets/images/restaurant-bg.jpeg') }}') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
        }
        .login-container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 400px;
            width: 90%;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 30px;
        }
        .form-control {
            border-radius: 25px;
            padding: 12px 20px;
        }
        .btn-login {
            border-radius: 25px;
            padding: 12px 20px;
            font-weight: 600;
            background-color: #ff6b6b;
            border: none;
            transition: all 0.3s;
        }
        .btn-login:hover {
            background-color: #ff5252;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="login-container text-center">
        <img src="{{ asset('assets/images/Logo.png') }}" alt="Lu & Yosh Catering" class="logo">
        <h2 class="mb-4"><strong>ZALALABB</strong><strong style="color: #ff6b6b"> - POS</strong></h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="text-start">
            @csrf
            <div class="mb-3">
                <input type="text" class="form-control" name="username" placeholder="Usuário" value="{{ old('username') }}" required autofocus>
            </div>
            <div class="mb-4">
                <input type="password" class="form-control" name="password" placeholder="Senha" required>
            </div>
            <button type="submit" class="btn btn-primary btn-login w-100">Entrar</button>
        </form>

        <p class="mt-4 mb-0">Esqueceu sua senha? <a href="#">Clique aqui</a></p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
