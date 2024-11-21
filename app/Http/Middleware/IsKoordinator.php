<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class IsKoordinator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        //return $next($request);

        if (Auth::user() &&  Auth::user()->gid <= 4) {
            return $next($request);
        }

        //return redirect('/');
        return redirect('main')->with('error','Halaman ini memerlukan Hak Akses minimal Koordinator');
    }
}
