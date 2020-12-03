<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCommentMeta extends Model
{
    protected $table = 'users_comments_meta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "post_comment_id", "user_id", "like", "dislike"
    ];

    public function user()
    {
        return $this->belonsTo("App\User");
    }

    public function post()
    {
        return $this->hasOne("App\Post");
    }
}
