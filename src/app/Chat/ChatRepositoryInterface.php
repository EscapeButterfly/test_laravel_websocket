<?php

namespace App\Chat;

use App\Chat\DTO\MessageData;

interface ChatRepositoryInterface
{
    public function store(MessageData $data);

    public function findDialogMessages(int $receiverId, int $senderId);
}
