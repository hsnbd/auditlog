<?php

namespace Hsnbd\AuditLogger\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;

class ESMessagePushed
{
    use Dispatchable, SerializesModels;

    /**
     * @var array
     */
    public array $logData;

    /**
     * Create a new event instance.
     *
     * @param array $logData
     */
    public function __construct(array $logData)
    {
        $this->logData = $logData;
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
