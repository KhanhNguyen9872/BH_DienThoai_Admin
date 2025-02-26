<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function notifications()
    {
        // Retrieve notifications ordered by most recent time
        $notifications = DB::table('notifications')
            ->orderBy('time', 'desc')
            ->get();

        return view('notifications', compact('notifications'));
    }
}
