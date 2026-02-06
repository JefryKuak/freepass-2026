<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    /**
     * Buat menu baru (canteen owner only)
     */
    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'canteen_id' => 'required|exists:canteens,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        // Pastikan canteen milik owner ini
        if (!$user->canteens()->where('id', $validated['canteen_id'])->exists()) {
            return response()->json(['message' => 'You do not own this canteen'], 403);
        }

        $menu = Menu::create($validated);

        return response()->json([
            'message' => 'Menu created successfully',
            'menu' => $menu
        ], 201);
    }

    /**
     * Update menu yang dimiliki canteen owner
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();

        $menu = Menu::findOrFail($id);

        // Cek kepemilikan
        if (!$user->canteens()->where('id', $menu->canteen_id)->exists()) {
            return response()->json(['message' => 'You do not own this menu'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $menu->update($validated);

        return response()->json([
            'message' => 'Menu updated successfully',
            'menu' => $menu
        ]);
    }

    /**
     * Hapus menu yang dimiliki canteen owner
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        $menu = Menu::findOrFail($id);

        // Cek kepemilikan
        if (!$user->canteens()->where('id', $menu->canteen_id)->exists()) {
            return response()->json(['message' => 'You do not own this menu'], 403);
        }

        $menu->delete();

        return response()->json([
            'message' => 'Menu deleted successfully'
        ]);
    }
}
