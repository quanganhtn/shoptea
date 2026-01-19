<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
    /**
     * Trang chủ
     */
    public function index(Request $request)
    {
        // 1) homepage json
        $homepage = [];
        $path = storage_path('app/data/homepage.json');
        if (file_exists($path)) {
            $homepage = json_decode(File::get($path), true);
        }

        // 2) danh mục từ DB (để user tự đổi theo admin)
        $categories = Category::orderBy('name')->get();

        // 3) query sản phẩm
        $query = Product::query()->with('category');

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        // lọc theo category_id
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        $products = $query->latest()->get();

        return view('User.checkout.home', compact('products', 'homepage', 'categories'));
    }

    /**
     * Trang chi tiết sản phẩm
     */
    public function show($id)
    {
        $product = Product::with([
            'category',
            'reviews.user'
        ])->findOrFail($id);

        // 1) Đã bán (đếm từ order_items của đơn completed)
        $soldCount = OrderItem::where('product_id', $product->id)
            ->whereHas('order', function ($q) {
                $q->where('status', 'completed');
            })
            ->sum('quantity');

        // 2) Đề xuất sản phẩm (ưu tiên cùng danh mục)
        $suggestProducts = Product::with('category')
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->latest()
            ->take(8)
            ->get();

        // nếu ít hơn 8 -> bù thêm sản phẩm mới nhất
        if ($suggestProducts->count() < 8) {
            $more = Product::with('category')
                ->where('id', '!=', $product->id)
                ->whereNotIn('id', $suggestProducts->pluck('id'))
                ->latest()
                ->take(8 - $suggestProducts->count())
                ->get();

            $suggestProducts = $suggestProducts->concat($more);
        }

        return view('User.checkout.show', compact('product', 'soldCount', 'suggestProducts'));
    }


    public function searchSuggest(Request $request)
    {
        $keyword = trim($request->keyword);

        if ($keyword === '') {
            return response()->json([]);
        }

        $products = Product::with('category:id,name')
            ->where('name', 'like', "%$keyword%")
            ->orWhereHas('category', function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%");
            })
            ->limit(10)
            ->get(['id', 'name', 'category_id']);


        return response()->json($products);
    }

}
