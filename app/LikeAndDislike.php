<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LikeAndDislike extends Model
{
    protected $table = 'post_likes_and_dislikes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_id', 'likes', 'dislikes'
    ];

    public function post()
    {
        return $this->belongsTo("App\Post");
    }
}
