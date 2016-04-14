<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Like;

class Comment extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'text',
    ];

    public function author()
    {
        return $this->belongsTo(\App\User::class, 'user_id')->select('id', 'nickname');
    }

    public function like()
    {
        return $this->morphMany(Like::class, 'likeable');
    }
}
