<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\DB;
use Closure;
use Illuminate\Http\Request;
use App\Services\DatabaseConnectionService;

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
        //$data = DB::table('users')->where('remember_token',$request->remember_token)->get();
        $collection = new DatabaseConnectionService();
        $conn = $collection->getConnection('users');
        $data = $conn->find(["remember_token"=>$request->remember_token]);
        $n = null;
        foreach($data as $d){
            $n = $d["name"];
        }
        if ($n){
            echo json_encode(['msg'=>'valid']);
            return $next($request);
        }
        else{
            return response()->json(['msg' => 'you are not login']);
        }
        
    }
}
