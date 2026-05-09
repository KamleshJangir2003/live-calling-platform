<?php

namespace App\Events;

use App\Models\Call;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IncomingCall implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Call $call) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('user.' . $this->call->receiver_id)];
    }

    public function broadcastAs(): string
    {
        return 'incoming.call';
    }

    public function broadcastWith(): array
    {
        return [
            'call_id' => $this->call->id,
            'caller_name' => $this->call->caller->name,
            'caller_avatar' => $this->call->caller->avatar_url,
            'call_type' => $this->call->call_type,
            'channel_name' => $this->call->channel_name,
        ];
    }
}
