<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    protected $table = 'post_comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id', 'content'
    ];

    public function likesAndDislikes()
    {
        return $this->hasOne("App\PostCommentLikeAndDislike");
    }
}
