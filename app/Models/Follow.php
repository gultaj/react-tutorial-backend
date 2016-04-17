<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Like;
use App\Models\User;

class Follow extends Model {


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function followable()
    {
        return $this->morphTo();
    }

}
