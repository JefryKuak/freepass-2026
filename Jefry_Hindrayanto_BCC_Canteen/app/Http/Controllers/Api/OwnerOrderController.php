<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OwnerOrderController extends Controller
{
    public function active(Request $request)
    {
        $user = $request->user();

        $orders = Order::with('items.menu', 'user')
            ->whereHas('canteen', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            })
            ->whereIn('order_status', ['waiting', 'processing'])
            ->get();

        return response()->json([
            'message' => 'Active orders',
            'data' => $orders
        ]);
    }

    public function history(Request $request)
    {
        $user = $request->user();

        $orders = Order::with('items.menu', 'user')
            ->whereHas('canteen', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            })
            ->whereIn('order_status', ['completed', 'cancelled'])
            ->get();

        return response()->json([
            'message' => 'Order history',
            'data' => $orders
        ]);
    }
}
