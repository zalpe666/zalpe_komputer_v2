<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = auth()->user();

        if (!$user) {
            abort(401, 'Unauthorized');
        }

        if (!$user->is_active) {
            auth()->logout();
            abort(403, 'Akun tidak aktif');
        }

        if (!in_array($user->role, $roles)) {
            abort(403, 'Tidak punya akses');
        }

        return $next($request);
    }
}