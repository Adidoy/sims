<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AMOFilter
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

        if(Auth::user()->access != 1 && Auth::user()->access != 6 && Auth::user()->access != 7 && Auth::user()->access != 8 && Auth::user()->access != 4)
        {
            return redirect('/');
        }
        
        return $next($request);
    }
}
