<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductUpdate implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $product;

    /**
     * Create a new event instance.
     *
     * @param  object  $product
     */
    public function __construct($product)
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
        return "update";
    }

    /**
     * Data to broadcast with the event.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            "message" => "[{$this->product->updated_at}] Product '{$this->product->productName}' was updated successfully!",
            "product" => [
                "id" => $this->product->id,
                "name" => $this->product->productName,
                "price" => $this->product->productPrice,
                "quantity" => $this->product->productQuantity
            ]
        ];
    }
}
