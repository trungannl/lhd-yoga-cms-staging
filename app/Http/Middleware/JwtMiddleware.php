<?php


namespace App\Http\Middleware;


use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
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
        try {
            if (!JWTAuth::parseToken()) {
                return response()->json(['status' => FALSE, 'message' => 'User not found'], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['status' => FALSE, 'message' => 'Token expired.'], 401);

        } catch (TokenInvalidException $e) {
            return response()->json(['status' => FALSE, 'message' => 'Token invalid.'], 401);

        } catch (JWTException $e) {
            return response()->json(['status' => FALSE, 'message' => 'Token absent.'], 401);

        }

        return $next($request);
    }
}
