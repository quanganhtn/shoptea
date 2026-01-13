<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
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

        // Lưu địa chỉ nếu user tick
        $user = $request->user();
        if ($user && $request->boolean('save_address')) {
            $address = trim($request->address ?? '');
            if ($address !== '') {
                $user->update([
                    'address' => $address,
                    'phone' => $request->phone,
                    'name' => $request->fullname,
                ]);
            }
        }

        try {
            $orderId = DB::transaction(function () use ($request, $checkoutIds, $userId) {

                // 1) Lấy cart rows (lock để tránh đổi trong lúc đặt)
                $cartRows = Cart::where('user_id', $userId)
                    ->whereIn('product_id', $checkoutIds)
                    ->lockForUpdate()
                    ->get();

                if ($cartRows->isEmpty()) {
                    throw new \RuntimeException('Giỏ hàng không còn sản phẩm đã chọn.');
                }

                // 2) Lock product tương ứng
                $productIds = $cartRows->pluck('product_id')->unique()->values()->all();

                $products = Product::whereIn('id', $productIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                // 3) Check tồn kho đủ cho từng item
                $errors = [];
                foreach ($cartRows as $row) {
                    $p = $products->get($row->product_id);

                    if (!$p) {
                        $errors[] = "Sản phẩm (ID {$row->product_id}) không còn tồn tại.";
                        continue;
                    }

                    $need = (int)$row->quantity;
                    $stock = (int)$p->stock;

                    if ($stock <= 0) {
                        $errors[] = "“{$p->name}” đã hết hàng.";
                    } elseif ($need > $stock) {
                        $errors[] = "“{$p->name}” chỉ còn {$stock} sản phẩm (bạn chọn {$need}).";
                    }
                }

                if (!empty($errors)) {
                    // rollback chắc chắn
                    throw new \RuntimeException(implode(' ', $errors));
                }

                // 4) Tính total bằng giá hiện tại
                $total = 0;
                foreach ($cartRows as $row) {
                    $p = $products->get($row->product_id);
                    $total += (float)$p->price * (int)$row->quantity;
                }

                // 5) Tạo Order
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

                // 6) Tạo OrderItems
                foreach ($cartRows as $row) {
                    $p = $products->get($row->product_id);

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $row->product_id,
                        'quantity' => (int)$row->quantity,
                        'price' => (float)$p->price,
                    ]);
                }

                // 7) Xóa cart đã checkout
                Cart::where('user_id', $userId)
                    ->whereIn('product_id', $checkoutIds)
                    ->delete();

                session()->forget('checkout_ids');

                return $order->id;
            });

            return redirect()->route('user')
                ->with('success', 'Đặt hàng thành công! Mã đơn: #' . $orderId);

        } catch (\Throwable $e) {
            return redirect()->route('cart.index')
                ->with('error', $e->getMessage());
        }
    }
}
