<?php

namespace App\Http\Middleware;
use Log;
use Closure;

class LoggServer
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
        return $next($request);
        
    }

    public function terminate($request, $response){
        Log::info("Entradas",["request"=>$request]);
        Log::info("Salidas",["response"=>$response]);
    }
}
