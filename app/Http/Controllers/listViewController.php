<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Services\DatabaseConnectionService;

class listViewController extends Controller
{
    public function checkFriend($conn,$fid){
        
        $res = $conn->findOne(["friends.friend_id"=>$fid]);
        if ($res != null){
            return true;
        } 
        else{
            return false;
        }
    }
    public function postList(Request $request){

        $collection = new DatabaseConnectionService();
        $conn1 = $collection->getConnection('users');

        $conn = $collection->getConnection('posts');
        $postsArr = null;
        $posts = $conn->find(['access' => 'public']);
        $postsArr = json_decode(json_encode($posts->toArray(),true));


        //for private posts
       
        $privatePosts =$conn->find(['access' => 'private']);
        
        $Arr = null;
        $count = 0;
        foreach($privatePosts as $post){
            if (self::checkFriend($conn1,$post->user_id)){
                $p = $post->_id;
                $single_post = (array)$post;
                $Arr[$count++] = $single_post;
            }  
        }
        $AllPosts = null;
        $AllPosts[0] = $postsArr;
        $AllPosts[1] = $Arr;
        return response()->json($AllPosts);
    }
}
