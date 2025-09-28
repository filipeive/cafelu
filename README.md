# 🏖️ ZALALA BEACH BAR - Sistema de Gestão

Sistema completo de gestão para restaurantes e bares desenvolvido em Laravel, focado especificamente nas necessidades do **ZALALA BEACH BAR** em Moçambique.

## 📋 Sobre o Sistema

O sistema ZALALA é uma solução abrangente de gestão que integra todas as operações de um restaurante/bar, desde o ponto de venda até relatórios financeiros detalhados, passando por controle de estoque, gestão de mesas e análise de performance.

## ✨ Funcionalidades Principais

### 🛒 **PDV (Ponto de Venda)**
- Interface moderna e intuitiva para vendas rápidas
- Suporte a produtos e serviços
- Múltiplos métodos de pagamento (dinheiro, cartão, transferência, crédito)
- Integração com sistema de mesas
- Impressão de recibos e faturas

### 🍽️ **Gestão de Mesas**
- Controle de ocupação de mesas em tempo real
- Associação de pedidos às mesas
- Status visual das mesas (livre, ocupada, reservada)
- Gestão de reservas

### 👨‍🍳 **Cozinha**
- Dashboard específico para a cozinha
- Gestão de pedidos ativos
- Controle de status dos itens (pendente, em preparo, pronto)
- Interface otimizada para ambiente de produção

### 📱 **Cardápio Digital**
- Visualização organizada por categorias
- Informações detalhadas de produtos
- Status de disponibilidade em tempo real
- Interface responsiva para diferentes dispositivos

### 📦 **Controle de Estoque**
- Gestão completa de produtos e serviços
- Controle de estoque com alertas de nível baixo
- Movimentações de estoque detalhadas
- Categorização de produtos
- Diferenciação entre produtos físicos e serviços

### 💰 **Gestão Financeira**
- Controle de vendas detalhado
- Gestão de despesas operacionais
- Cálculo automático de lucros e margens
- Fluxo de caixa
- Múltiplos relatórios financeiros

### 📊 **Relatórios e Análises**
- **Vendas**: Diárias, mensais, por produto, especializados
- **Financeiro**: Lucro/prejuízo, fluxo de caixa, despesas
- **Análises**: Rentabilidade de clientes, análise ABC, comparações de período
- **Estoque**: Produtos em baixa, inventário
- Exportação em PDF, Excel e CSV

### 👥 **Gestão de Pessoas**
- Cadastro e controle de funcionários
- Gestão de usuários do sistema com diferentes níveis de acesso
- Controle de permissões por função (admin, gerente, caixa, garçom, cozinheiro)

### 🤝 **Relacionamento com Clientes**
- Cadastro de clientes
- Histórico de compras
- Análise de rentabilidade por cliente

## 🏗️ Arquitetura Técnica

### **Backend**
- **Framework**: Laravel (versão atual baseada no código)
- **Linguagem**: PHP 8+
- **Banco de Dados**: MySQL/PostgreSQL
- **Autenticação**: Laravel Sanctum/Passport
- **Middleware**: Sistema de permissões personalizado

### **Frontend**
- **CSS Framework**: Bootstrap 5
- **Icons**: Material Design Icons (MDI)
- **JavaScript**: Vanilla JS + Chart.js para gráficos
- **Design System**: Tema customizado "Beach" com gradientes e cores do mar
- **Responsividade**: Mobile-first approach

### **Design System**
```css
/* Cores principais */
--primary-color: #0891b2     /* Azul oceano */
--secondary-color: #f59e0b   /* Amarelo sol */
--accent-color: #06b6d4      /* Azul claro */
--success-color: #10b981     /* Verde */
--warning-color: #f59e0b     /* Laranja */
--danger-color: #ef4444      /* Vermelho */

/* Gradientes temáticos */
--beach-gradient: linear-gradient(135deg, #0891b2 0%, #06b6d4 50%, #fbbf24 100%)
--sunset-gradient: linear-gradient(135deg, #f59e0b 0%, #fb923c 50%, #f97316 100%)
```

## 🚀 Instalação

### Pré-requisitos
- PHP 8.1+
- Composer
- Node.js & NPM
- MySQL 8.0+ ou PostgreSQL
- Servidor web (Apache/Nginx)

### Passos de Instalação

1. **Clone o repositório**
```bash
git clone https://github.com/seu-usuario/zalala-beach-bar.git
cd zalala-beach-bar
```

2. **Instale as dependências**
```bash
composer install
npm install && npm run build
```

3. **Configure o ambiente**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure o banco de dados no `.env`**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=zalala_db
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

5. **Execute as migrações**
```bash
php artisan migrate --seed
```

6. **Configure o storage**
```bash
php artisan storage:link
```

7. **Inicie o servidor**
```bash
php artisan serve
```

## 👤 Sistema de Usuários e Permissões

### **Níveis de Acesso**

| Função | Permissões Principais |
|--------|----------------------|
| **Admin** | Acesso total ao sistema, gestão de usuários, relatórios financeiros |
| **Manager** | Gestão operacional, relatórios, controle de estoque |
| **Cashier** | PDV, vendas, consulta de produtos |
| **Waiter** | Gestão de mesas, pedidos, consulta de cardápio |
| **Cook** | Dashboard da cozinha, gestão de pedidos ativos |

### **Middleware de Permissões**
```php
// Exemplo de uso no sistema
PermissionHelper::can('view_products')
PermissionHelper::canAll(['view_products', 'create_sales'])
PermissionHelper::canAny(['view_orders', 'manage_tables'])
```

## 📱 Interface e Experiência do Usuário

### **Características do Design**
- **Tema Beach**: Cores inspiradas no mar e praia
- **Sidebar Responsiva**: Navegação adaptável com efeitos blur e gradientes
- **Cards Estatísticos**: Informações importantes em destaque
- **Tabelas Interativas**: Hover effects e ações inline
- **Modais Funcionais**: Para ações críticas como exclusões
- **Animações Suaves**: Transições CSS para melhor UX
- **Estados de Loading**: Feedback visual para ações do usuário

### **Componentes Reutilizáveis**
- Sistema de notificações toast
- Badges de status coloridas
- Botões de ação com ícones
- Cards estatísticos com hover effects
- Tabelas responsivas com paginação

## 🔧 Funcionalidades Técnicas Específicas

### **Gestão de Estoque**
- Diferenciação automática entre produtos físicos e serviços
- Controle de estoque mínimo com alertas
- Movimentações de entrada e saída
- Histórico completo de movimentações

### **PDV (Ponto de Venda)**
- Interface touch-friendly
- Cálculos automáticos de impostos e descontos
- Suporte a múltiplas formas de pagamento
- Impressão automática de recibos
- Integração com sistema de mesas

### **Relatórios**
- Gráficos interativos com Chart.js
- Exportação em múltiplos formatos
- Filtros avançados por período e categoria
- Cálculos automáticos de margens e lucros
- Comparações de períodos

### **Cozinha**
- Interface otimizada para telas grandes
- Controle de tempo de preparo
- Status visual dos pedidos
- Notificações de novos pedidos

## 🌍 Localização

### **Moçambique - Características Específicas**
- **Moeda**: Metical Moçambicano (MZN)
- **Formato de Data**: dd/mm/yyyy
- **Idioma**: Português
- **Fuso Horário**: CAT (Central Africa Time)
- **Formatação de Números**: Vírgula para decimais, ponto para milhares

## 📊 Métricas e KPIs

O sistema calcula automaticamente:
- Ticket médio
- Margem de lucro por produto
- Rentabilidade por cliente
- Produtos mais vendidos
- Análise de sazonalidade
- Fluxo de caixa diário/mensal
- Performance por categoria de produto

## 🔒 Segurança

- Autenticação robusta
- Controle de sessões
- Validação de dados server-side
- Sanitização de inputs
- Logs de auditoria para ações críticas
- Backup automático de dados

## 🚀 Roadmap de Funcionalidades

### **Próximas Implementações**
- [ ] App mobile para garçons
- [ ] Integração com sistemas de pagamento locais
- [ ] Dashboard executivo avançado
- [ ] Sistema de fidelidade de clientes
- [ ] Controle de fornecedores
- [ ] Reservas online
- [ ] Integração com delivery

## 🤝 Contribuição

Para contribuir com o projeto:

1. Fork o repositório
2. Crie uma branch para sua feature (`git checkout -b feature/nova-funcionalidade`)
3. Commit suas mudanças (`git commit -am 'Adiciona nova funcionalidade'`)
4. Push para a branch (`git push origin feature/nova-funcionalidade`)
5. Abra um Pull Request

## 📞 Suporte e Contato

- **WhatsApp Suporte**: +258 84 724 0296
- **Telefone Principal**: +258 84 688 5214
- **Email**: suporte@zalalabeachbar.com
- **Localização**: Maputo, Moçambique

## 📄 Licença

Este projeto está licenciado sob a Licença MIT - veja o arquivo [LICENSE.md](LICENSE.md) para detalhes.

---

<div align="center">

**ZALALA BEACH BAR** 🏖️  
*Sistema de Gestão Completo*  
  
Desenvolvido com ❤️ em Moçambique  
© 2025 - Filipe dos Santos

</div>