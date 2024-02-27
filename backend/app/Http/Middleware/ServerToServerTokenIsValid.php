<?php

namespace App\Http\Middleware;

use App\Custom\CustomJWTGenerator;
use Closure;

class ServerToServerTokenIsValid
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
        $token = str_replace("Bearer ", "", $request->header('Authorization'));
        $checkToken = CustomJWTGenerator::validateToken($token);
        if($checkToken){
            return $next($request);
        } else {
            return response()->json(['message'=>'UnAuthenticated'], 401);
        }
        return response()->json([], 500);
    }
}
