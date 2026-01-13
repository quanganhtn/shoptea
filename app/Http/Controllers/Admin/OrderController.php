<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function edit($id)
    {
        $order = Order::with('items')->findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,shipping,completed,cancelled',
        ]);

        $next = (string)$request->status;

        try {
            \DB::transaction(function () use ($id, $next) {

                // 1) Lock order để tránh 2 admin update cùng lúc
                $order = Order::with('items')
                    ->where('id', $id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $current = (string)$order->status;

                // 2) Giữ rule chuyển trạng thái như bạn đã làm
                $allowed = [
                    'pending' => ['confirmed', 'cancelled'],
                    'confirmed' => ['shipping', 'cancelled'],
                    'shipping' => ['completed'],
                    'completed' => [],
                    'cancelled' => [],
                ];

                if (!in_array($next, $allowed[$current] ?? [], true)) {
                    throw new \RuntimeException("Không thể chuyển từ {$current} sang {$next}");
                }

                // 3) Case quan trọng: shipping -> completed => trừ kho
                if ($current === 'shipping' && $next === 'completed') {

                    // ✅ Chống trừ kho 2 lần
                    if (!is_null($order->stock_deducted_at)) {
                        // status có thể đang lệch trong DB (hiếm), nhưng tuyệt đối không trừ lại
                        $order->status = 'completed';
                        $order->save();
                        return;
                    }

                    if ($order->items->isEmpty()) {
                        throw new \RuntimeException('Đơn hàng không có sản phẩm.');
                    }

                    $productIds = $order->items->pluck('product_id')->unique()->values()->all();

                    // Lock product để trừ kho an toàn
                    $products = \App\Models\Product::whereIn('id', $productIds)
                        ->lockForUpdate()
                        ->get()
                        ->keyBy('id');

                    // 3.1) Check đủ kho tại thời điểm completed (tránh âm kho)
                    $errors = [];
                    foreach ($order->items as $it) {
                        $p = $products->get($it->product_id);

                        if (!$p) {
                            $errors[] = "Sản phẩm (ID {$it->product_id}) không còn tồn tại.";
                            continue;
                        }

                        $need = (int)$it->quantity;
                        $stock = (int)$p->stock;

                        if ($need > $stock) {
                            $errors[] = "“{$p->name}” không đủ kho để hoàn thành (cần {$need}, còn {$stock}).";
                        }
                    }

                    if (!empty($errors)) {
                        throw new \RuntimeException(implode(' ', $errors));
                    }

                    // 3.2) Trừ kho
                    foreach ($order->items as $it) {
                        $p = $products->get($it->product_id);
                        $p->stock = (int)$p->stock - (int)$it->quantity;
                        $p->save();

                        // Nếu bạn có flag is_out_of_stock thì set ở đây (tuỳ bạn)
                        // if ($p->stock <= 0) {
                        //     $p->is_out_of_stock = 1;
                        //     $p->save();
                        // }
                    }

                    // 3.3) Update order status + đánh dấu đã trừ kho
                    $order->status = 'completed';
                    $order->stock_deducted_at = now();
                    $order->save();

                    // TODO: notify admin/khách nếu stock về 0
                    // - Admin: thông báo nhập hàng
                    // - Khách: hiển thị "Hết hàng" (dựa vào stock <= 0)
                    return;
                }

                // 4) cancelled trước completed: không trừ kho
                // (allowed map của bạn đã chặn cancel sau completed)
                $order->status = $next;
                $order->save();
            });

            return back()->with('success', 'Cập nhật trạng thái thành công!');

        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }


    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if (in_array($order->status, ['completed', 'cancelled'], true)) {
            return back()->with('error', 'Đơn đã kết thúc nên không thể chỉnh sửa.');
        }

        $data = $request->validate([
            'fullname' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'note' => 'nullable|string|max:1000',
            'payment' => 'required|string|max:50',
        ]);

        $order->update($data);

        return back()->with('success', 'Cập nhật thông tin đơn hàng thành công!');
    }

    public function cancel(Request $request, Order $order)
    {
        // ✅ Chỉ cho hủy đơn của chính user
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // ✅ Chỉ hủy khi đang pending
        if ($order->status !== 'pending') {
            return back()->with('error', 'Đơn đã được shop xác nhận nên không thể hủy.');
        }

        // ✅ Update trạng thái
        $order->status = 'cancelled';

        // (tuỳ bạn) lưu lý do khách hủy
        // $order->cancel_reason = $request->input('reason');

        $order->save();

        return back()->with('success', 'Bạn đã hủy đơn thành công.');
    }
}
