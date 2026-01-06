@extends('admin.admin')

@section('title','Thêm sản phẩm')
@section('page_title','➕ Thêm sản phẩm')

@section('content')
    <div class="admin-container">
        <div class="admin-card">
            <h2 class="admin-h2 admin-h2--center">Thêm sản phẩm mới</h2>

            @if ($errors->any())
                <div class="admin-alert admin-alert--danger">
                    <ul class="admin-ul">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.products.store') }}" method="POST" class="admin-form">
                @csrf

                <div class="admin-field">
                    <label class="admin-label">Tên sản phẩm</label>
                    <input type="text" name="name" class="admin-input" value="{{ old('name') }}" required>
                </div>

                <div class="admin-field">
                    <label class="admin-label">Giá</label>
                    <input type="number" name="price" class="admin-input" value="{{ old('price') }}" required>
                </div>

                <div class="admin-field">
                    <label class="admin-label">Mô tả</label>
                    <textarea name="description" class="admin-textarea" rows="6">{{ old('description') }}</textarea>
                </div>

                <div class="admin-field">
                    <label class="admin-label">Ảnh (tên file trong uploads/...)</label>
                    <input type="text" name="image" class="admin-input" value="{{ old('image') }}">
                </div>

                <div class="admin-field">
                    <label class="admin-label">Loại trà</label>
                    <select name="category_id" class="admin-select" required>
                        <option value="">-- Chọn danh mục --</option>

                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}"
                                @selected(old('category_id') == $cat->id)>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="admin-form__actions">
                    <button type="submit" class="admin-btn admin-btn--success">Thêm mới</button>
                    <a href="{{ route('admin.products.index') }}" class="admin-btn admin-btn--outline">Hủy</a>
                </div>
            </form>
        </div>
    </div>
@endsection
