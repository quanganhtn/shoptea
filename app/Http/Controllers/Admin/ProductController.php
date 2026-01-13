<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Danh sách sản phẩm (Pagination + Filter + Sort)
    public function index(Request $request)
    {
        $query = Product::query()->with('category');

        // 🔍 Tìm theo tên
        if ($request->filled('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }

        // 📁 Lọc theo category_id
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 💰 Lọc giá
        if ($request->filled('min')) {
            $query->where('price', '>=', $request->min);
        }

        if ($request->filled('max')) {
            $query->where('price', '<=', $request->max);
        }

        // 🔃 SORT
        $sort = $request->get('sort', 'id');
        $dir = $request->get('dir', 'desc');

        if (!in_array($sort, ['id', 'name', 'price'])) {
            $sort = 'id';
        }
        if (!in_array($dir, ['asc', 'desc'])) {
            $dir = 'desc';
        }

        $products = $query
            ->orderBy($sort, $dir)
            ->paginate(20)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories', 'sort', 'dir'));
    }

    // Form thêm sản phẩm

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer|min:0', // ✅ thêm
            'description' => 'nullable|string',
            'image' => 'nullable',
            'category_id' => 'required|exists:categories,id',
        ]);

        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock, // ✅ thêm
            'description' => $request->description,
            'image' => $request->image,
            'category_id' => $request->category_id,
        ]);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Thêm sản phẩm thành công!');
    }

    // Lưu sản phẩm

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    // Form sửa sản phẩm

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    // Cập nhật sản phẩm
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock_add' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable',
            'category_id' => 'required|exists:categories,id',
        ]);

        // 1) Update các field bình thường (KHÔNG đụng stock ở đây)
        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $request->image,
            'category_id' => $request->category_id,
        ]);

        // 2) Cộng dồn tồn kho nếu có nhập thêm
        $add = max(0, (int)$request->input('stock_add', 0));
        if ($add > 0) {
            $product->increment('stock', $add);
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Cập nhật sản phẩm thành công!');
    }


    // Xóa sản phẩm
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Xóa sản phẩm thành công!');
    }
}
