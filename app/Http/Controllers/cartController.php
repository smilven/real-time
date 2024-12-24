<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Product;
use App\Events\ProductAddedToCart; // 导入新事件

class CartController extends Controller
{
    public function addToCart(Request $request)
{
    $productId = $request->input('productId');
    $product = Product::find($productId);

    if (!$product || $product->productQuantity <= 0) {
        return response()->json(['message' => 'Product is out of stock'], 400);
    }

    $userId = auth()->id();

    // 减少库存
    $product->productQuantity -= 1;
    $product->save();
    
    event(new ProductAddedToCart($product)); // 通知管理员库存更新

    // 添加到购物车表
    $cartItem = \App\Models\Cart::where('user_id', $userId)->where('product_id', $productId)->first();

    if ($cartItem) {
        $cartItem->quantity += 1;
        $cartItem->save();
    } else {
        \App\Models\Cart::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'quantity' => 1,
        ]);
    }

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
            'product_id' => $cartItem->product_id,
            'quantity' => $cartItem->quantity,
            'price' => $cartItem->product->productPrice,
        ]);

        // 更新库存
        $cartItem->product->decrement('productQuantity', $cartItem->quantity);
    }

    // 清空购物车
    \App\Models\Cart::where('user_id', $userId)->delete();

    return redirect()->route('my.cart')->with('success', 'Order placed successfully!');
}


}