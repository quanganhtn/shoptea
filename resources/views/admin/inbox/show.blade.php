@extends('admin.admin')

@section('title','Inbox')
@section('page_title','💬 Inbox - Message khách hàng')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/inbox.css') }}">
@endpush

@section('content')
    <div class="admin-container">

        <div class="inbox">

            {{-- LEFT: LIST CONVERSATIONS --}}
            <div class="admin-card inbox-list">
                <div class="inbox-list__title">Cuộc trò chuyện</div>

                @forelse($conversations as $c)
                    @php
                        $active = $c->id === $conversation->id;
                        $name = $c->user?->name ?? 'Người dùng';
                        $updated = optional($c->last_message_at)->format('d/m/Y H:i');
                    @endphp

                    <a class="conv {{ $active ? 'is-active' : '' }}"
                       href="{{ route('admin.inbox.show', $c) }}">
                        <div class="conv__top">
                            <div class="conv__name">{{ $name }}</div>
                            <span class="admin-pill">Mở</span>
                        </div>
                        <div class="conv__meta">Cập nhật: {{ $updated }}</div>
                    </a>
                @empty
                    <div class="admin-alert">Chưa có cuộc trò chuyện nào.</div>
                @endforelse

                <div style="margin-top:12px">
                    {{ $conversations->links() }}
                </div>
            </div>

            {{-- RIGHT: CHAT PANEL --}}
            <div class="admin-card chatpanel">

                <div class="chatpanel__head">
                    <div>
                        <div class="chatpanel__title">
                            {{ $conversation->user?->name ?? 'Người dùng' }}
                        </div>
                        <div class="chatpanel__sub">
                            Trả lời khách hàng trực tiếp • Tin nhắn sẽ cập nhật tự động
                        </div>
                    </div>


                </div>

                <div class="chatpanel__body" id="adminChatBody"
                     data-conversation="{{ $conversation->id }}"
                     data-last-id="{{ $messages->last()?->id ?? 0 }}">
                    @forelse($messages as $m)
                        <div class="amsg {{ $m->sender === 'admin' ? 'amsg--admin' : 'amsg--user' }}">
                            {{ $m->body }}
                            <div class="amsg__time">{{ $m->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    @empty
                        <div class="admin-alert">Chưa có tin nhắn.</div>
                    @endforelse
                </div>

                <div class="chatpanel__composer">
                    <form method="POST" action="{{ route('admin.inbox.send', $conversation) }}" class="composer"
                          id="adminChatForm">
                        @csrf
                        <textarea name="body" class="composer__input" rows="2"
                                  placeholder="Nhập tin nhắn trả lời..." required></textarea>

                        <button class="composer__btn" type="submit">Gửi</button>
                    </form>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                const body = document.getElementById('adminChatBody');
                const form = document.getElementById('adminChatForm');
                if (!body) return;

                // auto scroll to bottom on load
                body.scrollTop = body.scrollHeight;

                // chống double submit
                if (form) {
                    form.addEventListener('submit', () => {
                        const btn = form.querySelector('button[type="submit"]');
                        if (btn) {
                            btn.disabled = true;
                        }
                    });
                }

                // ===== polling: tự cập nhật tin nhắn (admin không cần F5) =====
                const conversationId = body.dataset.conversation;
                let lastId = Number(body.dataset.lastId || 0);

                async function fetchNew() {
                    try {
                        // dùng endpoint chat.fetch luôn được, nhưng nó đang auth theo user (khách) -> admin sẽ bị 403
                        // => mình tạo 1 endpoint admin riêng là chuẩn nhất (bên dưới mình đưa luôn).
                        const url = `{{ url('/admin/inbox') }}/${conversationId}/messages?after_id=${lastId}`;
                        const res = await fetch(url, {headers: {'X-Requested-With': 'XMLHttpRequest'}});
                        if (!res.ok) return;
                        const data = await res.json();
                        data.forEach(m => {
                            const div = document.createElement('div');
                            div.className = 'amsg ' + (m.sender === 'admin' ? 'amsg--admin' : 'amsg--user');
                            div.textContent = m.body;

                            const t = document.createElement('div');
                            t.className = 'amsg__time';
                            t.textContent = m.created_at_fmt || '';
                            div.appendChild(t);

                            body.appendChild(div);
                            lastId = Math.max(lastId, m.id);
                        });
                        if (data.length) body.scrollTop = body.scrollHeight;
                    } catch (e) {
                    }
                }

                setInterval(fetchNew, 2500);
            })();
        </script>
    @endpush
@endsection
