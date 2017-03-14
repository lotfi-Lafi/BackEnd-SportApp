<?php

namespace App\Http\Middleware;

use Closure;

class Cors {
    public function handle($request, Closure $next)
    {
        return $next($request)
            ->header('Access-Control-Allow-Origin', '*')
	    ->header('Access-Control-Allow-Headers', 'accept, content-type,x-xsrf-token, x-csrf-token')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    }
}


              
