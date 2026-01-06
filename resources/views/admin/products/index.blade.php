@extends('admin.admin')

@php
    if (!function_exists('sort_link')) {
        function sort_link($column) {
            $currentSort = request('sort', 'id');
            $currentDir  = request('dir', 'desc');
            $dir = ($currentSort === $column && $currentDir === 'asc') ? 'desc' : 'asc';

            return request()->fullUrlWithQuery([
                'sort' => $column,
                'dir'  => $dir,
            ]);
        }
    }
@endphp

@section('title','Sản phẩm')
@section('page_title','🧋 Sản phẩm')

@section('content')
    <div class="admin-container">
        <div class="admin-pagehead">
            <h2 class="admin-h2">Danh sách sản phẩm</h2>

            <a href="{{ route('admin.products.create') }}" class="admin-btn admin-btn--success">
                ➕ Thêm sản phẩm mới
            </a>
        </div>

        @if (session('success'))
            <div class="admin-alert admin-alert--success">{{ session('success') }}</div>
        @endif

        {{-- FILTER --}}
        <form method="GET" class="admin-filter">
            <div class="admin-field">
                <input name="q" value="{{ request('q') }}" class="admin-input" placeholder="Tìm tên sản phẩm">
            </div>

            <div class="admin-field">
                <div class="admin-field">
                    <select name="category_id" class="admin-select">
                        <option value="">-- Tất cả danh mục --</option>

                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}"
                                @selected((string)request('category_id') === (string)$cat->id)>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="admin-field">
                <input name="min" value="{{ request('min') }}" class="admin-input" placeholder="Giá từ">
            </div>

            <div class="admin-field">
                <input name="max" value="{{ request('max') }}" class="admin-input" placeholder="Giá đến">
            </div>

            <div class="admin-filter__actions">
                <button class="admin-btn admin-btn--primary" type="submit">Lọc</button>
                <a class="admin-btn admin-btn--outline" href="{{ route('admin.products.index') }}">Reset</a>
            </div>
        </form>

        {{-- TABLE --}}
        <div class="admin-card">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                    <tr>
                        <th><a class="admin-sort" href="{{ sort_link('id') }}">ID</a></th>
                        <th><a class="admin-sort" href="{{ sort_link('name') }}">Tên</a></th>
                        <th><a class="admin-sort" href="{{ sort_link('price') }}">Giá</a></th>
                        <th>Mô tả</th>
                        <th>Ảnh</th>
                        <th>Danh mục</th>
                        <th>Hành động</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td class="admin-td--left">{{ $product->name }}</td>
                            <td>{{ number_format($product->price) }} đ</td>
                            <td class="admin-td--left">{{ \Illuminate\Support\Str::limit($product->description, 50) }}</td>
                            <td>
                                @if ($product->image)
                                    <img class="admin-thumb"
                                         src="{{ asset('uploads/' . $product->image) }}"
                                         alt="{{ $product->name }}">
                                @else
                                    <span class="admin-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $product->category?->name ?? '—' }}</td>

                            <td>
                                <div class="admin-actions">
                                    <a href="{{ route('admin.products.edit', $product->id) }}"
                                       class="admin-btn admin-btn--primary admin-btn--sm">Sửa</a>

                                    <form action="{{ route('admin.products.destroy', $product->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Bạn có chắc muốn xóa?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-btn admin-btn--danger admin-btn--sm">Xóa
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="admin-empty">Không có sản phẩm nào.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="admin-pagination">
                {{ $products->links() }}
            </div>
        </div>
    </div>
@endsection
