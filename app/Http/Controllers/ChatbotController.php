<?php

namespace App\Http\Controllers;

use App\Models\UserInfo;
use App\Models\HistoryChatbot;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function index()
    {
        $users = UserInfo::paginate(10);
        return view('chatbot.index', compact('users'));
    }

    /**
     * Display the chat history for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
{
    // Retrieve the chatbot avatar and user avatar URLs from the 'settings' table.
    $chatbotAvatar = \DB::table('settings')
        ->where('key', 'CHATBOT_AVATAR')
        ->value('value');

    $userAvatar = \DB::table('settings')
        ->where('key', 'CHATBOT_USER_AVATAR')
        ->value('value');

    if ($chatbotAvatar) {
        $chatbotAvatar = '/storage/' . $chatbotAvatar;
    }

    if ($userAvatar) {
        $userAvatar = '/storage/' . $userAvatar;
    }

    // Retrieve all chat history records for the given user_id, ordered by time ascending.
    $history = HistoryChatbot::where('user_id', $id)
        ->orderBy('time', 'asc')
        ->get();

    foreach ($history as $item) {
        $item->message = str_replace("\n\n", '<br>', $item->message);
    }

    // Pass the history, chatbot avatar URL, and user avatar URL to the view.
    return view('chatbot.show', compact('id', 'history', 'chatbotAvatar', 'userAvatar'));
}

    /**
     * Delete all chat history for the specified user ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clear($id)
    {
        // Delete all chatbot history for the given user
        HistoryChatbot::where('user_id', $id)->delete();

        // Redirect back (or to another route) with a success message
        return redirect()->back()->with('success', 'Lịch sử trò chuyện đã được xóa thành công.');
    }

}
