<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        return view('notifications.index', [
            'notifications' => Notification::where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->get()
        ]);
    }

    public function markRead($id)
    {
        Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->update(['is_read' => true]);

        return back();
    }

    public function markAllRead()
    {
        Notification::where('user_id', auth()->id())
            ->update(['is_read' => true]);

        return back();
    }

    public function delete($id)
    {
        Notification::where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();

        return back();
    }

    public function deleteAll()
    {
        Notification::where('user_id', auth()->id())->delete();
        return back();
    }
}
