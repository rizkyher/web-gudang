<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryRequest extends Model
{
    protected $fillable = [
        'request_number',
        'user_id',
        'status',
        'notes',
        'admin_note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(InventoryRequestItem::class);
    }
}
