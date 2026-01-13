<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyOrderController extends Controller
{
    public function index(Request $request)
    {
        $q = Order::with(['items.product']) // ✅ lấy items + product để show ảnh/tên
        ->where('user_id', Auth::id())
            ->latest();

        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }

        $orders = $q->paginate(10);

        return view('User.orders.my_orders', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['items.product', 'user'])
            ->where('user_id', Auth::id())  // ✅ user chỉ xem đơn của mình
            ->findOrFail($id);

        return view('User.orders.show', compact('order'));
    }
}
