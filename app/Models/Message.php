<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Message extends Model {

    public function author()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function acceptor()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}
