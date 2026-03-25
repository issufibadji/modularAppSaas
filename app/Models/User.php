<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;

use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;

use OwenIt\Auditing\Contracts\Auditable;
use App\Traits\Uuid;
use PragmaRX\Google2FALaravel\Google2FA;

class User extends Authenticatable implements Auditable, MustVerifyEmail
{
    use HasPermissions, HasApiTokens, HasFactory, Notifiable, HasRoles;
    use Uuid;
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'active',
        'active_2fa',
        'google2fa_secret',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function pushSubscriptions()
    {
        return $this->hasMany(PushSubscription::class);
    }

    public function enable2FA(Google2FA $google2fa)
    {
        $this->google2fa_secret = $google2fa->generateSecretKey();
        $this->save();
    }

    public function disable2FA()
    {
        $this->google2fa_secret = null;
        $this->save();
    }

    public function getRouteKeyName()
    {
        return 'uuid'; // Define UUID como chave para rotas
    }

    public function pushNotify(array $payload)
    {
        foreach ($this->pushSubscriptions as $subscription) {
            $subscriptionData = [
                'endpoint' => $subscription->endpoint,
                'keys' => [
                    'p256dh' => $subscription->p256dh_key,
                    'auth' => $subscription->auth_key,
                ],
            ];
            
            \App\Http\Controllers\PushSubscriptionController::sendPushNotification($subscriptionData, $payload);
        }
    }
}
