<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function fromBranch()
    {
        return $this->belongsTo(branches::class, 'branch_from_id');
    }

    public function toBranch()
    {
        return $this->belongsTo(branches::class, 'branch_to_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(products::class, 'product_id');
    }

    public function unit()
    {
        return $this->belongsTo(Product_units::class, 'unit_id');
    }
}
