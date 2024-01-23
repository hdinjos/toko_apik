<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use JWTAuth;
use Exception;
use PHPOpenSourceSaver\JWTAuth\Http\Middleware\BaseMiddleware;

class VerifyJwt extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if ($user && in_array($user->role_id, $roles)) {
                return $next($request);
            } else {
                return response()->json([
                    "success" => false,
                    "message" => "You are unauthorized to access this resource",
                ], 401);
            }
        } catch (Exception $e) {
            if ($e instanceof \PhpOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['message' => 'token is invalid'], 400);
            } else if ($e instanceof \PhpOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['message' => 'token is expired'], 400);
            } else {
                return response()->json(['message' => 'Authorization Token not found'], 400);
            }
        }
    }
}
