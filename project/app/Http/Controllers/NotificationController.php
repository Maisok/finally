<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'notifications' => $request->user()->notifications()
                ->latest()
                ->take(10)
                ->get(),
            'unread_count' => $request->user()->unreadNotifications()->count(),
        ]);
    }
    
    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        
        if (is_null($notification->read_at)) {
            $notification->update(['read_at' => now()]);
        }
    
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'unread_count' => $request->user()->unreadNotifications()->count()
            ]);
        }
    
        return back()->with('success', 'Уведомление помечено как прочитанное');
    }
    
    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);
        
        return response()->json([
            'success' => true,
            'unread_count' => 0
        ]);
    }

    public function list(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('notifications.list', compact('notifications'));
    }
}