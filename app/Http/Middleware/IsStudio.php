<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class IsStudio
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user('api');
        if ($user &&  $user->is_studio == 1) {
            return $next($request);
        }

        return response ([
            'status' => FALSE,
            'message' => 'User have not studio access'
        ], 401);
    }
}
