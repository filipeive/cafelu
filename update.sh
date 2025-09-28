#!/bin/bash
# Apenas para após git pull manual
cd /var/www/html/zalala-beach-bar
sudo php artisan down
sudo composer install --no-dev
sudo php artisan config:clear && sudo php artisan cache:clear
sudo chown -R www-data:www-data .
sudo chmod -R 775 storage/ bootstrap/cache/
sudo systemctl reload nginx php8.3-fpm
sudo php artisan up
echo "Atualização concluída!"
