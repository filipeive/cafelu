# 📱 Guia de Compilação Mobile & PWA — RestPOS v1.3

Este documento detalha como operar e compilar o **RestPOS** para **PWA (Progressive Web App)** e **Aplicação Mobile Nativa (Android APK via Capacitor)**.

---

## 🌐 1. PWA (Progressive Web App)

A aplicação conta com suporte nativo a PWA tanto no portal do cliente quanto no painel operacional:

- **Manifesto**: `public/manifest.json` com nome, ícones em alta resolução (192px, 512px, maskable), cores do restaurante (`#f97316`) e atalhos rápidos de PDV, Mesas e Cozinha.
- **Service Worker**: `public/sw.js` com estratégia Stale-While-Revalidate para ativos estáticos, Network-First para navegação HTML com contingência offline e bypass seguro para chamadas de faturação, impressão e polling de cozinha em tempo real.
- **Barra de Navegação Inferior (Mobile Bottom Navigation)**: Integrada no layout para ecrãs móveis (`lg:hidden`), oferecendo acesso com 1 toque a Início, Mesas, Frente de Caixa Elevado (PDV), Cozinha e Menu.
- **Banner de Instalação Automático**: Notifica os utilizadores no smartphone com botão "Instalar App RestPOS" para fixação no ecrã inicial.

---

## 📱 2. Configuração do Capacitor (Android)

A aplicação móvel nativa está configurada no ficheiro `capacitor.config.json`:
- **App ID**: `mz.co.restpos.app`
- **App Name**: `RestPOS`
- **Servidor Ativo**: `http://146.235.224.99/RestPOS/`
- **Tráfego Seguro / Cleartext**: Ativado no AndroidManifest (`android:usesCleartextTraffic="true"`).
- **Splash Screen & Status Bar**: Tema Dark (`#0f172a`) com acento Laranja (`#f97316`).

---

## 🛠️ 3. Pré-requisitos de Compilação

- **Node.js** >= 18 (`v22.x` recomendado)
- **Java JDK** 21 (`/home/fdev-ms/.jdks/jbr-21.0.11`)
- **Android SDK** (`/home/fdev-ms/Android/Sdk`)

---

## 🚀 4. Comandos para Sincronizar e Compilar

### A. Compilação Rápida Automatizada (Script)
Para gerar o ficheiro APK com um único comando:
```bash
./build-apk.sh
```
O ficheiro final será copiado diretamente para a raiz do projeto:
`RestPOS-v1.3.apk`

### B. Sincronização Manual de Recursos Web
```bash
npx cap sync android
```

### C. Compilar via Gradle
```bash
cd android
./gradlew assembleDebug
```
O binário gerado estará em:
`android/app/build/outputs/apk/debug/app-debug.apk`

### D. Abrir no Android Studio (Opcional)
```bash
npm run cap:open
```

---

## 🎨 5. Recursos Mobile Ativos
1. **Frente de Caixa (POS) Touch**: Operação otimizada para terminais móveis e tablets.
2. **Dual-View Responsivo**: Listagens essenciais (ex: Vendas e Pedidos) adaptam-se automaticamente de tabela para cartões compactos em telemóveis.
3. **Safe Area Insets**: Ajuste perfeito a entalhes de ecrã (notches) via `viewport-fit=cover` e `env(safe-area-inset-bottom)`.
4. **Gaveta Lateral Deslizante**: Acesso ao menu completo administrativo a qualquer momento.
