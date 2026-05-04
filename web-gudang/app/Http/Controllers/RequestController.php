<?php

namespace App\Http\Controllers;

use App\Models\InventoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        $baseQuery = InventoryRequest::query();
        
        if ($request->user()->role !== 'admin') {
            $baseQuery->where('user_id', $request->user()->id);
        }

        $requests = QueryBuilder::for($baseQuery)
            ->allowedFilters(['status', 'request_number'])
            ->allowedIncludes(['user', 'items.item'])
            ->defaultIncludes(['user', 'items.item'])
            ->allowedSorts(['created_at', 'status'])
            ->defaultSort('-created_at')
            ->get();

        return response()->json($requests);
    }

    public function show(Request $request, InventoryRequest $inventoryRequest)
    {
        if ($request->user()->role !== 'admin' && $request->user()->id !== $inventoryRequest->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($inventoryRequest->load(['user', 'items.item']));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $inventoryRequest = DB::transaction(function () use ($request, $validated) {
            $inventoryRequest = InventoryRequest::create([
                'request_number' => 'REQ-' . strtoupper(Str::random(6)),
                'user_id' => $request->user()->id,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($validated['items'] as $item) {
                $inventoryRequest->items()->create([
                    'item_id' => $item['id'],
                    'quantity' => $item['quantity']
                ]);
            }

            return $inventoryRequest;
        });

        return response()->json($inventoryRequest->load('items.item'), 201);
    }

    public function updateStatus(Request $request, InventoryRequest $inventoryRequest)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,processed,ready,completed,rejected',
            'admin_note' => 'nullable|string',
        ]);

        $inventoryRequest->update($validated);

        // Create notification for the user
        \App\Models\Notification::create([
            'user_id' => $inventoryRequest->user_id,
            'title' => 'Status Permintaan Diperbarui',
            'message' => "Permintaan {$inventoryRequest->request_number} Anda kini berstatus: " . ucfirst($validated['status']),
            'type' => $validated['status'] === 'rejected' ? 'error' : 'info',
            'is_read' => false,
        ]);

        return response()->json($inventoryRequest);
    }
}
