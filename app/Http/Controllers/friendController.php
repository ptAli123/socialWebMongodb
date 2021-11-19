<?php

namespace App\Http\Controllers;
use App\Models\Friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\DataBaseController;

class friendController extends Controller
{
    public function friend(Request $request){

        $data = DB::table('users')->where('remember_token',$request->remember_token)->get();
            $friend = new Friend();
            $friend->user1_id = $data[0]->id;
            $friend->user2_id = $request->user2_id;
            $friend->save();
            return response()->json(['msg' => 'Now you are Friends']);
    }

    function friendRemove(Request $request){
        $data = DB::table('users')->where('remember_token',$request->remember_token)->get();
        DB::table('friends')->where('user1_id',$data[0]->id)->where('user2_id',$request->friend_id)->delete();
        return response()->json(['msg' => 'Now you are Unfriends']);
    }
}
