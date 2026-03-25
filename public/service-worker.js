if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
        navigator.serviceWorker.register('/service-worker.js')
            .then(function (registration) {
                console.log('Service Worker registrado com sucesso:', registration.scope);
            })
            .catch(function (error) {
                console.log('Falha ao registrar o Service Worker:', error);
            });
    });
}

// Cache básico para funcionalidades offline
self.addEventListener('install', event => {
    console.log('Service Worker instalado');
    // event.waitUntil(
    //     caches.open('my-cache').then(cache => {
    //         return cache.addAll([
    //             '/',
    //             '/css/app.css',
    //             '/js/app.js',
    //             // Outras rotas e arquivos para cache
    //         ]);
    //     })
    // );
});

self.addEventListener('activate', function (event) {
    console.log('Service Worker ativado');
});

self.addEventListener('fetch', event => {
    // console.log('Interceptando requisição para:', event.request.url);
    event.respondWith(
        caches.match(event.request).then(response => {
            return response || fetch(event.request);
        })
    );
});

// Listener para notificações push
self.addEventListener('push', function(event) {
    const data = event.data.json();

    // Configurar os parâmetros da notificação
    const title = data.title || 'Notificação Padrão';
    const options = {
        body: data.body || 'Este é o corpo da notificação.',
        icon: data.icon || '/img/notification.png', // Ícone principal
        badge: data.badge || '/img/notification.png', // Ícone de badge
        image: data.image || undefined, // Imagem grande (opcional)
        actions: data.actions || [], // Botões de ação
        vibrate: data.vibrate || [200, 100, 200], // Padrão de vibração
        data: data.data || {}, // Dados adicionais para uso posterior
        tag: data.tag || 'general-notification', // Tag para agrupar notificações
        renotify: data.renotify || false, // Reproduzir som novamente para notificações com a mesma tag
        requireInteraction: data.requireInteraction || false // Manter a notificação ativa até interação
    };

    // Mostrar a notificação
    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});

// Listener para cliques em ações de notificação
self.addEventListener('notificationclick', function(event) {
    event.notification.close(); // Fecha a notificação quando clicada

    // Verificar se há uma ação específica
    if (event.action) {
        // Ações específicas (personalize conforme necessário)
        if (event.action === 'explore') {
            clients.openWindow(event.notification.data.url || 'https://default-url.com'); // URL a ser aberta
        } else if (event.action === 'dismiss') {
            // Talvez nada a fazer para dismiss
            console.log('Ação de dismiss clicada');
        }
    } else {
        // Ação padrão para cliques fora de botões de ação
        clients.openWindow(event.notification.data.url || 'https://default-url.com');
    }
});

// Listener para notificações fechadas
self.addEventListener('notificationclose', function(event) {
    console.log('Notificação fechada', event);
    // Pode adicionar lógica para registrar que o usuário fechou a notificação
});