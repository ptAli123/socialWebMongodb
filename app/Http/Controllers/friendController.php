<?php

namespace App\Http\Controllers;
use App\Models\Friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\DataBaseController;
use App\Services\DatabaseConnectionService;

class friendController extends Controller
{
    public function friend(Request $request){
        $collection = new DatabaseConnectionService();
        $conn = $collection->getConnection('users');
        $data = $conn->findOne(["remember_token"=>$request->remember_token]);
        $friendId = new \MongoDB\BSON\ObjectId($request->friend_id);
        $friend = array(
            "friend_id" => $friendId
        );
        $conn->updateOne(["remember_token"=>$request->remember_token],['$push'=>["friends" => $friend]]);
        return response()->json(['msg' => 'Now you are Friends']);
    }

    function friendRemove(Request $request){
        $data = DB::table('users')->where('remember_token',$request->remember_token)->get();
        DB::table('friends')->where('user1_id',$data[0]->id)->where('user2_id',$request->friend_id)->delete();
        return response()->json(['msg' => 'Now you are Unfriends']);
    }
}
