<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branches extends Model
{
    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class, 'branch_users', 'branch_id', 'user_id');
    }

    public function syncUsers($users)
    {
        return $this->users()->sync($users);
    }
}
