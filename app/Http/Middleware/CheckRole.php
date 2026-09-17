<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$role): Response
    {
        if (! $request->user() || ! in_array($request->user()->role, $role)) {
            return response()->view('errors.unauthorized', [], 403);
        }

        return $next($request);
    }
}