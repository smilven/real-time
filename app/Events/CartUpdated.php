<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CartUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $totalQuantity;
    public $userId; 
    public function __construct($userId,$totalQuantity) {
        $this->totalQuantity = $totalQuantity;
        $this->userId = $userId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn() {
        return new PrivateChannel('cart-updates.' . $this->userId);  // 使用私人频道
    }

    public function broadcastWith() {
        return [
            'userId' => $this->userId, 
            'totalQuantity' => $this->totalQuantity,
        ];
    }
}
