<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrder extends Model
{
    protected $fillable = [
        'do_number',
        'inventory_request_id',
        'driver_name',
        'license_plate',
        'status',
        'notes',
    ];

    public function inventoryRequest()
    {
        return $this->belongsTo(InventoryRequest::class);
    }
}
