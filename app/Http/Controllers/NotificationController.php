<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->paginate(20);
        
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        if($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->markAsRead();

        if($notification->action_url) {
            return redirect($notification->action_url);
        }

        return back()->with('success', 'Notification marquée comme lue.');
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications()->update(['read' => true]);

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }
}