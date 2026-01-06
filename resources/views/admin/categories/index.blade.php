@extends('admin.admin')

@section('title','Danh mục')
@section('page_title','🏷️ Danh mục')

@section('content')
    <div class="admin-container">

        <div class="admin-pagehead">
            <h2 class="admin-h2">Danh sách danh mục</h2>
        </div>

        @if(session('success'))
            <div class="admin-alert admin-alert--success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="admin-alert admin-alert--danger">
                <ul class="admin-ul">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM THÊM (ngay trong trang) --}}
        <div class="admin-card" style="margin-bottom:14px;">
            <form method="POST" action="{{ route('admin.categories.store') }}" class="admin-filter">
                @csrf
                <div class="admin-field">
                    <input name="name" value="{{ old('name') }}" class="admin-input"
                           placeholder="Nhập tên danh mục (VD: Trà xanh)">
                </div>
                <div class="admin-filter__actions">
                    <button class="admin-btn admin-btn--success" type="submit">➕ Thêm</button>
                </div>
            </form>
        </div>

        {{-- TABLE --}}
        <div class="admin-card">
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                    <tr>
                        <th style="width:90px;">ID</th>
                        <th class="admin-th--left">Tên danh mục</th>
                        <th style="width:180px;">Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($categories as $cat)
                        <tr>
                            <td>{{ $cat->id }}</td>
                            <td class="admin-td--left">{{ $cat->name }}</td>
                            <td>
                                <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST"
                                      onsubmit="return confirm('Xóa danh mục này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="admin-btn admin-btn--danger admin-btn--sm" type="submit">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="admin-empty">Chưa có danh mục.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="admin-pagination">
                {{ $categories->links() }}
            </div>
        </div>

    </div>
@endsection
