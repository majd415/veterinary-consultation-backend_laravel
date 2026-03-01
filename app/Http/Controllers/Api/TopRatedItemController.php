<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TopRatedItem;

class TopRatedItemController extends Controller
{
    public function index()
    {
        $items = TopRatedItem::all();
        // Explicitly ensuring the name field is correctly populated
        $data = $items->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name, // Should be array because of cast
                'image' => $item->image,
                'type' => $item->type,
                'rating' => $item->rating,
                'price' => $item->price,
            ];
        });
        return response()->json($data);
    }
}
