<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Share extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sharable()
    {
        return $this->morphTo();
    }
}
