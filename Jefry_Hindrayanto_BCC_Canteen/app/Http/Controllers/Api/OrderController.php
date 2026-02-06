<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'canteen_id' => 'required|exists:canteens,id',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        $user = $request->user();

        DB::beginTransaction();

        try {
            $totalPrice = 0;

            $order = Order::create([
                'user_id' => $user->id,
                'canteen_id' => $validated['canteen_id'],
                'total_price' => 0,
                'payment_status' => 'unpaid',
                'order_status' => 'waiting'
            ]);

            foreach ($validated['items'] as $item) {
                $menu = Menu::findOrFail($item['menu_id']);

                if ($menu->stock < $item['quantity']) {
                    throw new \Exception("Stock not enough for {$menu->name}");
                }

                $menu->stock -= $item['quantity'];
                $menu->save();

                $price = $menu->price * $item['quantity'];
                $totalPrice += $price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'quantity' => $item['quantity'],
                    'price' => $price
                ]);
            }

            $order->update([
                'total_price' => $totalPrice
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order->load('items.menu')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
