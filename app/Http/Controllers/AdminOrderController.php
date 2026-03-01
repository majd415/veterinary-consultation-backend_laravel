<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class AdminOrderController extends Controller
{
    public function index()
    {
        // Users model likely has a relationship to orders or we fetch directly.
        // But the table is 'orders', it has 'product_id' (or we check migration)
        // From Step 3865: 'orders' has 'product_id'.
        $orders = Order::with('product')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update($request->only(['status', 'payment_status']));
        return redirect()->back()->with('success', 'Order updated successfully');
    }

    public function destroy($id)
    {
        Order::destroy($id);
        return redirect()->back()->with('success', 'Order deleted successfully');
    }
}
