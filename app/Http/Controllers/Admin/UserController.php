<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostAttendance;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('role', 'asc')->orderBy('id', 'asc')->get();
        return view('admin.users.index', compact('users'));
    }

    public function approve(User $user)
    {
        $user->email_verified_at = now();
        $user->save();
        return back()->with('message', "{$user->name} を承認しました。");
    }

    public function destroy(User $user)
    {
        PostAttendance::where('user_id', $user->id)->delete();
        $user->delete();
        return back()->with('message', 'ユーザーを削除しました。');
    }
}
