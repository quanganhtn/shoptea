@extends('admin.admin')

@section('title','Danh mục')
@section('page_title','🏷️ Danh mục')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/categories.css') }}">
@endpush

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

        {{-- FORM THÊM --}}
        <div class="admin-card cat-add-card">
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
                        <th class="cat-col-id">ID</th>
                        <th class="admin-th--left">Tên danh mục</th>
                        <th class="cat-col-actions">Hành động</th>
                    </tr>
                    </thead>
                    <tbody>

                    @forelse($categories as $cat)
                        <tr>
                            <td>{{ $cat->id }}</td>

                            <td class="admin-td--left">

                                {{-- VIEW --}}
                                <span class="cat-view" id="view-{{ $cat->id }}">
                            {{ $cat->name }}
                        </span>

                                {{-- EDIT --}}
                                <form id="edit-{{ $cat->id }}"
                                      class="cat-edit-form"
                                      action="{{ route('admin.categories.update', $cat->id) }}"
                                      method="POST">
                                    @csrf
                                    @method('PUT')

                                    <input name="name"
                                           value="{{ $cat->name }}"
                                           class="admin-input admin-input--sm cat-edit-input">

                                    <button class="admin-btn admin-btn--success admin-btn--sm">Lưu</button>

                                    <button type="button"
                                            onclick="cancelEdit({{ $cat->id }})"
                                            class="admin-btn admin-btn--muted admin-btn--sm">
                                        Hủy
                                    </button>
                                </form>

                            </td>

                            <td class="cat-actions">

                                <button type="button"
                                        onclick="startEdit({{ $cat->id }})"
                                        class="admin-btn admin-btn--warning admin-btn--sm">
                                    Sửa
                                </button>

                                <form action="{{ route('admin.categories.destroy', $cat->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Xóa danh mục này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="admin-btn admin-btn--danger admin-btn--sm">Xóa</button>
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

    {{-- JS --}}
    @push('scripts')
        <script>
            function startEdit(id) {
                document.getElementById('view-' + id).style.display = 'none';
                document.getElementById('edit-' + id).style.display = 'flex';
            }

            function cancelEdit(id) {
                document.getElementById('edit-' + id).style.display = 'none';
                document.getElementById('view-' + id).style.display = 'inline';
            }
        </script>
    @endpush

@endsection
