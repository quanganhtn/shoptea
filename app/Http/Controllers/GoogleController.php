<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        // redirect user sang Google
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        // lấy thông tin user từ Google (stateless giúp tránh lỗi state mismatch khi dev/local)
        $googleUser = Socialite::driver('google')->stateless()->user();

        // 1) ưu tiên tìm theo google_id
        $user = User::where('google_id', $googleUser->getId())->first();

        // 2) nếu chưa có, thử tìm theo email (trường hợp user đã đăng ký bằng email trước đó)
        if (!$user) {
            $user = User::where('email', $googleUser->getEmail())->first();
        }

        // 3) nếu vẫn chưa có user -> tạo mới
        if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName() ?: 'Google User',
                'email' => $googleUser->getEmail(),
                'phone' => null,                       // google thường không trả phone
                'password' => bcrypt(Str::random(32)),    // random vì không dùng mật khẩu
                'role' => 'user',
                'google_id' => $googleUser->getId(),
            ]);
        } else {
            // 4) nếu user tồn tại nhưng chưa gắn google_id thì cập nhật
            if (!$user->google_id) {
                $user->update([
                    'google_id' => $googleUser->getId(),
                ]);
            }
        }

        // login và nhớ đăng nhập luôn (true)
        Auth::login($user, true);

        // redirect theo role giống AuthController của bạn
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Đăng nhập Google (admin) thành công!');
        }

        return redirect()->route('user')
            ->with('success', 'Đăng nhập Google thành công!');
    }
}
