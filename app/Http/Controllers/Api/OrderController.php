<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = \App\Models\Order::with('product')
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'shipping_name' => 'required|string',
            'shipping_address' => 'required|string',
            'shipping_phone' => 'required|string',
            'total_amount' => 'required|numeric',
            'transaction_id' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'currency' => 'nullable|string',
        ]);

        $order = \App\Models\Order::create([
            'user_id' => $request->user()->id,
            'product_id' => $validated['product_id'],
            'shipping_name' => $validated['shipping_name'],
            'shipping_address' => $validated['shipping_address'],
            'shipping_phone' => $validated['shipping_phone'],
            'total_amount' => $validated['total_amount'],
            'status' => 'pending',
            'payment_status' => 'paid', // Assuming payment gateway success
        ]);

        // Log Payment
        if ($request->transaction_id) {
            \App\Models\InfoPayment::create([
                'user_id' => $request->user()->id,
                'amount' => $validated['total_amount'],
                'currency' => $request->currency ?? 'USD',
                'payment_method' => $request->payment_method ?? 'stripe',
                'transaction_id' => $request->transaction_id,
                'status' => 'paid',
                'type' => 'store_order',
                'description' => 'Payment for Order #' . $order->id,
                'payload' => json_encode($request->all()),
            ]);
        }

        return response()->json(['message' => 'Order created successfully', 'order' => $order]);
    }
}
