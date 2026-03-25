<?php

use Illuminate\Support\Facades\Route;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Notifications\GeneralNotification;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// TODO implementar tabela de módulos, para futura listagem e relacionamentos
// TODO implementar aceite de Barra de cookies
// TODO implementar multi-selects sempre onde tiver permissões 

Auth::routes();
Auth::routes(['verify' => true]);


// Tratativa para / e login, para ja cair direto no painel.
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/generate-push-notification-key', function(){
    $keys = Minishlink\WebPush\VAPID::createVapidKeys();

    echo "Chave Pública: " . $keys['publicKey'] . '</br>';
    echo "Chave Privada: " . $keys['privateKey'] . '</br>';
});

Route::middleware(['auth'])->group(function () {
    Route::get('/2fa', [App\Http\Controllers\TwoFactorController::class, 'showForm'])->name('2fa.form');
    Route::post('/2fa', [App\Http\Controllers\TwoFactorController::class, 'verify'])->name('2fa.verify');
});


Route::middleware(['auth', '2fa', 'check.email.verification'])->group(function () {

    Route::get('/notifications/send', [App\Http\Controllers\NotificationController::class, 'create'])->name('notifications.create');
    Route::post('/notifications/send', [App\Http\Controllers\NotificationController::class, 'send'])->name('notifications.send');

    Route::post('/save-subscription', [App\Http\Controllers\PushSubscriptionController::class, 'store']);

    Route::post('/notifications/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::get('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('/profile', [App\Http\Controllers\UserProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [App\Http\Controllers\UserProfileController::class, 'update'])->name('profile.update');
    
    
    //Rotas para roles e permissões e menus
    Route::resource('roles', App\Http\Controllers\RoleController::class);
    Route::resource('permissions', App\Http\Controllers\PermissionController::class);
    Route::resource('menu', App\Http\Controllers\MenuSideBarController::class);
    
    //Rotas para atribuição/remoção de roles a usuarios
    Route::get('/user-roles', [App\Http\Controllers\UserRoleController::class, 'index'])->name('user_roles.index');
    Route::post('/user-roles/assign/{user}', [App\Http\Controllers\UserRoleController::class, 'assignRole'])->name('user_roles.assign');
    Route::post('/user-roles/revoke/{user}', [App\Http\Controllers\UserRoleController::class, 'revokeRole'])->name('user_roles.revoke');

    //rota para configurações do sistema
    Route::resource('config', App\Http\Controllers\AppConfigController::class);

    Route::resource('users',  App\Http\Controllers\UserController::class);
    Route::put('/users/{user:uuid}/toggle', [App\Http\Controllers\UserController::class, 'toggleActivation'])->name('users.toggle');

    Route::get('/2fa/setup', [App\Http\Controllers\TwoFactorController::class, 'showSetupForm'])->name('2fa.setup');
    Route::post('/2fa/setup', [App\Http\Controllers\TwoFactorController::class, 'setup'])->name('2fa.setup.post');
    Route::post('/2fa/disable', [App\Http\Controllers\TwoFactorController::class, 'disable'])->name('2fa.disable');
  
    //rota para visualizar logs do sistema
    Route::get('/audit-logs', [App\Http\Controllers\AuditLogController::class, 'index'])->name('audit.logs');
});
