<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class AdminInboxController extends Controller
{
    public function index()
    {
        //lấy danh sách
        $conversations = Conversation::with('user') //lấy danh sách lấy luôn bảng use
        ->orderByDesc('last_message_at')//sắp xếp lần lượt mới lên đầu
        ->paginate(20); // chia mỗi trang 20 dòng

        // mở đoạn chat đầu tiên
        if ($conversations->count()) {
            $firstConversation = $conversations->getCollection()->first();
            return redirect()->route('admin.inbox.show', $firstConversation);
        }

        return view('admin.inbox.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        // ✅ ADMIN VỪA MỞ CHAT → COI NHƯ ĐÃ ĐỌC
        $conversation->update([
            'admin_last_read_at' => now(),
            'admin_unread' => 0,
        ]);

        $conversations = Conversation::with('user')
            ->orderByDesc('last_message_at')//mới nhất lên đầu.
            ->paginate(20);// chia mỗi trang 20 dòng

        $messages = $conversation->messages()->orderBy('id')->get();//lấy tất cả cuộc trò chuyền sắp xếp theo ID tăng dần từ cũ đến mới

        return view('admin.inbox.show', compact('conversation', 'messages', 'conversations'));
    }

    public function messages(Request $req, Conversation $conversation)
    {
        $afterId = (int)$req->get('after_id', 0); //ép id kiểu int, id bắt đầu từ 1

        $msgs = $conversation->messages()//chỉ lấy tin nhắn từ 1 cuộc trò chuyện
        ->where('id', '>', $afterId)//lọc tin nhắn mới. sẽ lưu tin nhắn tiếp theo bạn nhắn với id tăng dần
        ->orderBy('id')// sắp xếp từ cũ đến mới
        ->get(['id', 'sender', 'body', 'created_at']);//chỉ dùng những cột cần thiết

        if ($msgs->isNotEmpty()) {
            $conversation->update([
                'admin_last_read_at' => now(),
                'admin_unread' => 0,
            ]);
        }

        return response()->json(
            $msgs->map(fn($m) => [//Biến đổi từng message model thành mảng
                'id' => $m->id,
                'sender' => $m->sender, //user hoặc admin
                'body' => $m->body, //nội dung
                'created_at_fmt' => $m->created_at->format('d/m/Y H:i'),
            ])
        );
    }


    public function send(Request $req, Conversation $conversation)
    {
        $data = $req->validate(['body' => 'required|string|max:2000']);

        Message::create([
            'conversation_id' => $conversation->id,
            'sender' => 'admin',
            'body' => $data['body'],   // ✅ tránh magic property
        ]);

        $conversation->update([
            'last_message_at' => now(),
            'last_sender' => 'admin',
            'admin_last_read_at' => now(),
            'admin_unread' => 0,       // ✅ admin rep => xử lý xong => hết “mới”
        ]);

        return back();

    }

    public function unreadCount()
    {
        $count = Conversation::where('admin_unread', 1)->count();
        return response()->json(['count' => $count]);
    }
}
