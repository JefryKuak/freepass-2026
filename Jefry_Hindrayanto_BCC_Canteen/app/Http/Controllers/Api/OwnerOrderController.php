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

    public function paymentStatus(Request $request)
    {
        $user = $request->user();

        $orders = Order::with('items.menu', 'user')
            ->whereHas('canteen', function ($q) use ($user) {
                $q->where('owner_id', $user->id);
            })
            ->get();

        $response = $orders->map(function ($order) {
            return [
                'order_id' => $order->id,
                'user' => [
                    'id' => $order->user->id,
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                ],
                'total_price' => $order->total_price,
                'payment_status' => $order->payment_status,
                'order_status' => $order->order_status,
                'items' => $order->items->map(function ($item) {
                    return [
                        'menu_name' => $item->menu->name,
                        'quantity' => $item->quantity,
                        'price' => $item->price
                    ];
                })
            ];
        });

        return response()->json([
            'message' => 'Payment status of orders',
            'data' => $response
        ]);
    }
}
