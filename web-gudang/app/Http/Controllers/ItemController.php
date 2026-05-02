<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('category')->get();
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:items,code',
            'name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'unit' => 'required|string',
            'icon' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $item = Item::create($validated);
        return response()->json($item, 201);
    }
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'code' => 'sometimes|required|unique:items,code,' . $item->id,
            'name' => 'sometimes|required|string',
            'category_id' => 'sometimes|required|exists:categories,id',
            'stock' => 'sometimes|required|integer|min:0',
            'unit' => 'sometimes|required|string',
            'icon' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $item->update($validated);
        return response()->json($item);
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return response()->json(null, 204);
    }
}
