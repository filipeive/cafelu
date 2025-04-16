<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Café Lufamina</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <style>
            body {
                font-family: Figtree, sans-serif;
                background-color: #f8f9fa;
                color: #333;
                margin: 0;
                padding: 0;
            }
            .header {
                background: url('{{ asset('assets/images/restaurant-bg.jpg') }}') no-repeat center center fixed;
                background-size: cover;
                height: 60vh;
                display: flex;
                justify-content: center;
                align-items: center;
                color: white;
                text-align: center;
                position: relative;
            }
            .header-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
            }
            .header-content {
                position: relative;
                z-index: 2;
            }
            .header h1 {
                font-size: 3rem;
                margin: 0;
            }
            .header p {
                font-size: 1.25rem;
                margin-top: 1rem;
            }
            .menu-section {
                padding: 4rem 2rem;
                text-align: center;
            }
            .menu-section h2 {
                font-size: 2rem;
                margin-bottom: 1.5rem;
            }
            .menu-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 2rem;
            }
            .menu-item {
                background: white;
                border-radius: 8px;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                transition: transform 0.3s ease;
            }
            .menu-item:hover {
                transform: scale(1.05);
            }
            .menu-item img {
                width: 100%;
                height: 200px;
                object-fit: cover;
            }
            .menu-item h3 {
                font-size: 1.5rem;
                margin: 1rem 0;
            }
            .menu-item p {
                font-size: 1rem;
                color: #666;
                padding: 0 1rem 1rem;
            }
            .footer {
                background: #333;
                color: white;
                text-align: center;
                padding: 2rem 1rem;
            }
            .footer p {
                margin: 0;
            }
            .nav-buttons {
                position: absolute;
                top: 1rem;
                right: 1rem;
                display: flex;
                gap: 1rem;
            }
            .nav-buttons a {
                text-decoration: none;
                color: white;
                background: #007bff;
                padding: 0.5rem 1rem;
                border-radius: 5px;
                transition: background 0.3s ease;
            }
            .nav-buttons a:hover {
                background: #0056b3;
            }
        </style>
    </head>
    <body>
        <header class="header">
            <div class="header-overlay"></div>
            <div class="header-content">
                <h1>Bem-vindo ao Café Lufamina</h1>
                <p>O lugar perfeito para saborear o melhor café e refeições deliciosas.</p>
            </div>
            <div class="nav-buttons">
                @auth
                    <a href="{{ url('/home') }}">Home</a>
                @else
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Register</a>
                @endauth
            </div>
        </header>

        <section class="menu-section">
            <h2>Nosso Menu</h2>
            <div class="menu-grid">
                <div class="menu-item">
                    <img src="https://source.unsplash.com/400x300/?coffee" alt="Café">
                    <h3>Café Especial</h3>
                    <p>Desfrute do nosso café especial, feito com grãos selecionados.</p>
                </div>
                <div class="menu-item">
                    <img src="https://source.unsplash.com/400x300/?pastry" alt="Pastelaria">
                    <h3>Pastelaria</h3>
                    <p>Saboreie nossos bolos e doces frescos, perfeitos para acompanhar o café.</p>
                </div>
                <div class="menu-item">
                    <img src="https://source.unsplash.com/400x300/?breakfast" alt="Pequeno-almoço">
                    <h3>Pequeno-almoço</h3>
                    <p>Comece o dia com um pequeno-almoço nutritivo e saboroso.</p>
                </div>
                <div class="menu-item">
                    <img src="https://source.unsplash.com/400x300/?lunch" alt="Almoço">
                    <h3>Almoço</h3>
                    <p>Experimente nossas refeições caseiras, preparadas com amor.</p>
                </div>
            </div>
        </section>

        <footer class="footer">
            <p>&copy; {{ date('Y') }} Café Lufamina. Todos os direitos reservados.</p>
        </footer>
    </body>
</html>
