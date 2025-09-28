<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="ZALALA BEACH BAR - Restaurante e Bar na Praia de Quelimane">
    <title>ZALALA BEACH BAR</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet">

    <!-- Estilos -->
    <style>
        :root {
            --primary: #0891b2;
            --primary-dark: #0f4453;
            --secondary: #f59e0b;
            --accent: #06b6d4;
            --beach-gradient: linear-gradient(135deg, #0891b2 0%, #06b6d4 50%, #fbbf24 100%);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Figtree', sans-serif;
            background: linear-gradient(135deg, #4380a8 0%, #81cdff 50%, #ffd102 100%);
            color: #1f2937;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(8, 145, 178, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(245, 158, 11, 0.03) 0%, transparent 50%),
                radial-gradient(circle at 40% 80%, rgba(6, 182, 212, 0.03) 0%, transparent 50%);
            z-index: -1;
        }
        
        .header {
            background: url('{{ asset('assets/images/zalala-beach-bg.jpg') }}') no-repeat center center fixed;
            background-size: cover;
            height: 70vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            position: relative;
            border-bottom-left-radius: 60px;
            border-bottom-right-radius: 60px;
            overflow: hidden;
        }
        
        .header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100px;
            background: var(--beach-gradient);
            clip-path: polygon(0 30%, 100% 0, 100% 100%, 0 100%);
        }
        
        .header-content {
            position: relative;
            z-index: 2;
            max-width: 800px;
            padding: 0 2rem;
        }
        
      .logo {
            width: 250px;
            height: 250px;
            margin: 0 auto 1.5rem;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            overflow: hidden; /* garante que a imagem siga o círculo */
        }

        .logo img {
            width: 100%;   /* ocupa toda a div */
            height: 100%;  /* ocupa toda a div */
            object-fit: cover; /* cobre a área mantendo proporção */
        }

        
        .header h1 {
            font-size: 3.5rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            letter-spacing: 1px;
        }
        
        .header p {
            font-size: 1.25rem;
            margin-top: 1rem;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .nav-buttons {
            position: absolute;
            top: 2rem;
            right: 2rem;
            display: flex;
            gap: 1rem;
        }
        
        .nav-buttons a {
            text-decoration: none;
            color: white;
            background: rgba(4, 180, 233, 0.2);
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            transition: all 0.3s ease;
            font-weight: 600;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .nav-buttons a:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        .features {
            padding: 6rem 2rem 4rem;
            text-align: center;
        }
        
        .features h2 {
            font-size: 2.5rem;
            margin-bottom: 3rem;
            color: var(--primary);
            position: relative;
        }
        
        .features h2::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--beach-gradient);
            border-radius: 2px;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(14, 165, 233, 0.1);
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(8, 145, 178, 0.2);
        }
        
        .feature-icon {
            width: 70px;
            height: 70px;
            background: var(--beach-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
        }
        
        .feature-card h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: var(--primary-dark);
        }
        
        .feature-card p {
            color: #6b7280;
            line-height: 1.6;
        }
        
        .footer {
            background: var(--beach-gradient);
            color: white;
            text-align: center;
            padding: 3rem 2rem 2rem;
            margin-top: 4rem;
        }
        
        .footer-content {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .footer h3 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }
        
        .contact-info {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin: 2rem 0;
            flex-wrap: wrap;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.1rem;
        }
        
        .copyright {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.3);
            color: rgba(255, 255, 255, 0.8);
        }
        
        @media (max-width: 768px) {
            .header h1 {
                font-size: 2.5rem;
            }
            
            .header p {
                font-size: 1.1rem;
            }
            
            .nav-buttons {
                position: static;
                margin: 1rem auto 0;
                justify-content: center;
            }
            
            .features h2 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <img src="{{ asset('assets/images/logo-zalala.png') }}" alt="ZALALA BEACH BAR Logo">
            </div>
            <h1>ZALALA BEACH BAR</h1>
            <p>O paraíso à beira-mar em Quelimane, onde a gastronomia encontra o oceano.</p>
        </div>
        <div class="nav-buttons" style="">
            @auth
                <a href="{{ url('/dashboard') }}">Acessar Sistema</a>
            @else
                <a href="{{ route('login') }}">Entrar no Sistema</a>
            @endauth
        </div>
    </header>

    <section class="features">
        <h2>Por que escolher o ZALALA?</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <span>🌊</span>
                </div>
                <h3>Localização Privilegiada</h3>
                <p>À beira-mar, com vista panorâmica para o Oceano Índico e o pôr do sol mais lindo de Quelimane.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <span>🍽️</span>
                </div>
                <h3>Gastronomia Premium</h3>
                <p>Cardápio diversificado com frutos do mar frescos, pratos internacionais e especialidades locais.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <span>🍹</span>
                </div>
                <h3>Cocktails Exclusivos</h3>
                <p>Bebidas artesanais preparadas com ingredientes tropicais e toque de criatividade.</p>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="footer-content">
            <h3>ZALALA BEACH BAR</h3>
            <p>Seu refúgio tropical em Quelimane, Zambézia</p>
            
            <div class="contact-info">
                <div class="contact-item">
                    <span>📍</span>
                    <span>ER470, Bairro Zalala, Quelimane</span>
                </div>
                <div class="contact-item">
                    <span>📱</span>
                    <span>(+258) 846 885 214</span>
                </div>
                <div class="contact-item">
                    <span>🆔</span>
                    <span>NUIT: 110735901</span>
                </div>
            </div>
            
            <div class="copyright">
                <p>&copy; {{ date('Y') }} ZALALA BEACH BAR. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html>