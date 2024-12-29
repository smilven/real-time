<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Events\OrderStatusUpdated;
use App\Models\Cart;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $userId = auth()->id();
        $orders = Order::where('user_id', $userId)->with('items.product')->latest()->get();
        $totalQuantity = Cart::where('user_id', $userId)->sum('quantity');
                return view('orders', compact('orders', 'totalQuantity'));
    }
    
     // Admin Dashboard to view all orders
     public function adminIndex()
     {
         // Fetch all orders for admin
         $orders = Order::with('user')->get(); // Assuming each order has a relationship with a user
 
         return view('adminOrders', compact('orders'));
     }
 

     public function updateStatus(Request $request, $id)
     {
         $order = Order::findOrFail($id);
         $order->status = $request->status;
         $order->save();
     
         broadcast(new OrderStatusUpdated($order));
     
         return response()->json(['success' => true, 'message' => "Order status has been updated to {$order->status}"]);
     }
     
}
