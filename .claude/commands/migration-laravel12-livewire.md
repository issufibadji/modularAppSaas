---
name: migration-laravel12-livewire
description: >
  Skill especializada em migrar o projeto modularApp de Laravel 10 + Bootstrap 5 + Laravel Mix
  para Laravel 12 + Livewire 3 + Tailwind CSS 3 + Vite. Use este skill SEMPRE que o usuário
  mencionar: migração do projeto, upgrade do Laravel, trocar Bootstrap por Tailwind, adicionar
  Livewire, trocar Mix por Vite, refatorar views, migrar módulos para Livewire, ou qualquer tarefa
  de modernização da stack do modularApp. Cobre todas as 4 fases da migração: fundação Laravel 12,
  pipeline Vite+Tailwind, reescrita de views com Livewire 3, e validação final. Também cobre
  breaking changes de pacotes (spatie v6, pragmarx/google2fa, owen-it/auditing, nwidart/modules).
---

# Skill: Migração modularApp → Laravel 12 + Livewire 3 + Tailwind CSS 3 + Vite

Você é um arquiteto sênior executando a migração do projeto **modularApp** em 4 fases isoladas.
Cada fase é uma branch Git independente. Nunca misture fases no mesmo commit.

---

## Contexto do Projeto

- **Projeto:** modularApp — aplicação enterprise modular
- **Stack atual:** Laravel 10.8 + Bootstrap 5.2.3 + SASS + Laravel Mix 6 + PHP 8.1+
- **Stack destino:** Laravel 12 + Livewire 3 + Alpine.js + Tailwind CSS 3 + Vite + PHP 8.2+
- **Módulos:** AgendaAi, Mercadopago, Report (via `nwidart/laravel-modules`)
- **Auth:** Sanctum + Spatie Permission + Google 2FA (`pragmarx/google2fa-laravel`)
- **Auditoria:** `owen-it/laravel-auditing`
- **Banco:** MySQL 8 — database `agenda_ai`

---

## FASE 1 — Fundação Laravel 12

**Branch:** `feat/laravel-12`
**Duração estimada:** 1–2 dias
**Objetivo:** Atualizar o framework e adaptar a infraestrutura sem tocar em nenhuma view.

### 1.1 Verificar compatibilidade ANTES de qualquer upgrade

```bash
composer outdated
composer audit

# Pacotes críticos — verificar se têm release para L12:
# spatie/laravel-permission     → precisa de v6 (tem breaking changes)
# pragmarx/google2fa-laravel    → verificar tag de L12
# owen-it/laravel-auditing      → verificar tag de L12
# nwidart/laravel-modules       → v10+ suporta L12
```

Se `pragmarx/google2fa-laravel` não tiver release para L12, substituir por:
```bash
composer require "antonioribeiro/google2fa-laravel" "^2.0"
# ou
composer require "hisorange/authenticator" "^4.0"
```

### 1.2 Atualizar composer.json

```json
{
  "require": {
    "php": "^8.2",
    "laravel/framework": "^12.0",
    "livewire/livewire": "^3.0",
    "spatie/laravel-permission": "^6.0"
  }
}
```

```bash
composer update --with-all-dependencies
```

### 1.3 Migrar Kernel → bootstrap/app.php

O `app/Http/Kernel.php` foi **eliminado** no Laravel 11/12.
Criar/atualizar `bootstrap/app.php`:

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Preservar fluxo crítico: auth → Check2FA → CheckEmailVerification
        $middleware->web(append: [
            \App\Http\Middleware\Check2FA::class,
            \App\Http\Middleware\CheckEmailVerification::class,
        ]);

        // Alias de middlewares legados
        $middleware->alias([
            'auth'              => \App\Http\Middleware\Authenticate::class,
            'verified'          => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'permission'        => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role'              => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'role_or_permission'=> \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
```

### 1.4 Remover RouteServiceProvider (eliminado no L12)

Mover qualquer lógica de `app/Providers/RouteServiceProvider.php` para `bootstrap/app.php`.
O arquivo pode ser deletado após a migração.

### 1.5 Breaking changes do Spatie Permission v5 → v6

Auditar todo o projeto antes de atualizar:

```bash
grep -r "getPermissionNames\|getAllPermissions\|getDirectPermissions\|getPermissionsViaRoles" app/ Modules/
```

Mudanças obrigatórias em v6:
```php
// v5 → v6
$user->getPermissionNames()          // removido → usar $user->permissions->pluck('name')
$role->permissions->pluck('name')    // funciona igual
$user->getAllPermissions()           // removido → usar $user->getPermissionsViaRoles()->merge($user->permissions)
```

Após atualizar:
```bash
php artisan permission:cache-reset
php artisan permission:setup  # se necessário para L12
```

### 1.6 UUID no model User — verificar Trait

```php
// app/Traits/Uuid.php — verificar se está assim (compatível com L12):
public function initializeUuid(): void
{
    if (empty($this->attributes[$this->getKeyName()])) {
        $this->attributes[$this->getKeyName()] = (string) Str::uuid();
    }
}

public function getIncrementing(): bool { return false; }
public function getKeyType(): string { return 'string'; }
```

### 1.7 Rodar migrations e testes

```bash
php artisan migrate --pretend    # verificar antes de rodar
php artisan migrate
php artisan test                 # todos os testes devem passar antes de avançar
```

---

## FASE 2 — Pipeline Vite + Tailwind CSS

**Branch:** `feat/vite-tailwind`
**Duração estimada:** 1 dia
**Objetivo:** Substituir Laravel Mix por Vite e instalar Tailwind CSS.

### 2.1 Remover Mix, instalar Vite + Tailwind

```bash
# Remover Mix
npm remove laravel-mix cross-env

# Instalar Vite e Tailwind
npm install --save-dev vite @vitejs/plugin-vue laravel-vite-plugin
npm install --save-dev tailwindcss @tailwindcss/forms @tailwindcss/typography postcss autoprefixer

# Inicializar Tailwind
npx tailwindcss init -p
```

### 2.2 tailwind.config.js

```js
/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './Modules/**/Resources/views/**/*.blade.php',
        './Modules/**/Http/Livewire/**/*.php',
        './app/Http/Livewire/**/*.php',
    ],
    theme: {
        extend: {
            // Manter variáveis do SASS antigo como tokens Tailwind
            colors: {
                primary: {
                    DEFAULT: 'var(--color-primary, #0d6efd)',
                    dark:    'var(--color-primary-dark, #0a58ca)',
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
}
```

### 2.3 vite.config.js (raiz)

```js
import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: [
                'resources/views/**',
                'Modules/**/Resources/views/**',
                'Modules/**/Http/Livewire/**',
                'routes/**',
            ],
        }),
    ],
    resolve: {
        alias: { '$': 'jquery' },
    },
})
```

### 2.4 resources/css/app.css

```css
@tailwind base;
@tailwind components;
@tailwind utilities;

/* Manter variáveis CSS legadas durante a transição */
:root {
    --color-primary: #0d6efd;
    --color-primary-dark: #0a58ca;
}
```

### 2.5 resources/js/app.js

```js
import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();
```

### 2.6 resources/js/bootstrap.js

```js
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
```

### 2.7 Atualizar layout principal

```html
{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100 text-gray-900 antialiased">
    @include('partials.sidebar')
    <main class="ml-64 p-6 min-h-screen">
        @yield('content')
    </main>
    @livewireScripts
</body>
</html>
```

### 2.8 Deletar arquivo legado

```bash
rm webpack.mix.js
```

### 2.9 Atualizar package.json scripts

```json
{
  "scripts": {
    "dev":   "vite",
    "build": "vite build"
  }
}
```

---

## FASE 3 — Livewire 3 + Reescrita de Views

**Branch:** `feat/livewire-ui`
**Duração estimada:** 3–5 dias (maior esforço)
**Objetivo:** Substituir todas as views Bootstrap por Livewire + Tailwind.

### 3.1 Ordem de migração (menor risco primeiro)

```
1. layouts/app.blade.php         → estrutura base (sidebar, navbar)
2. home.blade.php                → dashboard (só exibição)
3. users/                        → CRUD simples
4. roles/ e permissions/         → CRUD simples
5. menu/                         → formulário com estado
6. config/                       → formulário com estado
7. audit/                        → só listagem
8. Módulo Report                 → lógica pura, baixo risco
9. Módulo Mercadopago            → checkout + webhook (cuidado)
10. Módulo AgendaAi              → produto principal
11. 2fa/ e auth/                 → ÚLTIMO (nunca quebrar autenticação)
```

### 3.2 Padrão de conversão: Controller → Componente Livewire

**Antes (Bootstrap + Controller):**
```php
// app/Http/Controllers/UserController.php
public function index()
{
    $users = User::paginate(20);
    return view('users.index', compact('users'));
}
```

**Depois (Livewire 3):**
```php
// app/Http/Livewire/UserIndex.php
namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithPagination;

class UserIndex extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.user-index', [
            'users' => User::where('name', 'like', "%{$this->search}%")
                          ->orWhere('email', 'like', "%{$this->search}%")
                          ->paginate(20),
        ]);
    }
}
```

### 3.3 Padrão de conversão: Bootstrap → Tailwind

```html
{{-- ANTES: Bootstrap --}}
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">Usuários</h5>
                </div>
                <div class="card-body">
                    <input type="text" class="form-control mb-3" placeholder="Buscar...">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- DEPOIS: Tailwind + Livewire --}}
<div class="max-w-5xl mx-auto">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h5 class="text-lg font-medium text-gray-900">Usuários</h5>
        </div>
        <div class="p-6">
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Buscar por nome ou e-mail..."
                class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mb-4"
            >
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-3">Nome</th>
                        <th class="px-4 py-3">E-mail</th>
                        <th class="px-4 py-3">Ações</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
```

### 3.4 Componentes Livewire nos Módulos

```php
// Modules/AgendaAi/Http/Livewire/AgendamentoForm.php
namespace Modules\AgendaAi\Http\Livewire;

use Livewire\Attributes\Validate;
use Livewire\Component;

class AgendamentoForm extends Component
{
    #[Validate('required|string|max:255')]
    public string $titulo = '';

    #[Validate('required|date|after:today')]
    public string $data = '';

    #[Validate('required|string')]
    public string $descricao = '';

    public function salvar(): void
    {
        $this->validate();
        // lógica de persistência...
        $this->dispatch('agendamento-criado');
        $this->reset();
    }

    public function render()
    {
        return view('agendaai::livewire.agendamento-form');
    }
}
```

Registrar no ServiceProvider do módulo:
```php
// Modules/AgendaAi/Providers/AgendaAiServiceProvider.php
use Livewire\Livewire;

public function boot(): void
{
    Livewire::component(
        'agendaai::agendamento-form',
        \Modules\AgendaAi\Http\Livewire\AgendamentoForm::class
    );
}
```

Usar na view:
```html
<livewire:agendaai::agendamento-form />
```

### 3.5 Tabela de equivalências Bootstrap → Tailwind

| Bootstrap | Tailwind |
|---|---|
| `container` | `max-w-7xl mx-auto px-4` |
| `container-fluid` | `w-full px-4` |
| `row` | `flex flex-wrap -mx-2` |
| `col-md-6` | `w-full md:w-1/2 px-2` |
| `card` | `bg-white rounded-xl shadow-sm border border-gray-200` |
| `card-body` | `p-6` |
| `card-header` | `px-6 py-4 border-b border-gray-200` |
| `btn btn-primary` | `px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700` |
| `btn btn-secondary` | `px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300` |
| `btn btn-danger` | `px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700` |
| `form-control` | `w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500` |
| `form-label` | `block text-sm font-medium text-gray-700 mb-1` |
| `form-select` | `w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500` |
| `form-check-input` | `h-4 w-4 text-indigo-600 border-gray-300 rounded` |
| `table` | `w-full text-sm text-left` |
| `table-hover tr:hover` | `hover:bg-gray-50` |
| `badge bg-success` | `inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800` |
| `badge bg-danger` | `inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800` |
| `alert alert-danger` | `p-4 rounded-lg bg-red-50 border border-red-200 text-red-800` |
| `alert alert-success` | `p-4 rounded-lg bg-green-50 border border-green-200 text-green-800` |
| `modal` | Usar Livewire `$dispatch` + Alpine.js `x-show` |
| `d-flex align-items-center` | `flex items-center` |
| `d-none` | `hidden` |
| `text-muted` | `text-gray-500` |
| `fw-bold` | `font-semibold` |
| `mb-3` | `mb-3` (Tailwind usa mesma escala) |
| `mt-auto` | `mt-auto` |
| `gap-2` | `gap-2` |

### 3.6 Modal com Alpine.js (substituindo Bootstrap Modal)

```html
<div x-data="{ open: false }">
    <button
        @click="open = true"
        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700"
    >
        Novo Agendamento
    </button>

    <div
        x-show="open"
        x-transition
        @keydown.escape.window="open = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
        style="display: none"
    >
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg mx-4">
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium">Novo Agendamento</h3>
                <button @click="open = false" class="text-gray-400 hover:text-gray-600">✕</button>
            </div>
            <div class="p-6">
                <livewire:agendaai::agendamento-form />
            </div>
        </div>
    </div>
</div>
```

Fechar o modal via Livewire event:
```php
// No componente Livewire
$this->dispatch('close-modal');
```
```html
<!-- Na view -->
<div x-data="{ open: false }"
     @close-modal.window="open = false">
```

### 3.7 Sidebar dinâmica (menu_side_bars)

```html
{{-- resources/views/partials/sidebar.blade.php --}}
<aside class="fixed left-0 top-0 h-full w-64 bg-gray-900 text-white flex flex-col z-40">
    <div class="p-6 border-b border-gray-700">
        <span class="text-xl font-semibold">{{ config('app.name') }}</span>
    </div>
    <nav class="flex-1 overflow-y-auto p-4">
        @foreach($menuItems as $item)
            <a
                href="{{ $item->route }}"
                @class([
                    'flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors mb-1',
                    'bg-indigo-600 text-white' => request()->routeIs($item->route_name.'*'),
                    'text-gray-300 hover:bg-gray-800 hover:text-white' => !request()->routeIs($item->route_name.'*'),
                ])
            >
                <span>{!! $item->icon !!}</span>
                {{ $item->label }}
            </a>
        @endforeach
    </nav>
    <div class="p-4 border-t border-gray-700">
        <span class="text-xs text-gray-400">{{ auth()->user()->name }}</span>
    </div>
</aside>
```

---

## FASE 4 — Validação e Limpeza

**Branch:** `feat/cleanup-validation`
**Duração estimada:** 1–2 dias

### 4.1 Remover dependências Bootstrap/SASS

```bash
npm remove bootstrap sass resolve-url-loader
```

### 4.2 Limpar arquivos legados

```bash
rm webpack.mix.js
rm -rf resources/sass/           # todo o SASS antigo
# Manter apenas resources/css/app.css (Tailwind)
```

### 4.3 Verificar importações JS legadas

```bash
# Procurar por requires de Bootstrap
grep -r "require('bootstrap')\|import 'bootstrap'" resources/js/ Modules/
grep -r "require('jquery')\|window.jQuery" resources/js/ Modules/
```

### 4.4 Suite de testes

```bash
# Todos os testes devem passar
php artisan test

# Análise estática
./vendor/bin/phpstan analyse app/ Modules/ --level=5

# Code style
./vendor/bin/pint

# Build de produção
npm run build

# Verificar se os assets foram gerados
ls -la public/build/assets/
```

### 4.5 Reset de cache obrigatório

```bash
php artisan permission:cache-reset
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### 4.6 Checklist final

- [ ] `php artisan test` — 100% verde
- [ ] Login funciona (auth flow completo)
- [ ] 2FA funciona (Check2FA middleware)
- [ ] RBAC funciona (spatie v6 — testar `@can` nas views)
- [ ] Auditoria registrando (owen-it)
- [ ] Menu lateral carregando de `menu_side_bars`
- [ ] Módulo AgendaAi acessível
- [ ] Módulo Mercadopago acessível
- [ ] Módulo Report acessível
- [ ] `npm run build` sem erros
- [ ] Sem referências a `@mix()` nas views
- [ ] Sem classes Bootstrap nas views migradas
- [ ] UUID do User funcionando (criar/editar usuário)
- [ ] Permissões cacheadas (`permission:cache-reset` rodou)

---

## Pontos Críticos — Nunca Ignorar

1. **Middleware order** — sempre `auth → Check2FA → CheckEmailVerification`. Qualquer alteração nessa ordem quebra o fluxo de 2FA.

2. **Spatie v6** — auditar `getPermissionNames()` e `getAllPermissions()` ANTES de dar `composer update`. São os métodos mais usados e foram renomeados/removidos.

3. **UUID é string** — `$user->id` retorna string UUID. Jamais usar comparação com `==` inteiro. Sempre `===` ou `is()`.

4. **Livewire + Módulos** — componentes Livewire de módulos DEVEM ser registrados no `boot()` do ServiceProvider do módulo. Sem isso, a view não encontra o componente.

5. **Alpine.js substitui jQuery** — `window.jQuery` não estará disponível. Auditar uso de `$()` antes de remover jQuery.

6. **`wire:model` em selects e checkboxes** — usar `wire:model` diretamente. Não usar `wire:model.lazy` para campos de toggle (use `.live` ou `.blur`).

7. **CSRF com Livewire** — Livewire 3 injeta CSRF automaticamente. Não duplicar token em forms Livewire.

8. **Vite HMR com módulos** — o `refresh` no `vite.config.js` deve incluir o caminho `Modules/**/Resources/views/**` para hot-reload funcionar nos módulos.

---

## Referências Internas

- Contexto completo do projeto: `projeto.md` (skill carregado)
- Padrões de arquitetura: `senior-architect.md` (skill carregado)
- Estrutura de módulos: `/Modules/`
- Migrations do core: `database/migrations/`
- Seeders: `database/seeders/`
