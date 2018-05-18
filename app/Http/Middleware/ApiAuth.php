<?php

namespace App\Http\Middleware;

use Closure;

class ApiAuth
{
    private $allowedDomains = array("localhost", "127.0.0.1", "http://vatsimradar.com/", "79.137.83.171");
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!in_array($request->ip(), $this->allowedDomains)){
            return response('Unauthorized', 401);
        }
        return $next($request);
    }
}
