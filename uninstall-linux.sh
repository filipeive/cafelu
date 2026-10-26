#!/usr/bin/env bash

# ==============================================================================
# RestPOS - Desinstalador de Terminal e Atalhos para Linux
# ==============================================================================

set -e

DESKTOP_DIR="${HOME}/Desktop"
[ -d "${HOME}/Área de Trabalho" ] && DESKTOP_DIR="${HOME}/Área de Trabalho"
APP_DIR="${HOME}/.local/share/applications"
AUTOSTART_DIR="${HOME}/.config/autostart"

echo "Removendo atalhos do RestPOS..."
rm -f "${DESKTOP_DIR}/RestPOS Terminal POS.desktop"
rm -f "${APP_DIR}/restpos-terminal.desktop"
rm -f "${AUTOSTART_DIR}/restpos-terminal.desktop"

if [ -f "/etc/systemd/system/restpos.service" ]; then
    echo "Parando e removendo serviço de sistema..."
    sudo systemctl stop restpos.service || true
    sudo systemctl disable restpos.service || true
    sudo rm -f "/etc/systemd/system/restpos.service"
    sudo systemctl daemon-reload
fi

echo "Desinstalação do RestPOS concluída com sucesso."

