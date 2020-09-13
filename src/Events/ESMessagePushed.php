<?php

namespace AuditLogger\Events;

use AuditLogger\AuditLog;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;

class ESMessagePushed
{
    use Dispatchable, SerializesModels;

    /**
     * @var AuditLog
     */
    public AuditLog $logger;

    /**
     * Create a new event instance.
     *
     * @param AuditLog $logger
     */
    public function __construct(AuditLog $logger)
    {
        $this->logger = $logger;
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
