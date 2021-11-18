<?php

namespace App\Http\Controllers;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class loginController extends Controller
{
    public $jwtToken;
    function jwtToken(){
        $key = "malik626";
        $payload = array(
            "iss" => "localhost",
            "aud" => time(),
            "iat" => now(),
            "nbf" => 3400000
        );
        $jwt = JWT::encode($payload, $key, 'HS256');
        $this->jwtToken = $jwt;
        $token = array("remember_token"=>$jwt);
        echo json_encode($token);
    }
    function login(Request $request){
        $request->validate([
            "email" => "required | string",
            "password" => "required | min:6"
        ]);
        $data = DB::table('users')->where('email',$request->email)->get();
        if (Hash::check($request->password, $data[0]->password)){
            //echo ["status"=>"you are Successfully Login"];
            $this->jwtToken();
            DB::table('users')->where('email',$request->email)->update(['remember_token' => $this->jwtToken]);
        }
        else{
            return response()->json(["status"=>"your email and password is not Valid"]);
        }
    }


    function logout(Request $request){
        $data = DB::table('users')->where('remember_token',$request->remember_token)->update(['remember_token' => null]);
        return response()->json(["status"=>"you are successfully logout"]);
    }
}
