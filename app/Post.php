<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $table = 'post';
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "user_id", "content"
    ];

    public function comments()
    {
        return $this->hasMany("App\PostComment");
    }

    public function likesAndDislikes()
    {
        return $this->hasOne("App\LikeAndDislike");
    }

    public function user()
    {
        return $this->belongsTo("App\User");
    }
}
