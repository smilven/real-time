<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Product;
use App\Models\Cart;
use App\Events\ProductAddedToCart; 
use App\Events\CartUpdated;

class CartController extends Controller
{
        public function __construct()
    {
        $this->middleware('auth');
    }


    public function myCartIndex(){
        $userId = auth()->id();
        $cartItems = Cart::with('product')->where('user_id', auth()->id())->get();
        $totalQuantity = Cart::where('user_id', $userId)->sum('quantity'); 
        return view ("myCart",compact('cartItems','totalQuantity'));
    }

    public function addToCart(Request $request)
    {
        $productId = $request->input('productId');
        $product = Product::find($productId);
    
        if (!$product || $product->productQuantity <= 0) {
            return response()->json(['message' => 'Product is out of stock'], 400);
        }
    
        $userId = auth()->id();
    
        // Reduce product stock
        $product->productQuantity -= 1;
        $product->save();
        
        event(new ProductAddedToCart($product)); // Notify admin about stock update
    
        // Add or update the cart item
        $cartItem = Cart::where('user_id', $userId)->where('product_id', $productId)->first();
    
        if ($cartItem) {
            $cartItem->quantity += 1;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => 1,
            ]);
        }
    
        // Get the total quantity for the current user
        $totalQuantity = Cart::where('user_id', $userId)->sum('quantity');
    
        // Store in session and broadcast event
        Session::put('cart_total_quantity', $totalQuantity);
        event(new CartUpdated($userId, $totalQuantity));
    
        return response()->json(['message' => 'Product added to cart!']);
    }

public function placeOrder()
{
    $userId = auth()->id();
    $cartItems = \App\Models\Cart::where('user_id', $userId)->get();

    if ($cartItems->isEmpty()) {
        return redirect()->back()->with('error', 'Your cart is empty!');
    }

    // 创建订单
    $order = \App\Models\Order::create([
        'user_id' => $userId,
        'status' => 'Placed',
    ]);

    // 添加订单项并更新库存
    foreach ($cartItems as $cartItem) {
        
        \App\Models\OrderItem::create([
            'order_id' => $order->id,
            'user_id' => $order->user_id,
            'product_id' => $cartItem->product_id,
            'quantity' => $cartItem->quantity,
            'price' => $cartItem->product->productPrice,
        ]);

        
    }

    // 清空购物车
    \App\Models\Cart::where('user_id', $userId)->delete();

    return redirect()->route('myCart.index')->with('success', 'Order placed successfully!');
}


}