<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\commentRequest;

class commentController extends Controller
{
    public function comment(commentRequest $request){
        $request->validated();

        $data = DB::table('users')->where('remember_token',$request->remember_token)->get();
        // if (count($data) > 0){
            $path = $request->file('file')->store('comment');
            $comment = new Comment();
            $comment->comment = $request->comment;
            $comment->file = $path;
            $comment->user_id = $data[0]->id;
            $comment->post_id = $request->post_id;
            $comment->save();
            return response()->json(['msg' => 'you have comment....']);
        // }
        // else{
        //     return response()->json(['msg' => 'you are not login']);
        // }
    } 

    function commentUpdate(commentRequest $request){
        $request->validated();

        $data = DB::table('users')->where('remember_token',$request->remember_token)->get();
        // if (count($data) > 0){
    
            $path = $request->file('file')->store('comment');
            DB::table('comments')->where('user_id',$data[0]->id)->where('post_id',$request->post_id)->where('id',$request->comment_id)->update(['file' => $path,'comment' => $request->comment]);
            return response()->json(['msg' => 'you have updated your comment.']);
        // }
        // else{
        //     return response()->json(['msg' => 'you are not login']);
        // }
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
