#!/usr/bin/env bash

# ==============================================================================
# RestPOS Cloud & POS Suite v1.3
# Instalador e Configurador de Terminal Kiosk / Desktop para Linux
# Desenvolvido por Fdsmultiservices
# Suporte WhatsApp: (+258) 86 213 4230 | Email: fdsmultiservices@gmail.com
# ==============================================================================

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ICON_PATH="${SCRIPT_DIR}/public/favicon.png"
DESKTOP_DIR="${HOME}/Desktop"
[ -d "${HOME}/Área de Trabalho" ] && DESKTOP_DIR="${HOME}/Área de Trabalho"
APP_DIR="${HOME}/.local/share/applications"
AUTOSTART_DIR="${HOME}/.config/autostart"

mkdir -p "${APP_DIR}" "${AUTOSTART_DIR}" "${DESKTOP_DIR}"

clear
echo "=============================================================================="
echo "       RestPOS RESTAURANT CLOUD & POS SUITE - MOÇAMBIQUE"
echo "       Instalador de Terminal de Venda / KDS / Atendimento (Linux)"
echo "       Desenvolvido por: Fdsmultiservices"
echo "       Suporte Técnico: (+258) 86 213 4230 | fdsmultiservices@gmail.com"
echo "=============================================================================="
echo ""
echo "Escolha a opção de instalação:"
echo ""
echo "  [1] Configurar Terminal POS Kiosk (Modo App Nativo para Balcão/Caixa)"
echo "      - Recomendado para operadores de caixa em Restaurantes e Bares."
echo "      - Cria lançador no Desktop sem barra de navegação ou abas."
echo "      - Ativa suporte a impressão térmica automática (--kiosk-printing)."
echo ""
echo "  [2] Instalar Servidor Local Completo (Modo Offline / On-Premise)"
echo "      - Instala dependências PHP 8.3, banco de dados e serviço contínuo local."
echo ""
echo "  [3] Abrir RestPOS Nuvem imediatamente no navegador"
echo "  [4] Desinstalar Terminal e Atalhos deste computador"
echo "  [0] Sair"
echo ""
read -rp "Digite a opção desejada [1, 2, 3, 4 ou 0]: " OPTION

case "$OPTION" in
    1)
        echo ""
        echo "--- Configuração do Terminal POS Kiosk ---"
        read -rp "Informe a URL do RestPOS [ENTER para Nuvem: http://146.235.224.99/pos ou local: http://127.0.0.1:8000/pos]: " TARGET_URL
        TARGET_URL="${TARGET_URL:-http://146.235.224.99/pos}"

        echo ""
        echo "Formato de exibição:"
        echo "  1) Modo App Nativo (Janela limpa, barra compacta, redimensionável) [Padrão]"
        echo "  2) Modo Kiosk Total (Tela cheia travada para posto de caixa dedicado)"
        read -rp "Escolha [1 ou 2]: " MODE_CHOICE
        MODE_CHOICE="${MODE_CHOICE:-1}"

        echo ""
        read -rp "Deseja iniciar o RestPOS automaticamente ao ligar o computador? [S/n]: " AUTOSTART_CHOICE
        AUTOSTART_CHOICE="${AUTOSTART_CHOICE:-S}"

        # Detectar navegador
        BROWSER=""
        if command -v google-chrome &>/dev/null; then
            BROWSER="google-chrome"
        elif command -v chromium-browser &>/dev/null; then
            BROWSER="chromium-browser"
        elif command -v chromium &>/dev/null; then
            BROWSER="chromium"
        elif command -v brave-browser &>/dev/null; then
            BROWSER="brave-browser"
        elif command -v msedge &>/dev/null; then
            BROWSER="msedge"
        elif command -v firefox &>/dev/null; then
            BROWSER="firefox"
        fi

        if [ -z "$BROWSER" ]; then
            echo "Erro: Nenhum navegador compatível encontrado (Chrome/Chromium/Firefox)."
            exit 1
        fi

        echo "Navegador detectado: ${BROWSER}"

        if [ "$MODE_CHOICE" = "2" ]; then
            EXEC_CMD="${BROWSER} --kiosk ${TARGET_URL} --kiosk-printing --no-first-run --disable-pinch"
        else
            if [ "$BROWSER" = "firefox" ]; then
                EXEC_CMD="firefox --kiosk ${TARGET_URL}"
            else
                EXEC_CMD="${BROWSER} --app=${TARGET_URL} --no-first-run"
            fi
        fi

        DESKTOP_ENTRY="[Desktop Entry]
Version=1.0
Type=Application
Name=RestPOS Terminal POS
Comment=Ponto de Venda e Gestão de Restaurante
Exec=${EXEC_CMD}
Icon=${ICON_PATH}
Terminal=false
Categories=Office;Finance;PointOfSale;
StartupWMClass=${BROWSER}
"

        echo "${DESKTOP_ENTRY}" > "${APP_DIR}/restpos-terminal.desktop"
        chmod +x "${APP_DIR}/restpos-terminal.desktop"

        echo "${DESKTOP_ENTRY}" > "${DESKTOP_DIR}/RestPOS Terminal POS.desktop"
        chmod +x "${DESKTOP_DIR}/RestPOS Terminal POS.desktop"

        if [ "$AUTOSTART_CHOICE" = "S" ] || [ "$AUTOSTART_CHOICE" = "s" ]; then
            echo "${DESKTOP_ENTRY}" > "${AUTOSTART_DIR}/restpos-terminal.desktop"
            echo "Inicialização automática configurada com sucesso."
        fi

        echo ""
        echo "Sucesso! O atalho 'RestPOS Terminal POS' foi criado na Área de Trabalho."
        echo "URL Configurada: ${TARGET_URL}"
        ;;

    2)
        echo ""
        echo "--- Instalação de Servidor Local RestPOS (Modo Offline) ---"
        echo "Verificando dependências do sistema..."

        if ! command -v php &>/dev/null; then
            echo "PHP não encontrado. Instalando PHP e extensões necessárias..."
            sudo apt-get update
            sudo apt-get install -y php php-cli php-mysql php-sqlite3 php-curl php-gd php-mbstring php-xml php-zip composer
        fi

        cd "${SCRIPT_DIR}"
        echo "Instalando pacotes PHP..."
        composer install --no-dev --optimize-autoloader

        if [ ! -f .env ]; then
            cp .env.example .env
            php artisan key:generate
        fi

        php artisan migrate --force
        php artisan storage:link || true

        echo ""
        echo "Criando serviço de background do RestPOS..."
        SERVICE_FILE="/etc/systemd/system/restpos.service"
        USER_NAME="$(whoami)"

        sudo bash -c "cat > ${SERVICE_FILE}" <<EOF
[Unit]
Description=RestPOS Local Server Daemon
After=network.target

[Service]
Type=simple
User=${USER_NAME}
WorkingDirectory=${SCRIPT_DIR}
ExecStart=/usr/bin/php artisan serve --host=0.0.0.0 --port=8000
Restart=always
RestartSec=3

[Install]
WantedBy=multi-user.target
EOF

        sudo systemctl daemon-reload
        sudo systemctl enable restpos.service
        sudo systemctl restart restpos.service

        echo "Servidor local instalado e ativo em http://127.0.0.1:8000"
        ;;

    3)
        xdg-open "http://146.235.224.99/pos" 2>/dev/null || sensible-browser "http://146.235.224.99/pos" 2>/dev/null || true
        ;;

    4)
        rm -f "${DESKTOP_DIR}/RestPOS Terminal POS.desktop"
        rm -f "${APP_DIR}/restpos-terminal.desktop"
        rm -f "${AUTOSTART_DIR}/restpos-terminal.desktop"
        if [ -f "/etc/systemd/system/restpos.service" ]; then
            sudo systemctl stop restpos.service || true
            sudo systemctl disable restpos.service || true
            sudo rm -f "/etc/systemd/system/restpos.service"
            sudo systemctl daemon-reload
        fi
        echo "RestPOS desinstalado com sucesso deste computador."
        ;;

    *)
        echo "Operação cancelada."
        ;;
esac
