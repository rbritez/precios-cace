<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HasRoles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Request $request
     * @param  \Closure  $next
     * @param mixed ...$roles
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if ((!auth()->user()->load('roles')->roles )|| ($roles[0] !== auth()->user()->load('roles')->roles->first()->name)) {
            abort(401);
        }
        return $next($request);
    }
}