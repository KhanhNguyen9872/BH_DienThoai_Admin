<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class SettingsController extends Controller
{
    public function index()
    {
        // Define the keys for the settings.
        $keys = ['BOT_USERNAME', 'BOT_TOKEN', 'BOT_CHAT_ID', 'BOT_SEND_NOTIFICATION_AFTER_ORDER', 'MAINTENANCE', 'CHATBOT_ENABLE', 'GEMINI_API_KEY', 'LOCAL_CHATBOT_MODEL', 'LOCAL_CHATBOT_URL', 'LOCAL_CHATBOT_TEMPERATURE', 'CHATBOT_AVATAR', 'CHATBOT_USER_AVATAR'];
        
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
        'telegram_status'       => 'nullable|string|max:255',
        'telegram_username'     => 'nullable|string|max:255',
        'telegram_token'        => 'nullable|string|max:255',
        'telegram_chat_id'      => 'nullable|string|max:255',
        'maintenance'           => 'nullable|string|max:255',  // Trường mới cho chế độ bảo trì
        'chatbot_enable'        => 'nullable|string|max:255',  // Trường mới cho chatbot (0 = off, 1 = local, 2 = gemini)
        'local_chatbot_model'   => 'nullable|string|max:255',  // Trường mới cho Local Model
        'local_chatbot_url'     => 'nullable|string|max:255',
        'local_chatbot_temperature' => 'nullable|string|max:255',
        'gemini_api_key'        => 'nullable|string|max:255',  // Trường mới cho Gemini API Key
        'bot_avatar'  => 'nullable|image|max:2048',
        'user_avatar' => 'nullable|image|max:2048',
    ]);

    if ($validated['chatbot_enable'] == '1') {
        $model = $validated['local_chatbot_model'];
        if (!$model) {
            return redirect()->route('settings')
                         ->with('error', 'Tên mô hình Chatbot không được để trống khi chọn chế độ [Cục bộ]');
        }

        $url = $validated['local_chatbot_url'];
        if (!$url) {
            return redirect()->route('settings')
                         ->with('error', 'URL Chatbot cục bộ không được để trống khi chọn chế độ [Cục bộ]');
        }

        $temperature = $validated['local_chatbot_temperature'];
        if (!$temperature) {
            return redirect()->route('settings')
                         ->with('error', 'Mức độ ngẫu nhiên Chatbot cục bộ không được để trống khi chọn chế độ [Cục bộ]');
        }
    } else if ($validated['chatbot_enable'] == '2') {
        $gemini = $validated['gemini_api_key'];
        if (!$gemini) {
            return redirect()->route('settings')
                         ->with('error', 'Gemini API Key không được để trống khi chọn chế độ [Gemini]');
        }
    }

    $temperature = $validated['local_chatbot_temperature'] ?? null;

    if ($temperature < 0.0 || $temperature > 1.0) {
        return redirect()->route('settings')
                         ->with('error', 'Temperature phải nằm trong khoảng từ 0.0 đến 1.0');
    }
    
    DB::table('settings')
        ->where('key', 'LOCAL_CHATBOT_TEMPERATURE')
        ->update(['value' => $temperature]);

    // Update các trường cũ
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

    // Update chế độ MAINTENANCE
    DB::table('settings')->where('key', 'MAINTENANCE')->update([
        'value' => isset($validated['maintenance']) && $validated['maintenance'] ? 1 : 0,
    ]);

    // Update CHATBOT_ENABLE theo giá trị được chọn (0 = off, 1 = local, 2 = gemini)
    DB::table('settings')->where('key', 'CHATBOT_ENABLE')->update([
        'value' => $validated['chatbot_enable'] ?? '0',
    ]);

    // Update Local Model (LOCAL_CHATBOT_MODEL)
    DB::table('settings')->where('key', 'LOCAL_CHATBOT_MODEL')->update([
        'value' => $validated['local_chatbot_model'] ?? '',
    ]);

    DB::table('settings')->where('key', 'LOCAL_CHATBOT_URL')->update([
        'value' => $validated['local_chatbot_url'] ?? '',
    ]);
    
    // Update Gemini API Key (GEMINI_API_KEY)
    DB::table('settings')->where('key', 'GEMINI_API_KEY')->update([
        'value' => $validated['gemini_api_key'] ?? '',
    ]);

    // ----------------------------------
    // 2) Handle the Bot Avatar Upload
    // ----------------------------------
    if ($request->hasFile('bot_avatar')) {
        $botAvatar     = $request->file('bot_avatar');
        // Construct a random file name, e.g. "bot_avatar_1677612345.jpg"
        $randomFileName = 'bot_avatar_' . time() . '.' . $botAvatar->getClientOriginalExtension();

        // Make sure the target directory exists (public/storage/img). If not, create it.
        $destinationDir = public_path('storage/img');
        if (!\File::exists($destinationDir)) {
            \File::makeDirectory($destinationDir, 0755, true);
        }

        // Move the file to "public/storage/img/"
        $botAvatar->move($destinationDir, $randomFileName);

        // Update DB with the relative path you’ll use for displaying the avatar
        DB::table('settings')
            ->where('key', 'CHATBOT_AVATAR')
            ->update(['value' => '/img/' . $randomFileName]);
    }

    // -----------------------------------
    // 3) Handle the User Avatar Upload
    // -----------------------------------
    if ($request->hasFile('user_avatar')) {
        $userAvatar     = $request->file('user_avatar');
        $randomFileName = 'user_avatar_' . time() . '.' . $userAvatar->getClientOriginalExtension();

        // Make sure the target directory exists (public/storage/img). If not, create it.
        $destinationDir = public_path('storage/img');
        if (!\File::exists($destinationDir)) {
            \File::makeDirectory($destinationDir, 0755, true);
        }

        // Move the file to "public/storage/img/"
        $userAvatar->move($destinationDir, $randomFileName);

        DB::table('settings')
            ->where('key', 'CHATBOT_USER_AVATAR')
            ->update(['value' => '/img/' . $randomFileName]);
    }

    return redirect()->route('settings')->with('success', 'Cài đặt đã được cập nhật!');
}

public function getModels(Request $request)
{
    // Get the URL from the request body
    $baseUrl = $request->input('url');
    if (!$baseUrl) {
        return response()->json(['success' => false, 'error' => 'URL parameter is required.'], 400);
    }

    // Append /v1/models if not already present
    if (strpos($baseUrl, '/v1/models') === false) {
        $baseUrl = rtrim($baseUrl, '/');
        $url = $baseUrl . '/v1/models';
    } else {
        $url = $baseUrl;
    }

    try {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->timeout(5)->get($url);

        // Decode the response body to JSON
        $data = json_decode($response->body(), true);

        // Extract only the model ids from the response
        $models = [];
        if (isset($data['data']) && is_array($data['data'])) {
            foreach ($data['data'] as $item) {
                if (isset($item['object']) && $item['object'] === 'model' && isset($item['id'])) {
                    $models[] = $item['id'];
                }
            }
        }

        return response()->json(['success' => true, 'data' => $models]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}


public function testAPIGemini(Request $request)
    {
        // Retrieve the API key from the request body.
        $apiKey = $request->input('api_key');
        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'error'   => 'API key is required.'
            ], 400);
        }
        
        // Construct the Gemini API test URL.
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=" . urlencode($apiKey);
        
        // Prepare the test payload.
        $payload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => 'Hello World!']
                    ]
                ]
            ]
        ];
        
        try {
            // Send a POST request to the Gemini API.
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->timeout(5)->post($url, $payload);
            
            // Decode the response JSON.
            $data = json_decode($response->body(), true);
            
            // Check for a valid candidate text in the response.
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                return response()->json([
                    'success' => true
                ]);
            } else {
                return response()->json([
                    'success' => false
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage()
            ], 500);
        }
    }

}
