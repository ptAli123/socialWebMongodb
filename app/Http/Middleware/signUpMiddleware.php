<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class signUpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $request->validate([
            "name" => "required | string",
            "email" => "required | Email",
            "password" => "required | min:6",
            "gender" => "required | string" 
        ]);
        //return $next($request);
    }
}
