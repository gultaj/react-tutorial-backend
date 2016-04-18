<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Conversation;

class Message extends Model {

	protected $fillable = ['message'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }

    public function conversation()
    {
    	return $this->belongsTo(Conversation::class);
    }
}
