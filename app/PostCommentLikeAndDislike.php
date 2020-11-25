<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostCommentLikeAndDislike extends Model
{
    protected $table = 'post_comments_likes_and_dislikes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_comment_id', 'likes', 'dislikes'
    ];

    public function post()
    {
        return $this->belongsTo("App\PostComment");
    }
}
