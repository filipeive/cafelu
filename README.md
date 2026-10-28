# RestPOS Suite — Plataforma Multi-Restaurante SaaS & POS

<p align="center">
  <img src="public/assets/images/logo.png" width="120" alt="RestPOS Logo" onerror="this.style.display='none'">
</p>

<p align="center">
  <strong>Sistema Completo de Gestão de Restaurantes, POS Touchscreen, Faturação Moçambicana, QR Code na Mesa e Portal Multi-Restaurante SaaS</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10.x-red.svg" alt="Laravel 10">
  <img src="https://img.shields.io/badge/PHP-8.1%2B-blue.svg" alt="PHP 8.1+">
  <img src="https://img.shields.io/badge/Architecture-Multi--Tenant%20SaaS-indigo.svg" alt="Multi-Tenant">
  <img src="https://img.shields.io/badge/Invoicing-FT%20%7C%20FR%20%7C%20RC%20%7C%20FP%20%7C%20COT-emerald.svg" alt="Faturação">
  <img src="https://img.shields.io/badge/License-Proprietary-orange.svg" alt="Licença">
</p>

---

## 📌 Visão Geral do Sistema

O **RestPOS** é uma solução para o setor de restauração, hotelaria, cafetarias, bares e lounges. Funciona tanto como **SaaS Cloud** centralizado com múltiplos restaurantes isolados quanto em regime **Local / Híbrido** com sincronização.

O sistema divide-se em duas camadas:
1. **Consola do Dono da Plataforma (SuperAdmin / SaaS Controller)**: Gestão central de todos os restaurantes (tenants), emissão de chaves de licença, monitorização MRR/ARR, isolamento de dados, auditoria de segurança global e modo de suporte técnico.
2. **Ambiente Operacional do Restaurante (Tenant)**: Terminal POS de alta velocidade, KDS (Ecrã de Cozinha), gestão de mesas interativas com QR Code individual, pedidos online pelo cliente (autoatendimento), faturação e cotações com IVA, controlo de stocks e turnos de caixa com fecho cego.

---

## 🌟 Principais Funcionalidades

### 1. Arquitetura Multi-Tenant com Isolamento
- **Isolamento de Base de Dados**: Produtos, categorias, pedidos, vendas, despesas, turnos, stocks e mesas são estritamente isolados por `tenant_id` via global scopes do Eloquent.
- **Configurações Independentes (`/settings`)**: Cada restaurante define o seu nome comercial, logótipo, cores temáticas, NUIT, taxa de IVA, moeda (`MZN`, etc.) e textos do cardápio digital sem colisões.
- **Auditoria Diferenciada (`/audit-logs`)**:
  - *Dono da Plataforma*: Central de Segurança SaaS com KPIs de eventos globais, rastreio de sessões de suporte, histórico de logins e provisionamento.
  - *Restaurante*: Registo de atividades operacionais restrito à equipa interna.
- **Modo Suporte Técnico**: O SuperAdmin pode entrar no ambiente de qualquer restaurante em modo de suporte seguro sem conhecer a palavra-passe do cliente e com registo de auditoria.

### 2. Portal Público & Cardápio Digital por Restaurante
- **Diretório da Rede (`/`)**: Aceder à raiz exibe a **Rede de Restaurantes Registados**, permitindo pesquisar estabelecimentos por cidade, ver dados de contacto e aceder diretamente ao cardápio de cada um.
- **Acesso Dedicado por Restaurante**:
  - `http://servidor/RestPOS/{slug}` (ex: `/RestPOS/lu-yoshi`)
  - `http://servidor/RestPOS/{id}` (ex: `/RestPOS/1`)
  - `http://servidor/RestPOS/r/{slug}`
- **QR Code Interativo de Mesa (`/r/{slug}/m/{mesa}`)**: O cliente escaneia o código impresso na mesa física, o sistema reconhece a mesa de imediato, abre o cardápio em modo presencial e permite enviar pedidos à cozinha ou somar itens à comanda em aberto.
- **Chamada de Garçom com Alerta Sonoro**: Botão dedicado no cardápio móvel que notifica a equipa no POS e na cozinha com som de aviso.
- **Geofencing GPS Automático**: Alterna entre consumo na mesa e encomenda para levar (takeaway) com base na proximidade física do restaurante.

### 3. Ponto de Venda (POS) & Caixa
- Interface tátil otimizada para terminais e ecrãs tácteis.
- Retenção e recuperação rápida de comandas suspensas.
- Suporte para métodos de pagamento em Moçambique: Dinheiro, Cartão, M-Pesa, e-Mola e mKesh.
- Impressão térmica de talões de 80mm com logotipo, NUIT e discriminação de impostos.
- **Gestão de Turnos de Caixa (Fecho Z)**: Abertura com fundo de maneio e fecho cego (o operador digita os valores sem ver o esperado, evitando desvios).

### 4. Cozinha & Despacho (KDS - Kitchen Display System)
- Ecrã dinâmico em tempo real para os cozinheiros.
- Estados de preparação: *Pendente* ➔ *Em Preparo* ➔ *Pronto* ➔ *Entregue*.
- Rastreamento de tempo decorrido e notas especiais do cliente.

### 5. Documentos Comerciais & Faturação Moçambicana
- **Cotações / Proformas (`COT-YYYY/XXXX`)**: Geração em A4 PDF com dados completos da entidade, validade e conversão direta em venda com 1 clique.
- **Documentos Fiscais**:
  - **FR**: Factura-Recibo (Venda a pronto pagamento)
  - **FT**: Factura Comercial (Venda a crédito)
  - **RC**: Recibo de Quitação
  - **FP**: Factura Proforma
- Cálculo automático de IVA normal ou regime de isenção com indicação do NUIT.

---

## 🚀 Requisitos do Sistema

- **PHP**: 8.1 ou superior (extensões: `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`, `json`, `mbstring`, `openssl`, `pdo_mysql`, `tokenizer`, `xml`, `gd`)
- **Base de Dados**: MySQL 8.0+ ou MariaDB 10.4+
- **Servidor Web**: Apache 2.4+ (com `mod_rewrite`) ou Nginx
- **Composer**: 2.x
- **Node.js / NPM**: 18+ (opcional para compilação de assets locais)

---

## 🛠️ Instalação e Configuração

### 1. Clonar o Repositório
```bash
git clone https://github.com/filipeive/cafelu.git
cd cafelu
```

### 2. Instalar Dependências PHP
```bash
composer install --no-dev --optimize-autoloader
```

### 3. Configurar Variáveis de Ambiente
Copie o ficheiro de exemplo e configure a base de dados:
```bash
cp .env.example .env
php artisan key:generate
```

No `.env`, ajuste a ligação à base de dados e URL:
```ini
APP_NAME=RestPOS
APP_ENV=production
APP_DEBUG=false
APP_URL=http://seu-dominio.com/RestPOS

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=restpos_db
DB_USERNAME=restpos_user
DB_PASSWORD=sua_senha
```

### 4. Executar Migrações e Seeders
```bash
php artisan migrate --force
php artisan db:seed --force
```

### 5. Configurar Storage e Permissões
```bash
php artisan storage:link
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 6. Otimizar a Aplicação
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🔑 Contas de Acesso Padrão

| Nível / Papel | Username / Email | Função |
| :--- | :--- | :--- |
| **Dono da Plataforma** | `superadmin` / `superadmin@restpos.co.mz` | Gestão de Tenants, Licenças SaaS e Segurança Global |
| **Admin do Restaurante** | `admin` | Gestão do Restaurante, Stocks, Utilizadores e Configurações |
| **Operador de Caixa** | `cashier` | Operação do Terminal POS e Turnos |
| **Garçom / Atendente** | `waiter` | Registo de Pedidos de Mesa |

---

## 📂 Estrutura de Rotas e Endpoints

```
/                                   -> Portal da Rede de Restaurantes (Marketplace)
/{tenant}                           -> Cardápio Digital do Restaurante (ex: /lu-yoshi)
/r/{tenant}                         -> Rota canónica do restaurante
/r/{tenant}/m/{mesa}                -> Acesso QR Code com mesa pré-selecionada
/login                              -> Autenticação de utilizadores
/dashboard                          -> Painel administrativo do restaurante
/pos                                -> Terminal Ponto de Venda Touchscreen
/kitchen                            -> Ecrã de pedidos da Cozinha (KDS)
/tables                             -> Mapa de Mesas e Impressão de QR Codes
/quotations                         -> Emissão e conversão de Cotações
/sales                              -> Faturas, Recibos e Documentos Oficiais
/shifts                             -> Gestão e Fecho de Turnos de Caixa
/settings                           -> Configurações isoladas da empresa/plataforma
/audit-logs                         -> Auditoria Operacional (Tenant) ou SaaS (Dono)
/admin/tenants                      -> Consola Multi-Restaurante (Exclusivo SuperAdmin)
/admin/licenses                     -> Gestão de Chaves de Licenciamento SaaS
```

---

## 🧪 Testes Automatizados

A suite de testes cobre o isolamento multi-tenant, ciclo de vida das cotações e faturas, contingência de QR codes e auditoria:

```bash
# Executar todos os testes
php artisan test

# Executar testes específicos de isolamento e funcionalidades críticas
php artisan test tests/Feature/QrTablesQuotationsAndTenantIsolationTest.php
php artisan test tests/Feature/Website/WebsiteCmsTest.php
```

---

## 📄 Licença

Software proprietário. Todos os direitos reservados à equipa de desenvolvimento do RestPOS.
Proibida a distribuição, cópia ou engenharia reversa sem autorização expressa por escrito.
