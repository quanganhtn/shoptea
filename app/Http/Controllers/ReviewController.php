<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $userId = Auth::id();

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'content' => ['nullable', 'string', 'max:2000'],
        ]);

        // Tìm 1 order COMPLETED có chứa product này
        // và order đó CHƯA có review cho product này
        $eligibleOrderId = \DB::table('orders')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->leftJoin('product_reviews', function ($join) use ($userId, $product) {
                $join->on('product_reviews.order_id', '=', 'orders.id')
                    ->where('product_reviews.user_id', '=', $userId)
                    ->where('product_reviews.product_id', '=', $product->id);
            })
            ->where('orders.user_id', $userId)
            ->where('orders.status', 'completed')
            ->where('order_items.product_id', $product->id)
            ->whereNull('product_reviews.id') // chưa review đơn này
            ->orderByDesc('orders.id')
            ->value('orders.id');

        if (!$eligibleOrderId) {
            return back()->with('error', 'Bạn đã đánh giá hết các đơn hoàn thành cho sản phẩm này (mỗi đơn chỉ 1 lần).');
        }

//        ProductReview::create([
//            'product_id' => $product->id,
//            'user_id' => $userId,
//            'order_id' => $eligibleOrderId,
//            'rating' => $data['rating'],
//            'content' => $data['content'] ?? null,
//        ]);
        ProductReview::updateOrCreate(
            [
                'order_id' => $eligibleOrderId,
                'product_id' => $product->id,
                'user_id' => $userId,
            ],
            [
                'rating' => $data['rating'],
                'content' => $data['content'] ?? null,
            ]
        );

        return back()->with('success', 'Đã gửi đánh giá!');
    }

}
