<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->keyword) {
            $query->where('name', 'like', '%' . $request->keyword . '%');
        }

        if ($request->category) {
            $query->where('category', 'like', '%' . $request->category . '%');
        }

        $products = $query->latest()->get();

        return view('auth.home', compact('products'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('auth.show', compact('product'));
    }
}
