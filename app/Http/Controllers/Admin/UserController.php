<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostAttendance;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // 未承認ユーザーを先頭に表示
        $users = User::orderByRaw('email_verified_at IS NOT NULL')
                    ->orderBy('role', 'asc')
                    ->orderBy('id', 'asc')
                    ->get();
        return view('admin.users.index', compact('users'));
    }

    public function approve(User $user)
    {
        $user->email_verified_at = now();
        $user->save();
        return back()->with('message', "{$user->name} を承認しました。");
    }

    public function updateRole(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('message', '自分自身の権限は変更できません。');
        }

        $validated = $request->validate([
            'role' => 'required|in:admin,member',
        ]);

        $user->role = $validated['role'];
        $user->save();

        return back()->with('message', "{$user->name} の権限を変更しました。");
    }

    public function destroy(User $user)
    {
        PostAttendance::where('user_id', $user->id)->delete();
        $user->delete();
        return back()->with('message', 'ユーザーを削除しました。');
    }
}
