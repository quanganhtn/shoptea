@extends('admin.admin')

@section('title','Homepage - About')
@section('page_title','🏠 Trang chủ / About')

@section('content')
    <div class="admin-container">
        <div class="admin-card">
            @if (session('success'))
                <div class="admin-alert admin-alert--success">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="admin-alert admin-alert--danger">
                    @foreach($errors->all() as $e)
                        <div>{{ $e }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.homepage.about.update') }}" method="POST" enctype="multipart/form-data"
                  class="admin-form">
                @csrf

                <div class="admin-field">
                    <label class="admin-label">Ảnh About</label>
                    <input type="file" name="image" class="admin-input"/>
                    @if(!empty($data['image']))
                        <div class="admin-preview">
                            <div class="admin-muted">Ảnh hiện tại:</div>
                            <img class="admin-preview__img admin-preview__img--large" src="{{ asset($data['image']) }}"
                                 alt="Current about">
                        </div>
                    @endif
                </div>

                <div class="admin-field">
                    <label class="admin-label">Tiêu đề</label>
                    <input type="text" name="title" class="admin-input"
                           value="{{ old('title', $data['title'] ?? '') }}" placeholder="VD: Về ShopTea">
                </div>

                <div class="admin-field">
                    <label class="admin-label">Mô tả ngắn</label>
                    <input type="text" name="desc" class="admin-input"
                           value="{{ old('desc', $data['desc'] ?? '') }}"
                           placeholder="VD: Trà sạch – nguyên liệu chọn lọc">
                </div>

                <div class="admin-field">
                    <label class="admin-label">Nội dung</label>
                    <textarea name="text" class="admin-textarea" rows="6"
                              placeholder="Nhập nội dung about...">{{ old('text', $data['text'] ?? '') }}</textarea>
                </div>

                <div class="admin-form__actions">
                    <button class="admin-btn admin-btn--primary">💾 Lưu About</button>
                </div>
            </form>
        </div>
    </div>
@endsection
