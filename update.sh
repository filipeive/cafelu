#!/bin/bash

echo "=== Atualizando projeto Laravel ==="

# Corrigir permissões antes do pull
sudo chown -R $USER:www-data /var/www/html/zalala-beach-bar
sudo find /var/www/html/zalala-beach-bar -type f -exec chmod 664 {} \;
sudo find /var/www/html/zalala-beach-bar -type d -exec chmod 775 {} \;

# Atualizar código do repositório
cd /var/www/html/zalala-beach-bar
git pull origin main

# ou, se já estiver instalado:
composer dump-autoload -o

# Limpar caches antigos
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Recompilar caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Corrigir permissões de storage e cache
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Reiniciar PHP-FPM para aplicar alterações
sudo systemctl restart php8.3-fpm

echo "=== Atualização concluída com sucesso! 🚀 ==="
