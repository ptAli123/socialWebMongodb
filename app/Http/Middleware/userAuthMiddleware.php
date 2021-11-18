<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\DB;
use Closure;
use Illuminate\Http\Request;

class userAuthMiddleware
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
        $data = DB::table('users')->where('remember_token',$request->remember_token)->get();
        if (count($data) > 0){
            echo json_encode(['msg'=>'valid']);
            return $next($request);
        }
        else{
            return response()->json(['msg' => 'you are not login']);
        }
        
    }
}
