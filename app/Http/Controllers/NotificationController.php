<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Session;

class NotificationController extends Controller
{
    public function markAsRead(Request $request)
    {        
        $notification = Auth::user()->unreadNotifications->where('id', $request->id)->first();
        if ($notification) {
            $notification->markAsRead();
        }
        return response()->json(['success' => true]);
    }
    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'Notificações marcadas como lidas.');
    }

    public function create()
    {
        if (!Auth::user()->can('notification-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }   

        $users = User::all();
        return view('notifications.send', compact('users'));
    }

    public function send(Request $request)
    {

        if (!Auth::user()->can('notification-all')) {
            Session::flash('error', 'Permissão Negada!');
            return redirect()->back();
        }   
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'link' => 'required|string',
            'icon' => 'required|string',
            'users' => 'required|array',
            'notification_type' => 'required|in:internal,webpush,both'
        ]);

        $users = User::whereIn('id', $validated['users'])->get();

        foreach ($users as $user) {
            // Enviar notificação interna
            if ($validated['notification_type'] == 'internal' || $validated['notification_type'] == 'both') {
                $user->notify(new GeneralNotification($validated['title'], $validated['message'], $validated['link'], $validated['link']));
            }

            // Enviar notificação web push
            if ($validated['notification_type'] == 'webpush' || $validated['notification_type'] == 'both') {
                $payload = [
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
                
                $user->pushNotify($payload);
            }
        }

        return redirect()->route('notifications.create')->with('success', 'Notificação enviada com sucesso!');
    }
}
