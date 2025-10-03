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

    <!-- Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
            background: url('{{ asset('assets/images/zalala-beach-bg.jpeg') }}') no-repeat center center fixed;
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

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            z-index: 0;
        }

        .header>* {
            position: relative;
            z-index: 1;
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
            overflow: hidden;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
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

        /* Seção do Menu */
        .menu-section {
            padding: 6rem 2rem;
            background: white;
            position: relative;
        }

        .menu-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(8, 145, 178, 0.05) 0%, rgba(6, 182, 212, 0.05) 50%, rgba(245, 158, 11, 0.05) 100%);
            z-index: 0;
        }

        .menu-section>* {
            position: relative;
            z-index: 1;
        }

        .menu-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .menu-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .menu-header h2 {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }

        .menu-header h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--beach-gradient);
            border-radius: 2px;
        }

        .menu-categories {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 3rem;
        }

        .category-btn {
            background: white;
            border: 2px solid var(--primary);
            color: var(--primary);
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .category-btn.active,
        .category-btn:hover {
            background: var(--primary);
            color: white;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .menu-item {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .menu-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .menu-item-image {
            height: 200px;
            background-color: #f3f4f6;
            background-size: cover;
            background-position: center;
        }

        .menu-item-content {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .menu-item-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.5rem;
        }

        .menu-item-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-dark);
            margin-right: 1rem;
        }

        .menu-item-price {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--secondary);
            white-space: nowrap;
        }

        .menu-item-description {
            color: #6b7280;
            margin-bottom: 1rem;
            flex-grow: 1;
        }

        .menu-item-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .quantity-btn {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--primary);
            color: white;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .quantity-btn:hover {
            background: var(--primary-dark);
        }

        .quantity-input {
            width: 40px;
            text-align: center;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            padding: 0.25rem;
        }

        .add-to-cart-btn {
            background: var(--secondary);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .add-to-cart-btn:hover {
            background: #e69007;
        }

        /* Carrinho de Compras */
        .cart-container {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 1000;
        }

        .cart-toggle {
            width: 60px;
            height: 60px;
            background: var(--secondary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            position: relative;
        }

        .cart-toggle:hover {
            transform: scale(1.1);
        }

        .cart-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            color: white;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .cart-panel {
            position: absolute;
            bottom: 70px;
            right: 0;
            width: 350px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            padding: 1.5rem;
            display: none;
            max-height: 500px;
            overflow-y: auto;
        }

        .cart-panel.active {
            display: block;
        }

        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .cart-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-dark);
        }

        .close-cart {
            background: none;
            border: none;
            font-size: 1.25rem;
            cursor: pointer;
            color: #6b7280;
        }

        .cart-items {
            margin-bottom: 1rem;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .cart-item-info {
            flex-grow: 1;
        }

        .cart-item-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .cart-item-price {
            color: var(--secondary);
            font-weight: 600;
        }

        .cart-item-quantity {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .cart-item-actions {
            display: flex;
            gap: 0.5rem;
        }

        .remove-item {
            background: #ef4444;
            color: white;
            border: none;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.8rem;
        }

        .cart-total {
            display: flex;
            justify-content: space-between;
            font-weight: 700;
            font-size: 1.1rem;
            padding: 1rem 0;
            border-top: 1px solid #e5e7eb;
            margin-bottom: 1rem;
        }

        .cart-actions {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .checkout-btn, .whatsapp-btn {
            padding: 0.75rem;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .checkout-btn {
            background: var(--primary);
            color: white;
        }

        .checkout-btn:hover {
            background: var(--primary-dark);
        }

        .whatsapp-btn {
            background: #25d366;
            color: white;
        }

        .whatsapp-btn:hover {
            background: #128c7e;
        }

        /* Seção de Reservas */
        .reservation-section {
            padding: 6rem 2rem;
            background: var(--beach-gradient);
            color: white;
            text-align: center;
        }

        .reservation-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .reservation-header h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
        }

        .reservation-header p {
            font-size: 1.1rem;
            margin-bottom: 3rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .reservation-form {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .form-group {
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.9);
            font-family: 'Figtree', sans-serif;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .submit-btn {
            background: white;
            color: var(--primary);
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .submit-btn:hover {
            background: var(--secondary);
            color: white;
            transform: translateY(-2px);
        }

        .footer {
            background: var(--beach-gradient);
            color: white;
            text-align: center;
            padding: 3rem 2rem 2rem;
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

            .menu-grid {
                grid-template-columns: 1fr;
            }

            .cart-panel {
                width: 300px;
                right: -50px;
            }

            .form-row {
                grid-template-columns: 1fr;
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

    <!-- Seção do Menu -->
    <section class="menu-section" id="menu">
        <div class="menu-container">
            <div class="menu-header">
                <h2>Nosso Menu</h2>
                <p>Descubra nossas deliciosas opções gastronômicas</p>
            </div>

            <div class="menu-categories">
                <button class="category-btn active" data-category="all">Todos</button>
                <button class="category-btn" data-category="bebidas">Bebidas</button>
                <button class="category-btn" data-category="entradas">Entradas</button>
                <button class="category-btn" data-category="pratos-principais">Pratos Principais</button>
                <button class="category-btn" data-category="sobremesas">Sobremesas</button>
            </div>

            <div class="menu-grid" id="menu-items">
                <!-- Os itens do menu serão carregados aqui via JavaScript -->
            </div>
        </div>
    </section>

    <!-- Seção de Reservas -->
    <section class="reservation-section" id="reservations">
        <div class="reservation-container">
            <div class="reservation-header">
                <h2>Faça sua Reserva</h2>
                <p>Garanta seu lugar no ZALALA BEACH BAR e desfrute de uma experiência única à beira-mar</p>
            </div>

            <form class="reservation-form" id="reservation-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Nome Completo</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Telefone</label>
                        <input type="tel" id="phone" name="phone" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="date">Data</label>
                        <input type="date" id="date" name="date" required>
                    </div>
                    <div class="form-group">
                        <label for="time">Horário</label>
                        <select id="time" name="time" required>
                            <option value="">Selecione um horário</option>
                            <option value="12:00">12:00</option>
                            <option value="13:00">13:00</option>
                            <option value="14:00">14:00</option>
                            <option value="18:00">18:00</option>
                            <option value="19:00">19:00</option>
                            <option value="20:00">20:00</option>
                            <option value="21:00">21:00</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="guests">Número de Pessoas</label>
                        <select id="guests" name="guests" required>
                            <option value="1">1 pessoa</option>
                            <option value="2">2 pessoas</option>
                            <option value="3">3 pessoas</option>
                            <option value="4">4 pessoas</option>
                            <option value="5">5 pessoas</option>
                            <option value="6">6 pessoas</option>
                            <option value="7">7 pessoas</option>
                            <option value="8">8 pessoas</option>
                            <option value="9">9+ pessoas</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="table">Tipo de Mesa</label>
                        <select id="table" name="table" required>
                            <option value="interior">Interior</option>
                            <option value="exterior">Exterior</option>
                            <option value="vip">VIP (Beira-mar)</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="notes">Observações Especiais</label>
                    <textarea id="notes" name="notes" rows="3"></textarea>
                </div>

                <button type="submit" class="submit-btn">Reservar Agora</button>
            </form>
        </div>
    </section>

    <!-- Carrinho de Compras -->
    <div class="cart-container">
        <div class="cart-toggle" id="cart-toggle">
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-count" id="cart-count">0</span>
        </div>
        <div class="cart-panel" id="cart-panel">
            <div class="cart-header">
                <h3 class="cart-title">Seu Pedido</h3>
                <button class="close-cart" id="close-cart">&times;</button>
            </div>
            <div class="cart-items" id="cart-items">
                <!-- Itens do carrinho serão adicionados aqui -->
            </div>
            <div class="cart-total">
                <span>Total:</span>
                <span id="cart-total">0,00 MT</span>
            </div>
            <div class="cart-actions">
                <button class="checkout-btn" id="checkout-btn">
                    <i class="fas fa-credit-card"></i> Finalizar Pedido
                </button>
                <button class="whatsapp-btn" id="whatsapp-btn">
                    <i class="fab fa-whatsapp"></i> Pedir por WhatsApp
                </button>
            </div>
        </div>
    </div>

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

    <script>
        // Dados do menu (em um sistema real, viriam do backend)
        const menuItems = [
            {
                id: 1,
                name: "Caipirinha de Maracujá",
                category: "bebidas",
                price: 250,
                description: "Tradicional caipirinha preparada com maracujá fresco",
                image: "{{ asset('assets/images/caipirinha.jpg') }}"
            },
            {
                id: 2,
                name: "Mojito Tropical",
                category: "bebidas",
                price: 280,
                description: "Rum, hortelã, limão e um toque especial de frutas tropicais",
                image: "{{ asset('assets/images/mojito.jpg') }}"
            },
            {
                id: 3,
                name: "Camarão à Guilho",
                category: "entradas",
                price: 450,
                description: "Camarões salteados no alho e ervas, servidos com pão crocante",
                image: "{{ asset('assets/images/camarao-alho.jpg') }}"
            },
            {
                id: 4,
                name: "Bolinhos de Peixe",
                category: "entradas",
                price: 320,
                description: "Deliciosos bolinhos de peixe com molho tártaro",
                image: "{{ asset('assets/images/bolinho-peixe.jpg') }}"
            },
            {
                id: 5,
                name: "Robalo Grelhado",
                category: "pratos-principais",
                price: 850,
                description: "Filete de robalo grelhado com legumes salteados e arroz de coco",
                image: "{{ asset('assets/images/robalo-grelhado.jpg') }}"
            },
            {
                id: 6,
                name: "Frango à Zambeziana",
                category: "pratos-principais",
                price: 650,
                description: "Frango grelhado com molho especial da casa e batatas rústicas",
                image: "{{ asset('assets/images/frango-zambeziana.jpg') }}"
            },
            {
                id: 7,
                name: "Pudim de Coco",
                category: "sobremesas",
                price: 220,
                description: "Delicioso pudim de coco com calda de caramelo",
                image: "{{ asset('assets/images/pudim-coco.jpg') }}"
            },
            {
                id: 8,
                name: "Mousse de Maracujá",
                category: "sobremesas",
                price: 200,
                description: "Mousse leve e refrescante de maracujá",
                image: "{{ asset('assets/images/mousse-maracuja.jpg') }}"
            }
        ];

        // Estado do carrinho
        let cart = [];

        // Inicialização da página
        document.addEventListener('DOMContentLoaded', function() {
            renderMenuItems();
            setupEventListeners();
            updateCartUI();
        });

        // Renderizar itens do menu
        function renderMenuItems(category = 'all') {
            const menuGrid = document.getElementById('menu-items');
            menuGrid.innerHTML = '';

            const filteredItems = category === 'all' 
                ? menuItems 
                : menuItems.filter(item => item.category === category);

            filteredItems.forEach(item => {
                const menuItem = document.createElement('div');
                menuItem.className = 'menu-item';
                menuItem.innerHTML = `
                    <div class="menu-item-image" style="background-image: url('${item.image}')"></div>
                    <div class="menu-item-content">
                        <div class="menu-item-header">
                            <h3 class="menu-item-title">${item.name}</h3>
                            <span class="menu-item-price">${formatPrice(item.price)}</span>
                        </div>
                        <p class="menu-item-description">${item.description}</p>
                        <div class="menu-item-actions">
                            <div class="quantity-selector">
                                <button class="quantity-btn minus" data-id="${item.id}">-</button>
                                <input type="number" class="quantity-input" data-id="${item.id}" value="1" min="1">
                                <button class="quantity-btn plus" data-id="${item.id}">+</button>
                            </div>
                            <button class="add-to-cart-btn" data-id="${item.id}">
                                <i class="fas fa-cart-plus"></i> Adicionar
                            </button>
                        </div>
                    </div>
                `;
                menuGrid.appendChild(menuItem);
            });

            // Adicionar event listeners aos botões
            document.querySelectorAll('.add-to-cart-btn').forEach(button => {
                button.addEventListener('click', addToCart);
            });

            document.querySelectorAll('.quantity-btn.plus').forEach(button => {
                button.addEventListener('click', increaseQuantity);
            });

            document.querySelectorAll('.quantity-btn.minus').forEach(button => {
                button.addEventListener('click', decreaseQuantity);
            });
        }

        // Configurar event listeners
        function setupEventListeners() {
            // Filtros de categoria
            document.querySelectorAll('.category-btn').forEach(button => {
                button.addEventListener('click', function() {
                    document.querySelectorAll('.category-btn').forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');
                    renderMenuItems(this.dataset.category);
                });
            });

            // Carrinho
            document.getElementById('cart-toggle').addEventListener('click', toggleCart);
            document.getElementById('close-cart').addEventListener('click', toggleCart);
            document.getElementById('checkout-btn').addEventListener('click', checkout);
            document.getElementById('whatsapp-btn').addEventListener('click', sendWhatsAppOrder);

            // Formulário de reserva
            document.getElementById('reservation-form').addEventListener('submit', submitReservation);
        }

        // Funções do carrinho
        function addToCart(e) {
            const itemId = parseInt(e.target.dataset.id);
            const quantityInput = document.querySelector(`.quantity-input[data-id="${itemId}"]`);
            const quantity = parseInt(quantityInput.value);

            const existingItem = cart.find(item => item.id === itemId);
            
            if (existingItem) {
                existingItem.quantity += quantity;
            } else {
                const menuItem = menuItems.find(item => item.id === itemId);
                cart.push({
                    id: menuItem.id,
                    name: menuItem.name,
                    price: menuItem.price,
                    quantity: quantity
                });
            }

            updateCartUI();
            showNotification('Item adicionado ao carrinho!');
        }

        function removeFromCart(itemId) {
            cart = cart.filter(item => item.id !== itemId);
            updateCartUI();
        }

        function updateCartUI() {
            const cartCount = document.getElementById('cart-count');
            const cartItems = document.getElementById('cart-items');
            const cartTotal = document.getElementById('cart-total');

            // Atualizar contador
            cartCount.textContent = cart.reduce((total, item) => total + item.quantity, 0);

            // Atualizar itens
            cartItems.innerHTML = '';
            let total = 0;

            cart.forEach(item => {
                const itemTotal = item.price * item.quantity;
                total += itemTotal;

                const cartItem = document.createElement('div');
                cartItem.className = 'cart-item';
                cartItem.innerHTML = `
                    <div class="cart-item-info">
                        <div class="cart-item-name">${item.name}</div>
                        <div class="cart-item-price">${formatPrice(item.price)} x ${item.quantity}</div>
                    </div>
                    <div class="cart-item-actions">
                        <div class="cart-item-total">${formatPrice(itemTotal)}</div>
                        <button class="remove-item" data-id="${item.id}">&times;</button>
                    </div>
                `;
                cartItems.appendChild(cartItem);
            });

            // Adicionar event listeners aos botões de remover
            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', function() {
                    removeFromCart(parseInt(this.dataset.id));
                });
            });

            // Atualizar total
            cartTotal.textContent = formatPrice(total);
        }

        function toggleCart() {
            document.getElementById('cart-panel').classList.toggle('active');
        }

        function checkout() {
            if (cart.length === 0) {
                alert('Seu carrinho está vazio!');
                return;
            }

            // Em um sistema real, aqui seria a integração com o sistema de pagamento
            alert('Funcionalidade de checkout será implementada em breve!');
        }

        function sendWhatsAppOrder() {
            if (cart.length === 0) {
                alert('Seu carrinho está vazio!');
                return;
            }

            const phoneNumber = "258846885214"; // Número do ZALALA BEACH BAR
            let message = "Olá! Gostaria de fazer um pedido:\n\n";

            cart.forEach(item => {
                message += `• ${item.quantity}x ${item.name} - ${formatPrice(item.price * item.quantity)}\n`;
            });

            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            message += `\nTotal: ${formatPrice(total)}`;
            message += `\n\nNome: [Seu Nome]\nEndereço: [Seu Endereço]`;

            const encodedMessage = encodeURIComponent(message);
            const whatsappURL = `https://wa.me/${phoneNumber}?text=${encodedMessage}`;

            window.open(whatsappURL, '_blank');
        }

        // Funções do formulário de reserva
        function submitReservation(e) {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const reservation = {
                name: formData.get('name'),
                phone: formData.get('phone'),
                date: formData.get('date'),
                time: formData.get('time'),
                guests: formData.get('guests'),
                table: formData.get('table'),
                notes: formData.get('notes')
            };

            // Em um sistema real, aqui seria o envio para o backend
            const phoneNumber = "258846885214";
            let message = "Olá! Gostaria de fazer uma reserva:\n\n";
            message += `Nome: ${reservation.name}\n`;
            message += `Telefone: ${reservation.phone}\n`;
            message += `Data: ${reservation.date}\n`;
            message += `Horário: ${reservation.time}\n`;
            message += `Pessoas: ${reservation.guests}\n`;
            message += `Tipo de Mesa: ${reservation.table}\n`;
            
            if (reservation.notes) {
                message += `Observações: ${reservation.notes}\n`;
            }

            const encodedMessage = encodeURIComponent(message);
            const whatsappURL = `https://wa.me/${phoneNumber}?text=${encodedMessage}`;

            window.open(whatsappURL, '_blank');
            e.target.reset();
            showNotification('Reserva enviada com sucesso!');
        }

        // Funções auxiliares
        function formatPrice(price) {
            return `${price.toLocaleString('pt-MZ')},00 MT`;
        }

        function showNotification(message) {
            // Criar uma notificação simples
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: var(--secondary);
                color: white;
                padding: 1rem 1.5rem;
                border-radius: 5px;
                box-shadow: 0 5px 15px rgba(0,0,0,0.2);
                z-index: 10000;
                transition: all 0.3s ease;
            `;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        function increaseQuantity(e) {
            const itemId = e.target.dataset.id;
            const input = document.querySelector(`.quantity-input[data-id="${itemId}"]`);
            input.value = parseInt(input.value) + 1;
        }

        function decreaseQuantity(e) {
            const itemId = e.target.dataset.id;
            const input = document.querySelector(`.quantity-input[data-id="${itemId}"]`);
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }
    </script>
</body>

</html>