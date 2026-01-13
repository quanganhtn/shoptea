<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function init()
    {
        $conv = $this->findOrCreateConversation();
        return response()->json(['conversation_id' => $conv->id]);
    }

    private function findOrCreateConversation(): Conversation
    {
        $userId = auth()->id();

        $conv = Conversation::where('user_id', $userId)->first();

        if (!$conv) {
            // chọn admin đầu tiên (role = admin)
            $adminId = User::where('role', 'admin')->value('id');

            $conv = Conversation::create([
                'user_id' => $userId,
                'admin_id' => $adminId,
                'last_message_at' => now(),
                'last_sender' => 'admin',
            ]);

            // tin nhắn chào từ admin
            Message::create([
                'conversation_id' => $conv->id,
                'sender' => 'admin',
                'body' => 'ShopTea xin chào 👋 Bạn cần hỗ trợ gì ạ?',
            ]);
        }

        return $conv;
    }

    // GET /chat/init

    public function fetch(Request $req)
    {
        $req->validate([
            'conversation_id' => 'required|integer',
            'after_id' => 'nullable|integer',
        ]);

        $conv = Conversation::findOrFail($req->conversation_id);
        $this->authorizeConversation($conv);

        $afterId = (int)($req->after_id ?? 0);

        $msgs = $conv->messages()
            ->where('id', '>', $afterId)
            ->orderBy('id')
            ->get(['id', 'sender', 'body', 'created_at']);

        return response()->json($msgs);
    }

    // GET /chat/messages?conversation_id=1&after_id=0

    private function authorizeConversation(Conversation $conv): void
    {
        abort_unless($conv->user_id === auth()->id(), 403);
    }

    // POST /chat/send

    public function send(Request $req)
    {
        $data = $req->validate([
            'conversation_id' => 'required|integer',
            'body' => 'required|string|max:2000',
        ]);

        $conv = Conversation::findOrFail($data['conversation_id']);
        $this->authorizeConversation($conv);

        $msg = Message::create([
            'conversation_id' => $conv->id,
            'sender' => 'user',
            'body' => $data['body'],
        ]);

        $conv->update([
            'last_message_at' => now(),
            'last_sender' => 'user',
            'admin_unread' => 1,
        ]);

        return response()->json(['ok' => true, 'id' => $msg->id]);

    }
}
