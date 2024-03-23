<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = JWTAuth::parseToken()-> authenticate();
        } catch (TokenInvalidException $e) {
            return response()->json(
                [
                    'status' => 'Token is Invalid',
                    'msg' => $e->getMessage()
                ]
            );
        } catch (TokenExpiredException $e) {
            return response()->json(
                [
                    'status' => 'Token is Expired',
                    'msg' => $e->getMessage()
                ]);
        } catch (JWTException $e) {
            return response()->json(
                [
                    'status' => 'Authorization Token not found',
                    'msg' => $e->getMessage()
                ]);
        }
        return $next($request);
    }
}
