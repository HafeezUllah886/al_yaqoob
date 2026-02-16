<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class expenses extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function account()
    {
        return $this->belongsTo(accounts::class);
    }

    public function category()
    {
        return $this->belongsTo(expenseCategories::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branches::class);
    }

    public function scopeCurrentBranches($query)
    {
        return $query->whereIn('branch_id', auth()->user()->branches->pluck('id'));
    }
}
