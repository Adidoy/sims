<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class SuppliesChiefFilter
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
        if(Auth::user()->access != 4 && Auth::user()->access != 1) {
            return redirect('/');
        }
        return $next($request);
    }
}
