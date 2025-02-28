<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function index()
    {
        // Define the keys for the settings.
        $keys = ['BOT_USERNAME', 'BOT_TOKEN', 'BOT_CHAT_ID', 'BOT_SEND_NOTIFICATION_AFTER_ORDER', 'MAINTENANCE', 'CHATBOT_ENABLE'];
        
        // Retrieve the settings as an associative array: key => value.
        $settings = DB::table('settings')
                        ->whereIn('key', $keys)
                        ->pluck('value', 'key');

        // Pass the settings to the view.
        return view('settings.index', ['settings' => $settings]);
    }

    public function updateSettings(Request $request)
    {
        // Validate the incoming data
        $validated = $request->validate([
            'telegram_status'   => 'nullable|string|max:255',
            'telegram_username' => 'nullable|string|max:255',
            'telegram_token'    => 'nullable|string|max:255',
            'telegram_chat_id'  => 'nullable|string|max:255',
            'maintenance'       => 'nullable|string|max:255',  // New field for maintenance
            'chatbot_enable'    => 'nullable|string|max:255',  // New field for chatbot enable/disable
        ]);

        // Update the settings in the database.
        DB::table('settings')->where('key', 'BOT_SEND_NOTIFICATION_AFTER_ORDER')->update([
            'value' => isset($validated['telegram_status']) && $validated['telegram_status'] ? 1 : 0,
        ]);

        DB::table('settings')->where('key', 'BOT_USERNAME')->update([
            'value' => $validated['telegram_username'] ?? '',
        ]);

        DB::table('settings')->where('key', 'BOT_TOKEN')->update([
            'value' => $validated['telegram_token'] ?? '',
        ]);

        DB::table('settings')->where('key', 'BOT_CHAT_ID')->update([
            'value' => $validated['telegram_chat_id'] ?? '',
        ]);

        // Update MAINTENANCE key
        DB::table('settings')->where('key', 'MAINTENANCE')->update([
            'value' => isset($validated['maintenance']) && $validated['maintenance'] ? 1 : 0,
        ]);

        // Update CHATBOT_ENABLE key
        DB::table('settings')->where('key', 'CHATBOT_ENABLE')->update([
            'value' => isset($validated['chatbot_enable']) && $validated['chatbot_enable'] ? 1 : 0,
        ]);

        return redirect()->route('settings')->with('success', 'Cài đặt đã được cập nhật!');
    }

}
