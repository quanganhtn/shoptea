@extends('layouts.home')

@section('content')
    <div class="container my-4">
        <h2 class="mb-4 text-center fw-bold">Thêm sản phẩm mới</h2>

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

        {{-- Form thêm sản phẩm --}}
        <form action="{{ route('admin.products.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Tên sản phẩm:</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Giá:</label>
                <input type="number" name="price" class="form-control" value="{{ old('price') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả:</label>
                <textarea name="description" class="form-control">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Ảnh (tên file trong uploads/...):</label>
                <input type="text" name="image" class="form-control" value="{{ old('image') }}">
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
                        <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>
                            {{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-success">Thêm mới</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
@endsection
