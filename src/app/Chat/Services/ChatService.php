<?php

namespace App\Chat\Services;

use App\Chat\ChatRepositoryInterface;
use App\Chat\DTO\MessageData;
use App\Events\ChatMessage;
use App\Events\UserOnline;
use Illuminate\Support\Facades\Cache;

class ChatService
{
    const string CACHE_DIALOG_PREFIX = 'dialog_';
    public function __construct(
        private readonly ChatRepositoryInterface $chatRepository
    )
    {
    }

    public function setUserOnline(): void
    {
        event(new UserOnline(auth()->id()));
    }

    public function sendMessage(MessageData $message): void
    {
        $this->chatRepository->store($message);
        Cache::forget($this->getDialogCacheKey($message->receiverId));
        event(new ChatMessage($message));
    }

    public function getDialogMessages(int $senderId)
    {
        return Cache::rememberForever(
            $this->getDialogCacheKey($senderId),
            fn() => $this->chatRepository->findDialogMessages(auth()->id(), $senderId)
        );
    }

    private function getDialogCacheKey(int $senderId): string
    {
        return self::CACHE_DIALOG_PREFIX . implode('_', collect([$senderId, auth()->id()])->sort()->toArray());
    }
}
