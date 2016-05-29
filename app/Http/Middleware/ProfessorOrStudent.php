<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ProfessorOrStudent
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
        if(Auth::user()->isProfessor() === false && Auth::user()->isStudent() === false) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Forbidden.', 403);
            } else {
                return redirect()->back();
            }
        }

        return $next($request);
    }
}
