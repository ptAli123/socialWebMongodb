<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\postRequest;
use App\Services\DatabaseConnectionService;


class postController extends Controller
{
    function post(postRequest $request){
        $request->validated();
    
            $path = $request->file('file')->store('post');
            
            $collection = new DatabaseConnectionService();
            $conn = $collection->getConnection('users');
            $data = $conn->findOne(["remember_token"=>$request->remember_token]);
            $document = array( 
                "user_id" => $data["_id"],
                "file" => $path,
                "access" => $request->access,
                "comments" => []
             );

            $conn = $collection->getConnection('posts');
            $conn->insertOne($document);
            return response()->json(['msg' => 'your have post.....']);
    }

    function postUpdate(postRequest $request){
        $request->validated();

        $collection = new DatabaseConnectionService();
        $conn = $collection->getConnection('users');

        $userId = $conn->findOne(["remember_token" => $request->remember_token]);

        $path = $request->file('file')->store('post');
        $conn = $collection->getConnection('posts');
        $id = new \MongoDB\BSON\ObjectId($request->post_id);


        $conn->updateOne(array("_id"=>$id,"user_id"=>$userId["_id"]), 
              array('$set'=>array("file" => $path,"access"=>$request->access)));
        return response()->json(['msg' => 'your have updated post.....']);
    }

    function postDelete(Request $request){
        $collection = new DatabaseConnectionService();
        $conn = $collection->getConnection('users');

        $userId = $conn->findOne(["remember_token" => $request->remember_token]);

        $conn = $collection->getConnection('posts');
        $id = new \MongoDB\BSON\ObjectId($request->post_id);
        $conn->deleteOne(array("user_id"=> $userId["_id"],"_id"=>$id));
        return response()->json(['msg' => 'your have Deleted post.....']);
    }

    function checkFriend($user1_id,$user2_id){
        $data = DB::table('friends')->where('user1_id',$user1_id)->where('user2_id',$user2_id)->get();
        if (count($data) > 0){
            return true;
        }
        else{
            return false;
        }
    }

    function postSearch(Request $request){

        $data = DB::table('users')->where('remember_token',$request->remember_token)->get();
        $post = DB::table('posts')->where('id',$request->post_id)->get();
        if ($post[0]->access == 'public'){
                $CArr = array();
                $comments = DB::table('comments')->where('post_id',$post[0]->id)->get();
                foreach($comments as $comment){
                    
                    $C = array(['file' =>$comment->file, 'comment'=> $comment->comment]);
                    $CArr[$comment->id] = $C;
                }
                $P = array(['file' =>$post[0]->file, 'Access'=> $post[0]->access],$CArr);
                return response()->json($P);
        }
        else{
            if ($this->checkFriend($data[0]->id,$post[0]->user_id)){
                $CArr = array();
                $comments = DB::table('comments')->where('post_id',$post[0]->id)->get();
                foreach($comments as $comment){
                    
                    $C = array(['file' =>$comment->file, 'comment'=> $comment->comment]);
                    $CArr[$comment->id] = $C;
                }
                $P = array(['file' =>$post[0]->file, 'Access'=> $post[0]->access],$CArr);
                return response()->json($P);
            }
        }
    }
}
