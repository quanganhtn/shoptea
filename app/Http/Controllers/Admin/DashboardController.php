<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        //sản phẩm
        $totalProducts = Product::count();
        //đơn hàng
        $totalOrders = Order::count();
        // Danh mục
        $totalCategories = Category::count();

        // admin: đếm theo role
        $totalAdmins = User::where('role', 'admin')->count();
        // User: đếm theo role
        $totalNormalUsers = User::where(function ($q) {
            $q->whereNull('role')->orWhere('role', 'user');
        })->count();//đếm tất cả user mà ko có role là admin

        $totalUsers = $totalAdmins + $totalNormalUsers;

        // Orders theo status
        $ordersPending = Order::where('status', 'pending')->count(); //chờ xác nhận
        $ordersConfirmed = Order::where('status', 'confirmed')->count(); //xác nhận
        $ordersShipping = Order::where('status', 'shipping')->count();  //vận chuyển
        $ordersCompleted = Order::where('status', 'completed')->count(); //hoàn tất
        $ordersCancelled = Order::where('status', 'cancelled')->count(); //bị hủy

        //doanh thu chỉ tiính đơn giao thành công
        $revenueCompleted = (float)Order::where('status', 'completed')->sum('total_price');
        //lọc theo tháng năm
        $revenueThisMonth = (float)Order::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');

        //tổng doanh thu
        $revenueAll = Order::where('status', 'completed')->sum('total_price');
        
        // các thống kê bạn đã có...
        $ordersToday = \App\Models\Order::whereDate('created_at', today())->count();

        // ✅ 5 đơn gần nhất
        $recentOrders = \App\Models\Order::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalProducts', 'totalOrders', 'totalCategories',
            'totalAdmins', 'totalNormalUsers', 'totalUsers',
            'ordersPending', 'ordersConfirmed', 'ordersShipping', 'ordersCompleted', 'ordersCancelled',
            'revenueCompleted', 'revenueThisMonth', 'revenueAll', 'ordersToday',
            'recentOrders'
        ));
    }
}
