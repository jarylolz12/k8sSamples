<?php

namespace App\Http\Middleware;

use Closure;

class Cors
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
        $allowedOrigins = [
            'http://localhost:8001',
            'http://localhost:8080',
            'http://localhost:3000',
            'http://127.0.0.1:8000',
            'http://shiflclient.test',
            'http://staging.shifl.com',
            'http://staging.shifl.com:8080',
            'https://staging.shifl.com',
            'http://beta.shifl.com',
            'https://beta.shifl.com',
            'http://shifl.com',
            'https://shifl.com',
            'http://app.shifl.com',
            'https://app.shifl.com',
            'http://shiflvue.test',
            'https://shiflvue.test',
            'http://shifl.test',
            'https://shifl.test',
            'https://sandbox-quickbooks.api.intuit.com',
            'http://localhost:8082',
            'https://localhost:8082',
            'http://shifl.capital',
            'https://shifl.capital',
            'http://192.168.8.145:8082',
            'http://192.168.1.11:3000',
            'https://192.168.1.11:3000',
            'http://apptest.shifl.com',
            'https://apptest.shifl.com',
            'http://localhost:8080',
            'http://127.0.0.1:8080',
            'http://localhost:8081',
            'http://trucking.shifl.com',
            'https://trucking.shifl.com',
            'http://truckingservice.shifl.com',
            'https://truckingservice.shifl.com',
            'http://po.shifl.com',
            'https://po.shifl.com',
            'https://shipol-test.herokuapp.com', //kenneth for qa testing purpose,
            'https://shipol-second.herokuapp.com', //kenneth for qa testing purpose,
            'https://shipol-third.herokuapp.com', //kenneth for mendy testing purpose,
            'https://betabackup.shifl.com', //kenneth for testing purpose,
            "https://truckingtest.shifl.com",
            "http://truckingtest.shifl.com",
            'http://appdev.shifl.com', //accounting module test
            'https://appdev.shifl.com', //accounting module test
            'https://localhost:8081',
            'http://m.central.test',
            'https://m.central.test',
            'https://m.po.test',
            'http://m.po.test',
            'http://local.truckingapp.com',
            'http://local.truckingapp.com',
        ];
        $requestOrigin = $request->headers->get('origin');

        if (in_array($requestOrigin, $allowedOrigins)) {
            return $next($request)
                ->header('Access-Control-Allow-Origin', $requestOrigin)
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE')
                ->header('Access-Control-Allow-Credentials', 'true')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With,X-CSRF-Token,x-xsrf-token');
        }

        return $next($request);
    }
}
