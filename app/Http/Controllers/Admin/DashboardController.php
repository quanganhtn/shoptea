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
        $totalProducts = Product::count();
        $totalOrders = Order::count();

        // Danh mục
        $totalCategories = Category::count();

        // User: đếm theo role
        $totalAdmins = User::where('role', 'admin')->count();
        $totalNormalUsers = User::where(function ($q) {
            $q->whereNull('role')->orWhere('role', 'user');
        })->count();

        $totalUsers = $totalAdmins + $totalNormalUsers;

        // Orders theo status
        $ordersPending = Order::where('status', 'pending')->count();
        $ordersConfirmed = Order::where('status', 'confirmed')->count();
        $ordersShipping = Order::where('status', 'shipping')->count();
        $ordersCompleted = Order::where('status', 'completed')->count();
        $ordersCancelled = Order::where('status', 'cancelled')->count();


        $revenueCompleted = (float)Order::where('status', 'completed')->sum('total_price');


        $revenueThisMonth = (float)Order::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');


        $revenueAll = (float)Order::sum('total_price');

        return view('admin.dashboard', compact(
            'totalProducts', 'totalOrders', 'totalCategories',
            'totalAdmins', 'totalNormalUsers', 'totalUsers',
            'ordersPending', 'ordersConfirmed', 'ordersShipping', 'ordersCompleted', 'ordersCancelled',
            'revenueCompleted', 'revenueThisMonth', 'revenueAll'
        ));
    }
}
