<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    // =========================
    // HIỂN THỊ GIỎ HÀNG
    // =========================
    public function index()
    {
        $cart = session('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('checkout.cart', compact('cart', 'total'));
    }

    // =========================
    // THÊM VÀO GIỎ
    // =========================
    public function add(Request $request)
    {
        $product_id = $request->product_id;
        $quantity   = $request->quantity ?? 1;

        $product = Product::findOrFail($product_id);

        $cart = session()->get('cart', []);

        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity'] += $quantity;
        } else {
            $cart[$product_id] = [
                'name'     => $product->name,
                'price'    => $product->price,
                'image'    => $product->image,
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);

        // mua ngay
        if ($request->has('buy_now')) {
            return redirect()->route('cart.index');
        }

        return redirect()->back()->with('success', 'Đã thêm vào giỏ hàng');
    }

    // =========================
    // TĂNG / GIẢM SỐ LƯỢNG
    // =========================
    public function update(Request $request)
    {
        $cart = session()->get('cart', []);
        $id = $request->product_id;

        if (!isset($cart[$id])) {
            return back();
        }

        if ($request->action === 'increase') {
            $cart[$id]['quantity']++;
        }

        if ($request->action === 'decrease' && $cart[$id]['quantity'] > 1) {
            $cart[$id]['quantity']--;
        }

        session()->put('cart', $cart);
        return back();
    }
}
