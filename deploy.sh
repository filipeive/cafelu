#!/bin/bash

# Script de Deploy - Zalala Beach Bar
# Execute: chmod +x deploy.sh && ./deploy.sh

set -e  # Para em caso de erro

echo "🚀 Iniciando deploy do Zalala Beach Bar..."

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

PROJECT_PATH="/var/www/html/zalala-beach-bar"
BACKUP_PATH="/var/backups/zalala-beach-bar"

# Função para log colorido
log() {
    echo -e "${GREEN}[$(date +'%Y-%m-%d %H:%M:%S')] $1${NC}"
}

warning() {
    echo -e "${YELLOW}[WARNING] $1${NC}"
}

error() {
    echo -e "${RED}[ERROR] $1${NC}"
}

# 1. Verificar se estamos no diretório correto
log "Verificando diretório do projeto..."
if [ ! -d "$PROJECT_PATH" ]; then
    error "Diretório do projeto não encontrado: $PROJECT_PATH"
    exit 1
fi

cd $PROJECT_PATH

# 2. Fazer backup antes do deploy
log "Criando backup..."
sudo mkdir -p $BACKUP_PATH
sudo tar -czf "$BACKUP_PATH/backup-$(date +%Y%m%d-%H%M%S).tar.gz" \
    --exclude='vendor' \
    --exclude='node_modules' \
    --exclude='storage/logs/*' \
    --exclude='.git' \
    .

# 3. Entrar em modo de manutenção
log "Ativando modo de manutenção..."
sudo php artisan down --message="Deploy em andamento..." --retry=60

# 4. Atualizar código do repositório
log "Atualizando código do repositório..."
sudo git fetch --all
sudo git pull origin main  # ou origin/master se for o caso

# 5. Instalar/atualizar dependências
log "Atualizando dependências do Composer..."
sudo composer install --no-dev --optimize-autoloader --no-interaction

# 6. Limpar todos os caches
log "Limpando caches..."
sudo php artisan config:clear
sudo php artisan cache:clear
sudo php artisan route:clear
sudo php artisan view:clear

# 7. Recriar caches otimizados
log "Recriando caches otimizados..."
sudo php artisan config:cache
sudo php artisan route:cache

# 8. Corrigir permissões
log "Corrigindo permissões..."
sudo chown -R www-data:www-data $PROJECT_PATH
sudo find $PROJECT_PATH -type d -exec chmod 755 {} \;
sudo find $PROJECT_PATH -type f -exec chmod 644 {} \;
sudo chmod -R 775 $PROJECT_PATH/storage/
sudo chmod -R 775 $PROJECT_PATH/bootstrap/cache/

# 9. Reiniciar serviços
log "Reiniciando serviços..."
sudo systemctl reload nginx
sudo systemctl reload php8.3-fpm

# 10. Sair do modo de manutenção
log "Desativando modo de manutenção..."
sudo php artisan up

# 11. Testar se a aplicação responde
log "Testando aplicação..."
if curl -f -s http://localhost/zalala_beach_bar/ > /dev/null; then
    log "✅ Deploy concluído com sucesso!"
else
    error "❌ Aplicação não está respondendo corretamente!"
    warning "Verifique os logs: sudo tail -f /var/log/nginx/error.log"
    exit 1
fi

# 12. Limpar backups antigos (manter apenas últimos 5)
log "Limpando backups antigos..."
sudo find $BACKUP_PATH -name "backup-*.tar.gz" -type f -mtime +7 -delete

log "🎉 Deploy finalizado! Aplicação disponível em: http://163.192.7.41/zalala_beach_bar/"
