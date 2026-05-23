<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->email_verified_at && auth()->user()->role !== 'admin') {
            return redirect()->route('profile.edit')
                ->with('warning', '管理者の承認をお待ちください。承認されるまでプロフィールページのみ閲覧できます。');
        }

        return $next($request);
    }
}
