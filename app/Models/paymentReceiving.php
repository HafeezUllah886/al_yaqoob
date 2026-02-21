<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class paymentReceiving extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function fromAccount()
    {
        return $this->belongsTo(accounts::class, 'from_id');
    }

    public function inAccount()
    {
        return $this->belongsTo(accounts::class, 'to_id');
    }

    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeCurrentBranches($query)
    {
        return $query->whereIn('branch_id', auth()->user()->branch_ids());
    }
}
