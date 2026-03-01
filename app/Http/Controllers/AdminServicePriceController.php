<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServicePrice;

class AdminServicePriceController extends Controller
{
    public function index()
    {
        $prices = ServicePrice::all();
        return view('admin.service_prices.index', compact('prices'));
    }

    public function update(Request $request, $id)
    {
        $price = ServicePrice::findOrFail($id);
        
        $request->validate([
            'price' => 'required|numeric',
        ]);

        $price->update(['price' => $request->price]);

        return redirect()->back()->with('success', 'Price updated successfully');
    }
}
