<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\postRequest;

class postController extends Controller
{
    function post(postRequest $request){
        $request->validated();

        $data = DB::table('users')->where('remember_token',$request->remember_token)->get();
        // if (count($data) > 0){
    
            $path = $request->file('file')->store('post');
            $post = new Post();
            $post->file = $path;
            $post->access = $request->access;
            $post->user_id = $data[0]->id;
            $post->save();
            return response()->json(['msg' => 'your have post.....']);
        // }
        // else{
        //     return response()->json(['msg' => 'you are not login....']);
        // }
    }

    function postUpdate(postRequest $request){
        $request->validated();

        $data = DB::table('users')->where('remember_token',$request->remember_token)->get();
        // if (count($data) > 0){
            
            $path = $request->file('file')->store('post');
            DB::table('posts')->where('user_id',$data[0]->id)->where('id',$request->post_id)->update(['file' => $path,'access' => $request->access]);
            return response()->json(['msg' => 'your have updated post.....']);
            // }
        // else{
        //     echo json_encode(['msg' => 'you are not login']);
        // }
    }

    function postDelete(Request $request){
        $data = DB::table('users')->where('remember_token',$request->remember_token)->get();
        // if (count($data) > 0){
            DB::table('comments')->where('post_id',$request->post_id)->delete();
            DB::table('posts')->where('user_id',$data[0]->id)->where('id',$request->post_id)->delete();
            echo $data[0]->id.$request->post_id;
            return response()->json(['msg' => 'your have Deleted post.....']);
            // }
        // else{
        //     echo json_encode(['msg' => 'you are not login']);
        // }
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
