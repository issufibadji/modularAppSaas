@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('You are logged in!') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        if ('serviceWorker' in navigator && 'Notification' in window) {
            navigator.serviceWorker.register('/service-worker.js')
                .then(function(registration) {
                    console.log('Service Worker registrado com sucesso:', registration);

                    // Solicitar permissão para enviar notificações
                    Notification.requestPermission().then(function(permission) {
                        if (permission === 'granted') {
                            console.log('Permissão para notificações concedida.');

                            // Registrar a assinatura para push notifications
                            registration.pushManager.subscribe({
                                    userVisibleOnly: true,
                                    applicationServerKey: urlB64ToUint8Array("{{env('VAPID_PUBLIC_KEY')}}") // Gere uma chave pública usando Web Push
                                })
                                .then(function(subscription) {
                                    console.log('Usuário inscrito para notificações:', subscription);

                                    // Enviar a inscrição para o servidor para salvar no banco de dados
                                    saveSubscription(subscription);
                                })
                                .catch(function(error) {
                                    console.error('Falha ao se inscrever para notificações:', error);
                                });
                        } else {
                            console.log('Permissão para notificações negada.');
                        }
                    });
                })
                .catch(function(error) {
                    console.log('Falha ao registrar o Service Worker:', error);
                });
        }

        // Função para converter a chave pública para Uint8Array
        function urlB64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding)
                .replace(/\-/g, '+')
                .replace(/_/g, '/');

            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);

            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        }

        // Função para enviar os dados de inscrição para o servidor
        function saveSubscription(subscription) {
            fetch('/save-subscription', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(subscription)
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Inscrição salva com sucesso:', data);
                })
                .catch(error => {
                    console.error('Erro ao salvar inscrição:', error);
                });
        }
    </script>
@endsection
