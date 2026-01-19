<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            abort(403, 'Bạn chưa đăng nhập');
        }

        $user = auth()->user();

        // Voyager dùng role_id + relation role()
        $roleName = optional($user->role)->name; // admin/user/...

        if ($roleName !== 'admin') {
            abort(403, 'Bạn không có quyền truy cập Admin');
        }

        return $next($request);
    }
}
