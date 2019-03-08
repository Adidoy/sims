<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class InspectionTeamFilter
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
        if((Auth::user()->access == 9) || (Auth::user()->access == 10) || (Auth::user()->access == 4)) {
            return $next($request);      
        }
        return redirect('/');  
    }
}