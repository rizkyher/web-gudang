<?php

namespace App\Http\Controllers;

use App\Models\DeliveryOrder;
use App\Models\InventoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class DeliveryOrderController extends Controller
{
    public function index()
    {
        return response()->json(DeliveryOrder::with('inventoryRequest.user')->latest()->get());
    }

    public function show(DeliveryOrder $deliveryOrder)
    {
        return response()->json($deliveryOrder->load('inventoryRequest.user', 'inventoryRequest.items.item'));
    }

    public function store(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'inventory_request_id' => 'required|exists:inventory_requests,id',
            'driver_name' => 'required|string',
            'license_plate' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $inventoryReq = InventoryRequest::with('items.item')->findOrFail($validated['inventory_request_id']);

        if (!in_array($inventoryReq->status, ['processed', 'ready'])) {
            return response()->json(['message' => 'Permintaan belum diproses atau sudah selesai.'], 400);
        }

        // Start Transaction for stock deduction and DO creation
        return DB::transaction(function () use ($validated, $inventoryReq) {
            // Deduct stock
            foreach ($inventoryReq->items as $reqItem) {
                $item = $reqItem->item;
                if ($item->stock < $reqItem->quantity) {
                    throw new \Exception("Stok barang {$item->name} tidak mencukupi.");
                }
                $item->decrement('stock', $reqItem->quantity);
            }

            // Update request status
            $inventoryReq->update(['status' => 'completed']);

            // Create DO
            $do = DeliveryOrder::create([
                'do_number' => 'DO-' . strtoupper(Str::random(8)),
                'inventory_request_id' => $inventoryReq->id,
                'driver_name' => $validated['driver_name'],
                'license_plate' => $validated['license_plate'],
                'status' => 'delivered', // Assume immediately delivered for this simple flow
                'notes' => $validated['notes'] ?? null,
            ]);

            return response()->json($do->load('inventoryRequest.user', 'inventoryRequest.items.item'), 201);
        });
    }
}
