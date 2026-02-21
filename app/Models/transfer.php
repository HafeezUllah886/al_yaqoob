<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transfer extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function fromAccount()
    {
        return $this->belongsTo(accounts::class, 'from_id');
    }

    public function toAccount()
    {
        return $this->belongsTo(accounts::class, 'to_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branches::class, 'branch_id');
    }

    public function scopeCurrentBranches($query)
    {
        return $query->whereIn('branch_id', auth()->user()->branch_ids());
    }
}
