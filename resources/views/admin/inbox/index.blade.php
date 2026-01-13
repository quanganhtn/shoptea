@extends('admin.admin')

@section('title','Inbox')
@section('page_title','💬 Hộp thư khách hàng')

@section('content')
    <div class="admin-container">
        <div class="admin-card">
            <h2 class="admin-h2" style="margin:0 0 12px">Cuộc trò chuyện</h2>

            <div style="display:grid;gap:10px">
                @forelse($conversations as $c)
                    <a href="{{ route('admin.inbox.show', $c) }}" class="admin-card"
                       style="text-decoration:none;color:inherit">
                        <div style="display:flex;justify-content:space-between;gap:12px;align-items:center">
                            <div>
                                <div style="font-weight:800">{{ $c->user?->name ?? 'Người dùng' }}</div>
                                <div class="admin-muted" style="font-size:13px">
                                    Cập nhật: {{ optional($c->last_message_at)->format('d/m/Y H:i') }}
                                </div>
                            </div>
                            <span class="admin-pill">Mở</span>
                        </div>
                    </a>
                @empty
                    <div class="admin-alert">Chưa có cuộc trò chuyện nào.</div>
                @endforelse
            </div>

            <div style="margin-top:12px">{{ $conversations->links() }}</div>
            
        </div>
    </div>
@endsection
