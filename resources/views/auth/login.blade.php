<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Login - Sistema de Gestão ZALALA BEACH BAR">
    <title>Login - ZALALA BEACH BAR</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #0891b2;
            --primary-dark: #0e7490;
            --secondary: #f59e0b;
            --beach-gradient: linear-gradient(135deg, #0891b2 0%, #06b6d4 50%, #fbbf24 100%);
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 50%, #fef3c7 100%);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(8, 145, 178, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(245, 158, 11, 0.05) 0%, transparent 50%);
            z-index: 0;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 25px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            padding: 2.5rem;
            max-width: 450px;
            width: 95%;
            position: relative;
            z-index: 2;
            border: 1px solid rgba(14, 165, 233, 0.1);
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .logo {
            width: 100px;
            height: 100px;
            margin: 0 auto 1.5rem;
            border-radius: 50%;
            background: var(--beach-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid white;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden; /* garante que a imagem fique dentro do círculo */
        }

        .logo img {
            width: 100%;    /* ocupa toda a div */
            height: 100%;   /* ocupa toda a div */
            object-fit: cover; /* cobre a área mantendo proporção */
}

        
        .login-header h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .login-header p {
            color: #6b7280;
            font-size: 1rem;
        }
        
        .form-control {
            border-radius: 15px;
            padding: 0.875rem 1.25rem;
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
            font-size: 1rem;
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(8, 145, 178, 0.15);
        }
        
        .btn-login {
            background: var(--beach-gradient);
            border: none;
            border-radius: 15px;
            padding: 0.875rem 1.25rem;
            font-weight: 600;
            font-size: 1.1rem;
            color: white;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(8, 145, 178, 0.3);
        }
        
        .forgot-password {
            text-align: center;
            margin-top: 1.5rem;
        }
        
        .forgot-password a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .forgot-password a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        .alert {
            border-radius: 15px;
            border: none;
            padding: 0.875rem 1.25rem;
        }
        
        .beach-decoration {
            position: absolute;
            font-size: 3rem;
            opacity: 0.1;
            z-index: 1;
        }
        
        .wave-1 {
            top: 10%;
            left: 5%;
            transform: rotate(-15deg);
        }
        
        .wave-2 {
            bottom: 15%;
            right: 8%;
            transform: rotate(12deg);
        }
        
        @media (max-width: 576px) {
            .login-container {
                padding: 2rem;
                border-radius: 20px;
            }
            
            .logo {
                width: 80px;
                height: 80px;
            }
            
            .logo img {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="beach-decoration wave-1">🌊</div>
    <div class="beach-decoration wave-2">🌴</div>
    
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="login-container">
            <div class="login-header">
                <div class="logo">
                    <img src="{{ asset('assets/images/logo-zalala.png') }}" alt="ZALALA BEACH BAR Logo">
                </div>
                <h2>ZALALA BEACH BAR</h2>
                <p>Acesse o sistema de gestão</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ $errors->first() }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <input type="text" 
                           class="form-control" 
                           name="username" 
                           placeholder="Nome de usuário ou email" 
                           value="{{ old('username') }}" 
                           required 
                           autofocus>
                </div>
                <div class="mb-4">
                    <input type="password" 
                           class="form-control" 
                           name="password" 
                           placeholder="Senha" 
                           required>
                </div>
                <button type="submit" class="btn btn-login">
                    Entrar no Sistema
                </button>
            </form>

            <div class="forgot-password">
                <a href="#" onclick="alert('Contate o administrador para recuperação de senha.'); return false;">
                    Esqueceu sua senha?
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>