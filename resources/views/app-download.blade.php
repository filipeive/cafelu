<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $companyName = $tenant ? $tenant->name : \App\Models\Setting::get('company_name', 'RestPOS');
        $primaryColor = \App\Models\Setting::get('primary_color', '#f97316');
        $backUrl = $tenant ? route('restaurant.slug', $tenant->slug) : route('welcome');
    @endphp
    <title>Baixe a Nossa App Móvel — {{ $companyName }} | RestPOS</title>
    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&display=swap" rel="stylesheet">
    
    <!-- Material Design Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-serif { font-family: 'Playfair Display', Georgia, serif; }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased selection:bg-orange-500 selection:text-white min-h-screen flex flex-col">

    <!-- Header Elegante -->
    <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-slate-200/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between gap-4">
            <a href="{{ $backUrl }}" class="flex items-center gap-3 group">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-orange-500 to-amber-600 flex items-center justify-center text-white shadow-lg shadow-orange-500/25 group-hover:scale-105 transition-transform">
                    <i class="mdi mdi-cellphone-check text-2xl"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-extrabold text-xl tracking-tight text-slate-900">{{ $companyName }}</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800">App Móvel</span>
                    </div>
                    <p class="text-xs text-slate-500">Aplicação Oficial para Celular & Tablet</p>
                </div>
            </a>

            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ $backUrl }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs sm:text-sm font-bold transition-all shadow-xs">
                    <i class="mdi mdi-arrow-left text-orange-500 text-base"></i>
                    <span>{{ $tenant ? 'Voltar ao Restaurante' : 'Voltar ao Início' }}</span>
                </a>
            </div>
        </div>
    </header>

    <main class="flex-1">

        <!-- Hero Section -->
        <section class="relative overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-orange-950 text-white py-16 sm:py-24">
            <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#f97316_1px,transparent_1px)] [background-size:24px_24px]"></div>
            <div class="absolute -top-24 -right-24 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                    
                    <!-- Coluna de Texto & CTA Principal -->
                    <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/15 border border-emerald-400/30 text-emerald-300 text-xs font-bold uppercase tracking-wider">
                            <i class="mdi mdi-android text-base"></i>
                            <span>Versão 1.3 Oficial · Android & PWA</span>
                        </div>

                        <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight">
                            Tudo o que precisa no seu restaurante, <span class="text-transparent bg-clip-text bg-gradient-to-r from-orange-400 to-amber-300">agora na palma da sua mão.</span>
                        </h1>

                        <p class="text-sm sm:text-base text-slate-300 leading-relaxed max-w-2xl mx-auto lg:mx-0">
                            Baixe a aplicação nativa do <strong>{{ $companyName }}</strong>. Faça pedidos diretamente na sua mesa via QR Code rápido, acompanhe o preparo em direto pela cozinha e encomende para levar sem filas nem complicações.
                        </p>

                        <!-- Botões de Ação -->
                        <div class="pt-2 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                            <a href="{{ $apkUrl }}"
                               download="RestPOS-v1.3.apk"
                               class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold text-sm shadow-xl shadow-emerald-600/30 transition-all inline-flex items-center justify-center gap-3 active:scale-95 cursor-pointer">
                                <i class="mdi mdi-android text-3xl"></i>
                                <div class="text-left">
                                    <div class="text-[10px] uppercase font-bold text-emerald-200 leading-none">Download Direto Gratuito</div>
                                    <div class="text-base font-black leading-tight">Baixar APK Android ({{ $apkSizeFormatted }})</div>
                                </div>
                            </a>

                            <a href="{{ $backUrl }}"
                               class="w-full sm:w-auto px-6 py-4 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-bold text-sm border border-white/20 transition-all inline-flex items-center justify-center gap-2 backdrop-blur-xs">
                                <i class="mdi mdi-silverware-fork-knife text-lg text-orange-400"></i>
                                <span>Aceder via Navegador</span>
                            </a>
                        </div>

                        <div class="flex flex-wrap items-center justify-center lg:justify-start gap-4 pt-2 text-xs text-slate-400">
                            <span class="inline-flex items-center gap-1.5"><i class="mdi mdi-shield-check text-emerald-400 text-base"></i> Sem custos nem anúncios</span>
                            <span class="inline-flex items-center gap-1.5"><i class="mdi mdi-check-circle-outline text-emerald-400 text-base"></i> Android 8.0 ou superior</span>
                            <span class="inline-flex items-center gap-1.5"><i class="mdi mdi-lightning-bolt text-amber-400 text-base"></i> Rápido & Leve</span>
                        </div>
                    </div>

                    <!-- Coluna Visual: Mockup do Telemóvel -->
                    <div class="lg:col-span-5 flex justify-center">
                        <div class="relative w-full max-w-xs sm:max-w-sm rounded-[40px] p-4 bg-gradient-to-b from-slate-800 to-slate-900 border-4 border-slate-700 shadow-2xl">
                            <!-- Speaker notch -->
                            <div class="w-20 h-4 bg-slate-900 rounded-full mx-auto mb-3 border border-slate-800"></div>

                            <!-- Screen Card -->
                            <div class="rounded-[30px] overflow-hidden bg-white text-slate-900 p-5 space-y-4 shadow-inner">
                                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-xl bg-orange-500 text-white flex items-center justify-center text-sm font-bold">
                                            <i class="mdi mdi-silverware-fork-knife"></i>
                                        </div>
                                        <div>
                                            <div class="text-xs font-bold leading-tight">{{ $companyName }}</div>
                                            <div class="text-[10px] text-emerald-600 font-semibold">Mesa 04 · Conectada</div>
                                        </div>
                                    </div>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-orange-100 text-orange-700">App Ativa</span>
                                </div>

                                <!-- Mini Preview do Status do Pedido -->
                                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-bold text-slate-700">Status do Seu Pedido</span>
                                        <span class="text-orange-600 font-bold text-[11px]">No Fogão (KDS)</span>
                                    </div>
                                    <div class="w-full bg-slate-200 h-1.5 rounded-full overflow-hidden">
                                        <div class="bg-orange-500 h-full w-3/4"></div>
                                    </div>
                                    <p class="text-[11px] text-slate-500">1x Bife à Café Lu + 1x Sumo Natural</p>
                                </div>

                                <!-- Features Pills inside mockup -->
                                <div class="space-y-2 pt-1 text-xs">
                                    <div class="flex items-center gap-2 p-2 rounded-xl bg-emerald-50 text-emerald-900 font-medium">
                                        <i class="mdi mdi-qrcode-scan text-emerald-600 text-base"></i>
                                        <span class="text-[11px]">Leitura instantânea de QR Code da Mesa</span>
                                    </div>
                                    <div class="flex items-center gap-2 p-2 rounded-xl bg-blue-50 text-blue-900 font-medium">
                                        <i class="mdi mdi-bell-ring-outline text-blue-600 text-base"></i>
                                        <span class="text-[11px]">Chame o garçom com 1 clique</span>
                                    </div>
                                </div>

                                <a href="{{ $apkUrl }}" download="RestPOS-v1.3.apk"
                                   class="block w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs text-center shadow-md transition-all">
                                    Instalar no seu Telemóvel
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- SECÇÃO: O QUE O APLICATIVO FAZ (EXPLICAÇÕES DETALHADAS) -->
        <section class="py-16 sm:py-24 bg-white border-b border-slate-200/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="max-w-3xl mx-auto text-center space-y-3 mb-16">
                    <span class="text-xs font-black uppercase tracking-widest text-orange-600">Funcionalidades do Aplicativo</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                        O que você pode fazer com a nossa aplicação?
                    </h2>
                    <p class="text-sm sm:text-base text-slate-500 leading-relaxed">
                        Desenhada tanto para proporcionar o máximo de conforto aos clientes quanto para dar superpoderes à equipa do restaurante.
                    </p>
                </div>

                <!-- Grid de Funcionalidades -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    
                    <!-- Feature 1 -->
                    <div class="p-6 rounded-3xl bg-slate-50 border border-slate-200/70 hover:shadow-lg transition-all group space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-orange-100 text-orange-600 flex items-center justify-center text-2xl group-hover:bg-orange-500 group-hover:text-white transition-colors">
                            <i class="mdi mdi-qrcode-scan"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Cardápio Digital & QR Code</h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Aponte a câmara para o código da sua mesa e aceda imediatamente ao menu completo, com fotos, descrições detalhadas e preços atualizados sem esperar pelo menu em papel.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="p-6 rounded-3xl bg-slate-50 border border-slate-200/70 hover:shadow-lg transition-all group space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                            <i class="mdi mdi-silverware-clean"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Pedidos Diretos para a Cozinha</h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Personalize os seus pratos, adicione notas especiais (como pontos de carne ou alergias) e envie o pedido diretamente para o monitor da cozinha (KDS) em segundos.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="p-6 rounded-3xl bg-slate-50 border border-slate-200/70 hover:shadow-lg transition-all group space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-2xl group-hover:bg-amber-500 group-hover:text-white transition-colors">
                            <i class="mdi mdi-progress-clock"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Acompanhamento em Tempo Real</h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Acompanhe cada etapa da sua refeição: quando o pedido foi recebido, quando entrou em confeção no fogão e quando o garçom o traz até à sua mesa.
                        </p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="p-6 rounded-3xl bg-slate-50 border border-slate-200/70 hover:shadow-lg transition-all group space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center text-2xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                            <i class="mdi mdi-bell-ring-outline"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Chamar o Garçom com 1 Toque</h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Precisa de talheres adicionais, guardanapos ou pedir a fatura/conta? Clique no botão de chamada e um alerta sonoro discreto soa no terminal do garçom.
                        </p>
                    </div>

                    <!-- Feature 5 -->
                    <div class="p-6 rounded-3xl bg-slate-50 border border-slate-200/70 hover:shadow-lg transition-all group space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-600 flex items-center justify-center text-2xl group-hover:bg-purple-600 group-hover:text-white transition-colors">
                            <i class="mdi mdi-shopping-outline"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Takeaway & Encomendas</h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Prefere comer em casa ou no escritório? Faça a sua encomenda antecipada pela app e chegue ao restaurante apenas para levantar, pronto e quente.
                        </p>
                    </div>

                    <!-- Feature 6 -->
                    <div class="p-6 rounded-3xl bg-slate-50 border border-slate-200/70 hover:shadow-lg transition-all group space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl group-hover:bg-rose-600 group-hover:text-white transition-colors">
                            <i class="mdi mdi-tablet-cellphone"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900">Modo Equipa & POS Portátil</h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Os garçons e gerentes podem iniciar sessão na mesma aplicação para abrir mesas, registar comandas no salão exterior e consultar relatórios de vendas.
                        </p>
                    </div>

                </div>

            </div>
        </section>

        <!-- SECÇÃO: COMO INSTALAR NO ANDROID (PASSO A PASSO) -->
        <section class="py-16 sm:py-24 bg-slate-50 border-b border-slate-200/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <div class="max-w-3xl mx-auto text-center space-y-3 mb-16">
                    <span class="text-xs font-black uppercase tracking-widest text-emerald-600">Guia de Instalação</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                        Como instalar no seu celular em 3 passos
                    </h2>
                    <p class="text-sm text-slate-500">
                        Processo simples e direto sem necessidade de conta na Play Store.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    
                    <!-- Passo 1 -->
                    <div class="relative p-8 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-4 text-center">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white font-black text-xl flex items-center justify-center mx-auto shadow-md shadow-emerald-500/30">
                            1
                        </div>
                        <h3 class="text-lg font-extrabold text-slate-900">Baixar o Arquivo</h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Clique no botão <strong>Baixar APK Android</strong> nesta página. O download do arquivo <code class="bg-slate-100 px-1.5 py-0.5 rounded text-[11px] font-bold">RestPOS-v1.3.apk</code> começará imediatamente.
                        </p>
                    </div>

                    <!-- Passo 2 -->
                    <div class="relative p-8 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-4 text-center">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white font-black text-xl flex items-center justify-center mx-auto shadow-md shadow-emerald-500/30">
                            2
                        </div>
                        <h3 class="text-lg font-extrabold text-slate-900">Abrir o Ficheiro</h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Assim que o download terminar, puxe a barra de notificações do seu Android e toque no ficheiro, ou abra-o através do gestor de ficheiros na pasta <strong>Transferências / Downloads</strong>.
                        </p>
                    </div>

                    <!-- Passo 3 -->
                    <div class="relative p-8 rounded-3xl bg-white border border-slate-200 shadow-sm space-y-4 text-center">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white font-black text-xl flex items-center justify-center mx-auto shadow-md shadow-emerald-500/30">
                            3
                        </div>
                        <h3 class="text-lg font-extrabold text-slate-900">Autorizar e Usar</h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Caso o Android solicite permissão, ative a opção <em>"Permitir desta fonte"</em> e clique em <strong>Instalar</strong>. O ícone do RestPOS aparecerá no seu ecrã pronto a abrir!
                        </p>
                    </div>

                </div>

                <!-- Callout Banner de Download Final -->
                <div class="mt-12 p-8 rounded-3xl bg-slate-900 text-white flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div class="space-y-1 text-center sm:text-left">
                        <h4 class="text-lg font-extrabold">Pronto para começar?</h4>
                        <p class="text-xs text-slate-400">Instale agora e aproveite o melhor atendimento gastronómico.</p>
                    </div>
                    <a href="{{ $apkUrl }}" download="RestPOS-v1.3.apk"
                       class="px-8 py-3.5 rounded-2xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm shadow-lg shadow-emerald-600/30 transition-all flex items-center gap-2">
                        <i class="mdi mdi-download text-lg"></i>
                        <span>Baixar APK Agora ({{ $apkSizeFormatted }})</span>
                    </a>
                </div>

            </div>
        </section>

        <!-- SECÇÃO: PERGUNTAS FREQUENTES (FAQ) -->
        <section class="py-16 sm:py-20 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                
                <div class="text-center space-y-2">
                    <span class="text-xs font-black uppercase tracking-widest text-slate-400">Dúvidas Frequentes</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
                        Perguntas sobre a App
                    </h2>
                </div>

                <div class="space-y-4">
                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80">
                        <h4 class="text-sm font-bold text-slate-900">É seguro instalar o ficheiro APK diretamente?</h4>
                        <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
                            Sim, 100% seguro. O ficheiro APK é compilado e assinado digitalmente pelos próprios servidores oficiais do RestPOS, livre de qualquer malware ou rastreador de terceiros.
                        </p>
                    </div>

                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80">
                        <h4 class="text-sm font-bold text-slate-900">O aplicativo funciona em iPhones / iOS?</h4>
                        <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
                            Em utilizadores com iPhone (iOS), pode aceder ao cardápio pelo Safari e tocar no botão <strong>"Partilhar" &rarr; "Adicionar ao Ecrã Principal"</strong>. O sistema funciona como Progressive Web App (PWA) de forma idêntica à aplicação nativa.
                        </p>
                    </div>

                    <div class="p-6 rounded-2xl bg-slate-50 border border-slate-200/80">
                        <h4 class="text-sm font-bold text-slate-900">Tem algum custo para os clientes?</h4>
                        <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
                            Não, o download e uso da aplicação é completamente gratuito para todos os clientes e utilizadores do restaurante.
                        </p>
                    </div>
                </div>

            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 text-slate-400 text-xs py-8 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <i class="mdi mdi-silverware-fork-knife text-orange-500 text-base"></i>
                <span class="font-bold text-white">{{ $companyName }}</span>
                <span>— App Móvel Powered by RestPOS Suite</span>
            </div>
            <div>
                &copy; {{ date('Y') }} Todos os direitos reservados.
            </div>
        </div>
    </footer>

</body>
</html>
