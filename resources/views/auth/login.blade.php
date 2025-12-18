<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Lu & Yosh Catering</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        primary: '#FFA500',
                        'primary-hover': '#e69500',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background: url('{{ asset('assets/images/restaurant-bg.jpeg') }}') no-repeat center center fixed;
            background-size: cover;
        }
    </style>
</head>

<body class="h-screen flex items-center justify-center text-gray-800 dark:text-gray-100">
    <div
        class="bg-white/90 dark:bg-gray-900/95 backdrop-blur-md rounded-2xl shadow-2xl p-10 w-full max-w-md border border-gray-200 dark:border-gray-700 mx-4">
        <div class="text-center mb-8">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Lu & Yosh Catering" class="h-24 mx-auto mb-6">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                ZALALABB <span class="text-primary">POS</span>
            </h2>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                <p>{{ $errors->first() }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf
            <div>
                <input type="text" name="username" placeholder="Usuário"
                    class="w-full px-5 py-3 rounded-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200"
                    value="{{ old('username') }}" required autofocus>
            </div>
            <div>
                <input type="password" name="password" placeholder="Senha"
                    class="w-full px-5 py-3 rounded-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200"
                    required>
            </div>
            <button type="submit"
                class="w-full bg-primary hover:bg-primary-hover text-white font-semibold py-3 px-6 rounded-full transform hover:-translate-y-0.5 transition duration-200 shadow-lg">
                Entrar
            </button>
        </form>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Esqueceu sua senha? <a href="#" class="text-primary hover:text-primary-hover font-medium">Clique
                    aqui</a>
            </p>
        </div>
    </div>

    <script>
        // Apply saved theme
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</body>

</html>