<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryRequestItem extends Model
{
    protected $guarded = [];

    public function inventoryRequest()
    {
        return $this->belongsTo(InventoryRequest::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
