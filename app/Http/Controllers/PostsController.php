<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\User;
use App\Post;
use App\UserMeta;
use App\PostComment;
use App\LikeAndDislike;
use App\UserCommentMeta;
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

        $posts = Post::with(['likesAndDislikes', 'comments', 'comments.likesAndDislikes', 'user', 'comments.user'])->get()->flatten();

        if (!is_null($user_id)) {
            $posts = Post::with(['likesAndDislikes', 'comments', 'comments.likesAndDislikes', 'user', 'comments.user'])
                        ->where("user_id", "=", $user_id)
                        ->get()
                        ->flatten();
        }

        foreach ($posts as $key => $post) {
            $posts[$key]->date = date("Y-m-d H:i:s", (strtotime($post->created_at->jsonSerialize()) + 21600));
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
        $title = $req->title;
        $category = $req->category;

        $post = new Post;
        $post_meta = new LikeAndDislike;
        $user_meta = new UserMeta;

        $post->content = $post_content;
        $post->user_id = Auth::id();
        $post->title = $title;
        $post->category = $category;

        $post_meta->likes = 0;
        $post_meta->dislikes = 0;

        
        $status = $post->save();
        
        $post_meta->post_id = $post->id;
        $user_meta->post_id = $post->id;
        $user_meta->user_id = Auth::id();

        $meta = $post_meta->save();
        $user_meta_status = $user_meta->save();

        return response()->json(["status" => ["post" => $status, "meta" => $meta, "user_meta" => $user_meta_status]]);
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
        $user_meta = new UserCommentMeta;

        $post_comment->post_id = $post_id;
        $post_comment->user_id = Auth::id();
        $post_comment->content = $post_comment_content;

        $post_comment_meta->likes = 0;
        $post_comment_meta->dislikes = 0;
        
        $status = $post_comment->save();
        
        $post_comment_meta->post_comment_id = $post_comment->id;
        $user_meta->post_comment_id = $post_comment->id;
        $user_meta->user_id = Auth::id();

        $meta = $post_comment_meta->save();
        $user_meta_status = $user_meta->save();

        return response()->json(["status" => ["comment" => $status, "meta" => $meta, "user_meta" => $user_meta_status]]);
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
        
        $user_meta = UserMeta::where("post_id", $post_id)->where("user_id", Auth::id());

        if (count($user_meta->get()) == 0) {
            $_user_meta = new UserMeta;
            $_user_meta->post_id = $post_id;
            $_user_meta->user_id = Auth::id();
            $_user_meta->like = 0;
            $_user_meta->dislike = 0;
            $_user_meta->save();

            $user_meta = UserMeta::where("post_id", $post_id)->where("user_id", Auth::id());
        }
        
        $another_action = "like";
        if ($action_type == $another_action) $another_action = "dislike";

        if ($user_meta->get()[0]->$action_type == 1) {
            $user_meta->decrement($action_type, 1);
            LikeAndDislike::where("post_id", $post_id)->decrement($action_type."s", 1);
            $likes_and_dislikes = LikeAndDislike::where("post_id", $post_id)->get()->flatten();

            return response()->json(["meta" => $likes_and_dislikes]);
        } 
        
        elseif ($user_meta->get()[0]->$action_type == 0) {
            if ($user_meta->get()[0]->$another_action == 1) {
                $user_meta->decrement($another_action, 1);
                LikeAndDislike::where("post_id", $post_id)->decrement($another_action."s", 1);
            }
            $user_meta->increment($action_type, 1);
            $post_meta = LikeAndDislike::where("post_id", $post_id)->increment($action_type."s", 1);
            
            if ($action_type == "dislike") {
                User::find(Auth::id())->decrement("points", 10);
            } else {
                User::find(Auth::id())->increment("points", 10);
            }
        }

        $likes_and_dislikes = LikeAndDislike::where("post_id", $post_id)->get()->flatten();
        return response()->json(["meta" => $likes_and_dislikes]);
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

        $user_meta = UserCommentMeta::where("post_comment_id", $post_comment_id)->where("user_id", Auth::id());

        if (count($user_meta->get()) == 0) {
            $_user_meta = new UserCommentMeta;
            $_user_meta->post_comment_id = $post_comment_id;
            $_user_meta->user_id = Auth::id();
            $_user_meta->like = 0;
            $_user_meta->dislike = 0;
            $_user_meta->save();

            $user_meta = UserCommentMeta::where("post_comment_id", $post_comment_id)->where("user_id", Auth::id());
        }
        
        $another_action = "like";
        if ($action_type == $another_action) $another_action = "dislike";

        if ($user_meta->get()[0]->$action_type == 1) {
            $user_meta->decrement($action_type, 1);
            PostCommentLikeAndDislike::where("post_comment_id", $post_comment_id)->decrement($action_type."s", 1);
            $likes_and_dislikes = PostCommentLikeAndDislike::where("post_comment_id", $post_comment_id)->get()->flatten();

            return response()->json(["meta" => $likes_and_dislikes]);
        } 
        
        elseif ($user_meta->get()[0]->$action_type == 0) {
            if ($user_meta->get()[0]->$another_action == 1) {
                $user_meta->decrement($another_action, 1);
                PostCommentLikeAndDislike::where("post_comment_id", $post_comment_id)->decrement($another_action."s", 1);
            }
            $user_meta->increment($action_type, 1);
            $post_meta = PostCommentLikeAndDislike::where("post_comment_id", $post_comment_id)->increment($action_type."s", 1);

            if ($action_type == "dislike") {
                User::find(Auth::id())->decrement("points", 10);
            } else {
                User::find(Auth::id())->increment("points", 10);
            }
        }
        
        $likes_and_dislikes = PostCommentLikeAndDislike::where("post_comment_id", $post_comment_id)->get()->flatten();
        return response()->json(["meta" => $likes_and_dislikes]);
    }

    public function delete_post(Request $req)
    {
        $post_id = $req->post_id;
        $status = Post::find($post_id)->delete();

        return response()->json(["stauts" => $status]);
    }

    public function delete_comment(Request $req)
    {
        $post_comment_id = $req->post_comment_id;
        $status = PostComment::find($post_comment_id)->delete();

        return response()->json(["status" => $status]);
    }
}
