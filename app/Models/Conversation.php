<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Message;

class Conversation extends Model {

	protected $hidden = [
        'pivot',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->select('users.id', 'users.nickname', 'users.first_name', 'users.last_name', 'users.avatar');
    }

    public function messages()
    {
    	return $this->hasMany(Message::class, 'conversation_id');
    }

    public function scopeWithoutUser($query, $user_id)
    {
        return $query->with([
            'messages', 
            'users' => function($q) use ($user_id) {
                $q->where('user_id', '<>', $user_id);
            }
        ]);
    }

    public function messagesCount()
    {
        return $this->hasOne(Message::class)
            ->selectRaw('conversation_id, count(*) as count')->groupBy('conversation_id');
    }

    public function getMessagesCountAttribute($value)
    {
        if (! $this->relationLoaded('messagesCount')) $this->load('messagesCount');
        $related = $this->getRelation('messagesCount')->first();

        return $related ? (int) $related->count : 0;
    }
}
