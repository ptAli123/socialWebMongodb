<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class mailConfirmationController extends Controller
{
    function confirmed($email,$varify_token){
        DB::table('users')->where('email',$email)->where('varify_token',$varify_token)->update(['email_verified_at' => now()]);
        echo json_encode(['message' => 'you are varified']);
    }
}
