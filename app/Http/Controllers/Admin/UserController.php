<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $target = User::findOrFail($id);
        $me = auth()->user();

        $request->validate([
            'role' => 'required|in:admin,user',
        ]);

        $newRole = (string)$request->role;

        // 1) Không cho tự đổi role (tuỳ bạn, nhưng an toàn)
        if ($me && $target->id === $me->id) {
            return back()->with('error', 'Bạn không thể tự đổi quyền của chính mình.');
        }

        // 2) Không cho biến admin cuối cùng thành user
        if ($target->role === 'admin' && $newRole === 'user') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'Không thể hạ quyền admin cuối cùng.');
            }
        }

        $target->update(['role' => $newRole]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Cập nhật user thành công');
    }


    public function destroy($id)
    {
        $target = User::findOrFail($id);
        $me = auth()->user();

        // 1) Không cho tự xoá chính mình
        if ($me && $target->id === $me->id) {
            return back()->with('error', 'Bạn không thể xoá chính tài khoản của mình.');
        }

        // 2) Không cho xoá admin cuối cùng
        if (($target->role === 'admin') && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Không thể xoá admin cuối cùng (tránh khoá hệ thống).');
        }

        $target->delete();
        return back()->with('success', 'Đã xoá user.');
    }


}

