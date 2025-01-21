<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\http\Controllers\OrderController;
use App\Models\Product;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('product')->get(); 
        return response()->json($orders, 200);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $total_price = $product->price * $validated['quantity'];

        $order = Order::create([
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'total_price' => $total_price,
        ]);


        return response()->json($order, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $order = Order::findOrFail($id);
        $product = Product::findOrFail($validated['product_id']);
        $total_price = $product->price * $validated['quantity'];

        $order->update([
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'total_price' => $total_price,
        ]);

        return response()->json($order);
    }
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }

}