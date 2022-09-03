<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class IsStudent
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
        if ($user &&  $user->is_student == 1) {
            return $next($request);
        }

        return response ([
            'status' => FALSE,
            'message' => 'User have not student access'
        ], 401);
    }
}
