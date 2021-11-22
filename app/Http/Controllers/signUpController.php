<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\Http\Requests\signUpRequest;
use App\Services\DatabaseConnectionService;

class signUpController extends Controller
{   
    function signUp(signUpRequest $request){
        
        $request->validated();
        $varify_token=rand(100,100000);
        $collection = new DatabaseConnectionService();
        $conn = $collection->getConnection('users');

        $document = array( 
            "name" => $request->name, 
            "email" => $request->email,
            "password"=> hash::make($request->password),
            "gender"=>$request->gender,
            "verify_token" => $varify_token,
            "status" => 1
         );
          
        $conn->insertOne($document);
        $details = [
            'title' => 'confirmation Mail',
            'link' => 'http://127.0.0.1:8000/api/mail-confirmation/'.$request->email.'/'.$varify_token
        ];
        Mail::to($request->email)->send(new SendMail($details));
        return response()->json(["msg"=>"mail send...."]); 
    }
}
