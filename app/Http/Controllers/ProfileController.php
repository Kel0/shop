<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Post;
use App\User;
use App\UserMeta;
use App\PostComment;
use Validator;
use File;
use App\Services\PayUService\Exception;
use App\UserCommentMeta;


class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (is_null($user)) return redirect('/login');

        $posts = Post::with(['likesAndDislikes'])->where("user_id", $user->id)->get()->toArray();
        $comments = PostComment::with(['post', 'likesAndDislikes', 'post.likesAndDislikes'])->where("user_id", $user->id)->get()->toArray();

        $meta = ["likes" => 0, "dislikes" => 0];
        $posts_meta = UserMeta::where('user_id', $user->id)->get()->toArray();
        $posts_comment_meta = UserCommentMeta::where('user_id', $user->id)->get()->toArray();

        foreach ($posts_meta as $post_meta) {
            $meta["likes"] += $post_meta["like"];
            $meta["dislikes"] += $post_meta["dislike"];
        }

        foreach ($posts_comment_meta as $post_comment_meta) {
            $meta["likes"] += $post_comment_meta["like"];
            $meta["dislikes"] += $post_comment_meta["dislike"];
        }

        $all_data = [
            "meta" => $meta,
            "posts" => $posts,
            "user" => $user,
            "comments" => $comments,
            "posts_json" => json_encode($posts),
            "comments_json" => json_encode($comments),
        ];

        return view("profile", $all_data);
    }

    public function image_upload_post(Request $req)
    {
        try {
            if (!$req->image) return back()->with('error', 'Something went wrong...');
            $image_name = time().'.'.$req->image->getClientOriginalExtension();
            $req->image->move(public_path('images'), $image_name);
            $user = User::find(Auth::id());

            $previous_photo = public_path("images/".$user->photo_name);

            $user->photo_name = $image_name;
            $user->save();

            return redirect('/profile');
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong...');
        }
    }

    public function update_role(Request $req) {
        $user_id = $req->user_id;
        $role = $req->role;

        $user = User::where("id", $user_id)->update([
            "type" => $role
        ]);

        return back()->with('success_change', 'Success. Role has been changed!');
    }
}
