@extends('admin.admin')

@section('title','Sửa sản phẩm')
@section('page_title','✏️ Sửa sản phẩm')

@section('content')
    <div class="admin-container">
        <div class="admin-card">
            <h2 class="admin-h2 admin-h2--center">Sửa sản phẩm</h2>

            @if ($errors->any())
                <div class="admin-alert admin-alert--danger">
                    <ul class="admin-ul">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" class="admin-form">
                @csrf
                @method('PUT')

                <div class="admin-field">
                    <label class="admin-label">Tên sản phẩm</label>
                    <input type="text" name="name" class="admin-input"
                           value="{{ old('name', $product->name) }}" required>
                </div>

                <div class="admin-field">
                    <label class="admin-label">Giá</label>
                    <input type="number" name="price" class="admin-input"
                           value="{{ old('price', $product->price) }}" required>
                </div>

                <div class="admin-field">
                    <label class="admin-label">Mô tả</label>
                    <textarea name="description" class="admin-textarea"
                              rows="6">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="admin-field">
                    <label class="admin-label">Ảnh (tên file trong uploads/...)</label>
                    <input type="text" name="image" class="admin-input"
                           value="{{ old('image', $product->image) }}">
                </div>

                <div class="admin-field">
                    <label class="admin-label">Loại trà</label>

                    <select name="category_id" class="admin-select" required>
                        <option value="">-- Chọn danh mục --</option>

                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}"
                                @selected(old('category_id', $product->category_id) == $cat->id)>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <div class="admin-form__actions">
                    <button type="submit" class="admin-btn admin-btn--success">Cập nhật</button>
                    <a href="{{ route('admin.products.index') }}" class="admin-btn admin-btn--outline">Hủy</a>
                </div>
            </form>
        </div>
    </div>
@endsection
