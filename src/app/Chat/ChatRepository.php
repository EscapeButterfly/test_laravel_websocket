<?php

namespace App\Chat;

use App\Chat\DTO\MessageData;
use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;

class ChatRepository implements ChatRepositoryInterface
{
    public function store(MessageData $data): void
    {
        Message::query()->create([
            'sender_id'   => $data->senderId,
            'receiver_id' => $data->receiverId,
            'message'     => $data->message
        ]);
    }

    public function findDialogMessages(int $receiverId, int $senderId): Collection|array
    {
        //pagination here
        return Message::query()
            ->where(function ($query) use ($senderId, $receiverId) {
                $query->where('sender_id', $senderId)
                    ->where('receiver_id', $receiverId);
            })
            ->orWhere(function ($query) use ($senderId, $receiverId) {
                $query->where('sender_id', $receiverId)
                    ->where('receiver_id', $senderId);
            })
            ->orderBy('created_at')
            ->get();
    }

}
