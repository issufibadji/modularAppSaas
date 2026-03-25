<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PushSubscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class PushSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        // Validar os dados de inscrição
        $data = $request->validate([
            'endpoint' => 'required|string',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
        ]);

        // Salvar os dados de inscrição no banco de dados
        $subscription = new PushSubscription();
        $subscription->endpoint = $data['endpoint'];
        $subscription->p256dh_key = $data['keys']['p256dh'];
        $subscription->auth_key = $data['keys']['auth'];
        $subscription->user_id = auth()->id(); // Associe a assinatura ao usuário logado, se aplicável
        $subscription->save();

        return response()->json(['success' => true]);
    }

    public static function sendPushNotification($subscriptionData, $payload)
    {
        if (!Auth::user()->can('notification-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }  
        
        $auth = [
            'VAPID' => [
                'subject' => 'mailto:example@yourdomain.org',
                'publicKey' => env('VAPID_PUBLIC_KEY'), // Gere uma chave pública usando Web Push
                'privateKey' => env('VAPID_PRIVATE_KEY'), // Gere uma chave privada usando Web Push
            ],
        ];

        $webPush = new WebPush($auth);
        $subscription = Subscription::create($subscriptionData);

        // Adicione a notificação para ser enviada
        $webPush->queueNotification($subscription, json_encode($payload));

        foreach ($webPush->flush() as $report) {
            $endpoint = $report->getRequest()->getUri()->__toString();

            // Remover inscrições inválidas com base no status de erro
            if ($report->isSubscriptionExpired()) {
                PushSubscription::where('endpoint', $endpoint)->delete();
            }

            // Registro de logs de cada notificação enviada, por hora nao irei logar isso
            // if ($report->isSuccess()) {
            //     echo "[v] Mensagem enviada com sucesso para {$endpoint}.\n";
            // } else {
            //     echo "[x] Erro ao enviar mensagem para {$endpoint}: {$report->getReason()}\n";
            // }
        }
    }
}
