<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\PostComment;
use Auth;
use App\User;

class PostsController extends Controller
{
    public function index()
    {
        $user = User::where("id", Auth::id())->first();
        return view("phorum");
    }

    public function posts_get(Request $req)
    {
        $user_id = $req->user_id;

        $posts = Post::with(['likesAndDislikes', 'comments', 'comments.likesAndDislikes'])->get()->flatten();
        if (!is_null($user_id)) {
            $posts = Post::with(['likesAndDislikes', 'comments', 'comments.likesAndDislikes'])
                        ->where("user_id", "=", $user_id)
                        ->get()
                        ->flatten();
        }
        
        return response()->json(["posts" => $posts]);
    }

    public function post_create(Request $req)
    {
        $post_content = $req->content;
        $post = new Post;

        $post->content = $post_content;
        $post->user_id = Auth::id();

        $status = $post->save();

        return response()->json(["status" => $status]);
    }

    public function post_comment_create(Request $req)
    {
        $post_id = $req->post_id;
        $post_comment_content = $req->content;
        
        $post = Post::where("id", $post_id)->first();
        $post_comment = new PostComment;

        $post_comment->post_id = $post_id;
        $post_comment->content = $post_comment_content;

        $status = $post->comments()->save($post_comment);

        return response()->json(["status" => $status]);
    }
}
