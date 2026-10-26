# Guia Oficial de Sincronização Local <-> Nuvem (RestPOS Cloud Sync)

Este documento detalha o funcionamento, configuração e rotina operacional para sincronizar terminais e caixas locais offline do **RestPOS** com o servidor central em nuvem (`http://146.235.224.99/RestPOS`), garantindo controle para o **Dono da Plataforma** e visibilidade em tempo real para o **Dono do Restaurante**.

---

## 1. Como Funciona a Arquitetura de Sincronização

```
[Computador do Caixa / POS Local]
   ├── Opera normalmente 100% offline (quedas de internet não travam as vendas)
   ├── Armazena Vendas, Comandas e Movimentações com `offline_id`
   └── Dispara o comando `php artisan restpos:sync-push` (a cada 5 min ou manual)
                 │
                 │ HTTP POST /api/sync/ingest (Token de Licença)
                 ▼
[Servidor Central em Nuvem (146.235.224.99)]
   ├── Valida Chave Serial e identifica o Tenant (ex: Zambézia Grill)
   ├── Ingestão Idempotente: processa vendas e itens sem nunca duplicar valores
   ├── Atualiza o estoque central, relatórios e dashboard do restaurante
   └── Registra `last_synced_at` e status "Online" para o Dono da Plataforma
```

---

## 2. Configuração no Computador do Cliente (Instalação Local)

No computador local onde o RestPOS foi instalado ou atualizado (ex: caixa do restaurante), configure o arquivo `.env`:

```env
# === CONFIGURAÇÃO DE SINCRONIZAÇÃO COM A NUVEM ===
SYNC_ENABLED=true
SYNC_CLOUD_URL=http://146.235.224.99/RestPOS/api/sync/ingest
SYNC_VERIFY_URL=http://146.235.224.99/RestPOS/api/sync/verify-license

# Chave Serial Oficial do Restaurante gerada pelo Dono da Plataforma
SYNC_TOKEN=CAFE-XXXX-XXXX-XXXX-XXXX

# Identificador exclusivo do Restaurante (Slug)
SYNC_TENANT_SLUG=zambezia-grill
```

> **Dica**: O `SYNC_TOKEN` é a mesma Chave Serial ativada no menu **Minha Licença** (`/license/status`).

---

## 3. Automatização da Sincronização no Cliente

### A. Em Ambientes Linux (Ubuntu / Debian / Raspberry Pi)
Abra o cron do sistema com `crontab -e` e adicione a seguinte linha para sincronizar a cada 5 minutos:

```bash
*/5 * * * * cd /var/www/html/RestPOS && php artisan restpos:sync-push >> /var/log/restpos_sync.log 2>&1
```

### B. Em Ambientes Windows (Agendador de Tarefas / Task Scheduler)
1. Abra o **Agendador de Tarefas do Windows** (`taskschd.msc`).
2. Crie uma Tarefa Básica:
   - **Nome**: `RestPOS Cloud Sync`
   - **Disparador**: Diariamente, repetir a cada `5 minutos`.
   - **Ação**: Iniciar um Programa.
   - **Programa**: `C:\php\php.exe` (ou o executável do PHP).
   - **Argumentos**: `artisan restpos:sync-push`
   - **Iniciar em**: `C:\RestPOS` (caminho da instalação local).

---

## 4. Disparo Manual pelo Próprio Restaurante

Caso o restaurante queira forçar a sincronização de imediato (por exemplo, logo após fechar o turno do dia ou antes de desligar o caixa):
1. O gerente ou operador clica em **Configurações** -> **Minha Licença** (`/license/status`).
2. Localiza o card **Sincronização em Nuvem & Acesso Remoto**.
3. Clica no botão **Sincronizar Agora com a Nuvem**.
4. O sistema transmite todas as vendas pendentes e exibe a mensagem de sucesso com a data e hora atualizada.

---

## 5. Como o Dono do Restaurante Acompanha o Negócio

O dono do restaurante não precisa estar fisicamente no estabelecimento:
1. Ele acede pelo telemóvel, tablet ou notebook a:
   **`http://146.235.224.99/RestPOS/login`**
2. Faz login com o utilizador e senha do seu restaurante (ex: `admin_zambezia`).
3. O painel web exibe instantaneamente:
   - Total faturado no dia (Dinheiro, M-Pesa, E-Mola, Cartão).
   - Comandas e pedidos atendidos.
   - Gráficos de vendas por hora e produtos mais consumidos.
   - Relatórios financeiros e fecho de turnos dos seus operadores.

---

## 6. Como o Dono da Plataforma (SuperAdmin) Mantém o Controle

No menu **Dono da Plataforma** -> **Restaurantes (Tenants)** (`/admin/tenants`):
1. **Status em Tempo Real**: Na coluna "Modo & Limites", o sistema exibe o indicador pulsante verde `Sync: Há 2 minutos` para restaurantes ativos.
2. **Histórico Comercial**: A coluna "Métricas" atualiza o total de vendas transmitidas por cada restaurante.
3. **Bloqueio Remoto com 1 Clique**: Se o cliente deixar de pagar a mensalidade/anuidade, o Dono da Plataforma clica em **Suspender** (`󰏦`). No próximo ciclo de sincronização, a máquina local é bloqueada até a renovação.
4. **Modo Suporte**: O botão **Entrar** (`󰗼`) permite acessar remotamente o ambiente do restaurante para diagnóstico sem pedir a senha do cliente.

