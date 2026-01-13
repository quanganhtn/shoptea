<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(20);//lấy danh mục mới nhất, mỗi trang 20
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:categories,name'],
        ]);
        //required bắt buộc nhâp
        //'unique:categories,name' không được chùng
        Category::create($data);

        return back()->with('success', 'Đã thêm danh mục.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $id,
        ]);

        $category = Category::findOrFail($id);//id ko tồn tại 404
        $category->update([
            'name' => $request->name
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Đã cập nhật danh mục');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Đã xóa danh mục.');
    }
}
