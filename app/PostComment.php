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
        'user_id', 'post_id', 'content'
    ];

    public function likesAndDislikes()
    {
        return $this->hasOne("App\PostCommentLikeAndDislike");
    }

    public function post()
    {
        return $this->belongsTo("App\Post");
    }
    
    public function user()
    {
        return $this->belongsTo("App\User");
    }
}
