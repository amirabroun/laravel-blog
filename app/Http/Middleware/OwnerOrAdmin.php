<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OwnerOrAdmin
{
    public function handle(Request $request, Closure $next)
    {
        /**
         * @var User
         */
        $user = auth()->user();

        if ($user->id != $request->route()->parameter('id') && !$user->isAdmin()) {
            abort(404);
        }

        return $next($request);
    }
}
