<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Product;

class ProductAddedToCart implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $product;

    /**
     * Create a new event instance.
     *
     * @param  object  $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel
     */
    public function broadcastOn()
    {
        return new Channel("products");
    }

    /**
     * Customize the event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return "addToCart";  // 用于通知管理员商品添加到购物车并更新库存
    }

    /**
     * Data to broadcast with the event.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'message' => "Product '{$this->product->productName}' has been added to cart. New quantity: {$this->product->productQuantity}",
            'product' => [
                'id' => $this->product->id,
                'productName' => $this->product->productName,
                'productQuantity' => $this->product->productQuantity,
            ],
        ];
    }
}
