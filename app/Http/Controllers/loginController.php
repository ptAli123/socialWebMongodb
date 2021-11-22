<?php

namespace App\Http\Controllers;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\DatabaseConnectionService;


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
        $collection = new DatabaseConnectionService();
        $conn = $collection->getConnection('users');
        $data = $conn->findOne(["email" => $request->email]);
        if (Hash::check($request->password,$data["password"])){
            $this->jwtToken();
            $conn->updateOne(array("email"=>$request->email), 
            array('$set'=>array("remember_token" => $this->jwtToken)));
        }
        else{
            return response()->json(["status"=>"your email and password is not Valid"]);
        }
    }


    function logout(Request $request){
        $collection = new DatabaseConnectionService();
        $conn = $collection->getConnection('users');
        $data = $conn->updateOne(array('remember_token'=>$request->remember_token), 
        array('$unset'=>array('remember_token'=>'')));
        return response()->json(["status"=>"you are successfully logout"]);
    }
}
