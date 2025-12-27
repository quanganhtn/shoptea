<?php

namespace App\Http\Controllers;

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
        /* =======================
            1. ĐỌC DỮ LIỆU TRANG CHỦ
        ======================== */

        $homepage = [];
        $path = storage_path('app/data/homepage.json');

        if (file_exists($path)) {
            $homepage = json_decode(File::get($path), true);
        }

        /* =======================
            2. TRUY VẤN SẢN PHẨM
        ======================== */

        $query = Product::query();

        // Tìm theo tên
        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        // Lọc theo danh mục
        if ($request->filled('category')) {
            $query->where('category', 'like', '%' . $request->category . '%');
        }

        $products = $query->latest()->get();

        /* =======================
            3. TRẢ VIEW
        ======================== */

        return view('checkout.home', compact('products', 'homepage'));
    }

    /**
     * Trang chi tiết sản phẩm
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);

        return view('checkout.show', compact('product'));
    }

    public function searchSuggest(Request $request)
    {
        $keyword = trim($request->keyword);

        if ($keyword === '') {
            return response()->json([]);
        }

        $products = Product::where('name', 'like', '%' . $keyword . '%')
            ->limit(10)
            ->get(['id', 'name']);

        return response()->json($products);
    }

}
