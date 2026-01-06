<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Nếu chưa đăng nhập → chặn
        if (!auth()->check()) {
            abort(403, 'Bạn chưa đăng nhập');
        }

        // Nếu không phải admin → chặn
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Bạn không có quyền truy cập Admin');
        }

        return $next($request);
    }
}
