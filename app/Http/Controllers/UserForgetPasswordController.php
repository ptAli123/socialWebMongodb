<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\Http\Requests\forgetPasswordRequest;

class UserForgetPasswordController extends Controller
{
    public function forgetPasword(Request $request){
        $varify_token=rand(100,100000);
        DB::table('users')->where('email',$request->email)->update(['forget_password_varify_token' => $varify_token]);
        $details = [
            'title' => 'Forget password Mail',
            'link' => $varify_token
        ];
        Mail::to($request->email)->send(new SendMail($details));
        return response()->json(['msg' => 'Mail send...']);
    }

    public function updatePassword(forgetPasswordRequest $request){
        $newPassword = hash::make($request->password);
        DB::table('users')->where('forget_password_varify_token',$request->password_token)->update(['password' => $newPassword]);
        return response()->json(['msg' => 'Your password has Successfully Updated.']);
    }
}
