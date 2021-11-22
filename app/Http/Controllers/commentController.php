<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\commentRequest;
use App\Services\DatabaseConnectionService;

class commentController extends Controller
{
    public function comment(commentRequest $request){
        $request->validated();

        //$data = DB::table('users')->where('remember_token',$request->remember_token)->get();
        $collection = new DatabaseConnectionService();
        $conn = $collection->getConnection('users');
        $data = $conn->findOne(["remember_token"=>$request->remember_token]);

        $path = $request->file('file')->store('comment');

        $postId = new \MongoDB\BSON\ObjectId($request->post_id);
        $comment = array(
            "_id" => new \MongoDB\BSON\ObjectId(),
            "user_id" => $data["_id"],
            "file" => $path,
            "comment" => $request->comment,
        );

        $conn = $collection->getConnection('posts');
        $conn->updateOne(["_id"=>$postId],['$push'=>["comments" => $comment]]);
        return response()->json(['msg' => 'you have comment....']);
    } 

    function commentUpdate(commentRequest $request){
        $request->validated();
        //$data = DB::table('users')->where('remember_token',$request->remember_token)->get();
        $collection = new DatabaseConnectionService();
        $conn = $collection->getConnection('users');
        $data = $conn->findOne(["remember_token"=>$request->remember_token]);
    
        $path = $request->file('file')->store('comment');
        $comment = array(
            "file" => $path,
            "comment" => $request->comment,
        );
        // $conn = $collection->getConnection('posts');
        // $commentId = new \MongoDB\BSON\ObjectId($request->comment_id);
        // $postId = new \MongoDB\BSON\ObjectId($request->post_id);
        // $C = $conn->aggregate(['$unwind'=>"comments"],['$match' => ["comments._id" => $commentId]]);
        // dd($C)
        // //$conn->updateOne(["_id"=>$postId,"comments._id" => $commentId],['$match' => ["comments._id" => $commentId]],['$set'=>$comment]);
        // //DB::table('comments')->where('user_id',$data[0]->id)->where('post_id',$request->post_id)->where('id',$request->comment_id)->update(['file' => $path,'comment' => $request->comment]);
        // return response()->json(['msg' => 'you have updated your comment.']);
        
    }

    function commentDelete(Request $request){


        $data = DB::table('users')->where('remember_token',$request->remember_token)->get();
        // if (count($data) > 0){
            DB::table('comments')->where('user_id',$data[0]->id)->where('id',$request->comment_id)->delete();
            return response()->json(['msg' => 'you have successfully Delete your Comment']);
        // }
        // else{
        //     return response()->json(['msg' => 'you are not login']);
        // }
    }
}
