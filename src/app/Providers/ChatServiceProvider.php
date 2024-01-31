<?php

namespace App\Providers;

use App\Chat\ChatRepository;
use App\Chat\ChatRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class ChatServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ChatRepositoryInterface::class, ChatRepository::class);
    }
}
