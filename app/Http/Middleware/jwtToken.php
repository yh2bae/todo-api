<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class jwtToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not provided',
            ], 401);
        }
        try {
            $user = auth()->guard('api')->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Token invalid',
            ], 401);
        }
        return $next($request);
    }
}
