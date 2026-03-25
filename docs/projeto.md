# Skill: Contexto Completo do Projeto modularApp

Você é um engenheiro de software sênior trabalhando no projeto **modularApp**. Ao receber qualquer tarefa, use todo o contexto abaixo para tomar decisões alinhadas à arquitetura, convenções e objetivos do projeto.

---

## IDENTIDADE DO PROJETO

- **Nome:** modularApp
- **Tipo:** Aplicação web enterprise com arquitetura modular
- **Framework:** Laravel 10.8 (PHP)
- **Arquitetura:** Modular via `nwidart/laravel-modules` — cada feature é um módulo independente em `/Modules/`
- **Idioma padrão:** Português (tradução em `resources/lang/pt/`)
- **Banco de dados:** MySQL — database `agenda_ai`, host `127.0.0.1:8111`

---

## STACK COMPLETA

| Camada | Tecnologia |
|---|---|
| Backend | PHP + Laravel 10.8 |
| Módulos | nwidart/laravel-modules ^10.0 |
| Auth | Laravel Sanctum + Spatie Permission + Google 2FA |
| Auditoria | owen-it/laravel-auditing ^13.6 |
| Pagamentos | MercadoPago (módulo customizado) |
| PDF | tecnickcom/tcpdf ^6.7 |
| Push | minishlink/web-push (VAPID) |
| HTTP Client | guzzlehttp/guzzle ^7.2 |
| Frontend | Bootstrap 5.2.3 + SASS + Axios + Lodash |
| Build | Laravel Mix 6 (Webpack) |
| REPL | laravel/tinker |
| Dev tools | Laravel Debugbar, Laravel Pint, Laravel Sail |
| Testes | PHPUnit 10.1 |

---

## ESTRUTURA DE DIRETÓRIOS

```
modularApp/
├── app/                          # Core Laravel
│   ├── Http/Controllers/         # Controllers do core
│   │   ├── Auth/                 # Login, Register, Password, Verify
│   │   ├── AppConfigController.php
│   │   ├── AuditLogController.php
│   │   ├── HomeController.php
│   │   ├── MenuSideBarController.php
│   │   ├── NotificationController.php
│   │   ├── PermissionController.php
│   │   ├── PushSubscriptionController.php
│   │   ├── RoleController.php
│   │   ├── TwoFactorController.php
│   │   ├── UserController.php
│   │   ├── UserProfileController.php
│   │   └── UserRoleController.php
│   ├── Http/Middleware/
│   │   ├── Authenticate.php
│   │   ├── Check2FA.php          # Middleware de verificação 2FA
│   │   └── CheckEmailVerification.php
│   ├── Models/
│   │   ├── User.php              # usa Trait Uuid
│   │   ├── Role.php
│   │   ├── AppConfig.php
│   │   ├── MenuSideBar.php
│   │   └── PushSubscription.php
│   ├── Notifications/
│   │   └── GeneralNotification.php
│   ├── Providers/                # AppServiceProvider, AuthServiceProvider, etc.
│   └── Traits/
│       └── Uuid.php              # Trait de UUID para models
│
├── Modules/                      # Módulos independentes
│   ├── AgendaAi/                 # Sistema de agendamentos
│   ├── Mercadopago/              # Pagamentos
│   └── Report/                   # Relatórios dinâmicos
│
├── config/                       # Todos os arquivos de config
├── database/
│   ├── migrations/               # 13 migrations core
│   └── seeders/                  # User, Permissions, Menu, AppConfig
├── resources/
│   ├── views/                    # Blade templates
│   │   ├── layouts/app.blade.php # Layout principal
│   │   ├── auth/                 # Login, register, reset
│   │   ├── 2fa/                  # Setup e verificação 2FA
│   │   ├── users/                # CRUD usuários
│   │   ├── roles/                # CRUD roles
│   │   ├── permissions/          # CRUD permissions
│   │   ├── menu/                 # CRUD menu
│   │   ├── audit/                # Logs de auditoria
│   │   ├── config/               # Config da aplicação
│   │   └── home.blade.php        # Dashboard
│   ├── lang/pt/                  # Traduções PT-BR
│   └── sass/                     # SCSS com _variables.scss
├── routes/
│   ├── web.php                   # Rotas web (protegidas por auth+2fa+email)
│   └── api.php                   # Rotas API (Sanctum)
└── public/                       # Webroot — index.php é o entry point
```

---

## MÓDULOS

### AgendaAi (`/Modules/AgendaAi/`)
- **Propósito:** Sistema de agenda/agendamentos (o produto principal)
- **Status:** Habilitado (`modules_statuses.json`)
- **Controller:** `AgendaAiController.php`
- **Estrutura padrão de módulo:** Config, Console, Database/Migrations, Database/Seeders, Entities, Http/Controllers, Http/Middleware, Http/Requests, Providers, Resources/assets, Resources/lang, Resources/views, Routes/web.php, Routes/api.php, Tests
- **Build:** `vite.config.js` próprio

### Mercadopago (`/Modules/Mercadopago/`)
- **Propósito:** Integração de pagamentos MercadoPago
- **Status:** Habilitado
- **Controller:** `MercadoPagoController.php`
- **Entity:** `MercadoPayment.php`
- **Event:** `MPProcessWebHook.php` — processa webhooks do MP
- **Console:** `UpdatePayment.php` — comando de atualização de pagamento
- **View:** checkout
- **Env vars:** `ACCESS_TOKEN_MP`, `PUBLIC_KEY_MP`

### Report (`/Modules/Report/`)
- **Propósito:** Construtor dinâmico de relatórios
- **Status:** Habilitado
- **Controller:** `ReportController.php`
- **Entities:** `ReportModel`, `ReportField`, `ReportRelationship`, `ReportLayout`
- **Migrations:**
  - `create_report_models_table`
  - `create_report_relationships_table`
  - `create_report_fields_table`
  - `create_report_layouts_table`

---

## BANCO DE DADOS

### Tabelas Core
| Tabela | Descrição |
|---|---|
| `users` | Contas de usuário (UUID) |
| `password_reset_tokens` | Tokens de reset de senha |
| `failed_jobs` | Jobs de fila com falha |
| `personal_access_tokens` | Tokens Sanctum |
| `roles` | Roles do sistema (Spatie) |
| `permissions` | Permissões (Spatie) |
| `role_has_permissions` | Relação role ↔ permission |
| `model_has_permissions` | Permissões diretas em models |
| `model_has_roles` | Roles atribuídas a models |
| `menu_side_bars` | Itens do menu lateral (dinâmico) |
| `app_configs` | Configurações da aplicação |
| `audits` | Trilha de auditoria completa |
| `notifications` | Notificações do Laravel |
| `push_subscriptions` | Endpoints de Web Push |

### Tabelas dos Módulos
| Tabela | Módulo |
|---|---|
| `mercado_payments` | Mercadopago |
| `report_models` | Report |
| `report_fields` | Report |
| `report_relationships` | Report |
| `report_layouts` | Report |

---

## AUTENTICAÇÃO & SEGURANÇA

- **Login/Registro:** Laravel UI (controllers em `app/Http/Controllers/Auth/`)
- **2FA:** Google Authenticator via `pragmarx/google2fa-laravel`
  - Middleware `Check2FA` protege rotas após login
  - Views em `resources/views/2fa/`
- **RBAC:** Spatie Laravel Permission v5.10
  - Models: `Role`, `Permission`
  - Controllers: `RoleController`, `PermissionController`, `UserRoleController`
- **API Auth:** Laravel Sanctum v3.2
- **Verificação de e-mail:** Opcional (`REQUIRE_EMAIL_VERIFICATION=false` no `.env`)
- **Auditoria:** Todas as ações são registradas via `owen-it/laravel-auditing`
- **UUID:** Todos os models de usuário usam `app/Traits/Uuid.php`

### Fluxo de Middlewares nas Rotas Protegidas
```
auth → Check2FA → CheckEmailVerification → (rota)
```

---

## ROTAS PRINCIPAIS

### Web (protegidas)
```
GET  /home                          Dashboard
GET  /profile                       Perfil do usuário
POST /notifications/send            Enviar notificação
POST /save-subscription             Registrar push subscription
GET  /roles, /permissions           CRUD de roles e permissions
GET  /users                         CRUD de usuários
GET  /menu                          CRUD do menu lateral
GET  /config                        CRUD de configurações
GET  /user-roles                    Atribuição de roles
GET  /audit-logs                    Logs de auditoria
GET  /2fa, /2fa/setup               Two-factor authentication
```

### API
```
GET /api/user    (auth:sanctum) — retorna usuário autenticado
```

---

## NOTIFICAÇÕES

- **Database:** Tabela `notifications` (padrão Laravel)
- **Web Push:** Pacote `minishlink/web-push`, VAPID keys em `.env`
  - `PushSubscriptionController` gerencia inscrições
  - `GeneralNotification` é a notification class principal
- **Env vars:** `VAPID_PUBLIC_KEY`, `VAPID_PRIVATE_KEY`

---

## FRONTEND

- **Layout principal:** `resources/views/layouts/app.blade.php`
- **Sidebar dinâmica:** `resources/views/particles/sidebar.blade.php` — lê de `menu_side_bars`
- **Theme panel:** `resources/views/particles/themepanel.blade.php`
- **SCSS entry:** `resources/sass/app.scss` (usa `_variables.scss`)
- **JS entry:** `resources/js/app.js`
- **Build:** `webpack.mix.js` — compila para `public/css/app.css` e `public/js/app.js`
- **Assets dos módulos:** cada módulo tem seus próprios assets em `Modules/[Nome]/Resources/assets/`

---

## AMBIENTE (.env)

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=8111
DB_DATABASE=agenda_ai
DB_USERNAME=root

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025

REQUIRE_EMAIL_VERIFICATION=false
```

---

## CONVENÇÕES DO PROJETO

### Criando um novo Módulo
```bash
php artisan module:make NomeModulo
```
Estrutura gerada automaticamente em `/Modules/NomeModulo/` com:
- `module.json` — metadados do módulo
- `composer.json` — dependências do módulo
- `Providers/NomeModuloServiceProvider.php`
- `Http/Controllers/NomeModuloController.php`
- `Routes/web.php` e `Routes/api.php`
- `Resources/views/index.blade.php`
- `Database/Migrations/` e `Database/Seeders/`

### Ativando/Desativando Módulos
```bash
php artisan module:enable NomeModulo
php artisan module:disable NomeModulo
```
Estado salvo em `modules_statuses.json` na raiz do projeto.

### Models
- Usar o Trait `App\Traits\Uuid` em models que precisam de UUID
- Entities dos módulos ficam em `Modules/[Nome]/Entities/`
- Models do core ficam em `app/Models/`

### Views
- Core: `resources/views/[area]/[action].blade.php`
- Módulos: `Modules/[Nome]/Resources/views/[action].blade.php`
- Sempre extender `layouts.app` no core ou o layout do módulo

### Permissões
- Nomear permissões no padrão: `[acao]-[recurso]` (ex: `create-users`, `edit-roles`)
- Registrar no seeder `PermissionsSeeder.php`
- Verificar nas views com `@can('permissao')` e nos controllers com `$this->authorize()`

---

## SEEDERS

```bash
php artisan db:seed                     # Roda DatabaseSeeder
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=PermissionsSeeder
php artisan db:seed --class=MenuSidebarSeeder
php artisan db:seed --class=AppConfigSeeder
```

---

## COMANDOS ÚTEIS

```bash
# Desenvolvimento
php artisan serve                        # Inicia servidor
npm run dev                              # Compila assets em modo watch
npm run prod                             # Compila assets para produção

# Banco de dados
php artisan migrate                      # Roda migrations
php artisan migrate:fresh --seed         # Reset + seed completo
php artisan migrate --path=Modules/AgendaAi/Database/Migrations

# Cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Módulos
php artisan module:list                  # Lista módulos e status
php artisan module:make NomeModulo       # Cria novo módulo
php artisan module:migrate NomeModulo    # Roda migrations do módulo

# Testes
php artisan test
./vendor/bin/phpunit
```

---

## PACOTES CUSTOMIZADOS (Composer repositories)

O projeto usa pacotes privados do autor `lucas-freitas00`:
- `lucas-freitas00/laravel-module-installer`
- `lucas-freitas00/mercadopago-module`
- `lucas-freitas00/report-module`

Esses pacotes são instalados via repositórios customizados definidos no `composer.json`.

---

## ARQUIVOS DE CONFIGURAÇÃO RELEVANTES

| Arquivo | Descrição |
|---|---|
| `config/modules.php` | Caminhos, stubs e ativadores dos módulos |
| `config/permission.php` | Config do Spatie Permission (cache, modelos) |
| `config/audit.php` | Drivers e comportamento da auditoria |
| `config/google2fa.php` | Configuração do Google Authenticator |
| `modules_statuses.json` | Estado habilitado/desabilitado de cada módulo |
| `webpack.mix.js` | Pipeline de build dos assets |

---

## PONTOS DE ATENÇÃO

1. **Middleware order** — rotas protegidas exigem `auth` + `Check2FA` + `CheckEmailVerification` nessa ordem
2. **Módulos têm seus próprios ServiceProviders** — registrados automaticamente pelo nwidart
3. **UUID em vez de auto-increment** no model User — usar `$model->id` retorna UUID string
4. **Menu lateral é dinâmico** — itens vêm de `menu_side_bars` no banco, não hardcoded
5. **Assets de módulos** precisam ser compilados separadamente (cada módulo pode ter `vite.config.js`)
6. **Permissões são cacheadas** pelo Spatie — após alterar, rodar `php artisan permission:cache-reset`
7. **MercadoPago webhooks** são processados via evento `MPProcessWebHook` — não editar de forma síncrona
8. **Report module** usa 4 tabelas relacionadas — sempre manter integridade entre `report_models`, `report_fields`, `report_relationships` e `report_layouts`
