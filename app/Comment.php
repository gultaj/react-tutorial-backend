<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {

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
        return $this->belongsTo(\App\User::class, 'user_id')->select('id', 'nickname');
    }
}
