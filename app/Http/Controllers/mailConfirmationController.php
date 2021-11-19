<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\DatabaseConnectionService;

class mailConfirmationController extends Controller
{
    function confirmed($email,$varify_token){
        $collection = new DatabaseConnectionService();
        $conn = $collection->getConnection('users');
        echo $email.$varify_token;
        $res = $conn->updateOne(array("email"=>$email,"verify_token"=>(int)$varify_token), 
      array('$set'=>array("verified_token_at" => 9876)));
      dd($res);
        echo json_encode(['message' => 'you are varified']);
    }
}
