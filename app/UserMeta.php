<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    protected $table = 'users_meta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "post_id", "user_id", "like", "dislike"
    ];

    public function user()
    {
        return $this->belongsTo("App\User");
    }

    public function post()
    {
        return $this->hasOne("App\Post");
    }
}
