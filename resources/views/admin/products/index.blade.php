@extends('layouts.home')

@section('content')
    <div class="container my-4">
        <h2 class="mb-4 text-center fw-bold">Danh sách sản phẩm</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('admin.products.create') }}" class="btn btn-success mb-3">➕ Thêm sản phẩm mới</a>

        <table class="table table-striped table-bordered align-middle text-center">
            <thead class="table-success">
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Giá</th>
                    <th>Mô tả</th>
                    <th>Ảnh</th>
                    <th>Category</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ number_format($product->price) }} đ</td>
                        <td>{{ Str::limit($product->description, 50) }}</td>
                        <td>
                            @if ($product->image)
                                <img src="{{ asset('uploads/' . $product->image) }}" alt="{{ $product->name }}" width="60">
                            @endif
                        </td>
                        <td>{{ $product->category }}</td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product->id) }}"
                                class="btn btn-primary btn-sm mb-1">Sửa</a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
