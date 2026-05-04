<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'code',
        'name',
        'category_id',
        'stock',
        'unit',
        'icon',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
