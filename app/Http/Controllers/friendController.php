<?php

namespace App\Http\Controllers;
use App\Models\Friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\DataBaseController;
use App\Services\DatabaseConnectionService;

class friendController extends Controller
{
    public function checkFriend($conn,$fid){
        $res = $conn->findOne(["friends.friend_id"=>$fid]);
        if ($res != null){
            return false;
        } 
        else{
            return true;
        }
    }
    public function friend(Request $request){
        $collection = new DatabaseConnectionService();
        $conn = $collection->getConnection('users');
        $data = $conn->findOne(["remember_token"=>$request->remember_token]);
        $friendId = new \MongoDB\BSON\ObjectId($request->friend_id);
        $friend1 = array(
            "friend_id" => $friendId
        );
        $friend2 = array(
            "friend_id" => $data["_id"]
        );
        if (self::checkFriend($conn,$friendId)){
            $conn->updateOne(["remember_token"=>$request->remember_token],['$push'=>["friends" => $friend1]]);
            $conn->updateOne(["_id"=>$friendId],['$push'=>["friends" =>$friend2]]);
            return response()->json(['msg' => 'Now you are Friends']);
        }
        else{
            return response()->json(['msg' => 'you are already Friends']);
        }
        
    }

    function friendRemove(Request $request){
        $collection = new DatabaseConnectionService();
        $conn = $collection->getConnection('users');
        $data = $conn->findOne(["remember_token"=>$request->remember_token]);
        $friendId = new \MongoDB\BSON\ObjectId($request->friend_id);
        $friend1 = array(
            "friend_id" => $friendId
        );
        $friend2 = array(
            "friend_id" => $data["_id"]
        );
        $conn->updateOne(["remember_token"=>$request->remember_token],['$pull'=>["friends" => $friend1]]);
        $conn->updateOne(["_id"=>$friendId],['$pull'=>["friends" =>$friend2]]);
        return response()->json(['msg' => 'Now you are Unfriends']);
    }
}
