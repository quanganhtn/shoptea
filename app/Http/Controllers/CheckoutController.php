<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        // Nếu muốn bắt buộc login ngay tại đây:
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Bạn cần đăng nhập để thanh toán.');
        }

        // ids từ cart (checkbox)
        $idsStr = (string)$request->query('ids', '');
        $ids = array_values(array_filter(array_map('intval', explode(',', $idsStr))));

        if (empty($ids)) {
            return redirect()->route('cart.index')
                ->with('error', 'Bạn chưa chọn sản phẩm nào để thanh toán.');
        }

        // ✅ LẤY GIỎ HÀNG TỪ DB carts
        $rows = Cart::with('product')
            ->where('user_id', Auth::id())
            ->whereIn('product_id', $ids)
            ->get();

        if ($rows->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Sản phẩm đã chọn không còn trong giỏ hàng.');
        }

        // build $checkoutCart giống format view đang dùng
        $checkoutCart = [];
        $total = 0;

        foreach ($rows as $row) {
            if (!$row->product) continue;

            $pid = $row->product_id;
            $price = (float)$row->product->price;
            $qty = (int)$row->quantity;

            $checkoutCart[$pid] = [
                'name' => $row->product->name,
                'price' => $price,
                'image' => $row->product->image,
                'quantity' => $qty,
            ];

            $total += $price * $qty;
        }

        // lưu selection để placeOrder dùng
        session()->put('checkout_ids', array_keys($checkoutCart));

        return view('User.checkout.pay', [
            'cart' => $checkoutCart,
            'total' => $total,
        ]);
    }

    public function placeOrder(Request $request)
    {
        // bắt buộc login (route đã middleware auth là đẹp nhất)
        $userId = Auth::id();

        $checkoutIds = session()->get('checkout_ids', []);
        if (empty($checkoutIds)) {
            return redirect()->route('cart.index')
                ->with('error', 'Không tìm thấy sản phẩm cần thanh toán.');
        }

        $request->validate([
            'fullname' => 'required|string|max:255',
            'phone' => ['required', 'regex:/^0\d{9}$/'],
            'address' => 'required|string|max:500',
            'note' => 'nullable|string|max:1000',
            'payment' => 'required|in:cod',
        ]);

        // Lấy lại cart từ DB để tính total + tạo order_items
        $cartRows = Cart::with('product')
            ->where('user_id', $userId)
            ->whereIn('product_id', $checkoutIds)
            ->get();

        if ($cartRows->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Giỏ hàng không còn sản phẩm đã chọn.');
        }

        return DB::transaction(function () use ($request, $cartRows, $checkoutIds, $userId) {

            $total = 0;
            foreach ($cartRows as $row) {
                if (!$row->product) continue;
                $total += (float)$row->product->price * (int)$row->quantity;
            }


            $order = Order::create([
                'user_id' => $userId,
                'fullname' => $request->fullname,
                'phone' => $request->phone,
                'address' => $request->address,
                'note' => $request->note,
                'payment' => $request->payment,
                'total_price' => $total,
                'status' => 'pending',
            ]);


            // ✅ TẠO ORDER ITEMS
            foreach ($cartRows as $row) {
                if (!$row->product) continue;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $row->product_id,
                    'quantity' => (int)$row->quantity,
                    'price' => (float)$row->product->price,
                ]);
            }

            // ✅ XÓA CÁC MÓN ĐÃ THANH TOÁN KHỎI carts
            Cart::where('user_id', $userId)
                ->whereIn('product_id', $checkoutIds)
                ->delete();

            // clear selection
            session()->forget('checkout_ids');

            return redirect()->route('home')
                ->with('success', 'Đặt hàng thành công! Mã đơn: #' . $order->id);
        });
    }
}
