<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Canteen;

class CanteenController extends Controller
{
    // GET /api/canteens
    public function index()
    {
        $canteens = Canteen::with('menus')->get();

        return response()->json([
            'canteens' => $canteens
        ]);
    }

    // GET /api/canteens/{id}/menus
    public function menus($id)
    {
        $canteen = Canteen::with('menus')->find($id);

        if (!$canteen) {
            return response()->json([
                'message' => 'Canteen not found'
            ], 404);
        }

        return response()->json([
            'canteen' => $canteen->name,
            'menus' => $canteen->menus
        ]);
    }
}
