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
        // Retrieve the 50 most recent notifications ordered by the 'time' column
        $notifications = DB::table('notifications')
            ->orderBy('time', 'desc') // Order notifications by time in descending order
            ->limit(50) // Limit the number of results to 50
            ->get();

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
