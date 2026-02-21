<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(accounts::class, 'customer_id');
    }

    public function details()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function scopeCurrentBranches($query)
    {
        return $query->whereIn('branch_id', auth()->user()->branches->pluck('id'));
    }
}
