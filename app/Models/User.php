<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use App\Models\Comments;
use App\Models\Post;
use App\Models\Message;
use App\Models\Like;
use App\Models\Follow;
use App\Models\Conversation;

class User extends Model implements
    AuthenticatableContract,
    AuthorizableContract
{
    use Authenticatable, Authorizable;



    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nickname', 'email', 'password'
    ];

    // protected $hidden = ['pivot'];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class);
    }


    public function messages()
    {
        return $this->hasMany(Message::class, 'to_user_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function follower()
    {
        return $this->hasMany(Follow::class);
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    public function getAvatarAttribute($value)
    {
        return 'uploads/'.$value;
    }

    public function getHaveConversationWithAttribute($value, $user_id)
    {
        return $this->conversations->intersect(static::find($user_id)->conversations);
    }

    public function scopeLoggedUser($query, $token)
    {
        return $query->where('remember_token', $token)->select('id', 'nickname', 'avatar', 'first_name', 'last_name');
    }

    public function toArray()
    {
        $this->setHidden(['pivot']);
        return parent::toArray();
    }
}
