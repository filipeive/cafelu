#!/bin/bash

# Aguardar o sistema inicializar completamente
sleep 10

# Dar tempo extra só por segurança
sleep 5

# Abrir Chromium em modo quiosque apontando para o servidor remoto
# (sem --kiosk-printing, assim o usuário escolhe a impressora)
flatpak run org.chromium.Chromium --kiosk --incognito \
--disable-infobars \
--disable-features=TranslateUI \
--disable-session-crashed-bubble \
--disable-component-extensions-with-background-pages \
--disable-web-security \
--disable-popup-blocking \
--unsafely-treat-insecure-origin-as-secure=http://146.235.224.99 \
http://146.235.224.99/zalala_beach_bar/login &

