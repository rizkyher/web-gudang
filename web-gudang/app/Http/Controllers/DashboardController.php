<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\InventoryRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            return $this->adminDashboard();
        }

        return $this->userDashboard($user);
    }

    private function adminDashboard()
    {
        return response()->json([
            'stats' => [
                'total_items' => Item::count(),
                'low_stock_count' => Item::where('stock', '<=', 5)->count(),
                'pending_requests' => InventoryRequest::where('status', 'pending')->count(),
                'processed_this_month' => InventoryRequest::whereIn('status', ['processed', 'completed'])
                    ->where('updated_at', '>=', Carbon::now()->startOfMonth())
                    ->count(),
            ],
            'low_stock_items' => Item::where('stock', '<=', 5)->take(5)->get(),
            'recent_requests' => InventoryRequest::with('user')->latest()->take(5)->get()
        ]);
    }

    private function userDashboard($user)
    {
        return response()->json([
            'stats' => [
                'total_requests_month' => InventoryRequest::where('user_id', $user->id)
                    ->where('created_at', '>=', Carbon::now()->startOfMonth())
                    ->count(),
                'pending_count' => InventoryRequest::where('user_id', $user->id)
                    ->whereIn('status', ['pending', 'processed'])
                    ->count(),
                'completed_count' => InventoryRequest::where('user_id', $user->id)
                    ->where('status', 'completed')
                    ->count(),
            ],
            'recent_requests' => InventoryRequest::where('user_id', $user->id)
                ->with('items.item')
                ->latest()
                ->take(3)
                ->get()
        ]);
    }
}
