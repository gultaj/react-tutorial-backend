<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Message;

class Conversation extends Model {

	protected $fillable = [
        'user_one', 'user_two',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function messages()
    {
    	return $this->hasMany(Message::class, 'conversation_id');
    }

}
