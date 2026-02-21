<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payments extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo(accounts::class, 'account_id');
    }

    public function toAccount()
    {
        return $this->belongsTo(accounts::class, 'to_account_id');
    }

    public function paidBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeCurrentBranches($query)
    {
        return $query->whereIn('branch_id', auth()->user()->branch_ids());
    }
}
