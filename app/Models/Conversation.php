<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Message;

class Conversation extends Model {

	// protected $hidden = ['pivot'];

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->select('users.id', 'users.nickname', 'users.first_name', 'users.last_name', 'users.avatar');
    }

    public function messages()
    {
    	return $this->hasMany(Message::class);
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->select('id', 'conversation_id', 'message', 'unreaded', 'created_at')->latest();
    }

    public function scopeMessagesCount($query)
    {
        return $query->addSelect(\DB::raw(
            'conversations.id as id,(select count(*) 
            from messages 
            where messages.conversation_id=conversations.id) 
            as messages_count'
        ));
    }

    public function scopeWithoutUser($query, $user_id)
    {
        return $query->with([
            'lastMessage',
            'users' => function($q) use ($user_id) {
                $q->where('user_id', '<>', $user_id);
            }
        ])->orderBy('created_at', 'desc');
    }

    public function toArray()
    {
        $this->setHidden(['pivot']);
        return parent::toArray();
    }

}
