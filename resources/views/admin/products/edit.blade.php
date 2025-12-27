@extends('admin.admin')

@section('content')
    <div class="container my-4">
        <h2 class="mb-4 text-center fw-bold">Sửa sản phẩm</h2>

        {{-- Hiển thị lỗi validate --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form sửa sản phẩm --}}
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Tên sản phẩm:</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Giá:</label>
                <input type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả:</label>
                <textarea name="description"
                          class="form-control"
                          rows="6">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Ảnh (tên file trong uploads/...):</label>
                <input type="text" name="image" class="form-control" value="{{ old('image', $product->image) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Loại trà:</label>
                <select name="category" class="form-select" required>
                    @php
                        $categories = [
                            'Trà xanh',
                            'Trà đen',
                            'Trà ô long',
                            'Trà thảo mộc',
                            'Trà trái cây',
                            'Trà sữa',
                            'Trà đặc sản',
                        ];
                    @endphp
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}"
                            {{ old('category', $product->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-success">Cập nhật</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection
