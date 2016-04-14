<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Comment;

class Post extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'text',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    // protected $hidden = [
    //     'password',
    // ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id', 'nickname');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function shared()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function liked()
    {
        return $this->belongsTo(User::class, 'user_id')
    }
}
