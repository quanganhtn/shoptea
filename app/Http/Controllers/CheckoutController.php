<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        // Lấy giỏ hàng từ session
        $cart = session()->get('cart', []);

        // test commit bên này
        // Tính tổng tiền
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('checkout.cart', compact('cart', 'total'));
    }
}
