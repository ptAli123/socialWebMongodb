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

    public function checkFriend($conn,$fid){
        
        $res = $conn->findOne(["friends.friend_id"=>$fid]);
        if ($res != null){
            return true;
        } 
        else{
            return false;
        }
    }

    function postSearch(Request $request){

        $collection = new DatabaseConnectionService();
        $conn1 = $collection->getConnection('users');
        $id = new \MongoDB\BSON\ObjectId($request->post_id);
        $conn = $collection->getConnection('posts');
        $FindPost = null;
        $posts = $conn->find(['access' => 'public']);
        foreach($posts as $post){
            if ($id == $post->_id){
                $FindPost = (array)$post;
            }
        }


        //for private posts
       
        $privatePosts =$conn->find(['access' => 'private']);
        
        $Arr = null;
        $count = 0;
        foreach($privatePosts as $post){
            if (self::checkFriend($conn1,$post->user_id)){
                if ($id == $post->_id){
                    $FindPost = (array)$post;
                }
            }  
        }
        return response()->json($FindPost);
    }
}
