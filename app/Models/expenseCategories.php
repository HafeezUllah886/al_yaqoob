<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class expenseCategories extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function parent()
    {
        return $this->belongsTo(expenseCategories::class, 'parent_id');
    }

    public function subcategories()
    {
        return $this->hasMany(expenseCategories::class, 'parent_id');
    }
}
