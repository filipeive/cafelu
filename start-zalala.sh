#!/bin/bash

# Aguardar o sistema inicializar completamente
sleep 10

# Aguardar o nginx estar rodando
while ! systemctl is-active --quiet nginx; do
    sleep 2
done

# Aguardar o MySQL estar rodando
while ! systemctl is-active --quiet mariadb; do
    sleep 2
done

# Aguardar o PHP-FPM estar rodando
while ! systemctl is-active --quiet php8.3-fpm; do
    sleep 2
done

# Aguardar mais um pouco para garantir que tudo esteja funcionando
sleep 5

# Abrir Chromium em modo quiosque com impressão direta
flatpak run org.chromium.Chromium --kiosk --incognito \
--disable-infobars \
--disable-features=TranslateUI \
--disable-session-crashed-bubble \
--disable-component-extensions-with-background-pages \
--disable-web-security \
--disable-popup-blocking \
http://localhost &

