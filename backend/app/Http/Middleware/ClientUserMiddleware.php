<?php

namespace App\Http\Middleware;

use Closure;
use Lcobucci\JWT\Parser;
use Auth;
use Laravel\Passport\Token;

class ClientUserMiddleware
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
        $bearerToken = request()->bearerToken();


        $tokenId = (new Parser())->parse($bearerToken)->getClaim('jti');

        $client = Token::find($tokenId)->client;

        $auth_user = \Laravel\Passport\Client::findOrFail($client->id)->user;

        if($auth_user){
            Auth::login($auth_user);
            return $next($request);
        }

        return response([
          'message' => 'User not found. No user is associated with this Client'
        ],404);
    }
}
