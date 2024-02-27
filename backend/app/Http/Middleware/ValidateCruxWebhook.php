<?php

namespace App\Http\Middleware;

use Closure;

class ValidateCruxWebhook
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
        if ($request->header('Authorization') == 'Token token="'.env("CRUX_API_KEY", null).'"') {
            return $next($request);
        } else {
            abort(403);
        }
    }
}
