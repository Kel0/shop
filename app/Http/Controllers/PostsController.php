<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\User;
use App\Post;
use App\PostComment;
use App\LikeAndDislike;
use App\PostCommentLikeAndDislike;

class PostsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Posts controller
    |--------------------------------------------------------------------------
    |
    | This controller handles posts create, posts get, post comments create,
    | post meta info update, post comments' meta info update.
    |
    */

    /**
     * Render phorum page
     *
     * @return view of phorum page
     */
    public function index()
    {
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

    /**
     * Create post and post's meta info
     *
     * @return json
     */
    public function post_create(Request $req)
    {
        $post_content = $req->content;
        $post = new Post;
        $post_meta = new LikeAndDislike;

        $post->content = $post_content;
        $post->user_id = Auth::id();

        $post_meta->likes = 0;
        $post_meta->dislikes = 0;
        
        $status = $post->save();
        
        $post_meta->post_id = $post->id;
        $meta = $post_meta->save();

        return response()->json(["status" => ["post" => $status, "meta" => $meta]]);
    }

    /**
     * Create post's comment and post's comment's meta info
     *
     * @return json
     */
    public function post_comment_create(Request $req)
    {
        $post_id = $req->post_id;
        $post_comment_content = $req->content;
        
        $post_comment = new PostComment;  // Create post object
        $post_comment_meta = new PostCommentLikeAndDislike;  // Create post meta info object

        $post_comment->post_id = $post_id;
        $post_comment->content = $post_comment_content;

        $post_comment_meta->likes = 0;
        $post_comment_meta->dislikes = 0;
        
        $status = $post_comment->save();
        
        $post_comment_meta->post_comment_id = $post_comment->id;
        $meta = $post_comment_meta->save();

        return response()->json(["status" => ["comment" => $status, "meta" => $meta]]);
    }

    /**
     * Update meta info of post
     *
     * @return json
     */
    public function post_like_dislike_update(Request $req) 
    {
        $post_id = $req->post_id;
        $action_type = $req->action_type;  // like or dislike

        if ($action_type != "like" && $action_type != "dislike")
        {
            return response()->json(["status" => "Not correct action type -> ".$action_type]);
        }
        
        $post_meta = LikeAndDislike::where("post_id", $post_id)->increment($action_type."s", 1);

        return response()->json(["status" => $post_meta]);
    }

    /**
     * Update meta info of post's comment
     *
     * @return json
     */
    public function post_comments_like_dislike_update(Request $req) 
    {
        $post_comment_id = $req->post_comment_id;
        $action_type = $req->action_type;  // like or dislike

        if ($action_type != "like" && $action_type != "dislike")
        {
            return response()->json(["status" => "Not correct action type -> ".$action_type]);
        }
        
        $post_meta = PostCommentLikeAndDislike::where("post_comment_id", $post_comment_id)->increment($action_type."s", 1);

        return response()->json(["status" => $post_meta]);
    }
}
