<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        // ✅ Logged in -> lấy từ DB
        if (Auth::check()) {
            $items = Cart::with('product')
                ->where('user_id', Auth::id())
                ->get();

            $cart = [];
            $total = 0;

            foreach ($items as $row) {
                if (!$row->product) continue;

                $pid = $row->product_id;
                $cart[$pid] = [
                    'name' => $row->product->name,
                    'price' => $row->product->price,
                    'image' => $row->product->image,
                    'quantity' => $row->quantity,
                ];
                $total += $row->product->price * $row->quantity;
            }

            $this->syncCartCountDb();
            return view('User.checkout.cart', compact('cart', 'total'));
        }

        // ❌ Guest -> lấy từ session (giữ như cũ)
        $cart = session('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        $this->syncCartCountSession($cart);

        return view('User.checkout.cart', compact('cart', 'total'));
    }

    private function syncCartCountDb(): void
    {
        $count = Cart::where('user_id', Auth::id())->sum('quantity');
        session(['cart_count' => $count]);
    }

    private function syncCartCountSession(array $cart): void
    {
        $count = array_sum(array_column($cart, 'quantity'));
        session(['cart_count' => $count]);
    }

    public function add(Request $request)
    {
        $product_id = (int)$request->product_id;
        $quantity = max(1, (int)($request->quantity ?? 1));

        $product = Product::findOrFail($product_id);

        // ✅ Logged in -> lưu DB
        if (Auth::check()) {
            $userId = Auth::id();

            $cartItem = Cart::firstOrNew([
                'user_id' => $userId,
                'product_id' => $product_id
            ]);

            $cartItem->quantity = (int)($cartItem->quantity ?? 0) + $quantity;
            $cartItem->save();

            $this->syncCartCountDb();

            return $request->has('buy_now')
                ? redirect()->route('cart.index')
                : back()->with('success', 'Đã thêm vào giỏ hàng');
        }

        // ❌ Guest -> lưu session (giữ như cũ)
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
        $this->syncCartCountSession($cart);

        return $request->has('buy_now')
            ? redirect()->route('cart.index')
            : back()->with('success', 'Đã thêm vào giỏ hàng');
    }

    // ===== Helpers =====

    public function update(Request $request)
    {
        $productId = (int)$request->product_id;
        $action = $request->action;

        // ✅ Logged in -> update DB
        if (Auth::check()) {
            $row = Cart::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->first();

            if (!$row) return back();

            if ($action === 'increase') {
                $row->quantity += 1;
            } elseif ($action === 'decrease') {
                $row->quantity = max(1, $row->quantity - 1);
            }
            $row->save();

            $this->syncCartCountDb();
            return back();
        }

        // ❌ Guest -> update session (giữ như cũ)
        $cart = session()->get('cart', []);
        if (!isset($cart[$productId])) return back();

        if ($action === 'increase') {
            $cart[$productId]['quantity']++;
        } elseif ($action === 'decrease') {
            $cart[$productId]['quantity'] = max(1, $cart[$productId]['quantity'] - 1);
        }

        session(['cart' => $cart]);
        $this->syncCartCountSession($cart);

        return back();
    }

    public function deleteSelected(Request $request)
    {
        $ids = array_values(array_filter(array_map('intval', explode(',', (string)$request->ids))));

        // ✅ Logged in -> delete DB
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())
                ->whereIn('product_id', $ids)
                ->delete();

            $this->syncCartCountDb();
            return back()->with('success', 'Đã xóa các sản phẩm đã chọn');
        }

        // ❌ Guest -> delete session
        $cart = session('cart', []);
        foreach ($ids as $id) unset($cart[$id]);

        session(['cart' => $cart]);
        $this->syncCartCountSession($cart);

        return back()->with('success', 'Đã xóa các sản phẩm đã chọn');
    }
}
