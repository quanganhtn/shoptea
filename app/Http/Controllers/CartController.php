<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

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
        $quantity = $request->quantity ?? 1;

        $product = Product::findOrFail($product_id);

        $cart = session()->get('cart', []);

        if (isset($cart[$product_id])) {
            $cart[$product_id]['quantity'] += $quantity;
        } else {
            $cart[$product_id] = [
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);
        $this->updateCartCount($cart);

        // mua ngay
        if ($request->has('buy_now')) {
            return redirect()->route('cart.index');
        }

        return redirect()->back()->with('success', 'Đã thêm vào giỏ hàng');
    }

    // =========================
    // TĂNG / GIẢM SỐ LƯỢNG
    // =========================

    private function updateCartCount($cart)
    {
        $count = 0;
        foreach ($cart as $item) {
            $count += (int)($item['quantity'] ?? 0); // tổng quantity
        }
        session()->put('cart_count', $count);
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);
        $id = $request->product_id;
        $action = $request->action;

        if (!isset($cart[$id])) {
            return back();
        }

        if ($action === 'increase') {
            $cart[$id]['quantity']++;
        } elseif ($action === 'decrease') {
            // giữ đúng như UI của bạn: không giảm dưới 1
            if ($cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity']--;
            }
        }

        // ✅ đồng bộ lại session cart + cart_count
        $this->syncCartCount($cart);

        return back();
    }

    private function syncCartCount(array $cart): void
    {
        $count = array_sum(array_column($cart, 'quantity')); // tổng quantity
        session(['cart' => $cart, 'cart_count' => $count]);
    }

    public function deleteSelected(Request $request)
    {
        $ids = array_filter(explode(',', (string)$request->ids));

        $cart = session('cart', []);

        foreach ($ids as $id) {
            unset($cart[$id]);
        }

        $this->syncCartCount($cart);

        return back()->with('success', 'Đã xóa các sản phẩm đã chọn');
    }


}
