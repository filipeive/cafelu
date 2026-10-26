# RestPOS - Sistema de Gestão de Restaurantes & POS Cloud Suite

## 1. Visão Geral do Sistema

O **RestPOS** (anteriormente cafelu) é uma plataforma híbrida completa para restaurantes, cafés, bares e padarias desenvolvida em **PHP 8.2+ / Laravel**, **MySQL / MariaDB**, **TailwindCSS**, **Alpine.js** e componentes de auto-atendimento interativos.

O sistema integra:
- **Ponto de Venda (PDV/POS)** otimizado para telas sensíveis ao toque com leitura de código de barras e impressão térmica contínua.
- **Controle de Turnos de Caixa & Fecho Cego** com emissão de relatórios de Fecho Z (80mm).
- **Ordem de Cozinha Digital (KDS)** em tempo real com tempos de preparo e status por prato.
- **Cardápio Digital de Auto-atendimento (Self-service)** via QR Code de mesa (`/m/{mesa}`) sem necessidade de app externo.
- **Módulo de Licenciamento & Faturamento do Dono do Sistema** com geração de chaves seriais criptografadas.
- **PWA Mobile e Instaladores Desktop Kiosk** para Linux e Windows.

---

## 2. Isolamento de Papéis e Permissões

Para assegurar o isolamento entre o **Dono do Software** (quem comercializa e fatura as licenças) e os **Donos dos Restaurantes** (clientes que utilizam o sistema), a hierarquia de papéis é rigorosamente segregada:

| Papel (`role`) | Descrição e Escopo de Acesso | Área Exclusiva |
| :--- | :--- | :--- |
| **`superadmin`** | **Dono da Plataforma / Sistema**. Controla todas as instalações, gera chaves seriais, define validade, suspende/reativa licenças e monitora o faturamento das licenças. | `/admin/licenses` (Gerador de Seriais) |
| **`admin`** | **Proprietário / Gerente Geral do Restaurante**. Administra seu próprio restaurante, cardápio, estoque, mesas, garçons, finanças e ativa a licença do seu estabelecimento. Não tem acesso ao gerador de chaves. | `/license/status` (Ativação de Chave) |
| **`manager`** | Gerente Operacional. Abre/fecha caixas, gerencia dívidas, mesas e relatórios de turnos. | `/shifts`, `/reports`, `/debts` |
| **`cashier` / `waiter`**| Operadores de Caixa e Garçons. Realizam vendas no POS, atendem mesas e registram pedidos. | `/pos`, `/tables`, `/orders` |
| **`chef`** | Equipe de Cozinha / Bar. Controla a esteira de pedidos no monitor KDS. | `/orders/kitchen` |
| **`customer`** | Clientes finais. Acessam cardápio digital via QR Code, acompanham status do pedido. | `/welcome`, `/customer/dashboard` |

### Credenciais Padrão do SuperAdmin:
- **Login / Username**: `superadmin`
- **Senha Inicial**: `superadmin1234`
- **Comando de Criação/Atualização**: `php artisan admin:create --username=superadmin --role=superadmin`

---

## 3. Sistema de Licenciamento & Faturamento

Inspirado na arquitetura do ZBIZ, o RestPOS conta com um gerador e validador de licenças:
- **Formato da Chave**: `CAFE-XXXX-XXXX-XXXX-XXXX` (16 caracteres alfanuméricos com verificação criptográfica SHA-256).
- **Tipos de Licença**:
  - `trial`: 15 dias de testes gratuitos.
  - `monthly`: 30 dias com aviso prévio de expiração.
  - `quarterly`: 90 dias.
  - `annual`: 365 dias.
  - `lifetime`: Licença perpétua sem data de expiração.
- **Limites Parametrizáveis**:
  - Quantidade máxima de mesas ativas simultâneas.
  - Quantidade máxima de operadores/utilizadores.
- **Hardware Fingerprint**:
  - Hash exclusivo gerado pelo servidor local (Hostname + IP + Hardware signature) para permitir validação offline ou amarração de máquina.
- **Página do Restaurante (`/license/status`)**:
  - Apresenta os dias restantes, status ativo/expirado, limites e campo para inserção de nova chave fornecida pelo Dono do Sistema.

---

## 4. Sistema de Turnos de Caixa & Fecho Z

O sistema segue as normas de auditoria e controle de caixa cego:
1. **Abertura de Turno**:
   - O operador informa o **Fundo de Maneio Inicial** com atalhos rápidos (`0`, `100`, `200`, `500`, `1000`, `2000` MZN) e observações.
2. **Troca Rápida de Operador**:
   - Na barra superior do POS, é possível selecionar outro membro da equipe cadastrada para assumir o caixa sem deslogar da sessão.
3. **Associação Automática**:
   - Todas as vendas efetuadas no POS alimentam os totais do turno ativo (`cash_sales`, `card_sales`, `mpesa_sales`, `emola_sales`, `total_sales`).
4. **Fecho Cego (Blind Closing)**:
   - No encerramento, o operador conta as notas e moedas físicas na gaveta e introduz o valor real.
   - O sistema calcula o saldo esperado (`Fundo Inicial + Vendas Dinheiro`) e gera a **Diferença (Quebra ou Sobra)**.
5. **Talão de Fecho Z (80mm)**:
   - Emissão instantânea do relatório térmico no formato padrão de impressoras de talões para conferência e assinatura de supervisor.
6. **Histórico & Auditoria (`/shifts`)**:
   - Painel para administradores com KPIs de turnos abertos, quebras acumuladas, faturamento e filtro por data e operador.

---

## 5. POS: Guardar (Hold) & Recuperar (Retrieve)

- **Fluxo Corrigido e Sincronizado**:
  - Guardar um pedido vincula o carrinho a uma mesa física ou cria uma comanda/mesa temporária identificada (`9000+`).
  - Recuperar carrega o pedido no POS exibindo o banner informativo de pedido vinculado.
  - Concluir o pagamento do pedido recuperado marca o registro como `completed` / `paid`, desvincula e limpa mesas temporárias, ou libera mesas fixas (`free`).
  - Adicionado botão de descarte direto (`pos.discard-order`) para cancelamento rápido caso o cliente desista.

---

## 6. PWA Mobile & Aplicativo Web Nativo

- **Manifesto PWA (`public/manifest.json`)**:
  - Ícones adaptativos, tema laranja (`#f97316`) e fundo escuro (`#111827`).
  - Modos de visualização `standalone` sem barra de endereço.
  - Atalhos diretos para PDV, Cozinha KDS, Mesas e Dashboard.
- **Service Worker (`public/sw.js`)**:
  - Cache de recursos estáticos (CSS, JS, fontes, ícones) para carregamento rápido e suporte a instabilidade de rede.

---

## 7. Instaladores Desktop (Linux & Windows)

Arquivos de instalação rápida localizados na raiz do projeto:

### Linux (`install-linux.sh`):
- Executar via terminal: `./install-linux.sh`
- Opção 1: Configura o navegador (Chrome, Chromium, Edge, Brave, Firefox) em modo **App Nativo** (`--app`) ou **Kiosk Total** (`--kiosk --kiosk-printing`) e gera o atalho `RestPOS Terminal POS.desktop` na Área de Trabalho e no menu de aplicativos.
- Opção 2: Instala servidor local autônomo com serviço systemd em segundo plano (`restpos.service`).
- Desinstalador: `./uninstall-linux.sh`

### Windows (`install-windows.bat`):
- Executar com duplo clique.
- Cria o atalho nativo na Área de Trabalho do Windows apontando para o terminal POS com o Edge ou Chrome em modo janela limpa.
- Permite configurar e iniciar o servidor local offline na porta 8000.

---

## 8. Deploy em Produção (Oracle Cloud & Nginx)

### Servidor de Produção:
- **Host**: `146.235.224.99`
- **Usuário**: `ubuntu`
- **Chave SSH**: `/home/fdev-ms/.ssh/oracle-2025`
- **Diretório de Destino**: `/var/www/html/RestPOS`
- **Branch**: `v1.3`

### Execução do Deploy:
```bash
./deploy.sh
```
O script verifica o commit local em relação ao `origin/v1.3`, conecta via SSH, sincroniza o código, instala pacotes PHP com otimização, executa as migrações, recria o link de storage, limpa caches e reinicia o PHP-FPM e Nginx automaticamente.

### Configuração do Nginx:
O arquivo `nginx/restpos.conf` contém o bloco pronto para apontar para `/var/www/html/RestPOS/public`, com timeouts para impressões, cabeçalhos de segurança, suporte a PWA e PHP 8.3 FastCGI.

---

## 9. Arquitetura Multi-Tenant & Painel do Dono da Plataforma (Implementado)

O sistema agora opera no modelo **Multi-Tenant Nativo com Isolamento por `tenant_id` e Suporte a Impersonação 1-Click (Padrão ZBIZ)**:

```
[ Nuvem Central (Oracle Cloud: /var/www/html/RestPOS) ]
     |
     +---> SuperAdmin: Gestão Multi-Restaurantes (/admin/tenants) & Licenças (/admin/licenses)
     |          |
     |          +---> Métricas SaaS Executivas: MRR, ARR, Contratos Ativos e Renovações
     |          +---> Acesso com 1-Click (Impersonate) para Suporte Técnico Imediato
     |          +---> Emissão de Certificados Oficiais em PDF / HTML com Validação
     |
     +---> Tenant 1 (Lu & Yoshi Cafe - Matriz)    [tenant_id = 1] -> Isolamento Total
     +---> Tenant 2 (Restaurante Zambézia Grill) [tenant_id = 2] -> Isolamento Total
     +---> Tenant N (Outros Clientes Contratados) [tenant_id = N] -> Isolamento Total
```

### Componentes de Software:
1. **`App\Models\Tenant`**:
   - Campos: `name`, `slug`, `subdomain`, `nuit`, `phone`, `email`, `city`, `plan`, `installation_mode`, `max_tables`, `max_users`, `license_expires_at`, `status`.
2. **`App\Services\TenantContext`**:
   - Singleton registrado em `AppServiceProvider` que mantém o contexto do restaurante ativo. Suporta o método `withoutTenant(callable)` para que o SuperAdmin visualize dados globais ou de suporte sem interferência de escopos.
3. **`App\Scopes\TenantScope` & `App\Traits\BelongsToTenant`**:
   - Todas as entidades do restaurante (`Product`, `Category`, `Table`, `Order`, `Sale`, `CashShift`, `Expense`, `Client`) utilizam a trait `BelongsToTenant`.
   - Todas as operações de leitura e escrita (`find`, `all`, `where`, `create`) filtram e gravam automaticamente o `tenant_id` correspondente.
4. **`App\Http\Middleware\IdentifyTenant`**:
   - Resolução transparente por:
     - **Modo Suporte (Impersonação)**: `session('is_support_mode')` e `session('current_tenant_id')`.
     - **Utilizador Autenticado**: `auth()->user()->tenant_id`.
     - **API / Terminais PDV Offline**: Cabeçalhos HTTP `X-Tenant-Slug` ou `X-Tenant-ID`.
     - **Subdomínio**: `slug.restpos.co.mz`.
5. **Painel do Dono da Plataforma (`/admin/tenants`)**:
   - **Métricas SaaS**: MRR (Receita Recorrente Mensal), ARR, Total de Empresas, Ativos, Trials e a expirar nos próximos 30 dias.
   - **Cadastro Guiado**: Criação de novo restaurante com provisionamento automático de conta administrador e chave de licença com limites operacionais.
   - **Suporte 1-Click (Impersonate)**: O Dono da Plataforma acessa o ambiente de qualquer cliente sem necessitar da senha dele. Uma barra superior de aviso permite retornar ao Painel do Dono a qualquer momento.
   - **Certificado Oficial de Licenciamento**: Tela interativa e exportação em PDF de alta qualidade com brasão, QR Code, NUIT e validação para entrega ao cliente.


