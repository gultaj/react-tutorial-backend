<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Message;

class Conversation extends Model {

	protected $fillable = [
        'user_one', 'user_two',
    ];

    public function user_one()
    {
        return $this->belongsTo(User::class, 'user_one')
        	->select('id','nickname','first_name', 'last_name', 'avatar');
    }

    public function user_two()
    {
        return $this->belongsTo(User::class, 'user_two')
	        ->select('id','nickname','first_name', 'last_name', 'avatar');
    }

    public function messages()
    {
    	return $this->hasMany(Message::class, 'conversation_id');
    }

    public function scopeByUser($query, $user_id)
    {

    	// return $query->userOne($user_id)->userTwo($user_id);
    	return $query->where('user_one', $user_id)->orWhere('user_two', $user_id)
    		->with('user_two', 'user_one');
    }
    public function scopeUserOne($query, $user_id)
    {
    	return $query->where('user_one', $user_id)->with('user_two');
    }
    public function scopeUserTwo($query, $user_id)
    {
    	return $query->where('user_two', $user_id)->with('user_one');
    }
}
