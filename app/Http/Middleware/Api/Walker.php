<?php

namespace App\Http\Middleware\Api;

use Closure;

class Walker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!\Auth::user() || \Auth::user() && !\Auth::user()->isWalker()) {
            return \Response::json([
                'success' => false,
                'status' => 406,
                'message' => 'You don\'t have permission',
                'data' => null
            ]);
        }
        return $next($request);
    }
}
