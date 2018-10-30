<?php

namespace App\Http\Middleware;

use Closure;
//You may access the authenticated user via the Auth facade
use Illuminate\Support\Facades\Auth;

class CheckRootUser
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
        if (in_array(Auth::id(), [12])) {
            return $next($request);
        }
        return redirect(route('PagesController@index')->with('error','Assess Denied'));
    }
}
