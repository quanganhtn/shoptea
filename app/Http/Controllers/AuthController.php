<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // =========================
    // FORM ĐĂNG KÝ
    // =========================
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // =========================
    // XỬ LÝ ĐĂNG KÝ
    // =========================
    public function register(Request $req)
    {
        $req->validate([
            'name' => 'required|string|max:255',
            'phone' => ['required', 'regex:/^0\d{9}$/'],
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $req->name,
            'email' => $req->email,
            'phone' => $req->phone,
            'password' => Hash::make($req->password),
            'role' => 'user',
        ]);

        return redirect()->route('login')
            ->with('success', 'Đăng ký thành công! Mời bạn đăng nhập.');
    }

    // =========================
    // FORM ĐĂNG NHẬP
    // =========================
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // =========================
    // XỬ LÝ ĐĂNG NHẬP + MERGE CART
    // =========================
    public function login(Request $req)
    {
        $req->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        $loginInput = $req->login;
        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if (Auth::attempt([$field => $loginInput, 'password' => $req->password], $req->remember ?? false)) {

            $req->session()->regenerate();

            // MERGE CART SESSION → DATABASE
            if (session()->has('cart')) {
                foreach (session('cart') as $productId => $item) {
                    Cart::updateOrCreate(
                        [
                            'user_id' => Auth::id(),
                            'product_id' => $productId,
                        ],
                        [
                            'quantity' => DB::raw('quantity + ' . (int)$item['quantity'])
                        ]
                    );
                }
                session()->forget('cart');
            }

            // ✅ Redirect theo role
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Đăng nhập admin thành công!');
            }

            return redirect()->route('home')->with('success', 'Đăng nhập thành công!');
        }

        return back()->with('error', 'Email/SĐT hoặc mật khẩu không đúng!');
    }

    // =========================
    // ĐĂNG XUẤT
    // =========================
    public function logout(Request $req)
    {
        Auth::logout();
        $req->session()->invalidate();
        $req->session()->regenerateToken();

        return redirect()->route('login');
    }
}
