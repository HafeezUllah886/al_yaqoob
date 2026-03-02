<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonBusinessExpenseCategories extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function parent()
    {
        return $this->belongsTo(NonBusinessExpenseCategories::class, 'parent_id');
    }

    public function subcategories()
    {
        return $this->hasMany(NonBusinessExpenseCategories::class, 'parent_id');
    }
}
