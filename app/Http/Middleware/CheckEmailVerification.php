<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CheckEmailVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Verificar a configuração do sistema
        // $requiresEmailVerification = config('settings.require_email_verification', false);
        $requiresEmailVerification = $requiresEmailVerification = env('REQUIRE_EMAIL_VERIFICATION', false);
        
        // Se a verificação de email estiver ativada e o usuário não tiver verificado o email
        if ($requiresEmailVerification && Auth::check() && !Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        return $next($request);
    }
}