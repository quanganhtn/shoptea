@extends('admin.admin')

@section('title','Homepage - Banner')
@section('page_title','🏠 Trang chủ / Banner')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/homepage.css') }}">
@endpush

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

            <form action="{{ route('admin.homepage.hero.update') }}" method="POST" enctype="multipart/form-data"
                  class="admin-form">
                @csrf

                <div class="admin-field">
                    <label class="admin-label">Ảnh Banner (Hero)</label>
                    <input type="file" name="image" class="admin-input"/>
                    @if (!empty($data['image']))
                        <div class="admin-preview">
                            <div class="admin-muted">Ảnh hiện tại:</div>
                            <img class="admin-preview__img" src="{{ asset($data['image']) }}" alt="Current hero">
                        </div>
                    @endif
                </div>

                <div class="admin-field">
                    <label class="admin-label">Tiêu đề (title)</label>
                    <input type="text" name="title" class="admin-input"
                           value="{{ old('title', $data['title'] ?? '') }}" placeholder="Nhập tiêu đề ...">
                </div>

                <div class="admin-field">
                    <label class="admin-label">Phụ đề (subtitle)</label>
                    <input type="text" name="subtitle" class="admin-input"
                           value="{{ old('subtitle', $data['subtitle'] ?? '') }}" placeholder="Nhập nội dung ...">
                </div>

                <div class="admin-form__actions">
                    <button class="admin-btn admin-btn--primary">💾 Lưu Banner</button>

                </div>
            </form>
        </div>
    </div>
@endsection
