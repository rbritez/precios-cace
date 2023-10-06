<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CheckAlterationsEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @property int $event_id
     * @property int $shop_id
     * @property int $product_id
     * @property array $prices
     */
    public $event_id;
    public $shop_id;
    public $product_id;
    public $prices;

    /**
     * Create a new event instance.
     *
     * @param int $event
     * @param int $shop
     * @param int $product
     * @param array $prices
     */
    public function __construct($event,$shop,$product,$prices)
    {
        $this->event_id     = $event;
        $this->shop_id      = $shop;
        $this->product_id   = $product;
        $this->prices       = $prices;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}