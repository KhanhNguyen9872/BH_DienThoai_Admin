<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use Carbon\Carbon;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = DB::table('notifications')
    ->orderBy('time', 'desc')
    ->paginate(50);

        return view('notifications.index', compact('notifications'));
    }

    // Mark a specific notification as read
    public function markAsRead($id)
    {
        $notification = Notification::find($id);
        
        if ($notification) {
            $notification->isRead = true;
            $notification->save();
        }

        return back()->with('success', `Đánh dấu đã đọc thành công [ID: ${id}`);
    }

    // Mark all notifications as read
    public function markAllAsRead()
    {
        Notification::where('isRead', false)->update(['isRead' => true]);
        return back()->with('success', 'Tất cả thông báo đã được đánh dấu đã đọc!');
    }
}
