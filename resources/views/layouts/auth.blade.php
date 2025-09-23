<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Sistema de Gestão Gastronômica - CaféLufamina POS">
    
    <title>{{ config('app.name', 'CaféLufamina POS') }} - @yield('title', 'Autenticação')</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Icons -->
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #FF6B35;
            --secondary-color: #2C3E50;
            --success-color: #27AE60;
            --warning-color: #F39C12;
            --danger-color: #E74C3C;
            --info-color: #3498DB;
            --dark-color: #1A1A1A;
            --light-color: #F8F9FA;
            --primary-gradient: linear-gradient(135deg, #FF6B35 0%, #FF8A56 100%);
            --secondary-gradient: linear-gradient(135deg, #2C3E50 0%, #34495E 100%);
            --shadow-soft: 0 8px 25px rgba(0, 0, 0, 0.08);
            --shadow-strong: 0 15px 35px rgba(0, 0, 0, 0.12);
            --border-radius: 16px;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        /* Animated Background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 80%, rgba(255, 107, 53, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(52, 152, 219, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(39, 174, 96, 0.1) 0%, transparent 50%);
            animation: backgroundMove 20s ease-in-out infinite;
            z-index: -1;
        }

        @keyframes backgroundMove {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(30px, -30px) rotate(120deg); }
            66% { transform: translate(-20px, 20px) rotate(240deg); }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInFromLeft {
            from { opacity: 0; transform: translateX(-50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes slideInFromRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        /* Main Container */
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        /* Auth Card */
        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-strong);
            width: 100%;
            max-width: 900px;
            overflow: hidden;
            animation: fadeIn 0.8s ease-out;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Two Column Layout */
        .auth-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 600px;
        }

        @media (max-width: 768px) {
            .auth-content {
                grid-template-columns: 1fr;
            }
            
            .auth-visual {
                display: none;
            }
        }

        /* Left Visual Section */
        .auth-visual {
            background: var(--primary-gradient);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem 2rem;
            color: white;
            text-align: center;
        }

        .auth-visual::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="20" cy="80" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="80" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
            background-size: 50px 50px;
            opacity: 0.3;
        }

        .visual-content {
            position: relative;
            z-index: 1;
        }

        .visual-logo {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            animation: float 6s ease-in-out infinite;
        }

        .visual-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            animation: slideInFromLeft 0.8s ease-out 0.3s both;
        }

        .visual-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.6;
            animation: slideInFromLeft 0.8s ease-out 0.5s both;
        }

        .visual-features {
            margin-top: 2rem;
            animation: slideInFromLeft 0.8s ease-out 0.7s both;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            font-size: 0.95rem;
        }

        .feature-item i {
            margin-right: 0.75rem;
            font-size: 1.2rem;
            opacity: 0.8;
        }

        /* Right Form Section */
        .auth-form {
            padding: 3rem 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            text-align: center;
            margin-bottom: 2rem;
            animation: slideInFromRight 0.8s ease-out 0.3s both;
        }

        .form-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            color: #6c757d;
            font-size: 0.95rem;
        }

        /* Form Styling */
        .form-group {
            margin-bottom: 1.5rem;
            animation: slideInFromRight 0.8s ease-out 0.5s both;
        }

        .form-label {
            font-weight: 500;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: var(--transition);
            background: rgba(255, 255, 255, 0.8);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.15);
            background: white;
            transform: scale(1.02);
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            z-index: 5;
        }

        .form-control.with-icon {
            padding-left: 2.75rem;
        }

        .password-toggle {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            z-index: 5;
            transition: var(--transition);
        }

        .password-toggle:hover {
            color: var(--primary-color);
        }

        /* Button Styling */
        .btn {
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            color: white;
            font-size: 1rem;
            animation: slideInFromRight 0.8s ease-out 0.7s both;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        /* Social Login */
        .social-login {
            margin-top: 1.5rem;
            animation: slideInFromRight 0.8s ease-out 0.9s both;
        }

        .social-divider {
            position: relative;
            text-align: center;
            margin: 1.5rem 0;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .social-divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e9ecef;
            z-index: 1;
        }

        .social-divider span {
            background: white;
            padding: 0 1rem;
            position: relative;
            z-index: 2;
        }

        .btn-social {
            width: 100%;
            margin-bottom: 0.75rem;
            border: 2px solid #e9ecef;
            background: white;
            color: #495057;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .btn-social:hover {
            border-color: var(--primary-color);
            background: rgba(255, 107, 53, 0.05);
            transform: translateY(-1px);
        }

        /* Links */
        .auth-links {
            text-align: center;
            margin-top: 1.5rem;
            animation: slideInFromRight 0.8s ease-out 1.1s both;
        }

        .auth-links a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
        }

        .auth-links a:hover {
            color: var(--secondary-color);
            text-decoration: underline;
        }

        /* Alerts */
        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 1.5rem;
            animation: slideInFromRight 0.8s ease-out 0.4s both;
        }

        .alert-danger {
            background: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }

        .alert-success {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }

        /* Loading State */
        .btn-loading {
            position: relative;
            pointer-events: none;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 20px;
            height: 20px;
            margin-top: -10px;
            margin-left: -10px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Remember Me Checkbox */
        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            animation: slideInFromRight 0.8s ease-out 0.6s both;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid #e9ecef;
            border-radius: 4px;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-check-label {
            font-size: 0.9rem;
            color: #6c757d;
            cursor: pointer;
        }

        /* Footer */
        .auth-footer {
            position: fixed;
            bottom: 1rem;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.85rem;
        }

        @media (max-width: 768px) {
            .auth-footer {
                position: static;
                transform: none;
                margin-top: 2rem;
                color: #6c757d;
            }
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-content">
                <!-- Visual Section -->
                <div class="auth-visual">
                    <div class="visual-content">
                        <div class="visual-logo">
                            <i class="mdi mdi-silverware-fork-knife"></i>
                        </div>
                        
                        <h1 class="visual-title">
                            Café<span style="color: #FFD700;">Lufamina</span>
                        </h1>
                        
                        <p class="visual-subtitle">
                            Sistema completo de gestão gastronômica com tecnologia de ponta
                        </p>
                        
                        <div class="visual-features">
                            <div class="feature-item">
                                <i class="mdi mdi-point-of-sale"></i>
                                <span>PDV Inteligente</span>
                            </div>
                            <div class="feature-item">
                                <i class="mdi mdi-chart-line"></i>
                                <span>Relatórios em Tempo Real</span>
                            </div>
                            <div class="feature-item">
                                <i class="mdi mdi-food-variant"></i>
                                <span>Gestão de Cardápio</span>
                            </div>
                            <div class="feature-item">
                                <i class="mdi mdi-shield-check"></i>
                                <span>Segurança Avançada</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Section -->
                <div class="auth-form">
                    <div class="form-header">
                        <h2 class="form-title">@yield('form-title', 'Bem-vindo')</h2>
                        <p class="form-subtitle">@yield('form-subtitle', 'Faça login para acessar o sistema')</p>
                    </div>

                    <!-- Alerts -->
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <div class="d-flex align-items-center">
                                <i class="mdi mdi-alert-circle me-2"></i>
                                <div>
                                    @if($errors->count() == 1)
                                        {{ $errors->first() }}
                                    @else
                                        <strong>Foram encontrados os seguintes erros:</strong>
                                        <ul class="mb-0 mt-1">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            <div class="d-flex align-items-center">
                                <i class="mdi mdi-check-circle me-2"></i>
                                <div>{{ session('success') }}</div>
                            </div>
                        </div>
                    @endif

                    @if(session('status'))
                        <div class="alert alert-success">
                            <div class="d-flex align-items-center">
                                <i class="mdi mdi-information me-2"></i>
                                <div>{{ session('status') }}</div>
                            </div>
                        </div>
                    @endif

                    <!-- Form Content -->
                    @yield('form-content')
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="auth-footer">
        <div>© {{ date('Y') }} CaféLufamina POS - Todos os direitos reservados</div>
        <div class="mt-1">
            <small>Versão 1.0.0 | Desenvolvido com ❤️ para restaurantes</small>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle functionality
            const passwordToggles = document.querySelectorAll('.password-toggle');
            passwordToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const input = this.previousElementSibling;
                    const icon = this.querySelector('i');
                    
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('mdi-eye-outline');
                        icon.classList.add('mdi-eye-off-outline');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('mdi-eye-off-outline');
                        icon.classList.add('mdi-eye-outline');
                    }
                });
            });
            
            // Form submission loading state
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.classList.add('btn-loading');
                        submitBtn.disabled = true;
                    }
                });
            });
            
            // Auto-dismiss alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        alert.remove();
                    }, 300);
                }, 5000);
            });
        });
    </script>

    @stack('scripts')
</body>
</html>