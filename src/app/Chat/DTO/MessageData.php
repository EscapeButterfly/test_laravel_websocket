<?php

namespace App\Chat\DTO;

use App\Models\Message;

class MessageData
{
    public function __construct(
        public readonly int     $senderId,
        public readonly int     $receiverId,
        public readonly string  $message,
        public readonly ?string $name,
        public readonly ?string $created_at
    )
    {
    }

    public static function fromArray(array $data): self
    {
        if (!isset($data['sender_id'], $data['receiver_id'], $data['message'], $data['name'])) {
            throw new \InvalidArgumentException('Required keys are missing from the input array.');
        }
        return new self(
            senderId  : $data['sender_id'],
            receiverId: $data['receiver_id'],
            message   : $data['message'],
            name      : $data['name'],
            created_at: null
        );
    }
}
