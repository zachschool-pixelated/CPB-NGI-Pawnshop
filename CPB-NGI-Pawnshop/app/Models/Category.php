<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Auditable;

class Category extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get all items in this category
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
