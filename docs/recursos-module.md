
# Recursos Disponíveis nos Modulos

## 1. Sistema de Permissões e Roles

O sistema de permissões no Core utiliza o pacote **Laravel-Permission 10.0** para fornecer controle avançado de acesso baseado em papéis (roles) e permissões.

### Principais Funcionalidades:
- **Roles**: Define papéis que podem ser atribuídos aos usuários (ex.: Admin, Usuário).
- **Permissions**: Define permissões específicas que podem ser atribuídas aos papéis (ex.: `create-post`, `edit-user`).
- **Menu**: Cria Menus individualmente por módulo, com regras de acesso baseado em permissões (ACL).

### Exemplo de Uso:
```php
// Verificando se o usuário tem uma permissão específica
if (\$user->can('create-post')) {
    // O usuário pode criar posts
}

// Atribuindo um papel a um usuário
\$user->assignRole('Admin');

// Verificando o papel do usuário
if (\$user->hasRole('Admin')) {
    // O usuário é um administrador
}

//Criando uma nova Role
Role::create(['name' => 'Super-Admin']);

//Criando uma nova permissão
Permission::create(['name' => 'permissions-all']);

//Atualizar as permissoes de uma Role
if ($request->has('permissions')) {
    $role->syncPermissions($request->permissions);
}

//criando um novo Menu
MenuSideBar::create([
    'description' => 'Enviar notificações',
    'icon' => "fa-message ",
    'module' => "",
    'menu_above' => "",
    'level' => 0,
    'route' => "notifications/send",
    'acl' => "notification-all",
    'order' => 1,
    'active' => true,
    'style' => '',
]);

```

---

## 2. Auditoria (Logs de Atividade)

O Core também oferece uma funcionalidade de auditoria para registrar mudanças no sistema, como alterações em registros e ações de usuários. Essa funcionalidade utiliza o pacote **Laravel Auditing**.

### Funcionalidades Principais:
- **Auditoria Automática**: Cada vez que uma alteração é feita em um registro monitorado, uma entrada de auditoria é criada.
- **Registro de Ações**: Registra o "antes" e o "depois" de cada alteração.
- **Controle e relatórios**: Há um menu no sistema que é possível verificar os logs, inclusive filtra-los, menu Audits.

### Exemplo de Uso:
```php
// Acessando logs de auditoria
\$auditLogs = \$user->audits;
```

---

## 3. Configurações do Sistema

O Core permite o gerenciamento centralizado das configurações do sistema, com um painel de controle de fácil acesso. Essas configurações são armazenadas no banco de dados e podem ser alteradas conforme necessário.

### Funcionalidades:
- **Configurações Globais**: Definição de variáveis de configuração acessíveis em toda a aplicação.
- **Atualização Dinâmica**: As configurações podem ser atualizadas sem necessidade de reiniciar o sistema.

### Exemplo de Uso:
```php
// Obtendo um valor de configuração
\App\Models\AppConfig::where('id', '>', '0')->get();

//Cria Configurações, qe podem ser editadas via sistema
\AppConfig::create(['key' => 'description_user', 'value' => '', 'description' => 'Descrição default do usuario', 'required' => true]);
```

---

## 4. Gestão Básica de Usuários

O Core oferece uma interface básica para o gerenciamento de usuários, permitindo o cadastro, edição e remoção de usuários, além da ativação/desativação de contas.

### Funcionalidades:
- **Cadastro e Edição de Usuários**: Interface para gerenciar informações básicas dos usuários.
- **Ativação/Desativação**: Permite ativar ou desativar usuários diretamente da interface.

### Exemplo de Uso:
```php
// Ativando/Desativando um usuário
\$user->active = !\$user->active;
\$user->save();
```

---

## 5. Autenticação em Dois Fatores (2FA)

O Core inclui a funcionalidade de autenticação em dois fatores (2FA) para aumentar a segurança do sistema. O 2FA pode ser opcional e ativado/desativado pelos próprios usuários.

### Funcionalidades:
- **Ativação de 2FA**: Usuários podem configurar o 2FA usando chaves QR ou códigos de autenticação.
- **Validação de Login**: Após o login, se o 2FA estiver ativo, o usuário precisa inserir o código de autenticação.

### Exemplo de Uso:
```php
// Verificando se o 2FA está ativado
if (\$user->active_2fa) {
    // Solicitar o código de autenticação
}
```

---

## 6. Página de Perfil do Usuário

O Core fornece uma página de perfil para que os usuários possam visualizar e editar suas próprias informações pessoais, incluindo a alteração de senhas e configurações de 2FA.

### Funcionalidades:
- **Edição de Informações Pessoais**: Usuários podem editar nome, email, e outras informações pessoais.
- **Alteração de Senha**: Funcionalidade para alteração de senha diretamente na página de perfil.
- **Configurações de Segurança**: Ativação/Desativação de 2FA diretamente na página de perfil.

### Exemplo de Uso:
```php
// Atualizando informações do perfil do usuário
\$user->name = \$request->name;
\$user->email = \$request->email;
\$user->save();
```

---

## 7. Notificações, internas e WebPush

O Core fornece uma página e uma interface para envios de notificações a usuarios, notificações internas e WebPush

### Funcionalidades:
- **Interface para envio de notificações**: É possivel fazer o envio de notificações diretamente pela interface, tanto notificações internas e webpush, permissão 'notification-all'.
- **Metodos para envio de notificações**: Core disponibiliza métodos para envio de notificações.


### Exemplo de Uso:
```php
// Enviando notificação interna
\$user->notify(new GeneralNotification($validated['title'], $validated['message'], $validated['link'], $validated['link']));

// Enviando notificação WebPush
\$payload = [
    'title' => $validated['title'],
    'body' => $validated['message'],
    'icon' => '/img/notification.png',
    'actions' => [
        ['action' => 'Ver', 'title' => 'Explore Now', 'icon' => '/img/notification.png'],
        ['action' => 'Fechar', 'title' => 'Dismiss', 'icon' => '/img/notification.png']
    ],
    'vibrate' => [200, 100, 200],
    'data' => ['url' => $validated['link']],
    'tag' => 'update-notification',
    'renotify' => true,
    'requireInteraction' => true
];

\$user->pushNotify($payload);
```

---

## 8. Eventos e Listeners

O Core permite a criação de eventos e listeners para executar lógica personalizada com base em ações disparadas dentro do sistema. Isso segue o padrão de eventos do Laravel, onde um evento pode ser ouvido por um ou mais listeners.

### Criando um Evento

Para criar um evento, use o Artisan para gerar uma classe de evento:

```bash
php artisan module:make-event MPProcessWebHook MercadoPago
```

### Exemplo de Evento:
```php
namespace Modules\MercadoPago\Events;

use Illuminate\Queue\SerializesModels;
use Modules\MercadoPago\Entities\MercadoPayment;

class MPProcessWebHook
{
    use SerializesModels;

    public $mp;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(MercadoPayment $mp)
    {
        $this->mp = $mp;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
```

### Criando um Listener

Os listeners são responsáveis por reagir ao evento disparado. Use o Artisan para criar um listener:

```bash
php artisan module:make-listener ProcessPaymentScript Script
```

### Exemplo de Listener:
```php
namespace Modules\Script\Listeners;

use Carbon\Carbon;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Modules\MercadoPago\Events\MPProcessWebHook;
use Modules\Script\Entities\ScriptAccount;

class ProcessPaymentScript
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param \Modules\MercadoPago\Events\MPProcessWebHook $event
     * @return void
     */
    public function handle(MPProcessWebHook $event)
    {
        
    }
}

```

### Registrando o Evento e Listener

Você deve registrar o evento e seu listener no arquivo `\Modules\MODULO\Providers\EventServiceProvider.php`:
```bash
php artisan module:make-provider EventServiceProvider MODULO
```

```php
class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        MPProcessWebHook::class => [
            ProcessPaymentScript::class,
        ],
        Registered::class => [
            CreateScriptAccount::class,
        ]
    ];
}
```

### Disparando o Evento

Você pode disparar o evento usando o método `event()` de qualquer lugar na aplicação:

```php
event(new NovoEvento($dados));
```

---

## 9. Envio de E-mails Usando o Core

O Core permite o envio de e-mails utilizando o sistema de notificações do Laravel ou diretamente com o serviço de mailer.

### Configuração

O Core utiliza as configurações de e-mail definidas no arquivo `.env`. Certifique-se de que as variáveis de configuração de e-mail estão corretamente configuradas:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=seu-usuario
MAIL_PASSWORD=sua-senha
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=seuemail@dominio.com
MAIL_FROM_NAME="Seu Nome ou Empresa"
```

### Exemplo de Envio de E-mail Simples

Você pode enviar e-mails diretamente através do serviço de mailer do Laravel:

```php
use Illuminate\Support\Facades\Mail;

Mail::raw('Esta é uma mensagem simples de e-mail.', function ($message) {
    $message->to('destinatario@example.com')
            ->subject('Assunto do E-mail');
});
```

### Exemplo de E-mail Usando Views

Para e-mails mais complexos, você pode usar views Blade para gerar o conteúdo do e-mail:

```php
Mail::send('emails.template', ['data' => $dados], function ($message) {
    $message->to('destinatario@example.com')
            ->subject('Assunto do E-mail');
});
```