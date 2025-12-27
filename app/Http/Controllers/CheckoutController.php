<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Giỏ hàng trống, không thể thanh toán.');
        }

        // ids được chọn từ cart
        $idsStr = (string)$request->query('ids', '');
        $ids = array_filter(explode(',', $idsStr));

        if (empty($ids)) {
            return redirect()->route('cart.index')
                ->with('error', 'Bạn chưa chọn sản phẩm nào để thanh toán.');
        }

        // chỉ lấy sản phẩm được chọn
        $checkoutCart = [];
        foreach ($ids as $id) {
            if (isset($cart[$id])) {
                $checkoutCart[$id] = $cart[$id];
            }
        }

        if (empty($checkoutCart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Sản phẩm đã chọn không còn trong giỏ hàng.');
        }

        // lưu lại selection để place() dùng
        session()->put('checkout_ids', array_keys($checkoutCart));

        $total = 0;
        foreach ($checkoutCart as $item) {
            $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
        }

        return view('checkout.pay', [
            'cart' => $checkoutCart,
            'total' => $total,
        ]);
    }


    public function placeOrder(Request $request)
    {
        $cart = session()->get('cart', []);
        $checkoutIds = session()->get('checkout_ids', []);

        if (empty($cart) || empty($checkoutIds)) {
            return redirect()->route('cart.index')
                ->with('error', 'Không tìm thấy sản phẩm cần thanh toán.');
        }

        $request->validate([
            'fullname' => 'required|string|max:255',
            'phone' => ['required', 'regex:/^0\d{9}$/'],
            'address' => 'required|string|max:500',
            'note' => 'nullable|string|max:1000',
            // bạn muốn chỉ COD thì để đúng 1 option:
            'payment' => 'required|in:cod',
        ]);

        // tính total theo selection
        $total = 0;
        foreach ($checkoutIds as $id) {
            if (isset($cart[$id])) {
                $item = $cart[$id];
                $total += ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
            }
        }

        // TODO: chỗ này sau này bạn lưu DB orders + order_items

        // chỉ xóa các món đã thanh toán khỏi giỏ
        foreach ($checkoutIds as $id) {
            unset($cart[$id]);
        }

        // cập nhật lại session cart + cart_count
        session()->put('cart', $cart);
        $count = 0;
        foreach ($cart as $item) $count += (int)($item['quantity'] ?? 0);
        session()->put('cart_count', $count);

        // xóa selection
        session()->forget('checkout_ids');

        return redirect()->route('home')
            ->with('success', 'Đặt hàng thành công! Tổng tiền: ' . number_format($total) . 'đ');
    }

}
